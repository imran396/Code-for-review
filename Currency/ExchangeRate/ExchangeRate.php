<?php
/**
 * SAM-7973: Refactor \Exrate_Sync_Ajax
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Currency\ExchangeRate;

use Sam\Core\Service\CustomizableClass;

/**
 * Currency exchange rate value object
 *
 * Class ExchangeRate
 * @package Sam\Currency\ExchangeRate
 */
class ExchangeRate extends CustomizableClass
{
    public readonly int $currencyId;
    public readonly string $currencyName;
    public readonly string $currencySign;
    public readonly float $exchangeRate;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $currencyId
     * @param string $name
     * @param string $sign
     * @param float $exchangeRate
     * @return static
     */
    public function construct(int $currencyId, string $name, string $sign, float $exchangeRate): static
    {
        $this->currencyId = $currencyId;
        $this->currencyName = $name;
        $this->currencySign = $sign;
        $this->exchangeRate = $exchangeRate;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'currencyId' => $this->currencyId,
            'currencyName' => $this->currencyName,
            'currencySign' => $this->currencySign,
            'exchangeRate' => $this->exchangeRate,
        ];
    }
}
