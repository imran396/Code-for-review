<?php
/**
 * SAM-10956: Adjust GraphQL custom fields for auction structure
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 18, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Resolve\AuctionType;

use Closure;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use Sam\Api\GraphQL\AppContext;
use Sam\Api\GraphQL\Type\Query\Resolve\FieldResolverHelperCreateTrait;
use Sam\Api\GraphQL\Type\Query\Resolve\FieldResolverInterface;
use Sam\Core\Service\CustomizableClass;

/**
 * Class CustomFieldsFieldResolver
 * @package Sam\Api\GraphQL\Type\Query\Resolve\AuctionType
 */
class CustomFieldsFieldResolver extends CustomizableClass implements FieldResolverInterface
{
    use FieldResolverHelperCreateTrait;

    protected Closure|ObjectType $typeDefinition;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(Closure|ObjectType $lazyTypeDefinition): static
    {
        $this->typeDefinition = $lazyTypeDefinition;
        return $this;
    }

    public function dependentOnDataFields(array $referencedFieldNodes): array
    {
        $typeDefinition = $this->typeDefinition;
        if ($typeDefinition instanceof Closure) {
            $typeDefinition = $typeDefinition();
        }
        $dependentOnFields = $this->createFieldResolverHelper()
            ->collectDependentOnFields($typeDefinition, $referencedFieldNodes);
        return $dependentOnFields;
    }

    public function resolve(array $objectValue, array $args, AppContext $appContext, ResolveInfo $info): array
    {
        return $objectValue;
    }
}
