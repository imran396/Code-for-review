<?php
/**
 *
 * SAM-4557: Settlement management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/12/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Currency;

use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Storage\ReadRepository\Entity\SettlementItem\SettlementItemReadRepositoryCreateTrait;

/**
 * Class SettlementCurrencyDetector
 * @package Sam\Settlement\Currency
 */
class SettlementCurrencyDetector extends CustomizableClass
{
    use CurrencyLoaderAwareTrait;
    use SettlementItemReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Returns the currency sign
     *
     * @param int $settlementId settlement.id
     * @param bool $isReadOnlyDb
     * @return string
     */
    public function detectSign(int $settlementId, bool $isReadOnlyDb = false): string
    {
        $settlementItem = $this->createSettlementItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterSettlementId($settlementId)
            ->filterActive(true)
            ->loadEntity();
        $auctionId = $settlementItem->AuctionId ?? null;
        $currencySign = $this->getCurrencyLoader()->detectDefaultSign($auctionId, $isReadOnlyDb);
        return $currencySign;
    }

    /**
     * Returns the currency sign and ex rate
     *
     * @param int $settlementId settlement.id
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function detectCurrencyAndExRate(int $settlementId, bool $isReadOnlyDb = false): array
    {
        $currencySign = $this->detectSign($settlementId, $isReadOnlyDb);
        $defaultSign = $this->getCurrencyLoader()->detectDefaultSign(null, $isReadOnlyDb);
        if ($currencySign !== $defaultSign) {
            $exRate = (float)$this->getCurrencyLoader()->loadExRateBySign($currencySign, $isReadOnlyDb);
        } else {
            $exRate = 1.;
        }
        return [$currencySign, $exRate];
    }
}
