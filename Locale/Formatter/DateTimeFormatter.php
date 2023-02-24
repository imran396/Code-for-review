<?php
/**
 * SAM-5884: Admin side internationalization
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 07, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Locale\Formatter;

use Sam\Core\Service\CustomizableClass;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use IntlDateFormatter;
use Sam\Locale\LocaleAwareTrait;
use Sam\Timezone\ApplicationTimezoneProviderAwareTrait;

/**
 * This class contains methods for formatting date and time based on locale.
 *
 * Class DateTimeFormatter
 * @package Sam\Locale\Formatter
 */
class DateTimeFormatter extends CustomizableClass
{
    use ApplicationTimezoneProviderAwareTrait;
    use LocaleAwareTrait;

    /**
     * Date formats
     * https://www.php.net/manual/en/class.intldateformatter.php#intl.intldateformatter-constants
     */
    public const DATE_TYPE_FULL = IntlDateFormatter::FULL;
    public const DATE_TYPE_LONG = IntlDateFormatter::LONG;
    public const DATE_TYPE_MEDIUM = IntlDateFormatter::MEDIUM;
    public const DATE_TYPE_SHORT = IntlDateFormatter::SHORT;
    public const DATE_TYPE_NONE = IntlDateFormatter::NONE;

    /**
     * Time formats
     * https://www.php.net/manual/en/class.intldateformatter.php#intl.intldateformatter-constants
     */
    public const TIME_TYPE_FULL = IntlDateFormatter::FULL;
    public const TIME_TYPE_LONG = IntlDateFormatter::LONG;
    public const TIME_TYPE_MEDIUM = IntlDateFormatter::MEDIUM;
    public const TIME_TYPE_SHORT = IntlDateFormatter::SHORT;
    public const TIME_TYPE_SHORT_WITH_TZ = 4;
    public const TIME_TYPE_NONE = IntlDateFormatter::NONE;

    public const DEFAULT_DATE_TYPE = self::DATE_TYPE_MEDIUM;
    public const DEFAULT_TIME_TYPE = self::TIME_TYPE_SHORT_WITH_TZ;

    /** @var IntlDateFormatter[] */
    protected array $cachedFormatters = [];

    /** @var string[] */
    protected array $cachedPatterns = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * Format the date/time value as a string
     * @param DateTimeInterface|string|int|null $date Value to format.
     * @param string|null $timeZone NULL means default system timezone.
     * @param int|null $dateType Intl date formatter type. NULL means default.
     * @param int|null $timeType Intl time formatter type. NULL means default.
     * @param string|null $locale NULL means the most relevance locale or default if it is impossible to determine.
     * @param string|null $pattern NULL means the default or previously changed pattern.
     * @return string
     * @throws \Exception
     */
    public function format(
        DateTimeInterface|string|int|null $date,
        ?string $timeZone = null,
        ?int $dateType = null,
        ?int $timeType = null,
        ?string $locale = null,
        ?string $pattern = null
    ): string {
        $dateTime = $this->getDateTime($date);
        if ($dateTime) {
            $dateType = $dateType ?? static::DEFAULT_DATE_TYPE;
            $timeType = $timeType ?? static::DEFAULT_TIME_TYPE;
            $pattern = $pattern ?? $this->getPattern($dateType, $timeType, $locale);
            $timeZone = $timeZone ?? $this->getApplicationTimezoneProvider()->getSystemTimezone()->Location;

            $formatter = $this->getFormatter($dateType, $timeType, $locale, $timeZone, $pattern);
            return $formatter->format((int)$dateTime->format('U'));
        }
        return $date ?: '';
    }

    /**
     * Parse string to a DateTime value
     * @param string $date
     * @param string|null $timeZone
     * @param int|null $dateType
     * @param int|null $timeType
     * @param string|null $locale
     * @param string|null $pattern
     * @return DateTimeImmutable|null
     * @throws \Exception
     */
    public function parse(
        string $date,
        ?string $timeZone = null,
        ?int $dateType = null,
        ?int $timeType = null,
        ?string $locale = null,
        ?string $pattern = null
    ): ?DateTimeImmutable {
        if ($date) {
            $dateType = $dateType ?? static::DEFAULT_DATE_TYPE;
            $timeType = $timeType ?? static::DEFAULT_TIME_TYPE;
            $pattern = $pattern ?? $this->getPattern($dateType, $timeType, $locale);
            $timeZone = $timeZone ?? $this->getApplicationTimezoneProvider()->getSystemTimezone()->Location;

            $formatter = $this->getFormatter($dateType, $timeType, $locale, $timeZone, $pattern);
            $timestamp = $formatter->parse($date);

            if ($timestamp !== false) {
                $date = new DateTimeImmutable();
                $date = $date->setTimestamp($timestamp);
                return $date;
            }
        }
        return null;
    }

    /**
     * Format the date value as a string
     * @param DateTimeInterface|string|int|null $date Value to format.
     * @param string|null $timeZone NULL means default system timezone.
     * @param int|null $dateType Intl date formatter type. NULL means default.
     * @param string|null $locale NULL means the most relevance locale or default if it is impossible to determine.
     * @return string
     * @throws \Exception
     */
    public function formatDate(DateTimeInterface|string|int|null $date, ?string $timeZone = null, ?int $dateType = null, ?string $locale = null): string
    {
        return $this->format($date, $timeZone, $dateType, IntlDateFormatter::NONE, $locale);
    }

