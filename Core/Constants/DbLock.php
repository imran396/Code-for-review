<?php
/**
 * SAM-10557: Supply uniqueness of lot item fields: item#, unique lot custom fields
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

class DbLock
{
    /**
     * Lock name will be hashed to compose key of respective length (64 characters for GET_LOCK())
     */
    public const AUCTION_BY_ACCOUNT_ID_TPL = "Auction lock by acc: %s";
    public const AUCTION_LOT_BY_ACCOUNT_ID_TPL = "AuctionLot lock by acc: %s";
    public const INVOICE_BY_ACCOUNT_ID_TPL = "Invoice lock by acc: %s";
    public const LOT_ITEM_BY_ACCOUNT_ID_TPL = "LotItem lock by acc: %s";
    public const UNIQUE_LOT_ITEM_CUSTOM_FIELD_TPL = 'Unique LotItemCustData by acc: %s, licf: %s, text: %s';
    public const USER_MODIFICATION_LOCK = "User modification lock";
    public const AUCTION_LOT_FOR_LOT_NO_GENERATION_BY_AUCTION_ID_TPL = 'Auction Lot lock for lot# generation by a: %s';
    public const MULTIPLE_AUCTION_BIDDER_REGISTRATION_BY_AUCTION_ID_TPL = 'Multiple auction bidders registration lock by a: %s';
    public const SINGLE_AUCTION_BIDDER_REGISTRATION_BY_USER_ID_AND_AUCTION_ID_TPL = 'Single auction bidder registration lock by u: %s, a: %s';
}
