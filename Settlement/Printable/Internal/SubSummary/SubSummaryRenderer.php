<?php
/**
 * SAM-7661: Settlement Printable & viewable version to the new layout Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           02-26, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Printable\Internal\SubSummary;

use Sam\Core\Constants\SettlementItemPrintableConstants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Load\Dto\SettlementItemDto;
use Sam\Settlement\Printable\Internal\Translation\SettlementTranslatorCreateTrait;
use Sam\View\Base\SettlementItemList\SettlementItemListSubtotalViewCreateTrait;
use Settlement;

/**
 * Renders sub-summary HTML.
 *
 * Class SubSummaryRenderer
 * @package Sam\Settlement\Printable
 */
class SubSummaryRenderer extends CustomizableClass
{
    use SettlementItemListSubtotalViewCreateTrait;
    use SettlementTranslatorCreateTrait;

    protected Settlement $settlement;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Settlement $settlement
     * @return $this
     */
    public function construct(Settlement $settlement): static
    {
        $this->settlement = $settlement;
        return $this;
    }


    /**
     * @param SettlementItemDto[] $settlementItemDtos
     * @param string $currencySign
     * @param int $settlementAccountId
     * @param bool $isTranslatableLabels
     * @return string
     */
    public function render(
        array $settlementItemDtos,
        string $currencySign,
        int $settlementAccountId,
        bool $isTranslatableLabels = false
    ): string {
        $subtotalView = $this->createSettlementItemListSubtotalView()
            ->construct($this->settlement->Id, $settlementItemDtos, $currencySign, $settlementAccountId);
        $headersTranslations = $this->createSettlementTranslator()->getTranslatedHeaders($isTranslatableLabels);

        $cssClassHp = SettlementItemPrintableConstants::CSS_SETTLEMENT_PRINT_SUB_SUMMARY_HP;
        $cssClassTaxOnHp = SettlementItemPrintableConstants::CSS_SETTLEMENT_PRINT_SUB_SUMMARY_TAX_ON_HP;
        $cssClassCommission = SettlementItemPrintableConstants::CSS_SETTLEMENT_PRINT_SUB_SUMMARY_COMMISSION;
        $cssClassTaxOnCommission = SettlementItemPrintableConstants::CSS_SETTLEMENT_PRINT_SUB_SUMMARY_TAX_ON_COMMISSION;
        $cssClassFee = SettlementItemPrintableConstants::CSS_SETTLEMENT_PRINT_SUB_SUMMARY_FEE;
        $cssClassSubtotal = SettlementItemPrintableConstants::CSS_SETTLEMENT_PRINT_SUB_SUMMARY_SUBTOTAL;

        $html = <<<HTML
<section class="{$cssClassHp}">
    <i><span>{$headersTranslations->hammerHeader}: </span></i>
    <em>{$subtotalView->makeHPFormatted()}</em>
    <div class="clearfix"></div>
</section>
<section class="{$cssClassTaxOnHp}">
    <i><span>{$headersTranslations->taxOnHPHeader}: </span></i>
    <em>{$subtotalView->makeTaxOnHPFormatted()}</em>
    <div class="clearfix"></div>
</section>
<section class="{$cssClassCommission}">
    <i><span>{$headersTranslations->commissionHeader}: </span></i>
    <em>{$subtotalView->makeCommissionFormatted()}</em>
    <div class="clearfix"></div>
</section>
<section class="{$cssClassTaxOnCommission}">
    <i><span>{$headersTranslations->taxOnCommHeader}: </span></i>
    <em>{$subtotalView->makeTaxOnCommissionFormatted()}</em>
    <div class="clearfix"></div>
</section>
<section class="{$cssClassFee}">
    <i><span>{$headersTranslations->feeHeader}: </span></i>
    <em>{$subtotalView->makeFeeFormatted()}</em>
    <div class="clearfix"></div>
</section>
<section class="{$cssClassSubtotal}">
    <i><span>{$headersTranslations->subtotalHeader}: </span></i>
    <em>{$subtotalView->makeSubtotalFormatted()}</em>
    <div class="clearfix"></div>
</section>
HTML;
        return $html;
    }
}
