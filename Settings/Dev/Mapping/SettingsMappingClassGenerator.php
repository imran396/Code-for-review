<?php
/**
 * SAM-4720: Refactor logic of System Parameters management pages to editor modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Dev\Mapping;

use Nette\PhpGenerator;
use Nette\PhpGenerator\PhpNamespace;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\Dev\Mapping\NamingStrategy\NamingStrategyAwareTrait;
use Sam\Settings\Repository\SettingsRepositoryProviderCreateTrait;
use Sam\Storage\Mapping\DatabaseColumnTypeToApplicationTypeConverterAwareTrait;
use Sam\Storage\Metadata\DatabaseColumnMetadata;
use Sam\Storage\Metadata\DatabaseColumnMetadataProviderCreateTrait;
use Sam\Storage\Metadata\DatabaseColumnTypeName;

/**
 * Responsible for generating php code for the class of setting constants
 *
 * Class SettingsMappingClassGenerator
 * @package Sam\Settings\Dev\Mapping
 */
class SettingsMappingClassGenerator extends CustomizableClass
{
    use DatabaseColumnMetadataProviderCreateTrait;
    use DatabaseColumnTypeToApplicationTypeConverterAwareTrait;
    use NamingStrategyAwareTrait;
    use SettingsRepositoryProviderCreateTrait;

    private const DEFAULT_NAMESPACE = 'Sam\Core\Constants';
    private const DEFAULT_CLASS_NAME = 'Setting';
    private const SKIP_COLUMNS = [
        'id',
        'account_id',
        'created_on',
        'created_by',
        'modified_on',
        'modified_by',
        'row_version',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string|null $classNamespace
     * @param string|null $className
     * @return string
     */
    public function generate(string $classNamespace = null, string $className = null): string
    {
        $file = new PhpGenerator\PhpFile;
        $file->addComment('This file is auto-generated.');
        $namespace = $file->addNamespace($classNamespace ?? self::DEFAULT_NAMESPACE);
        $this->buildClass($namespace, $className);
        return (new PhpGenerator\PsrPrinter)->printFile($file);
    }

    /**
     * @param PhpNamespace $namespace
     * @param string|null $className
     */
    private function buildClass(PhpGenerator\PhpNamespace $namespace, string $className = null): void
    {
        $namespace->addUse('Sam\Core\Constants');
        $class = $namespace->addClass($className ?? self::DEFAULT_CLASS_NAME);

        $settingsEntityClasses = $this->createSettingsRepositoryProvider()->getSettingsEntityClassNames();
        $propertyMapValues = [];
        foreach ($settingsEntityClasses as $settingsClassName) {
            $namespace->addUse($settingsClassName);
            $columns = $this->getSettingsColumnsMetadata($settingsClassName);
            foreach ($columns as $columnMetadata) {
                $constant = $this->makeColumnPropertyConstant($columnMetadata->getColumnName());
                $class->addMember($constant);
                $propertyMapValues[] = $this->buildTypeMapPropertyValue($columnMetadata, $settingsClassName);
            }
        }
        $typeMapProperty = $this->makeTypeMapProperty($propertyMapValues);
        $class->addMember($typeMapProperty);
    }

    /**
     * @param string[] $propertyMapValues
     * @return PhpGenerator\Property
     */
    private function makeTypeMapProperty(array $propertyMapValues): PhpGenerator\Property
    {
        $property = new PhpGenerator\Property('typeMap');
        $property
            ->setStatic()
            ->setComment('@var array')
            ->setValue(new PhpGenerator\Literal('[' . PHP_EOL . implode(',' . PHP_EOL, $propertyMapValues) . PHP_EOL . ']'));
        return $property;
    }

    /**
     * @param DatabaseColumnMetadata $column
     * @param string $settingsClassName
     * @return string
     */
    private function buildTypeMapPropertyValue(DatabaseColumnMetadata $column, string $settingsClassName): string
    {
        $constantName = $this->getNamingStrategy()->columnToConstantName($column->getColumnName());
        $applicationType = $this->guessPropertyApplicationType($column);
        $mapItems = [
            'type' => sprintf('Constants\Type::%s', $applicationType),
            'property' => sprintf('self::%s', $constantName),
            'entity' => $settingsClassName . '::class',
        ];
        if ($column->isNullable()) {
            $mapItems['nullable'] = 'true';
        }
        if ($column->getType()->getName() === DatabaseColumnTypeName::ENUM) {
            $knownSet = $column->getType()->getEnumChoices();
            $mapItems['knownSet'] = (new PhpGenerator\Dumper())->dump($knownSet);
        }
        return $this->makePropertyMap($constantName, $mapItems);
    }

    /**
     * @param string $propertyNameConstant
     * @param array $mapItems
     * @return string
     */
    private function makePropertyMap(string $propertyNameConstant, array $mapItems): string
    {
        $mapStringRepresentation = [];
        foreach ($mapItems as $key => $value) {
            $mapStringRepresentation[] = sprintf("\t\t'%s' => %s", $key, $value);
        }
        return sprintf("\tself::%s => [\n%s\n\t]", $propertyNameConstant, implode(",\n", $mapStringRepresentation));
    }

    /**
     * @param DatabaseColumnMetadata $columnMetadata
     * @return string
     */
    private function guessPropertyApplicationType(DatabaseColumnMetadata $columnMetadata): string
    {
        return $this->getDatabaseColumnTypeToApplicationTypeConverter()->convert($columnMetadata->getType());
    }

    /**
     * @param string $columnName
     * @return PhpGenerator\Constant
     */
    private function makeColumnPropertyConstant(string $columnName): PhpGenerator\Constant
    {
        $constantName = $this->getNamingStrategy()->columnToConstantName($columnName);
        $constantValue = $this->getNamingStrategy()->columnToPropertyName($columnName);
        $constant = new PhpGenerator\Constant($constantName);
        $constant->setValue($constantValue)->setPublic();

        return $constant;
    }

    /**
     * @return DatabaseColumnMetadata[]
     */
    private function getSettingsColumnsMetadata(string $settingsClassName): array
    {
        $tableName = $this->createSettingsRepositoryProvider()
            ->getReadRepository($settingsClassName)
            ->getTable();
        $columns = $this->createDatabaseColumnMetadataProvider()->getForTable($tableName);
        $columns = array_filter(
            $columns,
            static fn(DatabaseColumnMetadata $column) => !in_array($column->getColumnName(), self::SKIP_COLUMNS, true)
        );
        usort(
            $columns,
            static function (DatabaseColumnMetadata $left, DatabaseColumnMetadata $right) {
                return $left->getColumnName() < $right->getColumnName() ? -1 : 1;
            }
        );
        return $columns;
    }
}
