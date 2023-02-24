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

namespace Sam\Infrastructure\Generate\EntityGenerator\Internal\ClassFile;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\Generate\EntityGenerator\Internal\ClassGeneratorHelper;

/**
 * Class CustomizableClassFileFactory
 * @package Sam\Infrastructure\Generate\EntityGenerator\Internal\ClassFile
 */
class CustomizableClassFileFactory extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Generate a class that implement CustomizableClassInterface
     *
     * @param string $qualifiedClassName
     * @param string $qualifiedBaseClassName
     * @return PhpFile
     */
    public function create(string $qualifiedClassName, string $qualifiedBaseClassName = CustomizableClass::class): PhpFile
    {
        $methods = [
            $this->makeNewMethod(),
        ];
        $class = $this->makeClass($qualifiedClassName, $qualifiedBaseClassName, $methods);
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
        $namespace = (new PhpNamespace($classNamespace));
        return $namespace;
    }

    /**
     * @param string $qualifiedClassName
     * @param string $qualifiedBaseClassName
     * @param Method[] $methods
     * @return ClassType
     */
    protected function makeClass(string $qualifiedClassName, string $qualifiedBaseClassName, array $methods): ClassType
    {
        $className = ClassGeneratorHelper::new()->extractClassName($qualifiedClassName);
        $class = (new ClassType($className))
            ->addExtend($qualifiedBaseClassName)
            ->setMethods($methods);
        return $class;
    }

    protected function makeNewMethod(): Method
    {
        $method = (new Method('new'))
            ->setPublic()
            ->setStatic()
            ->setReturnType('self')
            ->setBody('return parent::_new(self::class);');
        return $method;
    }
}
