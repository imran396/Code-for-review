<?php
/**
 * A strategy how we will select "Other lots"
 *
 * @see https://bidpath.atlassian.net/browse/SAM-3241 Refactor Live and Timed Lot Details pages of responsive side
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 31, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\OtherLots\ShowStrategy;

use AuctionLotItem;

/**
 * Interface ShowStrategyInterface
 */
interface ShowStrategyInterface
{

    /**
     * @param int $action
     * @param int $currentPage
     * @return AuctionLotItem[]
     */
    public function getAuctionLots(int $action, int $currentPage): iterable;

    /**
     * @param bool $refresh
     * @return int
     */
    public function countAllAuctionLots(bool $refresh = false): int;

    /**
     * @return int
     */
    public function getLastPage(): int;

    /**
     * @param int $action
     * @param int $currentPage
     * @return int
     */
    public function getNextPageFromAction(int $action, int $currentPage): int;
}