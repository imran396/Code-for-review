<?php
/**
 * Render numeric fields for auction lot and lot item entities.
 * These fields require formatting specific to account settings.
 *
 * SAM-10339: Fetch US_NUMBER_FORMATTING and KEEP_DECIMAL_INVOICE from entity accounts
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 23, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Render\Amount;

use AuctionLotItem;
use LotItem;
use Sam\AuctionLot\Quantity\Scale\LotQuantityScaleLoaderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Transform\Number\NumberFormatterInterface;

/**
 * Class LotRenderer
 * @package Sam\Lot\Render
 */
class LotAmountRenderer extends CustomizableClass implements LotAmountRendererInterface
{
    use LotQuantityScaleLoaderCreateTrait;
    use SettingsManagerAwareTrait;

    public const OP_SHOW_HIGH_ESTIMATE = OptionalKeyConstants::KEY_SHOW_HIGH_EST; // bool
    public const OP_SHOW_LOW_ESTIMATE = OptionalKeyConstants::KEY_SHOW_LOW_EST; // bool

    protected ?NumberFormatterInterface $numberFormatter = null;

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
        $this->numberFormatter = $numberFormatter;
        return $this;
    }

    // --- Lot Estimates ---

    /**
     * Render lot estimated prices
     * @param float|null $lowEstimate
     * @param float|null $highEstimate
     * @param string $currencySign
     * @param bool $isShowLowEst
     * @param bool $isShowHighEst
     * @return string
     */
    public function makeEstimates(
        ?float $lowEstimate,
        ?float $highEstimate,
        string $currencySign,
        bool $isShowLowEst = true,
        bool $isShowHighEst = true
    ): string {
        $estimatesViewFormatted[] = $this->makeLowEstimateFormattedByMoney($lowEstimate, $currencySign, $isShowLowEst);
        $estimatesViewFormatted[] = $this->makeHighEstimateFormattedByMoney($highEstimate, $currencySign, $isShowHighEst);
        return implode(' - ', array_filter($estimatesViewFormatted));
    }

    /**
     * Render lot estimated prices
     * @param LotItem $lotItem
     * @param string $currencySign
     * @param array $optionals = [
     *     self::OP_SHOW_LOW_ESTIMATE => bool,
     *     self::OP_SHOW_HIGH_ESTIMATE => bool,
     * ]
     * @return string
     */
    public function renderEstimates(
        LotItem $lotItem,
        string $currencySign,
        array $optionals = []
    ): string {
        $sm = $this->getSettingsManager();
        $isShowLowEst = (bool)($optionals[self::OP_SHOW_LOW_ESTIMATE] ?? $sm->get(Constants\Setting::SHOW_LOW_EST, $lotItem->AccountId));
        $isShowHighEst = (bool)($optionals[self::OP_SHOW_HIGH_ESTIMATE] ?? $sm->get(Constants\Setting::SHOW_HIGH_EST, $lotItem->AccountId));
        $output = $this->makeEstimates($lotItem->LowEstimate, $lotItem->HighEstimate, $currencySign, $isShowLowEst, $isShowHighEst);
        return $output;
    }

    // --- Quantity ---

    public function makeQuantity(?float $quantity, int $scale): string
    {
        if (!$quantity) {
            return '';
        }

        $output = $this->numberFormatter->format($quantity, $scale);
        return $output;
    }

    public function renderQuantity(AuctionLotItem $auctionLot): string
    {
        $scale = $this->createLotQuantityScaleLoader()
            ->loadAuctionLotQuantityScale($auctionLot->LotItemId, $auctionLot->AuctionId);
        $output = $this->makeQuantity($auctionLot->Quantity, $scale);
        return $output;
    }

    public function makeLowEstimateFormattedByMoney(
        ?float $lowEstimate,
        string $currencySign,
        bool $isShowLowEst = false,
    ): string {
        return $this->makeEstimateFormatted($lowEstimate, $currencySign, $isShowLowEst);
    }

    public function makeHighEstimateFormattedByMoney(
        ?float $highEstimate,
        string $currencySign,
        bool $isShowHighEst = false,
    ): string {
        return $this->makeEstimateFormatted($highEstimate, $currencySign, $isShowHighEst);
    }

    public function makeLowEstimateFormattedByMoneyDetail(
        ?float $lowEstimate,
        string $currencySign,
        bool $isShowLowEst = false,
    ): string {
        return $this->makeEstimateFormatted($lowEstimate, $currencySign, $isShowLowEst, true);
    }

    public function makeHighEstimateFormattedByMoneyDetail(
        ?float $highEstimate,
        string $currencySign,
        bool $isShowHighEst = false,
    ): string {
        return $this->makeEstimateFormatted($highEstimate, $currencySign, $isShowHighEst, true);
    }

    protected function makeEstimateFormatted(
        ?float $estimate,
        string $currencySign,
        bool $isShown = false,
        bool $isMoneyDetail = false
    ): string {
        $estimate = Cast::toFloat($estimate, Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
        $formatted = '';
        if (
            $isShown
            && Floating::gt($estimate, 0)
        ) {
            $formatted = $currencySign . (
                $isMoneyDetail
                    ? $this->numberFormatter->formatMoneyDetail($estimate)
                    : $this->numberFormatter->formatMoney($estimate)
                );
        }
        return $formatted;
    }
}
