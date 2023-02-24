<?php
/**
 * SAM-9875: Implement a code generator for read repository classes
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Generate\EntityGenerator\ReadRepository\Code\MethodFactory;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Text\TextTransformer;
use Sam\Storage\Mapping\DatabaseColumnTypeToPhpTypeConverterCreateTrait;
use Sam\Storage\Metadata\DatabaseColumnMetadata;

/**
 * Class FilterInequalityMethodFactory
 * @package Sam\Infrastructure\Generate\EntityGenerator\ReadRepository\Code\MethodFactory
 */
class FilterInequalityMethodFactory extends CustomizableClass
{
    use DatabaseColumnTypeToPhpTypeConverterCreateTrait;

    public const TYPE_GREATER = '>';
    public const TYPE_GREATER_OR_EQUAL = '>=';
    public const TYPE_LESS = '<';
    public const TYPE_LESS_OR_EQUAL = '<=';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Construct inequality filter method for the column.
     * Depending on the inequality type one of these methods will be created:
     *  - filter<ColumnName>Greater()
     *  - filter<ColumnName>GreaterOrEqual()
     *  - filter<ColumnName>Less()
     *  - filter<ColumnName>LessOrEqual()
     *
     * @param DatabaseColumnMetadata $columnMetadata
     * @param string $inequalityType
     * @return Method
     */
    public function create(DatabaseColumnMetadata $columnMetadata, string $inequalityType): Method
    {
        $methodName = $this->makeMethodName($columnMetadata->getColumnName(), $inequalityType);

        $method = (new Method($methodName))
            ->setReturnType('static')
            ->setVisibility(ClassType::VISIBILITY_PUBLIC)
            ->setComment($this->makeMethodComment($columnMetadata, $inequalityType))
            ->setBody($this->makeMethodBody($columnMetadata, $inequalityType));

        $columnPhpType = $this->createDatabaseColumnTypeToPhpTypeConverter()->convert($columnMetadata->getType());
        $method->addParameter('filterValue')->setType($columnPhpType);
        return $method;
    }

    protected function makeMethodComment(DatabaseColumnMetadata $columnMetadata, string $inequalityType): string
    {
        $columnPhpType = $this->createDatabaseColumnTypeToPhpTypeConverter()->convert($columnMetadata->getType());
        return <<<TEXT
Filter by {$this->detectInequalityDescription($inequalityType)} than {$columnMetadata->getTableName()}.{$columnMetadata->getColumnName()}
@param {$columnPhpType} \$filterValue
@return static
TEXT;
    }

    protected function makeMethodBody(DatabaseColumnMetadata $columnMetadata, string $inequalityType): string
    {
        return <<<PHP
\$this->filterInequality(\$this->alias . '.{$columnMetadata->getColumnName()}', \$filterValue, '{$inequalityType}');
return \$this;
PHP;
    }

    protected function makeMethodName(string $columnName, string $inequalityType): string
    {
        return sprintf(
            'filter%s%s',
            TextTransformer::new()->toCamelCase($columnName),
            $this->detectMethodSuffix($inequalityType)
        );
    }

    protected function detectInequalityDescription(string $inequalityType): string
    {
        $inequalityDescription = [
            self::TYPE_GREATER => 'greater',
            self::TYPE_GREATER_OR_EQUAL => 'equal or greater',
            self::TYPE_LESS => 'less',
            self::TYPE_LESS_OR_EQUAL => 'equal or less',
        ];
        if (!isset($inequalityDescription[$inequalityType])) {
            throw new \RuntimeException("Unknown inequality type '{$inequalityType}'");
        }
        return $inequalityDescription[$inequalityType];
    }

    protected function detectMethodSuffix(string $inequalityType): string
    {
        $methodSuffix = [
            self::TYPE_GREATER => 'Greater',
            self::TYPE_GREATER_OR_EQUAL => 'GreaterOrEqual',
            self::TYPE_LESS => 'Less',
            self::TYPE_LESS_OR_EQUAL => 'LessOrEqual',
        ];
        if (!isset($methodSuffix[$inequalityType])) {
            throw new \RuntimeException("Unknown inequality type '{$inequalityType}'");
        }
        return $methodSuffix[$inequalityType];
    }
}
