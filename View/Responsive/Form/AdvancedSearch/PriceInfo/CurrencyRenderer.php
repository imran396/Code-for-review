<?php
/**
 * SAM-5877: Advanced search rendering module
 * SAM-5282 Show 'you won' on lot lists (catalog, search, my items)
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 17, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\PriceInfo;

use Sam\Core\Service\CustomizableClass;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\Transform\Number\NumberFormatterInterface;
use Sam\View\Responsive\Form\AdvancedSearch\Cache\CurrencyCacherAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\HtmlWrapRendererCreateTrait;
use Sam\Core\Constants;

/**
 * Class CurrencyHelper
 */
class CurrencyRenderer extends CustomizableClass
{
    use CurrencyCacherAwareTrait;
    use HtmlWrapRendererCreateTrait;
    use NumberFormatterAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(NumberFormatterInterface $numberFormatter): static
    {
        $this->setNumberFormatter($numberFormatter);
        return $this;
    }

    /**
     * @param int $auctionId
     * @return string
     */
    public function renderCurrencySign(int $auctionId): string
    {
        $controlId = sprintf(Constants\Responsive\AdvancedSearchConstants::CID_LBL_PRICE_INFO_TPL, $auctionId);
        $currencySign = $this->getCurrencyCacher()->getDefaultCurrencySign($auctionId);
        $currencySign = $this->createHtmlWrapRenderer()->withSpan($currencySign, ["class" => $controlId]);
        return $currencySign;
    }

    /**
     * @param int $auctionId
     * @return string
     */
    public function renderDefaultCurrency(int $auctionId): string
    {
        $currencyIds = $this->getCurrencyCacher()->getAuctionCurrencyIds($auctionId);
        $defaultCurrency = 'cur:' . array_shift($currencyIds);
        return $defaultCurrency;
    }

    /**
     * @param int $auctionId
     * @return string
     */
    public function renderAdditionalCurrencies(int $auctionId): string
    {
        $currencyIds = $this->getCurrencyCacher()->getAuctionCurrencyIds($auctionId);
        array_shift($currencyIds);
        $additionalCurrencies = 'cur:' . implode(',', $currencyIds);
        return $additionalCurrencies;
    }

    /**
     * @param float $amount
     * @param int $lotItemId
     * @param int $auctionId
     * @param string $additionalClass
     * @param string $spanId
     * @return string
     */
    public function decorateAmountWithCurrency(
        float $amount,
        int $lotItemId,
        int $auctionId,
        string $additionalClass = '',
        string $spanId = ''
    ): string {
        $currencySign = $this->renderCurrencySign($auctionId);
        $defaultCurrency = $this->renderDefaultCurrency($auctionId);
        $additionalCurrencies = $this->renderAdditionalCurrencies($auctionId);
        $amountFormatted = $this->getNumberFormatter()->formatMoney($amount);
        $attributes = [
            "class" => (trim($additionalClass) !== '' ? trim($additionalClass) . " " : '') . "exratetip",
            "id" => $spanId,
            "rev" => $defaultCurrency,
            "rel" => $additionalCurrencies,
            "data-lid" => $lotItemId,
        ];
        $amountDecorated = $currencySign . $this->createHtmlWrapRenderer()->withSpan($amountFormatted, $attributes);
        return $amountDecorated;
    }
}
