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

namespace Sam\View\Responsive\Form\AdvancedSearch;

use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\Transform\Number\NumberFormatterInterface;
use Sam\View\Responsive\Form\AdvancedSearch\Cache\CurrencyCacherAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\CurrencyRendererCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Translate\CachedTranslator;
use Sam\View\Responsive\Form\AdvancedSearch\Translate\CachedTranslatorAwareTrait;

/**
 * Class EstimatesRenderer
 */
class EstimatesRenderer extends CustomizableClass
{
    use CachedTranslatorAwareTrait;
    use CurrencyCacherAwareTrait;
    use CurrencyRendererCreateTrait;
    use HtmlWrapRendererCreateTrait;
    use NumberFormatterAwareTrait;
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param CachedTranslator $cachedTranslator
     * @param NumberFormatterInterface $numberFormatter
     * @return EstimatesRenderer
     */
    public function construct(
        CachedTranslator $cachedTranslator,
        NumberFormatterInterface $numberFormatter
    ): EstimatesRenderer {
        $this->setCachedTranslator($cachedTranslator);
        $this->setNumberFormatter($numberFormatter);
        return $this;
    }

    /**
     * @param float $lowAmount
     * @param float $highAmount
     * @param int $lotItemId
     * @param int $auctionId
     * @return string
     */
    public function render(float $lowAmount, float $highAmount, int $lotItemId, int $auctionId, int $lotAccountId): string
    {
        $output = '';
        $isShowLowEst = (bool)$this->getSettingsManager()->get(Constants\Setting::SHOW_LOW_EST, $lotAccountId);
        $isShowHighEst = (bool)$this->getSettingsManager()->get(Constants\Setting::SHOW_HIGH_EST, $lotAccountId);
        if (
            $isShowLowEst
            && $isShowHighEst
        ) {
            if (Floating::gt($lowAmount, 0.)) {
                $output .= $this->decorateWithSpan($lowAmount, $lotItemId, $auctionId, 'est-low');
            }
            if (Floating::gt($highAmount, 0.)) {
                $output .= ' - ';
                $output .= $this->decorateWithSpan($highAmount, $lotItemId, $auctionId, 'est-high');
            }
        } elseif (
            $isShowLowEst
            && !$isShowHighEst // @phpstan-ignore-line
            && Floating::gt($lowAmount, 0.)
        ) {
            $output = $this->getCachedTranslator()->translate('ITEM_MIN', 'item') . ' ';
            $output .= $this->decorateWithSpan($lowAmount, $lotItemId, $auctionId, 'est-min');
        } elseif (
            !$isShowLowEst
            && $isShowHighEst
            && Floating::gt($highAmount, 0.)
        ) {
            $output = $this->getCachedTranslator()->translate('ITEM_MAX', 'item') . ' ';
            $output .= $this->decorateWithSpan($highAmount, $lotItemId, $auctionId, 'est-max');
        }
        return $output;
    }

    /**
     * @param float $amount
     * @param int $lotItemId
     * @param int $auctionId
     * @param string $additionalClass
     * @return string
     */
    private function decorateWithSpan(float $amount, int $lotItemId, int $auctionId, string $additionalClass): string
    {
        $output = $this->createCurrencyHelper()
            ->construct($this->getNumberFormatter())
            ->decorateAmountWithCurrency($amount, $lotItemId, $auctionId, $additionalClass);
        return $output;
    }
}
