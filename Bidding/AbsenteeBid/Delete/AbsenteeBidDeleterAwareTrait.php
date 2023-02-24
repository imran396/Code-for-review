<?php
/**
 * SAM-4452: Apply Auction Bidder Deleter
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/17/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\AbsenteeBid\Delete;

/**
 * Trait AbsenteeBidDeleterAwareTrait
 * @package Sam\Bidding\AbsenteeBid\Delete
 */
trait AbsenteeBidDeleterAwareTrait
{
    protected ?AbsenteeBidDeleter $absenteeBidDeleter = null;

    /**
     * @return AbsenteeBidDeleter
     */
    protected function getAbsenteeBidDeleter(): AbsenteeBidDeleter
    {
        if ($this->absenteeBidDeleter === null) {
            $this->absenteeBidDeleter = AbsenteeBidDeleter::new();
        }
        return $this->absenteeBidDeleter;
    }

    /**
     * @param AbsenteeBidDeleter $absenteeBidDeleter
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setAbsenteeBidDeleter(AbsenteeBidDeleter $absenteeBidDeleter): static
    {
        $this->absenteeBidDeleter = $absenteeBidDeleter;
        return $this;
    }
}
