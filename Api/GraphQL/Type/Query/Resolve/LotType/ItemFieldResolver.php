<?php
/**
 * SAM-10493: Implement a GraphQL nested structure for a lot
 * SAM-10581: Split lot GraphQL structure into item and lot
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Resolve\LotType;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use Sam\Core\Service\CustomizableClass;
use Sam\Api\GraphQL\AppContext;
use Sam\Api\GraphQL\Type\Query\Resolve\FieldResolverHelperCreateTrait;
use Sam\Api\GraphQL\Type\Query\Resolve\FieldResolverInterface;

/**
 * Class ItemFieldResolver
 * @package Sam\Api\GraphQL\Type\Query\Resolve\LotType
 */
class ItemFieldResolver extends CustomizableClass implements FieldResolverInterface
{
    use FieldResolverHelperCreateTrait;

    protected Closure $lazyItemTypeDefinition;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(callable $lazyItemTypeDefinition): static
    {
        $this->lazyItemTypeDefinition = $lazyItemTypeDefinition;
        return $this;
    }

    public function dependentOnDataFields(array $referencedFieldNodes): array
    {
        $lazyItemTypeDefinition = $this->lazyItemTypeDefinition;
        $dependentOnFields = $this->createFieldResolverHelper()
            ->collectDependentOnFields($lazyItemTypeDefinition(), $referencedFieldNodes);
        return $dependentOnFields;
    }

    public function resolve(array $objectValue, array $args, AppContext $appContext, ResolveInfo $info): array
    {
        return $objectValue;
    }
}