    /**
     * Format the time value as a string
     * @param DateTimeInterface|string|int|null $date Value to format.
     * @param string|null $timeZone NULL means default system timezone.
     * @param int|null $timeType Intl time formatter type. NULL means default.
     * @param string|null $locale
     * @return string
     * @throws \Exception
     */
    public function formatTime(DateTimeInterface|string|int|null $date, ?string $timeZone = null, ?int $timeType = null, ?string $locale = null): string
    {
        return $this->format($date, $timeZone, IntlDateFormatter::NONE, $timeType, $locale);
    }

    /**
     * Get the pattern used for the IntlDateFormatter
     * @param int $dateType Constant of IntlDateFormatter
     * @param int $timeType Constant IntlDateFormatter
     * @param string|null $locale
     * @return string
     */
    public function getPattern(int $dateType, int $timeType, ?string $locale = null): string
    {
        $locale = $locale ?? $this->getLocale();

        $key = $this->makePatternCacheKey($dateType, $timeType, $locale);
        if (!isset($this->cachedPatterns[$key])) {
            $this->cachedPatterns[$key] = $this->makePattern($dateType, $timeType, $locale);
        }
        return $this->cachedPatterns[$key];
    }

    /**
     * Update cached pattern
     * @param int $dateType Constant of IntlDateFormatter
     * @param int $timeType Constant of IntlDateFormatter
     * @param string|null $locale
     * @param string|null $pattern
     */
    public function updatePattern(int $dateType, int $timeType, ?string $locale = null, ?string $pattern = null): void
    {
        $locale = $locale ?? $this->getLocale();

        $key = $this->makePatternCacheKey($dateType, $timeType, $locale);
        if ($pattern) {
            $this->cachedPatterns[$key] = $pattern;
        } else {
            $this->cachedPatterns[$key] = $this->makePattern($dateType, $timeType, $locale);
        }
    }

    /**
     * @param int $dateType
     * @param int $timeType
     * @param string $locale
     * @return string
     */
    private function makePatternCacheKey(int $dateType, int $timeType, string $locale): string
    {
        $key = md5(serialize([$dateType, $timeType, $locale]));
        return $key;
    }

    /**
     * @param int $dateType
     * @param int $timeType
     * @param string $locale
     * @return string
     */
    private function makePattern(int $dateType, int $timeType, string $locale): string
    {
        if ($timeType === self::TIME_TYPE_SHORT_WITH_TZ) {
            $intlFormatter = new IntlDateFormatter($locale, $dateType, self::TIME_TYPE_SHORT, null, IntlDateFormatter::GREGORIAN);
            $pattern = $intlFormatter->getPattern() . ' z';
        } else {
            $intlFormatter = new IntlDateFormatter($locale, $dateType, $timeType, null, IntlDateFormatter::GREGORIAN);
            $pattern = $intlFormatter->getPattern();
        }
        if ($dateType === self::DATE_TYPE_SHORT) {
            $pattern = str_replace(['yyyy', 'yy'], 'y', $pattern);
        }
        return $pattern;
    }

    /**
     * Gets instance of intl date formatter by parameters
     * @param int $dateType
     * @param int $timeType
     * @param string|null $locale
     * @param string|null $timeZone
     * @param string|null $pattern
     * @return IntlDateFormatter
     */
    protected function getFormatter(int $dateType, int $timeType, ?string $locale, ?string $timeZone, ?string $pattern): IntlDateFormatter
    {
        $locale = $locale ?? $this->getLocale();
        if (!$pattern) {
            $pattern = $this->getPattern($dateType, $timeType, $locale);
        }

        $key = md5(serialize([$timeZone, $pattern]));
        if (!isset($this->cachedFormatters[$key])) {
            $this->cachedFormatters[$key] = new IntlDateFormatter(
                $locale,
                IntlDateFormatter::FULL,
                IntlDateFormatter::FULL,
                $timeZone,
                IntlDateFormatter::GREGORIAN,
                $pattern
            );
        }

        return $this->cachedFormatters[$key];
    }

    /**
     * Returns DateTime by $data and $timezone and false otherwise
     * @param DateTimeInterface|string|int|null $date
     * @return DateTimeInterface|null
     */
    public function getDateTime(DateTimeInterface|string|int|null $date): ?DateTimeInterface
    {
        if ($date === null) {
            return null;
        }

        if ($date instanceof DateTimeInterface) {
            return $date;
        }

        $defaultTimezone = date_default_timezone_get();

        date_default_timezone_set('UTC');

        if (is_numeric($date)) {
            $timestamp = (int)$date;
        } else {
            $timestamp = strtotime($date);
            if (!$timestamp) {
                log_error(sprintf('Cannot convert date string "%s" to time. Invalid format.', $date));
                return null;
            }
        }

        $result = new DateTime();
        $result->setTimestamp($timestamp);

        date_default_timezone_set($defaultTimezone);

        return $result;
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return ['cachedPatterns'];
    }
}
