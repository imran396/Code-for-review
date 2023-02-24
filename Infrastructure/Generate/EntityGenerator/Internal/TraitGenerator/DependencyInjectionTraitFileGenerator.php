<?php
/**
 * SAM-9875: Implement a code generator for read repository classes
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Generate\EntityGenerator\Internal\TraitGenerator;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Property;
use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\Generate\EntityGenerator\Internal\ClassGeneratorHelper;

/**
 * This class contains methods for generating DI traits
 *
 * Class DependencyInjectionTraitFileGenerator
 * @package Sam\Infrastructure\Generate\EntityGenerator\Internal
 */
class DependencyInjectionTraitFileGenerator extends CustomizableClass
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
     * Generate a <Class>CreateTrait file
     *
     * @param string $traitNamespace
     * @param string $qualifiedClassName
     * @return PhpFile
     */
    public function generateCreateTrait(string $traitNamespace, string $qualifiedClassName): PhpFile
    {
        $qualifiedTraitName = $traitNamespace . '\\' . $this->makeCreateTraitName($qualifiedClassName);
        $methods = [
            $this->makeCreateMethod($qualifiedClassName),
            $this->makeSetMethod($qualifiedClassName),
        ];
        return $this->generateTrait($qualifiedTraitName, $qualifiedClassName, $methods);
    }

    /**
     * Generate a <Class>AwareTrait file
     *
     * @param string $traitNamespace
     * @param string $qualifiedClassName
     * @return PhpFile
     */
    public function generateAwareTrait(string $traitNamespace, string $qualifiedClassName): PhpFile
    {
        $qualifiedTraitName = $traitNamespace . '\\' . $this->makeAwareTraitName($qualifiedClassName);
        $methods = [
            $this->makeGetMethod($qualifiedClassName),
            $this->makeSetMethod($qualifiedClassName),
        ];
        return $this->generateTrait($qualifiedTraitName, $qualifiedClassName, $methods);
    }

    /**
     * Make name for the create trait
     *
     * @param string $qualifiedClassName
     * @return string
     */
    public function makeCreateTraitName(string $qualifiedClassName): string
    {
        return ClassGeneratorHelper::new()->extractClassName($qualifiedClassName) . 'CreateTrait';
    }

    /**
     * Make name for the aware trait
     *
     * @param string $qualifiedClassName
     * @return string
     */
    public function makeAwareTraitName(string $qualifiedClassName): string
    {
        return ClassGeneratorHelper::new()->extractClassName($qualifiedClassName) . 'AwareTrait';
    }

    /**
     * @param string $qualifiedTraitName
     * @param string $qualifiedClassName
     * @param Method[] $methods
     * @return PhpFile
     */
    protected function generateTrait(string $qualifiedTraitName, string $qualifiedClassName, array $methods): PhpFile
    {
        $properties = [
            $this->makeEntityProperty($qualifiedClassName),
        ];
        $class = $this->makeClass($qualifiedTraitName, $properties, $methods);
        $namespace = $this->makeNamespace($qualifiedTraitName);

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

    protected function makeNamespace(string $qualifiedTraitName): PhpNamespace
    {
        $entityWriteRepositoryNamespace = ClassGeneratorHelper::new()->extractNamespace($qualifiedTraitName);
        $namespace = new PhpNamespace($entityWriteRepositoryNamespace);
        return $namespace;
    }

    /**
     * @param string $qualifiedTraitName
     * @param Property[] $properties
     * @param Method[] $methods
     * @return ClassType
     */
    protected function makeClass(string $qualifiedTraitName, array $properties, array $methods): ClassType
    {
        $traitName = ClassGeneratorHelper::new()->extractClassName($qualifiedTraitName);
        $class = (new ClassType($traitName))
            ->setType(ClassType::TYPE_TRAIT)
            ->setProperties($properties)
            ->setMethods($methods);
        return $class;
    }

    protected function makeEntityProperty(string $qualifiedClassName): Property
    {
        $propertyName = $this->makePropertyName($qualifiedClassName);
        $property = (new Property($propertyName))
            ->setVisibility(ClassType::VISIBILITY_PROTECTED)
            ->setType($qualifiedClassName)
            ->setNullable(true)
            ->setValue(null);
        return $property;
    }

    protected function makeCreateMethod(string $qualifiedClassName): Method
    {
        $className = ClassGeneratorHelper::new()->extractClassName($qualifiedClassName);
        $propertyName = $this->makePropertyName($qualifiedClassName);
        $method = (new Method('create' . $className))
            ->setReturnType($qualifiedClassName)
            ->setVisibility(ClassType::VISIBILITY_PROTECTED)
            ->setBody(
                <<<TEXT
return \$this->{$propertyName} ?: {$className}::new();
TEXT
            );

        return $method;
    }

    protected function makeGetMethod(string $qualifiedClassName): Method
    {
        $className = ClassGeneratorHelper::new()->extractClassName($qualifiedClassName);
        $propertyName = $this->makePropertyName($qualifiedClassName);
        $method = (new Method('get' . $className))
            ->setReturnType($qualifiedClassName)
            ->setVisibility(ClassType::VISIBILITY_PROTECTED)
            ->setBody(
                <<<TEXT
if (\$this->{$propertyName} === null) {
    \$this->{$propertyName} = {$className}::new();
}
return \$this->{$propertyName};
TEXT
            );

        return $method;
    }

    protected function makeSetMethod(string $qualifiedClassName): Method
    {
        $className = ClassGeneratorHelper::new()->extractClassName($qualifiedClassName);
        $propertyName = $this->makePropertyName($qualifiedClassName);

        $method = (new Method('set' . $className))
            ->setReturnType('static')
            ->setVisibility(ClassType::VISIBILITY_PUBLIC)
            ->addComment("@param {$className} \${$propertyName}")
            ->addComment("@return static")
            ->addComment('@internal')
            ->setBody(
                <<<TEXT
\$this->{$propertyName} = \${$propertyName};
return \$this;
TEXT
            );
        $method
            ->addParameter($propertyName)
            ->setType($qualifiedClassName);

        return $method;
    }

    protected function makePropertyName(string $qualifiedClassName): string
    {
        $shortClassName = ClassGeneratorHelper::new()->extractClassName($qualifiedClassName);
        $propertyName = lcfirst($shortClassName);
        return $propertyName;
    }
}
