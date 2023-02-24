<?php
/**
 * SAM-9875: Implement a code generator for read repository classes
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Generate\EntityGenerator\ReadRepository\Code;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Text\TextTransformer;
use Sam\Infrastructure\Generate\EntityGenerator\Internal\ClassFile\ClassFileWriterCreateTrait;
use Sam\Infrastructure\Generate\EntityGenerator\Internal\ClassFile\CustomizableClassFileFactoryCreateTrait;
use Sam\Infrastructure\Generate\EntityGenerator\Internal\Db\DatabaseTablesDataProviderCreateTrait;
use Sam\Infrastructure\Generate\EntityGenerator\Internal\TraitGenerator\DependencyInjectionTraitFileGeneratorCreateTrait;
use Sam\Infrastructure\Generate\EntityGenerator\ReadRepository\Code\Internal\ReadRepositoryBaseClassFileGeneratorCreateTrait;

/**
 * This class is responsible for generating and saving read repositories and DI traits
 * for all entities in the application
 *
 * Class ReadRepositoryCodeGenerator
 * @package Sam\Infrastructure\Generate\EntityGenerator\ReadRepository\Code
 */
class ReadRepositoryCodeGenerator extends CustomizableClass
{
    use ClassFileWriterCreateTrait;
    use DatabaseTablesDataProviderCreateTrait;
    use DependencyInjectionTraitFileGeneratorCreateTrait;
    use ReadRepositoryBaseClassFileGeneratorCreateTrait;
    use CustomizableClassFileFactoryCreateTrait;

    protected const READ_REPOSITORY_NAMESPACE = 'Sam\Storage\ReadRepository\Entity';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Generate and save read repositories for all entities in the application
     *
     * @return array List of handled entities
     */
    public function generateAll(): array
    {
        $tables = $this->createDatabaseTablesDataProvider()->load();
        $handledEntities = array_map(
            function (string $tableName) {
                $entityName = $this->convertTableNameToEntityName($tableName);
                $this->generate($entityName);
                return $entityName;
            },
            $tables
        );
        return $handledEntities;
    }

    /**
     * Generate and save read repository for the entity
     *
     * @param string $entityName
     */
    public function generate(string $entityName): void
    {
        if (!class_exists($entityName)) {
            throw new \InvalidArgumentException("Entity {$entityName} doesn't exist");
        }

        $classFileWriter = $this->createClassFileWriter();

        $baseClassName = $this->makeBaseClassName($entityName);
        $readRepositoryBaseClass = $this->createReadRepositoryBaseClassFileGenerator()->generate($entityName, $baseClassName);
        $classFileWriter->write($baseClassName, $readRepositoryBaseClass);

        $className = $this->makeClassName($entityName);
        $readRepositoryClass = $this->createCustomizableClassFileFactory()->create($className, $baseClassName);
        $classFileWriter->write($className, $readRepositoryClass, false);

        $namespace = $this->makeNamespace($entityName);
        $traitFileGenerator = $this->createDependencyInjectionTraitFileGenerator();
        $writeRepositoryAwareTrait = $traitFileGenerator->generateCreateTrait($namespace, $className);
        $qualifiedTraitName = $namespace . '\\' . $traitFileGenerator->makeCreateTraitName($className);
        $classFileWriter->write($qualifiedTraitName, $writeRepositoryAwareTrait);
    }

    protected function makeClassName(string $entityName): string
    {
        $className = $this->makeNamespace($entityName) . '\\' . $entityName . 'ReadRepository';
        return $className;
    }

    protected function makeBaseClassName(string $entityName): string
    {
        $className = $this->makeNamespace($entityName) . '\\' . 'Abstract' . $entityName . 'ReadRepository';
        return $className;
    }

    protected function makeNamespace(string $entityName): string
    {
        $namespace = self::READ_REPOSITORY_NAMESPACE . '\\' . $entityName;
        return $namespace;
    }

    protected function convertTableNameToEntityName(string $tableName): string
    {
        return TextTransformer::new()->toCamelCase($tableName);
    }
}
