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

namespace Sam\Infrastructure\Generate\EntityGenerator\ReadRepository\Code\Internal;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Literal;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Property;
use Sam\Core\Constants\Db;
use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\Generate\EntityGenerator\Internal\ClassGeneratorHelper;
use Sam\Infrastructure\Generate\EntityGenerator\ReadRepository\Code\MethodFactory\FilterInequalityMethodFactory;
use Sam\Infrastructure\Generate\EntityGenerator\ReadRepository\Code\MethodFactory\FilterMethodFactory;
use Sam\Infrastructure\Generate\EntityGenerator\ReadRepository\Code\MethodFactory\GroupByMethodFactory;
use Sam\Infrastructure\Generate\EntityGenerator\ReadRepository\Code\MethodFactory\LikeMethodFactory;
use Sam\Infrastructure\Generate\EntityGenerator\ReadRepository\Code\MethodFactory\OrderByMethodFactory;
use Sam\Infrastructure\Generate\EntityGenerator\ReadRepository\Code\MethodFactory\SkipMethodFactory;
use Sam\Storage\Metadata\DatabaseColumnMetadataProviderCreateTrait;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * This class is responsible for generating read repository base class for entity.
 * Generated repository class contains methods for filtering, grouping and ordering
 * and extends Sam\Storage\ReadRepository\Entity\ReadRepositoryBase class.
 *
 * Class ReadRepositoryBaseClassFileGenerator
 * @package Sam\Infrastructure\Generate\EntityGenerator\ReadRepository\Code\Internal
 */
class ReadRepositoryBaseClassFileGenerator extends CustomizableClass
{
    use DatabaseColumnMetadataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Construct read repository base class for the entity
     *
     * @param string $entityName
     * @param string $qualifiedClassName
     * @return PhpFile
     */
    public function generate(string $entityName, string $qualifiedClassName): PhpFile
    {
        $tableName = $this->convertEntityNameToTableName($entityName);

        $properties = $this->makeProperties($tableName);
        $methods = $this->makeMethods($tableName);
        $class = $this->makeClass($entityName, $qualifiedClassName, $properties, $methods);
        $namespace = $this->makeNamespace($entityName, $qualifiedClassName);

        $file = $this->makeFile();
        $file
            ->addNamespace($namespace)
            ->add($class);
        return $file;
    }

    protected function makeFile(): PhpFile
    {
        $file = new PhpFile();
        $file->addComment('This file is auto-generated.');
        return $file;
    }

    protected function makeNamespace(string $entityName, string $qualifiedClassName): PhpNamespace
    {
        $classNamespace = ClassGeneratorHelper::new()->extractNamespace($qualifiedClassName);
        $namespace = (new PhpNamespace($classNamespace))
            ->addUse(ReadRepositoryBase::class)
            ->addUse(Db::class)
            ->addUse($entityName);
        return $namespace;
    }

    /**
     * @param string $entityName
     * @param string $qualifiedClassName
     * @param Property[] $properties
     * @param Method[] $methods
     * @return ClassType
     */
    protected function makeClass(string $entityName, string $qualifiedClassName, array $properties, array $methods): ClassType
    {
        $className = ClassGeneratorHelper::new()->extractClassName($qualifiedClassName);
        $class = (new ClassType($className))
            ->addExtend(ReadRepositoryBase::class)
            ->setProperties($properties)
            ->setAbstract()
            ->setMethods($methods)
            ->setComment(
                <<<TEXT
Abstract class {$className}
@method {$entityName}[] loadEntities() 
@method {$entityName}|null loadEntity()
TEXT
            );
        return $class;
    }

    protected function makeProperties(string $tableName): array
    {
        $properties = [
            (new Property('table'))
                ->setProtected()
                ->setType('string')
                ->setValue(new Literal(sprintf('Db::TBL_%s', mb_strtoupper($tableName)))),
            (new Property('alias'))
                ->setProtected()
                ->setType('string')
                ->setValue(new Literal(sprintf('Db::A_%s', mb_strtoupper($tableName)))),
        ];
        return $properties;
    }

    protected function makeMethods(string $tableName): array
    {
        $columns = $this->createDatabaseColumnMetadataProvider()->getForTable($tableName);
        $methods = [];
        foreach ($columns as $column) {
            $methods[] = FilterMethodFactory::new()->create($column);
            $methods[] = SkipMethodFactory::new()->create($column);
            $methods[] = GroupByMethodFactory::new()->create($column);
            $methods[] = OrderByMethodFactory::new()->create($column);

            $columnType = $column->getType();
            if ($columnType->isText()) {
                $methods[] = LikeMethodFactory::new()->create($column);
            }
            if (
                $columnType->isInteger()
                || $columnType->isFloat()
                || $columnType->isTimestamp()
                || $columnType->isDate()
            ) {
                $methods[] = FilterInequalityMethodFactory::new()->create($column, FilterInequalityMethodFactory::TYPE_GREATER);
                $methods[] = FilterInequalityMethodFactory::new()->create($column, FilterInequalityMethodFactory::TYPE_GREATER_OR_EQUAL);
                $methods[] = FilterInequalityMethodFactory::new()->create($column, FilterInequalityMethodFactory::TYPE_LESS);
                $methods[] = FilterInequalityMethodFactory::new()->create($column, FilterInequalityMethodFactory::TYPE_LESS_OR_EQUAL);
            }
        }
        return $methods;
    }

    protected function convertEntityNameToTableName(string $entityName): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $entityName));
    }
}
