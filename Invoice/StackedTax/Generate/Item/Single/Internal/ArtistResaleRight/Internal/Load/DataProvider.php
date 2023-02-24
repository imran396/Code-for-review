<?php
/**
 * SAM-9721: Refactor and implement unit test for single invoice producer
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Generate\Item\Single\Internal\ArtistResaleRight\Internal\Load;

use InvoiceAdditional;
use Sam\Core\Entity\Create\EntityFactory;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Convert\CurrencyConverter;
use Sam\Currency\Load\CurrencyLoader;

/**
 * Class DataProvider
 * @package Sam\Invoice\StackedTax\Generate\Item\Single\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function newInvoiceAdditional(): InvoiceAdditional
    {
        return EntityFactory::new()->invoiceAdditional();
    }

    public function detectDefaultCurrencyId(?int $auctionId = null, bool $isReadOnlyDb = false): ?int
    {
        return CurrencyLoader::new()->detectDefaultCurrencyId($auctionId, $isReadOnlyDb);
    }

    public function loadExRateById(int $currencyId, bool $isReadOnlyDb = false): ?float
    {
        return CurrencyLoader::new()->loadExRateById($currencyId, $isReadOnlyDb);
    }

    public function loadExRateByCode(string $code, bool $isReadOnlyDb = false): ?float
    {
        return CurrencyLoader::new()->loadExRateByCode($code, $isReadOnlyDb);
    }

    public function convertByRates(string $input, float $fromCurrencyExRate, float $toCurrencyExRate): float
    {
        return CurrencyConverter::new()->convertByRates($input, $fromCurrencyExRate, $toCurrencyExRate);
    }
}
