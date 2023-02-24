<?php
/**
 * SAM-10493: Implement a GraphQL nested structure for a lot
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Resolve\AuctionType;

use GraphQL\Executor\Promise\Promise;
use GraphQL\Type\Definition\ResolveInfo;
use Sam\Core\Service\CustomizableClass;
use Sam\Api\GraphQL\AppContext;
use Sam\Api\GraphQL\Type\Query\Resolve\FieldResolverHelperCreateTrait;
use Sam\Api\GraphQL\Type\Query\Resolve\FieldResolverInterface;
use Sam\Api\GraphQL\Type\Query\Resolve\Internal\FieldDataMapperCreateTrait;

/**
 * Class BidderFieldResolver
 * @package Sam\Api\GraphQL\Type\Query\Resolve\AuctionType
 */
class BidderFieldResolver extends CustomizableClass implements FieldResolverInterface
{
    use FieldDataMapperCreateTrait;
    use FieldResolverHelperCreateTrait;

    protected const BIDDER_TO_DATA_FIELD_MAP = [
        'id' => 'auction_bidder_id',
        'bidder_num' => 'bidder_num',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function dependentOnDataFields(array $referencedFieldNodes): array
    {
        $dependentOnFields = ['auction_bidder_id'];
        $referencedFields = array_keys($referencedFieldNodes);
        $dataMapper = $this->createFieldDataMapper();
        if ($dataMapper->hasMappingForAllReferencedFields($referencedFields, self::BIDDER_TO_DATA_FIELD_MAP)) {
            $dependentOnFields = array_merge(
                $dependentOnFields,
                $dataMapper->collectDataFields($referencedFields, self::BIDDER_TO_DATA_FIELD_MAP)
            );
            $dependentOnFields = array_unique($dependentOnFields);
        }
        return $dependentOnFields;
    }

    public function resolve(array $objectValue, array $args, AppContext $appContext, ResolveInfo $info): array|Promise|null
    {
        if ($objectValue['auction_bidder_id'] === null) {
            return null;
        }

        $bidderData = $this->createFieldDataMapper()->mapDataToType($objectValue, self::BIDDER_TO_DATA_FIELD_MAP);
        if ($this->createFieldResolverHelper()->hasEnoughDataToResolve($info, $bidderData)) {
            return $bidderData;
        }
        return $appContext->dataLoader->loadAuctionBidder((int)$objectValue['auction_bidder_id']);
    }
}
