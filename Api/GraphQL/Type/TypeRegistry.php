<?php
/**
 * SAM-10319: Implement a GraphQL prototype for a list of auctions
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type;

use Closure;
use GraphQL\Type\Definition\Type;
use InvalidArgumentException;
use RuntimeException;
use Sam\Api\GraphQL\AppContext;
use Sam\Core\Service\CustomizableClass;

/**
 * Class TypeRegistry
 * @package Sam\Api\GraphQL
 */
class TypeRegistry extends CustomizableClass
{
    /** @var Type[]|Closure[] */
    protected array $typeDefinitions = [];
    protected AppContext $context;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(AppContext $context): static
    {
        $this->context = $context;
        return $this;
    }

    public function getTypeDefinition(string $typeName): Closure
    {
        if (
            isset($this->typeDefinitions[$typeName])
            && $this->typeDefinitions[$typeName] instanceof Closure
        ) {
            return $this->typeDefinitions[$typeName];
        }
        return function () use ($typeName) {
            if (!isset($this->typeDefinitions[$typeName])) {
                $this->typeDefinitions[$typeName] = $this->constructType($typeName)->createTypeDefinition();
            }
            return $this->typeDefinitions[$typeName];
        };
    }

    public function addTypeDefinition(string $typeName, Closure|Type $type): static
    {
        $this->typeDefinitions[$typeName] = $type;
        return $this;
    }

    public function hasType(string $typeName): bool
    {
        return isset($this->typeDefinitions[$typeName])
            || KnownTypes::new()->getClass($typeName);
    }

    protected function constructType(string $typeName): TypeInterface
    {
        $className = KnownTypes::new()->getClass($typeName);
        if (!$className) {
            throw new InvalidArgumentException("Unknown type '{$typeName}'");
        }
        if (!is_a($className, TypeInterface::class, true)) {
            throw new RuntimeException("Type '{$typeName}' should implement TypeInterface");
        }
        $type = [$className, 'new']();
        if ($type instanceof TypeRegistryAwareInterface) {
            $type->setTypeRegistry($this);
        }
        if ($type instanceof AppContextAwareInterface) {
            $type->setAppContext($this->context);
        }
        return $type;
    }
}
