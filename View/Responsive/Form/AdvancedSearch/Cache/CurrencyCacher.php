<?php
/**
 * SAM-5877: Advanced search rendering module
 * SAM-5282 Show 'you won' on lot lists (catalog, search, my items)
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 12, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\Cache;

use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;

/**
 * Class CurrencyCacher
 */
class CurrencyCacher extends CustomizableClass
{
    use CurrencyLoaderAwareTrait;

    /**
     * Cache for already detected values
     */
    private array $defaultCurrencies = [];
    /**
     * Cache for already detected values
     */
    private array $auctionCurrencies = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return default currency for an auction from array or db
     * @param int $auctionId
     * @return string
     */
    public function getDefaultCurrencySign(int $auctionId): string
    {
        if (!isset($this->defaultCurrencies[$auctionId])) {
            $this->defaultCurrencies[$auctionId] = $this->getCurrencyLoader()->detectDefaultSign($auctionId);
        }
        return $this->defaultCurrencies[$auctionId];
    }

    /**
     * Return array of currencies for an auction from array or db
     * @param int $auctionId
     * @return int[]
     */
    public function getAuctionCurrencyIds(int $auctionId): array
    {
        if (!$auctionId) {
            return [];
        }
        if (!isset($this->auctionCurrencies[$auctionId])) {
            $this->auctionCurrencies[$auctionId] = $this->getCurrencyLoader()
                ->loadCurrencyIdsForAuction($auctionId, true);
        }
        return $this->auctionCurrencies[$auctionId];
    }
}
