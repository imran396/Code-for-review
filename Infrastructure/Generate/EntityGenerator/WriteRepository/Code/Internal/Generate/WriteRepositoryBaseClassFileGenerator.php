<?php
/**
 * SAM-9363: Write repository generator
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul. 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Generate\EntityGenerator\WriteRepository\Code\Internal\Generate;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\Generate\EntityGenerator\Internal\ClassGeneratorHelper;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

/**
 * This class is responsible for generating write repository base class for entity.
 * Generated repository class contains methods for saving and deleting entity
 * and extends Sam\Storage\WriteRepository\Entity\WriteRepositoryBase class.
 *
 * Class WriteRepositoryBaseClassFileGenerator
 * @package Sam\Infrastructure\Generate\EntityGenerator\WriteRepository\Code\Internal\Generate
 * @internal
 */
class WriteRepositoryBaseClassFileGenerator extends CustomizableClass
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
     * Construct write repository base class for the entity
     *
     * @param string $entityName
     * @param string $qualifiedClassName
     * @return PhpFile
     */
    public function generate(string $entityName, string $qualifiedClassName): PhpFile
    {
        $methods = [
            $this->makeSaveMethod($entityName),
            $this->makeSaveWithModifierMethod($entityName),
            $this->makeSaveWithSystemModifierMethod($entityName),
            $this->makeForceSaveMethod($entityName),
            $this->makeForceSaveWithModifierMethod($entityName),
            $this->makeDeleteMethod($entityName),
            $this->makeDeleteWithModifierMethod($entityName),
            $this->makeDeleteWithSystemModifierMethod($entityName)
        ];
        $class = $this->makeClass($qualifiedClassName, $methods);
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
            ->addUse(WriteRepositoryBase::class)
            ->addUse($entityName);
        return $namespace;
    }

    /**
     * @param string $qualifiedClassName
     * @param Method[] $methods
     * @return ClassType
     */
    protected function makeClass(string $qualifiedClassName, array $methods): ClassType
    {
        $className = ClassGeneratorHelper::new()->extractClassName($qualifiedClassName);
        $class = (new ClassType($className))
            ->addExtend(WriteRepositoryBase::class)
            ->setAbstract()
            ->setMethods($methods);
        return $class;
    }

    protected function makeSaveMethod(string $entityName): Method
    {
        $comment = <<<TEXT
Persist {$entityName} entity in DB.
(!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
@param {$entityName} \$entity
TEXT;
        $saveMethod = (new Method('save'))
            ->setComment($comment)
            ->setReturnType('void')
            ->setVisibility(ClassType::VISIBILITY_PUBLIC)
            ->setBody('$this->saveEntity($entity);');
        $saveMethod->addParameter('entity')->setType($entityName);
        return $saveMethod;
    }

    protected function makeSaveWithModifierMethod(string $entityName): Method
    {
        $comment = <<<TEXT
Persist {$entityName} entity in DB on behalf of respective modifier user.
@param {$entityName} \$entity
@param int \$editorUserId
TEXT;
        $saveMethod = (new Method('saveWithModifier'))
            ->setComment($comment)
            ->setReturnType('void')
            ->setVisibility(ClassType::VISIBILITY_PUBLIC)
            ->setBody('$this->saveEntityWithModifier($entity, $editorUserId);');
        $saveMethod->addParameter('entity')->setType($entityName);
        $saveMethod->addParameter('editorUserId')->setType('int');
        return $saveMethod;
    }

    protected function makeSaveWithSystemModifierMethod(string $entityName): Method
    {
        $comment = <<<TEXT
Persist {$entityName} entity in DB on behalf of system user.
@param {$entityName} \$entity
TEXT;
        $saveMethod = (new Method('saveWithSystemModifier'))
            ->setComment($comment)
            ->setReturnType('void')
            ->setVisibility(ClassType::VISIBILITY_PUBLIC)
            ->setBody('$this->saveEntityWithSystemModifier($entity);');
        $saveMethod->addParameter('entity')->setType($entityName);
        return $saveMethod;
    }

    protected function makeForceSaveMethod(string $entityName): Method
    {
        $comment = <<<TEXT
Persist {$entityName} entity in DB and ignore Optimistic Locking check.
(!) If you have a reason to call this function, you MUST describe it by comment near the call.
@param {$entityName} \$entity
TEXT;
        $saveMethod = (new Method('forceSave'))
            ->setComment($comment)
            ->setReturnType('void')
            ->setVisibility(ClassType::VISIBILITY_PUBLIC)
            ->setBody('$this->forceSaveEntity($entity);');
        $saveMethod->addParameter('entity')->setType($entityName);
        return $saveMethod;
    }

    protected function makeForceSaveWithModifierMethod(string $entityName): Method
    {
        $comment = <<<TEXT
Persist {$entityName} entity in DB on behalf of modifier user and ignore Optimistic Locking check.
(!) If you have a reason to call this function, you MUST describe it by comment near the call.
@param {$entityName} \$entity
@param int \$editorUserId
TEXT;
        $saveMethod = (new Method('forceSaveWithModifier'))
            ->setComment($comment)
            ->setReturnType('void')
            ->setVisibility(ClassType::VISIBILITY_PUBLIC)
            ->setBody('$this->forceSaveEntityWithModifier($entity, $editorUserId);');
        $saveMethod->addParameter('entity')->setType($entityName);
        $saveMethod->addParameter('editorUserId')->setType('int');
        return $saveMethod;
    }

    protected function makeDeleteMethod(string $entityName): Method
    {
        $comment = <<<TEXT
Delete {$entityName} entity without specifying user who performs the action.
(!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
@param {$entityName} \$entity
TEXT;
        $deleteMethod = (new Method('delete'))
            ->setComment($comment)
            ->setReturnType('void')
            ->setVisibility(ClassType::VISIBILITY_PUBLIC)
            ->setBody('$this->deleteEntity($entity);');
        $deleteMethod->addParameter('entity')->setType($entityName);
        return $deleteMethod;
    }

    protected function makeDeleteWithModifierMethod(string $entityName): Method
    {
        $comment = <<<TEXT
Delete {$entityName} entity on behalf of respective modifier user.
@param {$entityName} \$entity
@param int \$editorUserId
TEXT;
        $saveMethod = (new Method('deleteWithModifier'))
            ->setComment($comment)
            ->setReturnType('void')
            ->setVisibility(ClassType::VISIBILITY_PUBLIC)
            ->setBody('$this->deleteEntityWithModifier($entity, $editorUserId);');
        $saveMethod->addParameter('entity')->setType($entityName);
        $saveMethod->addParameter('editorUserId')->setType('int');
        return $saveMethod;
    }

    protected function makeDeleteWithSystemModifierMethod(string $entityName): Method
    {
        $comment = <<<TEXT
Delete {$entityName} entity on behalf of system user.
@param {$entityName} \$entity
TEXT;
        $saveMethod = (new Method('deleteWithSystemModifier'))
            ->setComment($comment)
            ->setReturnType('void')
            ->setVisibility(ClassType::VISIBILITY_PUBLIC)
            ->setBody('$this->deleteEntityWithSystemModifier($entity);');
        $saveMethod->addParameter('entity')->setType($entityName);
        return $saveMethod;
    }
}
