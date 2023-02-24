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
 * Class OrderByMethodFactory
 * @package Sam\Infrastructure\Generate\EntityGenerator\ReadRepository\Code\Internal\Internal
 */
class OrderByMethodFactory extends CustomizableClass
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
     * Construct ordering method orderBy<ColumnName>() for the column
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

        $method->addParameter('ascending', true)->setType('bool');
        return $method;
    }

    protected function makeMethodComment(DatabaseColumnMetadata $columnMetadata): string
    {
        return <<<TEXT
Order by {$columnMetadata->getTableName()}.{$columnMetadata->getColumnName()}
@param bool \$ascending
@return static
TEXT;
    }

    protected function makeMethodBody(DatabaseColumnMetadata $columnMetadata): string
    {
        return <<<PHP
\$this->order(\$this->alias . '.{$columnMetadata->getColumnName()}', \$ascending);
return \$this;
PHP;
    }

    protected function makeMethodName(string $columnName): string
    {
        return 'orderBy' . TextTransformer::new()->toCamelCase($columnName);
    }
}
