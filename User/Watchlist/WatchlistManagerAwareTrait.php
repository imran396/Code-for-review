<?php
/**
 * Trait that implements user watchlist Manager accessors
 *
 * SAM-3624: Watchlist Manager class integration
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           28 Feb, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Watchlist;

/**
 * Trait WatchlistManagerAwareTrait
 * @package Sam\User\Watchlist
 */
trait WatchlistManagerAwareTrait
{
    protected ?WatchlistManager $watchlistManager = null;

    /**
     * @return WatchlistManager
     */
    protected function getWatchlistManager(): WatchlistManager
    {
        if ($this->watchlistManager === null) {
            $this->watchlistManager = WatchlistManager::new();
        }
        return $this->watchlistManager;
    }

    /**
     * @param WatchlistManager $watchlistManager
     * @return static
     */
    public function setWatchlistManager(WatchlistManager $watchlistManager): static
    {
        $this->watchlistManager = $watchlistManager;
        return $this;
    }
}
