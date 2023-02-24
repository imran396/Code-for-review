<?php
/**
 * SAM-5706: Credit card expiry date builder
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/21/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\CreditCard\Build;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Date\CurrentDateTrait;
use Sam\Core\Constants;

/**
 * Class CcExpiryDateBuilder.
 * (!) Class logic is not responsible for input validation.
 * @package Sam\Billing\CreditCard\Build
 */
class CcExpiryDateBuilder extends CustomizableClass
{
    use CurrentDateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return years available for expired date of credit card
     * @return int[]
     */
    public function calcAvailableYears(): array
    {
        $minYear = (int)$this->getCurrentDateUtc()->format('Y');
        $years = range($minYear, $minYear + 9);
        return $years;
    }

    /**
     * Convert CC expiry date in native format to MMYY format.
     * @param string|null $ccExpDate
     * @return string
     * #[Pure]
     */
    public function convertNativeToMmYy(?string $ccExpDate): string
    {
        [$month, $year] = $this->explode($ccExpDate);
        $result = $this->implodeToMmYy($month, $year);
        return $result;
    }

    /**
     * Convert CC expiry date in native format to YYYY-MM format.
     * This format is used for Authorize.net CIM transaction only, and not for regular Authorize.net transaction.
     * @param string|null $ccExpDate
     * @return string
     * #[Pure]
     */
    public function convertNativeToYyyyDashMm(?string $ccExpDate): string
    {
        [$month, $year] = $this->explode($ccExpDate);
        $result = $this->implodeToYyyyDashMm($month, $year);
        return $result;
    }

    /**
     * Explode expiration date formatted by MM-YYYY to string month and year.
     * Note: it returns months with leading zero "01".
     * @param string|null $expiryDate
     * @return string[] [month, year]
     * #[Pure]
     */
    public function explode(?string $expiryDate): array
    {
        $parts = explode('-', $expiryDate);
        $month = $parts[0] ?? '';
        $year = $parts[1] ?? '';
        return [$month, $year];
    }

    /**
     * Explode expiration date formatted by MM-YYYY to integer month and year.
     * It filters by available months (all 12 months), but not by available 10 years, because we may want to parse date in past.
     * @param string $expiryDate
     * @return int[]|null[]
     * #[Pure]
     */
    public function explodeToInt(string $expiryDate): array
    {
        $parts = explode('-', $expiryDate);
        $month = isset($parts[0]) ? Cast::toInt($parts[0], Constants\Date::$months) : null;
        $year = isset($parts[1]) ? Cast::toInt($parts[1], Constants\Type::F_INT_POSITIVE) : null;
        return [$month, $year];
    }

    /**
     * Make expiry date according native format MM-YYYY, eg. "01-2020"
     * @param int|string|null $month
     * @param int|string|null $year
     * @return string
     * #[Pure]
     */
    public function implode(int|string|null $month, int|string|null $year): string
    {
        $expiryDate = $this->makeMonth($month) . '-' . $this->makeFullYear($year);
        return $expiryDate;
    }

    /**
     * Make expiry date according format MMYY.
     * @param int|string|null $month
     * @param int|string|null $year
     * @return string
     * #[Pure]
     */
    public function implodeToMmYy(int|string|null $month, int|string|null $year): string
    {
        $expiryDate = $this->makeMonth($month) . $this->makeShortYear($year);
        return $expiryDate;
    }

    /**
     * Make expiry date according format MM-YYYY.
     * This format is used in Authorize.net payment.
     * @param int|string|null $month
     * @param int|string|null $year
     * @return string
     * #[Pure]
     */
    public function implodeToYyyyDashMm(int|string|null $month, int|string|null $year): string
    {
        $expiryDate = $this->makeFullYear($year) . '-' . $this->makeMonth($month);
        return $expiryDate;
    }

    /**
     * Make expiry date according format MM-YY.
     * @param int|string|null $month
     * @param int|string|null $year
     * @return string
     * #[Pure]
     */
    public function implodeToMmDashYy(int|string|null $month, int|string|null $year): string
    {
        $expiryDate = $this->makeMonth($month) . '-' . $this->makeShortYear($year);
        return $expiryDate;
    }

    /**
     * Make month in MM format.
     * @param int|string|null $month
     * @return string
     * #[Pure]
     */
    public function makeMonth(int|string|null $month): string
    {
        $ccExpMonth = sprintf('%02d', (int)$month);
        $ccExpMonth = substr($ccExpMonth, -2);
        return $ccExpMonth;
    }

    /**
     * Make year in YYYY format.
     * @param int|string|null $year
     * @return string
     * #[Pure]
     */
    public function makeFullYear(int|string|null $year): string
    {
        $year = (int)$year;
        if (strlen((string)$year) === 2) {
            $year = '20' . $year; // JIC, we don't have such case
        }
        $ccExpYear = sprintf('%04d', $year);
        $ccExpYear = substr($ccExpYear, -4);
        return $ccExpYear;
    }

    /**
     * Make year in YY format.
     * @param int|string|null $year
     * @return string
     * #[Pure]
     */
    public function makeShortYear(int|string|null $year): string
    {
        $shortYear = sprintf('%02d', (int)$year);
        $shortYear = substr($shortYear, -2);
        return $shortYear;
    }
}
