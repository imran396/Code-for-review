<?php
/**
 * Helping methods for items in watchlist management
 *
 * SAM-3633: User watchlist manager and repository  https://bidpath.atlassian.net/browse/SAM-3633
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           23 Feb, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Watchlist;

use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\UserWatchlist\UserWatchlistReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\UserWatchlist\UserWatchlistWriteRepositoryAwareTrait;

/**
 * Class WatchlistManager
 * @package Sam\User\Watchlist
 */
class WatchlistManager extends CustomizableClass
{
    use EntityFactoryCreateTrait;
    use SettingsManagerAwareTrait;
    use UserWatchlistReadRepositoryCreateTrait;
    use UserWatchlistWriteRepositoryAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Add item to watchlist
     * @param int|null $userId
     * @param int $lotItemId
     * @param int $auctionId
     * @param int $editorUserId
     * @return bool
     */
    public function add(?int $userId, int $lotItemId, int $auctionId, int $editorUserId): bool
    {
        if (
            !$userId
            || !$lotItemId
            || !$auctionId
        ) {
            return false;
        }

        $info = composeSuffix(['u' => $userId, 'li' => $lotItemId, 'a' => $auctionId]);
        if (!$this->exist($userId, $lotItemId, $auctionId)) {
            $watchlist = $this->createEntityFactory()->userWatchlist();
            $watchlist->AuctionId = $auctionId;
            $watchlist->LotItemId = $lotItemId;
            $watchlist->UserId = $userId;
            $this->getUserWatchlistWriteRepository()->saveWithModifier($watchlist, $editorUserId);
            log_debug('Item added to watchlist' . $info);
            $isAdded = true;
        } else {
            log_error('Cannot add item to watchlist, item exists' . $info);
            $isAdded = false;
        }
        return $isAdded;
    }

    /**
     * Add item to watchlist after placed bid, if respective setting is on
     * @param int|null $userId
     * @param int $lotItemId
     * @param int $auctionId
     * @param int $editorUserId
     */
    public function autoAdd(?int $userId, int $lotItemId, int $auctionId, int $editorUserId): void
    {
        if (
            !$userId
            || !$lotItemId
            || !$auctionId
        ) {
            return;
        }

        $addBidsToWatchlist = $this->getSettingsManager()->getForMain(Constants\Setting::ADD_BIDS_TO_WATCHLIST);
        if (
            $addBidsToWatchlist
            && !$this->exist($userId, $lotItemId, $auctionId)
        ) {
            $this->add($userId, $lotItemId, $auctionId, $editorUserId);
        }
    }

    /**
     * Check item in watchlist
     * @param int|null $userId
     * @param int $lotItemId
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function exist(?int $userId, int $lotItemId, int $auctionId, bool $isReadOnlyDb = false): bool
    {
        if (
            !$userId
            || !$lotItemId
            || !$auctionId
        ) {
            return false;
        }

        $isFound = $this->createUserWatchlistReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->filterUserId($userId)
            ->exist();
        return $isFound;
    }

    /**
     * @param int|null $userId
     * @param int $lotItemId
     * @param int $auctionId
     * @param int $editorUserId
     */
    public function remove(?int $userId, int $lotItemId, int $auctionId, int $editorUserId): void
    {
        if (
            !$userId
            || !$lotItemId
            || !$auctionId
        ) {
            return;
        }

        $watchlist = $this->createUserWatchlistReadRepository()
            ->enableReadOnlyDb(true)
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->filterUserId($userId)
            ->loadEntity();
        $this->getUserWatchlistWriteRepository()->deleteWithModifier($watchlist, $editorUserId);
    }
}
