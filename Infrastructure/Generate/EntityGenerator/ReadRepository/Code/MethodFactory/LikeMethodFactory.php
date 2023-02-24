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
use Sam\Storage\Metadata\DatabaseColumnMetadata;

/**
 * Class LikeMethodFactory
 * @package Sam\Infrastructure\Generate\EntityGenerator\ReadRepository\Code\Internal\Internal
 */
class LikeMethodFactory extends CustomizableClass
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
     * Construct filtering method like<ColumnName>() for the column
     *
     * @param DatabaseColumnMetadata $columnMetadata
     * @return Method
     */
    public function create(DatabaseColumnMetadata $columnMetadata): Method
    {
        $methodName = $this->makeMethodName($columnMetadata->getColumnName());

        $method = (new Method($methodName))
            ->setReturnType('static')
            ->setVisibility(ClassType::VISIBILITY_PUBLIC)
            ->setComment($this->makeMethodComment($columnMetadata))
            ->setBody($this->makeMethodBody($columnMetadata));

        $method->addParameter('filterValue')->setType('string');
        return $method;
    }

    protected function makeMethodComment(DatabaseColumnMetadata $columnMetadata): string
    {
        return <<<TEXT
Filter {$columnMetadata->getTableName()}.{$columnMetadata->getColumnName()} by LIKE statement
@param string \$filterValue
@return static
TEXT;
    }

    protected function makeMethodBody(DatabaseColumnMetadata $columnMetadata): string
    {
        return <<<PHP
\$this->like(\$this->alias . '.{$columnMetadata->getColumnName()}', "%{\$filterValue}%");
return \$this;
PHP;
    }

    protected function makeMethodName(string $columnName): string
    {
        return 'like' . TextTransformer::new()->toCamelCase($columnName);
    }
}
