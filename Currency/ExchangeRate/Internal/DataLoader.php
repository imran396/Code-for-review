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

namespace Sam\Currency\ExchangeRate\Internal;

use Sam\Core\Service\CustomizableClass;
use Sam\Currency\ExchangeRate\ExchangeRate;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Storage\ReadRepository\Entity\Currency\CurrencyReadRepositoryCreateTrait;

/**
 * Class DataLoader
 * @package Sam\Currency\ExchangeRate\Internal
 */
class DataLoader extends CustomizableClass
{
    use CurrencyLoaderAwareTrait;
    use CurrencyReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param bool $isReadOnlyDb
     * @return ExchangeRate[]
     */
    public function loadAllCurrencyExchangeRates(bool $isReadOnlyDb = false): array
    {
        $rows = $this->createCurrencyReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->select(['id', 'name', 'sign', 'ex_rate'])
            ->filterActive(true)
            ->loadRows();

        $result = array_map(
            static function (array $row): ExchangeRate {
                return ExchangeRate::new()->construct((int)$row['id'], $row['name'], $row['sign'], (float)$row['ex_rate']);
            },
            $rows
        );
        return $result;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return string
     */
    public function loadDefaultCurrencySign(bool $isReadOnlyDb = false): string
    {
        $defaultCurrencyId = $this->getCurrencyLoader()->detectDefaultCurrencyId();
        if (!$defaultCurrencyId) {
            return '';
        }

        $row = $this->createCurrencyReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->select(['sign'])
            ->filterId($defaultCurrencyId)
            ->loadRow();
        $sign = $row['sign'] ?? '';
        return $sign;
    }
}
