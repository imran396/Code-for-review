<?php
/**
 * SAM-9891: Get rid of RepositoryBase::delete() usage
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Generate\EntityGenerator\DeleteRepository\Code\Internal;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Literal;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Property;
use Sam\Core\Constants\Db;
use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\Generate\EntityGenerator\Internal\ClassGeneratorHelper;
use Sam\Infrastructure\Generate\EntityGenerator\ReadRepository\Code\MethodFactory\FilterMethodFactory;
use Sam\Infrastructure\Generate\EntityGenerator\ReadRepository\Code\MethodFactory\SkipMethodFactory;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;
use Sam\Storage\Metadata\DatabaseColumnMetadataProviderCreateTrait;

/**
 * This class is responsible for generating delete repository base class for entity.
 * Generated repository class contains filter and skip methods and extends Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase class.
 *
 * Class ReadRepositoryBaseClassFileGenerator
 * @package Sam\Infrastructure\Generate\EntityGenerator\DeleteRepository\Code\Internal
 */
class DeleteRepositoryBaseClassFileFactory extends CustomizableClass
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
     * Construct delete repository base class for the entity
     *
     * @param string $tableName
     * @param string $qualifiedClassName
     * @return PhpFile
     */
    public function create(string $tableName, string $qualifiedClassName): PhpFile
    {
        $properties = $this->makeProperties($tableName);
        $methods = $this->makeMethods($tableName);
        $class = $this->makeClass($qualifiedClassName, $properties, $methods);
        $namespace = $this->makeNamespace($qualifiedClassName);

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

    protected function makeNamespace(string $qualifiedClassName): PhpNamespace
    {
        $classNamespace = ClassGeneratorHelper::new()->extractNamespace($qualifiedClassName);
        $namespace = (new PhpNamespace($classNamespace))
            ->addUse(DeleteRepositoryBase::class)
            ->addUse(Db::class);
        return $namespace;
    }

    /**
     * @param string $qualifiedClassName
     * @param Property[] $properties
     * @param Method[] $methods
     * @return ClassType
     */
    protected function makeClass(string $qualifiedClassName, array $properties, array $methods): ClassType
    {
        $className = ClassGeneratorHelper::new()->extractClassName($qualifiedClassName);
        $class = (new ClassType($className))
            ->addExtend(DeleteRepositoryBase::class)
            ->setProperties($properties)
            ->setAbstract()
            ->setMethods($methods);
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
        }
        return $methods;
    }
}
