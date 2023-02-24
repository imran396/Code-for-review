<?php
/**
 * SAM-10467: Implement a GraphQL nested structure for a single auction
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type;

use Sam\Api\GraphQL\Type\Mutation\Definition\CreateJwtType;
use Sam\Api\GraphQL\Type\Mutation\Definition\JwtType;
use Sam\Api\GraphQL\Type\Mutation\Definition\MutationType;
use Sam\Api\GraphQL\Type\Query\Definition\AccountType;
use Sam\Api\GraphQL\Type\Query\Definition\Aggregate\ItemAggregateType;
use Sam\Api\GraphQL\Type\Query\Definition\Aggregate\ItemCustomFieldCollectionAggregateType;
use Sam\Api\GraphQL\Type\Query\Definition\Aggregate\LotAggregateType;
use Sam\Api\GraphQL\Type\Query\Definition\Aggregate\LotCategoryAggregateType;
use Sam\Api\GraphQL\Type\Query\Definition\Aggregate\TaxDefinitionAggregateType;
use Sam\Api\GraphQL\Type\Query\Definition\Aggregate\TaxSchemaAggregateType;
use Sam\Api\GraphQL\Type\Query\Definition\Auction\AuctionAbsenteeBidDisplayType;
use Sam\Api\GraphQL\Type\Query\Definition\Auction\AuctionDateAssignmentStrategyType;
use Sam\Api\GraphQL\Type\Query\Definition\Auction\AuctionEventType;
use Sam\Api\GraphQL\Type\Query\Definition\Auction\AuctionProgressStatusType;
use Sam\Api\GraphQL\Type\Query\Definition\Auction\AuctionStatusType;
use Sam\Api\GraphQL\Type\Query\Definition\Auction\AuctionTypeType;
use Sam\Api\GraphQL\Type\Query\Definition\Auction\Filter\AuctionFilterType;
use Sam\Api\GraphQL\Type\Query\Definition\Auction\Filter\AuctionStatusFilterType;
use Sam\Api\GraphQL\Type\Query\Definition\Auction\Filter\AuctionTypeFilterType;
use Sam\Api\GraphQL\Type\Query\Definition\AuctionBidderType;
use Sam\Api\GraphQL\Type\Query\Definition\AuctioneerType;
use Sam\Api\GraphQL\Type\Query\Definition\AuctionSettingsType;
use Sam\Api\GraphQL\Type\Query\Definition\AuctionType;
use Sam\Api\GraphQL\Type\Query\Definition\Common\Filter\StringFilterType;
use Sam\Api\GraphQL\Type\Query\Definition\Common\Order\OrderDirectionType;
use Sam\Api\GraphQL\Type\Query\Definition\Common\Scalar\BigIntType;
use Sam\Api\GraphQL\Type\Query\Definition\CurrencyType;
use Sam\Api\GraphQL\Type\Query\Definition\CustomField\Auction\AuctionCustomFieldType;
use Sam\Api\GraphQL\Type\Query\Definition\CustomField\Auction\AuctionCustomFieldValueCollectionType;
use Sam\Api\GraphQL\Type\Query\Definition\CustomField\Auction\AuctionCustomFieldValueType;
use Sam\Api\GraphQL\Type\Query\Definition\CustomField\Item\ItemCustomFieldType;
use Sam\Api\GraphQL\Type\Query\Definition\CustomField\Item\ItemCustomFieldValueCollectionType;
use Sam\Api\GraphQL\Type\Query\Definition\CustomField\Item\ItemCustomFieldValueType;
use Sam\Api\GraphQL\Type\Query\Definition\Enum\ImageSizeType;
use Sam\Api\GraphQL\Type\Query\Definition\ItemImageType;
use Sam\Api\GraphQL\Type\Query\Definition\ItemType;
use Sam\Api\GraphQL\Type\Query\Definition\Location\LocationFilterType;
use Sam\Api\GraphQL\Type\Query\Definition\Location\LocationOrderType;
use Sam\Api\GraphQL\Type\Query\Definition\LocationType;
use Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\Internal\Type\LotListAuctionTypeFilterType;
use Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\Internal\Type\LotListCategoryMatchFilterType;
use Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\Internal\Type\LotListCustomFieldsFilterType;
use Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\Internal\Type\LotListDateCustomFieldFilterType;
use Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\Internal\Type\LotListDecimalCustomFieldFilterType;
use Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\Internal\Type\LotListIntegerCustomFieldFilterType;
use Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\Internal\Type\LotListPostalCodeCustomFieldFilterType;
use Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\Internal\Type\LotListTimedOptionFilterType;
use Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\LotListCatalogFilterType;
use Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\LotListMyItemsFilterType;
use Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\LotListSearchFilterType;
use Sam\Api\GraphQL\Type\Query\Definition\Lot\LotStatusType;
use Sam\Api\GraphQL\Type\Query\Definition\Lot\MyItemsPageType;
use Sam\Api\GraphQL\Type\Query\Definition\Lot\Sort\LotListCatalogSortType;
use Sam\Api\GraphQL\Type\Query\Definition\Lot\Sort\LotListSearchSortType;
use Sam\Api\GraphQL\Type\Query\Definition\LotCategoryType;
use Sam\Api\GraphQL\Type\Query\Definition\LotType;
use Sam\Api\GraphQL\Type\Query\Definition\QueryType;
use Sam\Api\GraphQL\Type\Query\Definition\TaxDefinition\TaxDefinitionFilterType;
use Sam\Api\GraphQL\Type\Query\Definition\TaxDefinition\TaxDefinitionOrderType;
use Sam\Api\GraphQL\Type\Query\Definition\TaxDefinitionType;
use Sam\Api\GraphQL\Type\Query\Definition\TaxSchema\TaxSchemaAmountSourceType;
use Sam\Api\GraphQL\Type\Query\Definition\TaxSchema\TaxSchemaFilterType;
use Sam\Api\GraphQL\Type\Query\Definition\TaxSchema\TaxSchemaOrderType;
use Sam\Api\GraphQL\Type\Query\Definition\TaxSchemaType;
use Sam\Api\GraphQL\Type\Query\Definition\TimezoneType;
use Sam\Api\GraphQL\Type\Query\Definition\UserType;
use Sam\Core\Service\CustomizableClass;

/**
 * Class KnownTypes
 * @package Sam\Api\GraphQL\Type
 */
