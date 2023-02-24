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

namespace Sam\Infrastructure\Generate\EntityGenerator\ReadRepository\Code\MethodFactory;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Text\TextTransformer;
use Sam\Storage\Mapping\DatabaseColumnTypeToPhpTypeConverterCreateTrait;
use Sam\Storage\Metadata\DatabaseColumnMetadata;

/**
 * Class SkipMethodFactory
 * @package Sam\Infrastructure\Generate\EntityGenerator\ReadRepository\Code\Internal\Internal
 */
class SkipMethodFactory extends CustomizableClass
{
    use DatabaseColumnTypeToPhpTypeConverterCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Construct filtering method skip<ColumnName>() for the column
     *
     * @param DatabaseColumnMetadata $columnMetadata
     * @return Method
     */
    public function create(DatabaseColumnMetadata $columnMetadata): Method
    {
        $methodName = $this->makeMethodName($columnMetadata->getColumnName());
        $columnPhpType = $this->createDatabaseColumnTypeToPhpTypeConverter()->convert($columnMetadata->getType());

        $method = (new Method($methodName))
            ->setReturnType('static')
            ->setVisibility(ClassType::VISIBILITY_PUBLIC)
            ->setComment($this->makeMethodComment($columnMetadata, $columnPhpType))
            ->setBody($this->makeMethodBody($columnMetadata));

        $type = "{$columnPhpType}|array";
        if ($columnMetadata->isNullable()) {
            $type .= '|null';
        }
        $method
            ->addParameter('skipValue')
            ->setType($type);
        return $method;
    }

    protected function makeMethodComment(DatabaseColumnMetadata $columnMetadata, string $columnPhpType): string
    {
        $phpDocType = "{$columnPhpType}|{$columnPhpType}[]";
        if ($columnMetadata->isNullable()) {
            $phpDocType .= '|null';
        }
        return <<<TEXT
Filter out {$columnMetadata->getTableName()}.{$columnMetadata->getColumnName()} from result
@param {$phpDocType} \$skipValue
@return static
TEXT;
    }

    protected function makeMethodBody(DatabaseColumnMetadata $columnMetadata): string
    {
        return <<<PHP
\$this->skipArray(\$this->alias . '.{$columnMetadata->getColumnName()}', \$skipValue);
return \$this;
PHP;
    }

    protected function makeMethodName(string $columnName): string
    {
        return 'skip' . TextTransformer::new()->toCamelCase($columnName);
    }
}
