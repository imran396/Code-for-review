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

namespace Sam\Infrastructure\Generate\EntityGenerator\ReadRepository\Code\MethodFactory;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Text\TextTransformer;
use Sam\Storage\Metadata\DatabaseColumnMetadata;

/**
 * Class GroupMethodFactory
 * @package Sam\Infrastructure\Generate\EntityGenerator\ReadRepository\Code\Internal\Internal
 */
class GroupByMethodFactory extends CustomizableClass
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
     * Construct grouping method groupBy<ColumnName>() for the column
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
        return $method;
    }

    protected function makeMethodComment(DatabaseColumnMetadata $columnMetadata): string
    {
        return <<<TEXT
Group by {$columnMetadata->getTableName()}.{$columnMetadata->getColumnName()}
@return static
TEXT;
    }

    protected function makeMethodBody(DatabaseColumnMetadata $columnMetadata): string
    {
        return <<<PHP
\$this->group(\$this->alias . '.{$columnMetadata->getColumnName()}');
return \$this;
PHP;
    }

    protected function makeMethodName(string $columnName): string
    {
        return 'groupBy' . TextTransformer::new()->toCamelCase($columnName);
    }
}
