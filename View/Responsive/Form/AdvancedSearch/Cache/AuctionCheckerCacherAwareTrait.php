<?php
/**
 * SAM-5877: Advanced search rendering module
 * SAM-5282 Show 'you won' on lot lists (catalog, search, my items)
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 15, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\Cache;

/**
 * Trait AuctionCheckerCacherAwareTrait
 */
trait AuctionCheckerCacherAwareTrait
{
    protected ?AuctionCheckerCacher $auctionCheckerCacher = null;

    /**
     * @return AuctionCheckerCacher
     */
    protected function getAuctionCheckerCacher(): AuctionCheckerCacher
    {
        if ($this->auctionCheckerCacher === null) {
            $this->auctionCheckerCacher = AuctionCheckerCacher::new();
        }
        return $this->auctionCheckerCacher;
    }

    /**
     * @param AuctionCheckerCacher $auctionCheckerCacher
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setAuctionCheckerCacher(AuctionCheckerCacher $auctionCheckerCacher): static
    {
        $this->auctionCheckerCacher = $auctionCheckerCacher;
        return $this;
    }
}
