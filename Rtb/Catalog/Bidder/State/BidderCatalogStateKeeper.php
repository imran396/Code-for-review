<?php
/**
 * This singleton caches html catalog rendered at consoles
 *
 * SAM-10431: Refactor rtb catalog renderer for v3-7
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Mar 21, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Catalog\Bidder\State;

use Sam\Core\Service\Singleton;

/**
 * Class Keeper
 * @package Sam\Rtb\Catalog\Bidder\Base
 */
class BidderCatalogStateKeeper extends Singleton
{
    protected array $catalog = [];
    protected array $catalogLastMod = [];

    /**
     * @return static
     */
    public static function getInstance(): static
    {
        return parent::_getInstance(self::class);
    }

    /**
     * Check if catalog should be created from scratch
     * if catalog structure does not exist
     * or if it does not have any items
     * or if expired (older than CatalogLifeTime seconds)
     * or static file does not exist
     * or static file on the server is expired (older than CatalogLifeTime seconds)
     * @param int $auctionId
     * @param int $destFileMTime
     * @return bool
     */
    public function needUpdate(int $auctionId, int $destFileMTime): bool
    {
        $catalogLifeTime = 60 * 30; // catalog max lifetime in seconds
        $createdTime = $this->catalogLastMod[$auctionId] ?? 0;

        $need = (!isset($this->catalog[$auctionId])
            || count($this->catalog[$auctionId]) === 0
            || ($createdTime > 0
                && $createdTime + $catalogLifeTime <= time())
            || !$destFileMTime
            || $destFileMTime + $catalogLifeTime <= time());
        return $need;
    }

    /**
     * @param int $auctionId
     * @param array $catalogInfo
     * @return static
     */
    public function set(int $auctionId, array $catalogInfo): static
    {
        $this->catalogLastMod[$auctionId] = time();
        $this->catalog[$auctionId] = $catalogInfo;
        return $this;
    }

    /**
     * @param int $auctionId
     * @param int $lotItemId
     * @param string $line
     * @return static
     */
    public function setRow(int $auctionId, int $lotItemId, string $line): static
    {
        $this->catalog[$auctionId][$lotItemId] = trim($line);
        return $this;
    }

    /**
     * @param int $auctionId
     * @param int $lotItemId
     * @return bool
     */
    public function checkRowExists(int $auctionId, int $lotItemId): bool
    {
        $isExists = array_key_exists($auctionId, $this->catalog)
            && array_key_exists($lotItemId, $this->catalog[$auctionId]);
        return $isExists;
    }

    /**
     * @param int $auctionId
     * @return array
     */
    public function get(int $auctionId): array
    {
        return $this->catalog[$auctionId];
    }

    /**
     * @param int $auctionId
     */
    public function drop(int $auctionId): void
    {
        unset($this->catalog[$auctionId], $this->catalogLastMod[$auctionId]);
    }
}
