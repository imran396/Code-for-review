<?php
/**
 * SAM-9648: Drop "approved" flag from "auction_bidder" table
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\AuctionBidder\Query;

use Sam\Bidder\BidderNum\Pad\BidderNumberPaddingConfigProviderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Auction bidder status SQL expression builder
 *
 * Class AuctionBidderQueryBuilderHelper
 * @package Sam\Bidder\AuctionBidder\Query
 */
class AuctionBidderQueryBuilderHelper extends CustomizableClass
{
    use BidderNumberPaddingConfigProviderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function makeApprovedBidderExpression(string $tableAlias = Constants\Db::A_AUCTION_BIDDER): string
    {
        $bidderNumPadChar = $this->createBidderNumberPaddingConfigProvider()->getChar();
        return "IF(TRIM(LEADING '{$bidderNumPadChar}' FROM {$tableAlias}.bidder_num) NOT IN ('', '0'), TRUE, FALSE)";
    }

    public function makeApprovedBidderWhereClause(string $tableAlias = Constants\Db::A_AUCTION_BIDDER): string
    {
        return $this->makeApprovedBidderExpression($tableAlias) . ' = TRUE';
    }

    public function makeUnApprovedBidderWhereClause(string $tableAlias = Constants\Db::A_AUCTION_BIDDER): string
    {
        return $this->makeApprovedBidderExpression($tableAlias) . ' = FALSE';
    }
}
