<?php
/**
 * SAM-9766: Check Printing for Settlements: Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Content\Build\Internal\AmountSpelling;

use NumberFormatter;
use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Content\Build\Internal\AmountSpelling\Internal\Load\DataProviderCreateTrait;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class AmountSpellingRenderer
 * @package Sam\Settlement\Check
 */
class AmountSpellingRenderer extends CustomizableClass
{
    use AdminTranslatorAwareTrait;
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Produce spelling for amount value according to system locale of settlement account.
     * @param float|null $amount
     * @param int $settlementAccountId
     * @return string
     */
    public function renderAmountSpelling(?float $amount, int $settlementAccountId): string
    {
        $locale = $this->createDataProvider()->loadLocale($settlementAccountId);
        return $this->translateAmountSpellingTemplate($amount, $locale);
    }

    /**
     * Produce spelling view of integer part of decimal number and fractional part of decimal number.
     * @param float|null $amount
     * @param string $locale
     * @return array
     */
    public function makeAmountSpellingParts(?float $amount, string $locale): array
    {
        $integer = (int)$amount;
        $fractional = round(($amount - $integer) * 100);
        $numberFormatter = new NumberFormatter($locale, NumberFormatter::SPELLOUT);
        $integerSpelling = $numberFormatter->format($integer);
        $integerSpelling = mb_convert_case($integerSpelling, MB_CASE_TITLE, "UTF-8");
        $fractionalFormatted = sprintf('%02d', $fractional);
        return [$integerSpelling, $fractionalFormatted];
    }

    /**
     * Translate template for amount spelling according locale. I.e. translate something like "%s and %s".
     * @param float|null $amount
     * @param string $locale
     * @return string
     */
    public function translateAmountSpellingTemplate(?float $amount, string $locale): string
    {
        if ($amount === null) {
            return '';
        }

        [$integerSpelling, $fractionalFormatted] = $this->makeAmountSpellingParts($amount, $locale);
        $spelling = $this->getAdminTranslator()->trans(
            'check.amount_spelling_tpl',
            [
                'integer' => $integerSpelling,
                'fractional' => $fractionalFormatted
            ],
            'admin_settlement'
        );
        return $spelling;
    }
}
