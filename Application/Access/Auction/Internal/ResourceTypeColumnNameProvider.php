<?php
/**
 * SAM-7764: Refactor \Auction_Access class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Access\Auction\Internal;

use Laminas\Filter\Word\UnderscoreToCamelCase;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Map of access resources and DB columns in which its values are stored
 *
 * Class ResourceTypeColumnNameProvider
 * @package Sam\Application\Access\Auction\Internal
 * @internal
 */
class ResourceTypeColumnNameProvider extends CustomizableClass
{
    protected const RESOURCE_TYPE_COLUMN_NAMES = [
        Constants\Auction::ACCESS_RESTYPE_AUCTION_VISIBILITY => 'auction_visibility_access',
        Constants\Auction::ACCESS_RESTYPE_AUCTION_INFO => 'auction_info_access',
        Constants\Auction::ACCESS_RESTYPE_AUCTION_CATALOG => 'auction_catalog_access',
        Constants\Auction::ACCESS_RESTYPE_LIVE_VIEW => 'live_view_access',
        Constants\Auction::ACCESS_RESTYPE_LOT_DETAILS => 'lot_details_access',
        Constants\Auction::ACCESS_RESTYPE_LOT_BIDDING_HISTORY => 'lot_bidding_history_access',
        Constants\Auction::ACCESS_RESTYPE_LOT_WINNING_BID => 'lot_winning_bid_access',
        Constants\Auction::ACCESS_RESTYPE_LOT_BIDDING_INFO => 'lot_bidding_info_access',
        Constants\Auction::ACCESS_RESTYPE_LOT_STARTING_BID => 'lot_starting_bid_access',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Detect DB column name in which access resource value is stored
     *
     * @param int $resourceType
     * @return string
     */
    public function getColumnName(int $resourceType): string
    {
        if (!array_key_exists($resourceType, static::RESOURCE_TYPE_COLUMN_NAMES)) {
            throw new \InvalidArgumentException('Resource type does not exist');
        }

        $columnName = static::RESOURCE_TYPE_COLUMN_NAMES[$resourceType];
        return $columnName;
    }

    /**
     * Detect entity property in which access resource value is stored
     *
     * @param int $resourceType
     * @return string
     */
    public function getPropertyName(int $resourceType): string
    {
        if (!array_key_exists($resourceType, static::RESOURCE_TYPE_COLUMN_NAMES)) {
            throw new \InvalidArgumentException('Resource type does not exist');
        }

        $filterToCamelCase = new UnderscoreToCamelCase();
        $propertyName = $filterToCamelCase->filter(static::RESOURCE_TYPE_COLUMN_NAMES[$resourceType]);
        return $propertyName;
    }
}
