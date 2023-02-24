<?php
/**
 * SAM-7661: Settlement Printable & viewable version to the new layout Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           03-02, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Printable\Internal\HtmlSections;

use LotItemCustField;
use Sam\Core\Constants;
use Sam\Core\Constants\SettlementItemPrintableConstants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Settlement\Currency\SettlementCurrencyDetectorAwareTrait;
use Sam\Settlement\Load\Dto\SettlementItemDto;
use Sam\Settlement\Load\SettlementItemLoaderAwareTrait;
use Sam\Settlement\Printable\Internal\Common\CommonRenderer;
use Sam\Settlement\Printable\Internal\Common\CommonRendererAwareTrait;
use Sam\Settlement\Printable\Internal\LotsTable\LotsTableRenderer;
use Sam\Settlement\Printable\Internal\SubSummary\SubSummaryRenderer;
use Sam\Settlement\Printable\Internal\Summary\SummaryRenderer;
use Sam\Settlement\Printable\Internal\Translation\SettlementTranslatorCreateTrait;
use Sam\View\Base\SettlementItemList\SettlementItemListTotalViewCreateTrait;
use Settlement;

/**
 * Renders full HTML layout for single settlement.
 *
 * Class HtmlSectionsRenderer
 * @package Sam\Settlement\Printable
 */
class HtmlSectionsRenderer extends CustomizableClass
{
    use CommonRendererAwareTrait;
    use SettingsManagerAwareTrait;
    use SettlementCurrencyDetectorAwareTrait;
    use SettlementItemListTotalViewCreateTrait;
    use SettlementItemLoaderAwareTrait;
    use SettlementTranslatorCreateTrait;

    protected Settlement $settlement;
    /**
     * Settlement entity account id
     */
    protected int $settlementAccountId;
    /**
     * Settlement entity consignor id
     */
    protected int $consignorUserId;
    protected string $currencySign;

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
        $this->settlementAccountId = $this->settlement->AccountId;
        $this->consignorUserId = $this->settlement->ConsignorId;
        $this->setCommonRenderer(CommonRenderer::new()->construct($this->settlement));

