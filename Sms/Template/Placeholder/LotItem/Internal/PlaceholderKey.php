<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 01, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template\Placeholder\LotItem\Internal;

/**
 * Class PlaceholderKey
 * @package Sam\Sms\Template\Placeholder\LotItem
 * @internal
 */
class PlaceholderKey
{
    public const CATEGORIES = 'categories';
    public const CONSIGNOR = 'consignor';
    public const COST = 'cost';
    public const DATE_SOLD = 'date_sold';
    public const DESCRIPTION = 'description';
    public const HAMMER_PRICE = 'hammer';
    public const HIGH_ESTIMATE = 'high_estimate';
    public const ID = 'id';
    public const IMAGES = 'images';
    public const INTERNET_BID = 'internet_bid';
    public const ITEM_NO = 'item_no';
    public const LOW_ESTIMATE = 'low_estimate';
    public const NAME = 'name';
    public const NO_TAX_OUTSIDE_STATE = 'no_tax_outside_state';
    public const REPLACEMENT_PRICE = 'replacement_price';
    public const RESERVE_PRICE = 'reserve';
    public const RETURNED = 'returned';
    public const SALES_TAX = 'sales_tax';
    public const SALE_SOLD_IN = 'sale_sold_in';
    public const SALE_SOLD_IN_NO = 'sale_sold_in_no';
    public const STARTING_BID = 'starting_bid';
    public const WARRANTY = 'warranty';
}
