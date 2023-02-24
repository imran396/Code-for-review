<?php
/**
 * SAM-10467: Implement a GraphQL nested structure for a single auction
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 28, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Sam\Api\GraphQL\Type\Query\Definition\Auction\AuctionAbsenteeBidDisplayType;
use Sam\Api\GraphQL\Type\Query\Definition\Auction\AuctionDateAssignmentStrategyType;
use Sam\Api\GraphQL\Type\Query\Definition\Auction\AuctionEventType;
use Sam\Api\GraphQL\Type\Query\Definition\Auction\AuctionProgressStatusType;
use Sam\Api\GraphQL\Type\Query\Definition\Auction\AuctionStatusType;
use Sam\Api\GraphQL\Type\Query\Definition\Auction\AuctionTypeType;
use Sam\Api\GraphQL\Type\Query\Definition\CustomField\Auction\AuctionCustomFieldValueCollectionType;
use Sam\Api\GraphQL\Type\Query\Definition\Enum\ImageSizeType;
use Sam\Api\GraphQL\Type\Query\Definition\Internal\CustomFieldHelperCreateTrait;
use Sam\Api\GraphQL\Type\Query\Resolve;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Auction\Load\AuctionCustomFieldLoaderAwareTrait;

/**
 * Class AuctionType
 * @package Sam\Api\GraphQL\Type
 */
class AuctionType extends CustomizableClass implements TypeInterface, TypeRegistryAwareInterface
{
    use AuctionCustomFieldLoaderAwareTrait;
    use CustomFieldHelperCreateTrait;
    use TypeRegistryAwareTrait;

    public const NAME = 'Auction';

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
        return new ObjectType(
            [
                'name' => self::NAME,
                'fields' => $this->makeFieldsConfiguration()
            ]
        );
    }

    protected function makeFieldsConfiguration(): array
    {
        $fields = [
            'id' => Type::id(),
            'auction_info_link' => Type::string(),
            'auction_seo_url' => Type::string(),
            'auction_type' => $this->getTypeRegistry()->getTypeDefinition(AuctionTypeType::NAME),
            'end_date' => Type::string(),
            'end_register_date' => Type::string(),
            'event_type' => $this->getTypeRegistry()->getTypeDefinition(AuctionEventType::NAME),
            'image_id' => Type::int(),
            'main_image_url' => [
                'type' => Type::string(),
                'args' => [
                    'size' => [
                        'type' => $this->getTypeRegistry()->getTypeDefinition(ImageSizeType::NAME),
                        'defaultValue' => Constants\Image::LARGE
                    ]
                ],
                'resolver' => Resolve\AuctionType\MainImageUrlFieldResolver::new(),
            ],
            'listing_only' => Type::boolean(),
            'name' => Type::string(),
            'sale_num_full' => [
                'type' => Type::string(),
                'resolver' => Resolve\AuctionType\SaleNumFullFieldResolver::new(),
            ],
            'sale_num' => Type::int(),
            'sale_num_ext' => Type::string(),
            'start_bidding_date' => Type::string(),
            'start_closing_date' => Type::string(),
            'extend_all_start_closing_date' => Type::string(),
            'extend_all' => Type::boolean(),
            'start_date' => Type::string(),
            'start_register_date' => Type::string(),
            'status' => [
                'type' => $this->getTypeRegistry()->getTypeDefinition(AuctionStatusType::NAME),
                'resolver' => Resolve\AuctionType\StatusFieldResolver::new(),
            ],
            'progress_status' => [
                'type' => $this->getTypeRegistry()->getTypeDefinition(AuctionProgressStatusType::NAME),
                'resolver' => Resolve\AuctionType\ProgressStatusFieldResolver::new(),
            ],
            'test_auction' => Type::boolean(),
            'total_lots' => Type::int(),
            'allow_force_bid' => Type::boolean(),
            'reverse' => Type::boolean(),
            'date_assignment_strategy' => $this->getTypeRegistry()->getTypeDefinition(AuctionDateAssignmentStrategyType::NAME),
            'extend_time' => Type::int(),
            'lot_start_gap_time' => Type::int(),
            'next_bid_button' => Type::boolean(),
            'notify_absentee_bidders' => Type::boolean(),
            'reserve_met_notice' => Type::boolean(),
            'reserve_not_met_notice' => Type::boolean(),
            'require_lot_change_confirmation' => Type::boolean(),
            'absentee_bids_display' => $this->getTypeRegistry()->getTypeDefinition(AuctionAbsenteeBidDisplayType::NAME),
            'account' => [
                'type' => $this->getTypeRegistry()->getTypeDefinition(AccountType::NAME),
                'resolver' => Resolve\AuctionType\AccountFieldResolver::new(),
            ],
            'event_location' => [
                'type' => $this->getTypeRegistry()->getTypeDefinition(LocationType::NAME),
                'resolver' => Resolve\AuctionType\LocationFieldResolver::new()->forEventLocation(),
            ],
            'invoice_location' => [
                'type' => $this->getTypeRegistry()->getTypeDefinition(LocationType::NAME),
                'resolver' => Resolve\AuctionType\LocationFieldResolver::new()->forInvoiceLocation(),
            ],
            'bidder' => [
                'type' => $this->getTypeRegistry()->getTypeDefinition(AuctionBidderType::NAME),
                'resolver' => Resolve\AuctionType\BidderFieldResolver::new(),
            ],
            'auctioneer' => [
                'type' => $this->getTypeRegistry()->getTypeDefinition(AuctioneerType::NAME),
                'resolver' => Resolve\AuctionType\AuctioneerFieldResolver::new(),
            ],
            'timezone' => [
                'type' => $this->getTypeRegistry()->getTypeDefinition(TimezoneType::NAME),
                'resolver' => Resolve\AuctionType\TimezoneFieldResolver::new(),
            ],
            'currency' => [
                'type' => $this->getTypeRegistry()->getTypeDefinition(CurrencyType::NAME),
                'resolver' => Resolve\AuctionType\CurrencyFieldResolver::new(),
            ],
            'settings' => [
                'type' => $this->getTypeRegistry()->getTypeDefinition(AuctionSettingsType::NAME),
                'resolver' => Resolve\AuctionType\SettingsFieldResolver::new(),
            ]
        ];

        /** @var ObjectType $customFieldsType */
        $customFieldsType = $this->getTypeRegistry()->getTypeDefinition(AuctionCustomFieldValueCollectionType::NAME)();
        if ($customFieldsType->getFieldNames()) {
            $fields['custom_fields'] = [
                'type' => $customFieldsType,
                'resolver' => Resolve\AuctionType\CustomFieldsFieldResolver::new()->construct($customFieldsType),
            ];
        }
        return $fields;
    }
}