class KnownTypes extends CustomizableClass
{
    protected const TYPE_CLASSES = [
        AccountType::NAME => AccountType::class,
        AuctionAbsenteeBidDisplayType::NAME => AuctionAbsenteeBidDisplayType::class,
        AuctionBidderType::NAME => AuctionBidderType::class,
        AuctionCustomFieldType::NAME => AuctionCustomFieldType::class,
        AuctionCustomFieldValueCollectionType::NAME => AuctionCustomFieldValueCollectionType::class,
        AuctionCustomFieldValueType::NAME => AuctionCustomFieldValueType::class,
        AuctionDateAssignmentStrategyType::NAME => AuctionDateAssignmentStrategyType::class,
        AuctionEventType::NAME => AuctionEventType::class,
        AuctionFilterType::NAME => AuctionFilterType::class,
        AuctionProgressStatusType::NAME => AuctionProgressStatusType::class,
        AuctionSettingsType::NAME => AuctionSettingsType::class,
        AuctionStatusFilterType::NAME => AuctionStatusFilterType::class,
        AuctionStatusType::NAME => AuctionStatusType::class,
        AuctionType::NAME => AuctionType::class,
        AuctionTypeFilterType::NAME => AuctionTypeFilterType::class,
        AuctionTypeType::NAME => AuctionTypeType::class,
        AuctioneerType::NAME => AuctioneerType::class,
        CurrencyType::NAME => CurrencyType::class,
        ImageSizeType::NAME => ImageSizeType::class,
        ItemCustomFieldType::NAME => ItemCustomFieldType::class,
        ItemCustomFieldValueCollectionType::NAME => ItemCustomFieldValueCollectionType::class,
        ItemCustomFieldValueType::NAME => ItemCustomFieldValueType::class,
        ItemImageType::NAME => ItemImageType::class,
        ItemType::NAME => ItemType::class,
        LocationFilterType::NAME => LocationFilterType::class,
        LocationOrderType::NAME => LocationOrderType::class,
        LocationType::NAME => LocationType::class,
        LotCategoryType::NAME => LotCategoryType::class,
        LotListAuctionTypeFilterType::NAME => LotListAuctionTypeFilterType::class,
        LotListCatalogFilterType::NAME => LotListCatalogFilterType::class,
        LotListCatalogSortType::NAME => LotListCatalogSortType::class,
        LotListCategoryMatchFilterType::NAME => LotListCategoryMatchFilterType::class,
        LotListCustomFieldsFilterType::NAME => LotListCustomFieldsFilterType::class,
        LotListDateCustomFieldFilterType::NAME => LotListDateCustomFieldFilterType::class,
        LotListDecimalCustomFieldFilterType::NAME => LotListDecimalCustomFieldFilterType::class,
        LotListIntegerCustomFieldFilterType::NAME => LotListIntegerCustomFieldFilterType::class,
        LotListMyItemsFilterType::NAME => LotListMyItemsFilterType::class,
        LotListPostalCodeCustomFieldFilterType::NAME => LotListPostalCodeCustomFieldFilterType::class,
        LotListSearchFilterType::NAME => LotListSearchFilterType::class,
        LotListSearchSortType::NAME => LotListSearchSortType::class,
        LotListTimedOptionFilterType::NAME => LotListTimedOptionFilterType::class,
        LotStatusType::NAME => LotStatusType::class,
        LotType::NAME => LotType::class,
        MyItemsPageType::NAME => MyItemsPageType::class,
        QueryType::NAME => QueryType::class,
        TaxDefinitionFilterType::NAME => TaxDefinitionFilterType::class,
        TaxDefinitionOrderType::NAME => TaxDefinitionOrderType::class,
        TaxDefinitionType::NAME => TaxDefinitionType::class,
        TaxSchemaAmountSourceType::NAME => TaxSchemaAmountSourceType::class,
        TaxSchemaFilterType::NAME => TaxSchemaFilterType::class,
        TaxSchemaOrderType::NAME => TaxSchemaOrderType::class,
        TaxSchemaType::NAME => TaxSchemaType::class,
        TimezoneType::NAME => TimezoneType::class,
        UserType::NAME => UserType::class,

        //Aggregate
        ItemAggregateType::NAME => ItemAggregateType::class,
        ItemCustomFieldCollectionAggregateType::NAME => ItemCustomFieldCollectionAggregateType::class,
        LotAggregateType::NAME => LotAggregateType::class,
        LotCategoryAggregateType::NAME => LotCategoryAggregateType::class,
        TaxDefinitionAggregateType::NAME => TaxDefinitionAggregateType::class,
        TaxSchemaAggregateType::NAME => TaxSchemaAggregateType::class,

        // Mutations
        CreateJwtType::NAME => CreateJwtType::class,
        JwtType::NAME => JwtType::class,
        MutationType::NAME => MutationType::class,

        // Common
        StringFilterType::NAME => StringFilterType::class,
        OrderDirectionType::NAME => OrderDirectionType::class,
        BigIntType::NAME => BigIntType::class,
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function getClass(string $typeName): ?string
    {
        return self::TYPE_CLASSES[$typeName] ?? null;
    }
}