        $this->currencySign = $this->getSettlementCurrencyDetector()->detectSign($this->settlement->Id, true);
        return $this;
    }

    /**
     * Renders full HTML layout.
     *
     * @param LotItemCustField[] $lotCustomFields
     * @param bool $isUseTranslatableLabels
     * @param bool $isRenderLotItemCustomFields
     * @param bool $isInlineCss
     * @return string
     */
    public function render(
        array $lotCustomFields,
        bool $isUseTranslatableLabels,
        bool $isRenderLotItemCustomFields,
        bool $isInlineCss
    ): string {
        $settlementItemDtos = $this->getSettlementItemLoader()->loadDtos($this->settlement->Id, true);

        $headerSectionHtml = $this->renderHeaderSectionHtml();
        $infoSectionHtml = $this->renderInfoSectionHtml($isUseTranslatableLabels);
        $mainDataSectionHtml = $this->renderMainSectionHtml(
            $settlementItemDtos,
            $lotCustomFields,
            $isUseTranslatableLabels,
            $isRenderLotItemCustomFields,
            $isInlineCss
        );
        $bottomSectionHtml = $this->renderBottomSectionHtml($settlementItemDtos, $isUseTranslatableLabels);
        $js = '';

        $html = <<<HTML
<article class="bodybox">
    {$headerSectionHtml}
    {$infoSectionHtml}
    {$mainDataSectionHtml}
    {$bottomSectionHtml}
</article>
<div class="clear"></div>

<script>{$js}</script>
HTML;

        return $html;
    }

    /**
     * Renders header section HTML
     * @return string
     */
    protected function renderHeaderSectionHtml(): string
    {
        $commonRenderer = $this->getCommonRenderer();
        $logo = $commonRenderer->renderLogoImage();
        $address = $commonRenderer->renderAddress($this->settlement->Id, $this->settlementAccountId);
        $cssClassLogo = SettlementItemPrintableConstants::CSS_SETTLEMENT_PRINT_LOGO_LINK;

        $output = <<<HTML
<div class="viewtitle">
    <figure>
        <a href="#" class="{$cssClassLogo}">{$logo}</a>
    </figure>
    <h3>
        <span>{$address}</span>
    </h3>
    <div class="clearfix"></div>
</div>
HTML;
        return $output;
    }

    /**
     * Renders info section HTML
     * @param bool $isUseTranslatableLabels
     * @return string
     */
    protected function renderInfoSectionHtml(bool $isUseTranslatableLabels): string
    {
        $commonRenderer = $this->getCommonRenderer();
        $consignorFullName = $commonRenderer->renderConsignorFullName($this->consignorUserId, $isUseTranslatableLabels);
        $consignorBillingAddress = $commonRenderer->renderConsignorBillingAddress($this->consignorUserId, $isUseTranslatableLabels);
        $consignorCustomFields = $commonRenderer->renderConsignorCustomFields($this->consignorUserId, $isUseTranslatableLabels);
        $shippingAddress = '&nbsp;';
        $settlementNo = $commonRenderer->renderSettlementNo();
        $dateCreated = $commonRenderer->renderDate();
        $status = $commonRenderer->renderStatus();
        $saleInfo = $commonRenderer->renderSaleInfo($isUseTranslatableLabels);

        $tr = $this->createSettlementTranslator()->getTranslatedCommon($isUseTranslatableLabels);

        $totalView = $this->createSettlementItemListTotalView()->construct($this->settlement->Id, $this->settlementAccountId);
        $balanceDueViewFormatted = $totalView->makeBalanceDueFormatted();
        $balanceDueHtml = "<span>{$tr->balanceDueLbl}:</span> {$balanceDueViewFormatted}";

        $cssClassConsignorInfo = SettlementItemPrintableConstants::CSS_SETTLEMENT_PRINT_CONSIGNOR_INFO_WRAPPER;
        $cssClassConsignorShipping = SettlementItemPrintableConstants::CSS_SETTLEMENT_PRINT_CONSIGNOR_SHIPPING_WRAPPER;
        $cssClassMainInfo = SettlementItemPrintableConstants::CSS_SETTLEMENT_PRINT_MAIN_INFO_WRAPPER;
        $cssClassStatusAndDate = SettlementItemPrintableConstants::CSS_SETTLEMENT_PRINT_STATUS_AND_DATE;
        $cssClassBalanceDue = SettlementItemPrintableConstants::CSS_SETTLEMENT_PRINT_BALANCE_DUE;

        $output = <<<HTML
<ul class="viewinfo">
    <li class="{$cssClassConsignorInfo}">
        <div class="settlement-sale">{$saleInfo}</div>
        <p>{$consignorFullName}</p>
        <div class="cons-billing">{$consignorBillingAddress}</div>
        <div class="cons-custom-fields">{$consignorCustomFields}</div>
    </li>
    <li class="{$cssClassConsignorShipping}"><div class="cons-shipping-address">{$shippingAddress}</div></li>
    <li class="{$cssClassMainInfo}">
        <div>
            <em>{$tr->consignorSettlementLbl} {$settlementNo}</em>
            <p class="{$cssClassStatusAndDate}">
                {$tr->dateCreatedLbl}: {$dateCreated}
                <br>
                {$tr->statusLbl}: {$status}
            </p>
            <p class="ttl {$cssClassBalanceDue}">{$balanceDueHtml}</p>
        </div>
    </li>
</ul>
HTML;
        return $output;
    }

    /**
     * Renders main section HTML. Main section contains Settlement Lots table.
     *
     * @param SettlementItemDto[] $settlementItemDtos
     * @param LotItemCustField[] $lotCustomFields
     * @param bool $isUseTranslatableLabels
     * @param bool $isRenderLotItemCustomFields
     * @param bool $isInlineCss
     * @return string
     */
    protected function renderMainSectionHtml(
        array $settlementItemDtos,
        array $lotCustomFields,
        bool $isUseTranslatableLabels,
        bool $isRenderLotItemCustomFields,
        bool $isInlineCss
    ): string {
        $isQuantityInSettlement = (bool)$this->getSettingsManager()->get(Constants\Setting::QUANTITY_IN_SETTLEMENT, $this->settlementAccountId);
        $settlementLotsTableHtml = LotsTableRenderer::new()
            ->construct($this->settlement)
            ->render(
                $this->settlementAccountId,
                $settlementItemDtos,
                $lotCustomFields,
                $isQuantityInSettlement,
                $this->currencySign,
                $isUseTranslatableLabels,
                $isRenderLotItemCustomFields,
                $isInlineCss
            );

        $output = <<<HTML
<div class="tablesection">
    {$settlementLotsTableHtml}
</div>
HTML;

        return $output;
    }

    /**
     * Render bottom section. Bottom section contains: settlement note, sub-summary and summary.
     *
     * @param SettlementItemDto[] $settlementItemDtos
     * @param bool $isUseTranslatableLabels
     * @return string
     */
    protected function renderBottomSectionHtml(array $settlementItemDtos, bool $isUseTranslatableLabels): string
    {
        $settlementNote = $this->getCommonRenderer()->renderNote();
        $notesDisplayNone = '';
        if (trim($settlementNote) === '') {
            $notesDisplayNone = 'style="display:none;"';
            $settlementNote = '&nbsp;';
        }

        $subSummaryHtml = SubSummaryRenderer::new()
            ->construct($this->settlement)
            ->render(
                $settlementItemDtos,
                $this->currencySign,
                $this->settlementAccountId,
                $isUseTranslatableLabels
            );

        $isChargeConsignorCommission = (bool)$this->getSettingsManager()->get(Constants\Setting::CHARGE_CONSIGNOR_COMMISSION, $this->settlementAccountId);
        $summaryHtml = SummaryRenderer::new()
            ->construct($this->settlement)
            ->render(
                $this->settlementAccountId,
                $isChargeConsignorCommission,
                $this->currencySign,
                $isUseTranslatableLabels
            );

        $cssClassNotes = SettlementItemPrintableConstants::CSS_SETTLEMENT_PRINT_NOTES_WRAPPER;
        $cssClassSubSummary = SettlementItemPrintableConstants::CSS_SETTLEMENT_PRINT_SUB_SUMMARY_WRAPPER;
        $cssClassSummary = SettlementItemPrintableConstants::CSS_SETTLEMENT_PRINT_SUMMARY_WRAPPER;
        $tr = $this->createSettlementTranslator()->getTranslatedCommon($isUseTranslatableLabels);

        $output = <<<HTML
<ul class="inv_btm threeColumns">
    <li class="col1 {$cssClassNotes}">
        <div class="notes">
            <h4 {$notesDisplayNone}><span>{$tr->notesLbl}</span></h4>
            <section>
                {$settlementNote}
            </section>

        </div>
    </li>
    <li class="col2 {$cssClassSubSummary}">
        <div class="notes sub-summary">
            <h4><span>{$tr->subSummaryLbl}</span></h4>
            {$subSummaryHtml}
        </div>
    </li>
    <li class="col3 {$cssClassSummary}">
        <div class="notes summary">
            <h4><span>{$tr->summaryLbl}</span></h4>
            {$summaryHtml}
        </div>
    </li>
</ul>
<div class="clear"></div>
HTML;

        return $output;
    }
}
