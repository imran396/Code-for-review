<?php
/**
 * Unassigned Lot Items Constants
 *
 * SAM-6273: Refactor Lot Items Panel at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 9, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotItemsPanel;

/**
 * Class UnassignedLotItemsConstants
 */
class UnassignedLotItemsConstants
{
    public const ORD_ACCOUNT_NAME = 'account_name';
    public const ORD_CONSIGNOR_USERNAME = 'consignor_username';
    public const ORD_CREATED_USERNAME = 'created_username';
    public const ORD_HAMMER_PRICE = 'hammer_price';
    public const ORD_ITEM_NUM = 'item_num';
    public const ORD_IMAGE_COUNT = 'image_count';
    public const ORD_LOT_NAME = 'lot_name';
    public const ORD_LOT_NO = 'lot_num';
    public const ORD_LOW_ESTIMATE = 'low_estimate';
    public const ORD_RECENT_AUCTION = 'recent_auction';
    public const ORD_STARTING_BID = 'starting_bid';
    public const ORD_WINNER_USERNAME = 'winner_username';
    public const ORD_DEFAULT = self::ORD_ITEM_NUM;

    public const CSS_CLASS_DTG_LOTS_COL_CUSTOM_FIELDS_PREFIX = 'uli-cf-';
    public const CSS_CLASS_DTG_LOTS_COL_ITEM_NUM = 'uli-item-num';
    public const CSS_CLASS_DTG_LOTS_COL_LOT_IMAGE = 'uli-image';
    public const CSS_CLASS_DTG_LOTS_COL_CATEGORY = 'uli-cat';
    public const CSS_CLASS_DTG_LOTS_COL_LOT_NAME = 'uli-name';
    public const CSS_CLASS_DTG_LOTS_COL_ESTIMATE = 'uli-est';
    public const CSS_CLASS_DTG_LOTS_COL_STARTING_BID = 'uli-start-bid';
    public const CSS_CLASS_DTG_LOTS_COL_HAMMER_PRICE = 'uli-hammer-price';
    public const CSS_CLASS_DTG_LOTS_COL_WINNING_BIDDER = 'uli-winner';
    public const CSS_CLASS_DTG_LOTS_COL_CONSIGNOR = 'uli-consignor';
    public const CSS_CLASS_DTG_LOTS_COL_CREATOR_MODIFIED = 'uli-creator-modified';
    public const CSS_CLASS_DTG_LOTS_COL_AUCTION_NUM = 'uli-auction-num';
    public const CSS_CLASS_DTG_LOTS_COL_ACCOUNT_NAME = 'uli-account-name';
    public const CSS_CLASS_DTG_LOTS_COL_ACTIONS = 'uli-actions';
    public const CSS_CLASS_DTG_LOTS_COL_SELECT = 'uli-select';
}
