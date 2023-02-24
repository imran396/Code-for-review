<?php
/**
 * SAM-10493: Implement a GraphQL nested structure for a lot
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Sam\Api\GraphQL\Type\Query\Definition\Lot\LotStatusType;
use Sam\Api\GraphQL\Type\Query\Resolve;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LotType
 * @package Sam\Api\GraphQL\Type\Query\Definition
 */
class LotType extends CustomizableClass implements TypeInterface, TypeRegistryAwareInterface
{
    use TypeRegistryAwareTrait;

    public const NAME = 'Lot';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function createTypeDefinition(): ObjectType
    {
        return new ObjectType([
            'name' => self::NAME,
            'fields' => $this->makeFieldsConfiguration()
        ]);
    }

    protected function makeFieldsConfiguration(): array
    {
        $fields = [
            'id' => [
                'type' => Type::id(),
                'resolver' => Resolve\LotType\IdFieldResolver::new(),
            ],
            'best_offer' => Type::boolean(),
            'bulk_master_id' => Type::id(),
            'buy_amount' => Type::float(),
            'end_prebidding_date' => Type::string(),
            'is_bulk_master' => Type::boolean(),
            'listing' => Type::boolean(),
            'lot_num_full' => [
                'type' => Type::string(),
                'resolver' => Resolve\LotType\LotNumFullFieldResolver::new(),
            ],
            'lot_num' => Type::int(),
            'lot_num_ext' => Type::string(),
            'lot_num_prefix' => Type::string(),
            'lot_seo_url' => Type::string(),
            'lot_st_dt' => Type::string(),
            'status' => [
                'type' => $this->getTypeRegistry()->getTypeDefinition(LotStatusType::NAME),
                'resolver' => Resolve\LotType\StatusFieldResolver::new(),
            ],
            'no_bidding' => Type::boolean(),
            'order_num' => Type::int(),
            'qty' => Type::float(),
            'qty_x_money' => Type::boolean(),
            'qty_scale' => Type::int(),
            'rtb_current_lot_id' => Type::id(),
            'rtb_lot_active' => Type::boolean(),
            'rtb_lot_end_date' => Type::string(),
            'rtb_pause_date' => Type::string(),
            'seconds_before' => Type::int(),
            'seconds_left' => Type::int(),
            'start_bidding_date' => Type::string(),
            'starting_bid_normalized' => Type::float(),
            'terms_and_conditions' => Type::string(),
            'item' => [
                'type' => $this->getTypeRegistry()->getTypeDefinition(ItemType::NAME),
                'resolver' => Resolve\LotType\ItemFieldResolver::new()->construct(
                    $this->getTypeRegistry()->getTypeDefinition(ItemType::NAME)
                ),
            ],
            'auction' => [
                'type' => $this->getTypeRegistry()->getTypeDefinition(AuctionType::NAME),
                'resolver' => Resolve\LotType\AuctionFieldResolver::new(),
            ],
            'timezone' => [
                'type' => $this->getTypeRegistry()->getTypeDefinition(TimezoneType::NAME),
                'resolver' => Resolve\LotType\TimezoneFieldResolver::new(),
            ]
        ];
        return $fields;
    }
}
