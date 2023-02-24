<?php
/**
 * SAM-9486: Entity factory class generator
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Generate\EntityGenerator\EntityFactory\Code;

use DateTimeImmutable;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PsrPrinter;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Text\TextTransformer;
use Sam\Infrastructure\Generate\EntityGenerator\EntityFactory\Code\Internal\File\EntityFactoryClassFileWriterCreateTrait;
use Sam\Infrastructure\Generate\EntityGenerator\Internal\Db\DatabaseTablesDataProviderCreateTrait;

/**
 * Class EntityFactoryCodeGenerator
 * @package Sam\Infrastructure\Generate\EntityGenerator\EntityFactory\Code
 */
class EntityFactoryCodeGenerator extends CustomizableClass
{
    use DatabaseTablesDataProviderCreateTrait;
    use EntityFactoryClassFileWriterCreateTrait;

    protected const NAMESPACE = 'Sam\Core\Entity\Create';
    protected const CLASS_NAME = 'EntityFactory';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function generate(): void
    {
        $namespace = (new PhpNamespace(self::NAMESPACE));

        // Build factory class instantiation method: new()
        $methods[] = $this->buildNewMethod();
        $namespace->addUse('Sam\Core\Service\CustomizableClass');

        // Build all entity creation methods
        $tables = $this->createDatabaseTablesDataProvider()->load();
        foreach ($tables as $table) {
            $methodName = $this->convertTableNameToMethodName($table);
            $entityName = $this->convertTableNameToEntityName($table);
            $methods[] = $this->buildFactoryMethod($methodName, $entityName);
            $namespace->addUse($entityName);
        }

        $class = (new ClassType(self::CLASS_NAME))
            ->addExtend(CustomizableClass::class)
            ->setMethods($methods);

        $fileContent = $this->buildFileContent($class, $namespace);
        $className = $this->makeClassName();
        $this->createEntityFactoryClassFileWriter()->write($className, $fileContent);
    }

    protected function convertTableNameToEntityName(string $tableName): string
    {
        return TextTransformer::new()->toCamelCase($tableName);
    }

    protected function convertTableNameToMethodName(string $tableName): string
    {
        return TextTransformer::new()->toPascalCase($tableName);
    }

    protected function makeClassName(): string
    {
        $className = self::NAMESPACE . '\\' . self::CLASS_NAME;
        return $className;
    }

    protected function buildNewMethod(): Method
    {
        return (new Method('new'))
            ->setBody("return parent::_new(self::class);")
            ->setComment("Class instantiation method\n@return static")
            ->setReturnType('static')
            ->setStatic()
            ->setVisibility(ClassType::VISIBILITY_PUBLIC);
    }

    protected function buildFactoryMethod(string $methodName, string $entityName): Method
    {
        return (new Method($methodName))
            ->setReturnType($entityName)
            ->setVisibility(ClassType::VISIBILITY_PUBLIC)
            ->setBody("return new {$entityName}();");
    }

    protected function buildFileContent(ClassType $class, PhpNamespace $namespace): string
    {
        $file = new PhpFile();
        $dateFormatted = (new DateTimeImmutable('now'))->format('M j, Y');
        $comment = <<<TEXT
(!) This file is auto-generated. Don't modify it.

Entity creation service.
Call its methods to replace newing up data classes by "new <Entity>();" execution. 
This way you can manage service dependency regular way and stub its methods in unit test.

SAM-9486: Entity factory class generator

@copyright       2021 Bidpath, Inc.
@author          Igors Kotlevskis
@package         com.swb.sam2
@version         SVN: \$Id: $
@since           {$dateFormatted}
file encoding    UTF-8

Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
TEXT;

        $file->addComment($comment);
        $file
            ->addNamespace($namespace)
            ->add($class);
        $fileContent = (new PsrPrinter())->printFile($file);
        return $fileContent;
    }
}
