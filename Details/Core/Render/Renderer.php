<?php
/**
 * Base class for rendering placeholders
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Feb 16, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Custom methods can be used there or in customized class
 *
 * Optional method called when rendering the custom auction field value
 * param AuctionCustField $customField the custom auction field object
 * param mixed $value the value
 * param ind $auctionId
 * return string the rendered value
 * public function AuctionCustomField_{Field name}_Render(AuctionCustField $customField, $value, $auctionId);
 *
 * {Field name} - Camel cased name of custom field (see TextTransformer::toCamelCase())
 */

namespace Sam\Details\Core\Render;

use Currency;
use DateTime;
use DateTimeZone;
use Exception;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Convert\CurrencyConverterAwareTrait;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Details\Core\ConfigManagerAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class Renderer
 * @package Sam\Details
 */
abstract class Renderer extends CustomizableClass
{
    use ConfigManagerAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CurrencyConverterAwareTrait;
    use CurrencyLoaderAwareTrait;
    use DateHelperAwareTrait;
    use SystemAccountAwareTrait;
    use TranslatorAwareTrait;

    /**
     * Cache loaded Currency objects
     * @var Currency[]|null
     */
    private ?array $currencies = null;
    /**
     * Language of translation
     * @var int|null
     */
    protected ?int $languageId = null;
    /**
     * Cache primary currency
     * @var Currency|null
     */
    private ?Currency $primaryCurrency = null;
    /**
     * Cache translated values
     * @var string[][]|null
     */
    protected ?array $translations = null;

    public function renderAsIs(array $row, string $key): string
    {
        return $this->getSingleSelectedValue($row, $key);
    }

    /**
     * Return formatted date
     */
    public function renderDate(array $row, string $key, string $format): string
    {
        $output = $this->getSingleSelectedValue($row, $key);
        if ($output) {
            try {
                $date = new DateTime($output);
            } catch (Exception) {
                log_errorBackTrace("Cannot create DateTime object" . composeSuffix(['value' => $output]));
                return $output;
            }
            $timezone = $this->findTimezone($row, $key);
            if ($timezone) {
                $date->setTimezone(new DateTimeZone($timezone));
            }
            if (!$format) {
                $format = Constants\Date::ISO;
            }
            $output = $date->format($format);
        }
        return $output;
    }

    /**
     * Extract timezone location value from fetched data in $row for date field defined by $key placeholder
     */
    private function findTimezone(array $row, string $key): ?string
    {
        $options = $this->getConfigManager()->getOptionsByKey($key);
        $timezoneField = $options['timezone'] ?? null;
        return $row[$timezoneField] ?? null;
    }

    /**
     * Render method for date additional placeholder
     * @throws Exception
     */
    public function renderDateAdditional(array $row, string $key): string
    {
        if (preg_match('/_offset$/', $key)) {
            $output = $this->renderTimezoneOffset($row, $key);
        } elseif (preg_match('/_code$/', $key)) {
            $output = $this->renderTimezoneCode($row, $key);
        } else {
            $output = $this->getSingleSelectedValue($row, $key);
        }
        return $output;
    }

    /**
     * Render money value, that can be converted by exchange rate of $conversionCurrency.
     * We cache currencies per auction: Auction Id => Currency
     */
    public function renderMoneyValue(array $row, string $key, ?Currency $conversionCurrency = null): string
    {
        $output = $this->getSingleSelectedValue($row, $key);
        if ($conversionCurrency) {
            $auctionCurrencyId = empty($row['currency'])
                ? $this->getPrimaryCurrency()->Id
                : (int)$row['currency'];
            if ($auctionCurrencyId !== $conversionCurrency->Id) {
                if (!isset($this->currencies[$auctionCurrencyId])) {
                    $this->currencies[$auctionCurrencyId] = $this->getCurrencyLoader()->load($auctionCurrencyId);
                }
                $output = $this->getCurrencyConverter()->convert($output, $this->currencies[$auctionCurrencyId], $conversionCurrency);
            }
        }
        $output = Floating::roundOutput($output);
        return preg_replace('/[.,]00$/', '', (string)$output);
    }

    /**
     * Translate and cache translated value
     */
    public function translate(string $key, string $section): string
    {
        if (!isset($this->translations[$key][$section])) {
            $this->translations[$key][$section] = $this->getTranslator()->translate(
                $key,
                $section,
                $this->getSystemAccountId(),
                $this->getLanguageId()
            );
        }
        return $this->translations[$key][$section];
    }

    /**
     * Returns value of first field from result set
     */
    protected function getSingleSelectedValue(array $row, string $key): string
    {
        $options = $this->getConfigManager()->getOptionsByKey($key);
        if (!$options) {
            return '';
        }

        $select = $options['select'];
        $resultSetField = is_array($select) ? $select[0] : $select;
        if (!array_key_exists($resultSetField, $row)) {
            log_errorBackTrace("Result set key \"{$resultSetField}\" is not found in result row" . composeSuffix($row));
            return '';
        }

        return (string)$row[$resultSetField];
    }

    /**
     * Returns timezone offset
     * @throws Exception
     */
    protected function renderTimezoneOffset(array $row, string $key): string
    {
        $output = '';
        $options = $this->getConfigManager()->getOptionsByKey($key);
        if ($options) {
            $select = (array)$options['select'];
            $location = preg_match('/_location$/', (string)$select[0]) ? $row[$select[0]] : null;
            if ($location) {
                try {
                    $forDate = isset($select[1]) ? new DateTime((string)$row[$select[1]]) : null;
                } catch (Exception $e) {
                    log_error('Problem with date' . composeSuffix(['error' => $e->getMessage(), 'code' => $e->getCode()]));
                    $forDate = null;
                }
                $offset = (int)$this->getDateHelper()->getTimezoneOffsetByLocation($location, $forDate);
                $output = $offset / 3600;
            }
        }
        return (string)$output;
    }

    /**
     * Returns timezone code
     * @throws Exception
     */
    protected function renderTimezoneCode(array $row, string $key): string
    {
        $output = '';
        $options = $this->getConfigManager()->getOptionsByKey($key);
        if ($options) {
            $select = (array)$options['select'];
            $location = preg_match('/_location$/', (string)$select[0]) ? $row[$select[0]] : null;
            if ($location) {
                $forDate = isset($select[1]) ? new DateTime((string)$row[$select[1]]) : null;
                $output = $this->getDateHelper()->getTimezoneCodeByLocation($location, $forDate);
            }
        }
        return $output;
    }

    /**
     * @return Currency[]|null
     * @noinspection PhpUnused
     */
    public function getCurrencies(): ?array
    {
        return $this->currencies;
    }

    /**
     * @param Currency[] $currencies
     * @noinspection PhpUnused
     */
    public function setCurrencies(array $currencies): static
    {
        $this->currencies = $currencies;
        return $this;
    }

    public function getLanguageId(): ?int
    {
        return $this->languageId;
    }

    public function setLanguageId(?int $languageId): static
    {
        $this->languageId = $languageId;
        return $this;
    }

    public function getPrimaryCurrency(): Currency
    {
        if ($this->primaryCurrency === null) {
            $this->primaryCurrency = $this->getCurrencyLoader()->loadPrimaryCurrency();
            $this->currencies[$this->primaryCurrency->Id] = $this->primaryCurrency;
        }
        return $this->primaryCurrency;
    }

    /**
     * @noinspection PhpUnused
     */
    public function setPrimaryCurrency(Currency $currency): static
    {
        $this->primaryCurrency = $currency;
        return $this;
    }
}
