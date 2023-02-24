<?php
/**
 * SAM-9363: Write repository generator
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul. 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Generate\EntityGenerator\WriteRepository\Code;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Text\TextTransformer;
use Sam\Infrastructure\Generate\EntityGenerator\Internal\ClassFile\ClassFileWriterCreateTrait;
use Sam\Infrastructure\Generate\EntityGenerator\Internal\ClassFile\CustomizableClassFileFactoryCreateTrait;
use Sam\Infrastructure\Generate\EntityGenerator\Internal\Db\DatabaseTablesDataProviderCreateTrait;
use Sam\Infrastructure\Generate\EntityGenerator\Internal\TraitGenerator\DependencyInjectionTraitFileGeneratorCreateTrait;
use Sam\Infrastructure\Generate\EntityGenerator\WriteRepository\Code\Internal\Generate\WriteRepositoryBaseClassFileGeneratorCreateTrait;

/**
 * This class is responsible for generating and saving write repositories and DI traits
 * for all entities in the application
 *
 * Class WriteRepositoryCodeGenerator
 * @package Sam\Infrastructure\Generate\EntityGenerator\WriteRepository\Code
 */
class WriteRepositoryCodeGenerator extends CustomizableClass
{
    use ClassFileWriterCreateTrait;
    use CustomizableClassFileFactoryCreateTrait;
    use DatabaseTablesDataProviderCreateTrait;
    use DependencyInjectionTraitFileGeneratorCreateTrait;
    use WriteRepositoryBaseClassFileGeneratorCreateTrait;

    protected const WRITE_REPOSITORY_NAMESPACE = 'Sam\Storage\WriteRepository\Entity';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Create and save write repositories for all entities in the application
     *
     * @return array List of handled entities
     */
    public function generateAll(): array
    {
        $tables = $this->createDatabaseTablesDataProvider()->load();

        $handledEntities = [];
        array_walk(
            $tables,
            function (string $tableName) use (&$handledEntities) {
                $entityName = $this->convertTableNameToEntityName($tableName);
                $this->generate($entityName);
                $handledEntities[] = $entityName;
            }
        );
        return $handledEntities;
    }

    /**
     * Create and save write repositories for the entity
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
        $writeRepositoryBaseClass = $this->createWriteRepositoryBaseClassFileGenerator()->generate($entityName, $baseClassName);
        $classFileWriter->write($baseClassName, $writeRepositoryBaseClass);

        $className = $this->makeClassName($entityName);
        $writeRepositoryClass = $this->createCustomizableClassFileFactory()->create($className, $baseClassName);
        $classFileWriter->write($className, $writeRepositoryClass, false);

        $namespace = $this->makeNamespace($entityName);
        $traitFileGenerator = $this->createDependencyInjectionTraitFileGenerator();
        $writeRepositoryAwareTrait = $traitFileGenerator->generateAwareTrait($namespace, $className);
        $qualifiedTraitName = $namespace . '\\' . $traitFileGenerator->makeAwareTraitName($className);
        $classFileWriter->write($qualifiedTraitName, $writeRepositoryAwareTrait);
    }

    public function makeClassName(string $entityName): string
    {
        $className = $this->makeNamespace($entityName) . '\\' . $entityName . 'WriteRepository';
        return $className;
    }

    public function makeBaseClassName(string $entityName): string
    {
        $className = $this->makeNamespace($entityName) . '\\' . 'Abstract' . $entityName . 'WriteRepository';
        return $className;
    }

    protected function makeNamespace(string $entityName): string
    {
        $namespace = self::WRITE_REPOSITORY_NAMESPACE . '\\' . $entityName;
        return $namespace;
    }

    protected function convertTableNameToEntityName(string $tableName): string
    {
        return TextTransformer::new()->toCamelCase($tableName);
    }
}
