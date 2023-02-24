<?php
/**
 * SAM-4364: Settlement item list loading optimization
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Base\SettlementItemList;

use InvoiceItem;
use LotItemCustData;
use LotItemCustField;
use Sam\Application\Url\Build\Config\Barcode\BarcodeUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Entity\Model\AuctionLotItem\Status\AuctionLotStatusPureCheckerAwareTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Core\Transform\Text\TextTransformer;
use Sam\CustomField\Base\Render\Css\CustomFieldCssClassMakerCreateTrait;
use Sam\CustomField\Lot\Load\LotCustomDataLoaderCreateTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoader;
use Sam\Date\DateHelperAwareTrait;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Lot\Render\Amount\LotAmountRendererFactoryCreateTrait;
use Sam\Lot\Render\Amount\LotAmountRendererInterface;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Settings\SettingsManager;
use Sam\Settlement\Load\Dto\SettlementItemDto;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\Base\SettlementItemList\Internal\SettlementItemTaxCalculatorCreateTrait;

/**
 * Class SettlementItemView
 * @package Sam\View\Base\SettlementItemList
 */
class SettlementItemView extends CustomizableClass
{
    use AuctionLotStatusPureCheckerAwareTrait;
    use AuctionRendererAwareTrait;
    use CustomFieldCssClassMakerCreateTrait;
    use DateHelperAwareTrait;
    use InvoiceItemLoaderAwareTrait;
    use InvoiceLoaderAwareTrait;
    use LotCustomDataLoaderCreateTrait;
    use LotRendererAwareTrait;
    use LotAmountRendererFactoryCreateTrait;
    use NumberFormatterAwareTrait;
    use OptionalsTrait;
    use SettlementItemTaxCalculatorCreateTrait;
    use UrlBuilderAwareTrait;

    public const OP_LOT_CUSTOM_FIELDS = 'lotCustomFields';
    public const OP_SETTLEMENT_UNPAID_LOTS = OptionalKeyConstants::KEY_SETTLEMENT_UNPAID_LOTS;
    public const OP_SHOW_HIGH_ESTIMATE = OptionalKeyConstants::KEY_SHOW_HIGH_EST;
    public const OP_SHOW_LOW_ESTIMATE = OptionalKeyConstants::KEY_SHOW_LOW_EST;

    protected SettlementItemDto $settlementItemDto;
    protected string $currencySign;
    protected int $settlementAccountId;
    protected LotAmountRendererInterface $lotAmountRenderer;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param SettlementItemDto $settlementItemDto
     * @param string $currencySign
     * @param int $settlementAccountId
     * @param array $optionals = [
     *     self::OP_LOT_CUSTOM_FIELDS => (LotItemCustField[])
     *     self::OP_SETTLEMENT_UNPAID_LOTS => (bool),
     *     self::OP_SHOW_HIGH_ESTIMATE => (bool),
     *     self::OP_SHOW_LOW_ESTIMATE => (bool),
     * ]
     * @return static
     */
    public function construct(
        SettlementItemDto $settlementItemDto,
        string $currencySign,
        int $settlementAccountId,
        array $optionals = []
    ): static {
        $this->settlementItemDto = $settlementItemDto;
        $this->currencySign = $currencySign;
        $this->settlementAccountId = $settlementAccountId;
        $this->initOptionals($optionals);
        $this->getNumberFormatter()->constructForSettlement($settlementAccountId);
        $this->lotAmountRenderer = $this->createLotAmountRendererFactory()->createForSettlement($settlementAccountId);
        return $this;
    }

    /**
     * @return string
     */
    public function makeHammerPriceFormatted(): string
    {
        return $this->currencySign . $this->getNumberFormatter()->formatMoney($this->settlementItemDto->hammerPrice);
    }

    /**
     * @return string
     */
    public function makeHammerPriceRealFormatted(): string
    {
        return $this->currencySign . $this->getNumberFormatter()->formatMoneyDetail($this->settlementItemDto->hammerPrice);
    }

    /**
     * @return string
     */
    public function makeFeeFormatted(): string
    {
        return $this->currencySign . $this->getNumberFormatter()->formatMoney($this->settlementItemDto->fee);
    }

    /**
     * @return string
     */
    public function makeFeeRealFormatted(): string
    {
        return $this->currencySign . $this->getNumberFormatter()->formatMoneyDetail($this->settlementItemDto->fee);
    }

    /**
     * @return string
     */
    public function makeCommissionFormatted(): string
    {
        return $this->currencySign . $this->getNumberFormatter()->formatMoney($this->settlementItemDto->commission);
    }

    /**
     * @return string
     */
    public function makeCommissionRealFormatted(): string
    {
        return $this->currencySign . $this->getNumberFormatter()->formatMoneyDetail($this->settlementItemDto->commission);
    }

    /**
     * @return string
     */
    public function makeSubtotalFormatted(): string
    {
        return $this->currencySign . $this->getNumberFormatter()->formatMoney($this->settlementItemDto->subtotal);
    }

    /**
     * @return string
     */
    public function makeSubtotalRealFormatted(): string
    {
        return $this->currencySign . $this->getNumberFormatter()->formatMoneyDetail($this->settlementItemDto->subtotal);
    }

    /**
     * @return string
     */
    public function makeQuantity(): string
    {
        return Floating::gt($this->settlementItemDto->quantity, 0, $this->settlementItemDto->quantityScale)
            ? $this->lotAmountRenderer->makeQuantity($this->settlementItemDto->quantity, $this->settlementItemDto->quantityScale)
            : '-';
    }

    /**
     * @return string
     */
    public function makeEstimatesFormatted(): string
    {
        $isShowLowEst = $this->fetchOptional(self::OP_SHOW_LOW_ESTIMATE, [$this->settlementAccountId]);
        $isShowHighEst = $this->fetchOptional(self::OP_SHOW_HIGH_ESTIMATE, [$this->settlementAccountId]);
        $lowEstimateViewFormatted = $this->lotAmountRenderer->makeLowEstimateFormattedByMoney(
            $this->settlementItemDto->lowEstimate,
            $this->currencySign,
            $isShowLowEst
        );

        $highEstimateViewFormatted = $this->lotAmountRenderer->makehighEstimateFormattedByMoney(
            $this->settlementItemDto->highEstimate,
            $this->currencySign,
            $isShowHighEst
        );

        $lowEstimateViewRealFormatted = $this->lotAmountRenderer->makeLowEstimateFormattedByMoneyDetail(
            $this->settlementItemDto->lowEstimate,
            $this->currencySign,
            $isShowLowEst
        );
        $highEstimateViewRealFormatted = $this->lotAmountRenderer->makeHighEstimateFormattedByMoneyDetail(
            $this->settlementItemDto->highEstimate,
            $this->currencySign,
            $isShowHighEst
        );

        $estimates = [];
        if ($lowEstimateViewFormatted) {
            $estimates[] = sprintf(
                '<span title="%s">%s</span>',
                $lowEstimateViewRealFormatted,
                $lowEstimateViewFormatted
            );
        }
        if ($highEstimateViewFormatted) {
            $estimates[] = sprintf(
                '<span title="%s">%s</span>',
                $highEstimateViewRealFormatted,
                $highEstimateViewFormatted
            );
        }
        return implode(' - ', $estimates);
    }

    /**
     * @return string
     */
    public function makeTaxOnHpFormatted(): string
    {
        $taxOnHp = $this->createSettlementItemTaxCalculator()->calcTaxOnHP($this->settlementItemDto);
        $taxOnHpViewFormatted = $this->getNumberFormatter()->formatMoney($taxOnHp);
        $taxOnHpRealFormatted = $this->getNumberFormatter()->formatMoneyDetail($taxOnHp);
        return sprintf(
            '%s<span title="%s%s">%s</span>',
            $this->currencySign,
            $this->currencySign,
            $taxOnHpRealFormatted,
            $taxOnHpViewFormatted
        );
    }

    /**
     * @return string
     */
    public function makeTaxOnCommissionFormatted(): string
    {
        $taxOnComm = $this->createSettlementItemTaxCalculator()->calcTaxOnComm($this->settlementItemDto);
        $taxOnCommRealFormatted = $this->getNumberFormatter()->formatMoneyDetail($taxOnComm);
        $taxOnCommViewFormatted = $this->getNumberFormatter()->formatMoney($taxOnComm);
        return sprintf(
            '%s<span title="%s%s">%s</span>',
            $this->currencySign,
            $this->currencySign,
            $taxOnCommRealFormatted,
            $taxOnCommViewFormatted
        );
    }

    /**
     * @return string
     */
    public function makeItemNo(): string
    {
        return $this->getLotRenderer()->makeItemNo($this->settlementItemDto->lotItemNum, $this->settlementItemDto->lotItemNumExt);
    }

    /**
     * @return string
     */
    public function makeLotNo(): string
    {
        $lotNo = '-';
        if ($this->isAuctionLotAvailable()) {
            $lotNo = $this->getLotRenderer()->makeLotNo(
                $this->settlementItemDto->lotNum,
                $this->settlementItemDto->lotNumExt,
                $this->settlementItemDto->lotNumPrefix
            );
        }
        return $lotNo;
    }

    /**
     * @return string
     */
    public function makeSaleNo(): string
    {
        $saleNo = '-';
        if ($this->isAuctionAvailable()) {
            $saleNo = $this->getAuctionRenderer()->makeSaleNo($this->settlementItemDto->saleNum, $this->settlementItemDto->saleNumExt);
        }
        return $saleNo;
    }

    /**
     * Check if auction is available for view at admin site - it must not be soft-deleted.
     * @return bool
     */
    public function isAuctionAvailable(): bool
    {
        return AuctionStatusPureChecker::new()
            ->amongNotDeletedAuctionStatuses($this->settlementItemDto->auctionStatusId);
    }

    /**
     * @return bool
     */
    public function isAuctionLotAvailable(): bool
    {
        $isAvailable = $this->isAuctionAvailable()
            && in_array($this->settlementItemDto->lotStatusId, Constants\Lot::$availableLotStatuses, true);
        return $isAvailable;
    }

    public function isLotUnsold(): bool
    {
        return !$this->settlementItemDto->winningBidderId;
    }

    /**
     * @return string
     */
    public function makeLotName(): string
    {
        $lotName = TextTransformer::new()->fixUnicodeCrop($this->settlementItemDto->lotName);
        $lotName = $this->getLotRenderer()->makeName($lotName, $this->settlementItemDto->isTestAuction);
        return ee($lotName);
    }

    /**
     * @return string
     */
    public function makeSaleName(): string
    {
        $saleName = $this->getAuctionRenderer()->makeName(
            $this->settlementItemDto->auctionName,
            $this->settlementItemDto->isTestAuction
        ) ?: '-';
        return ee($saleName);
    }

    /**
     * @return LotItemCustomFieldValueDto[]
     */
    public function makeLotCustomFieldValueList(): array
    {
        /** @var LotItemCustField[] $lotCustomFields */
        $lotCustomFields = $this->fetchOptional(self::OP_LOT_CUSTOM_FIELDS);
        $ids = [];
        foreach ($lotCustomFields as $lotCustomField) {
            $ids[] = $lotCustomField->Id;
        }
        $lotCustomDataEntities = $this->createLotCustomDataLoader()
            ->loadEntities($ids, $this->settlementItemDto->lotItemId, true);
        $lotCustomDataEntities = ArrayHelper::indexEntities($lotCustomDataEntities, 'LotItemCustFieldId');
        $valueList = array_map(
            function (LotItemCustField $lotCustomField) use ($lotCustomDataEntities) {
                $lotCustomData = $lotCustomDataEntities[$lotCustomField->Id] ?? null;
                return $this->makeLotCustomFieldValue($lotCustomField, $lotCustomData);
            },
            $lotCustomFields
        );
        return $valueList;
    }

    /**
     * @return bool
     */
    public function isUnpaid(): bool
    {
        $isSettlementUnpaidLots = $this->fetchOptional(self::OP_SETTLEMENT_UNPAID_LOTS, [$this->settlementAccountId]);
        if (!$isSettlementUnpaidLots) {
            return false;
        }

        $invoiceItem = $this->loadInvoiceItem($this->settlementItemDto);
        if (!$invoiceItem) {
            return true;
        }

        $invoice = $this->getInvoiceLoader()->load($invoiceItem->InvoiceId);
        if (!$invoice) {
            log_error(
                "Available invoice not found"
                . composeSuffix(['i' => $invoiceItem->InvoiceId, 'ii' => $invoiceItem->Id])
            );
        }

        $isPaid = $invoice && $invoice->isPaidOrShipped();
        return !$isPaid;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_LOT_CUSTOM_FIELDS] = $optionals[self::OP_LOT_CUSTOM_FIELDS]
            ?? static function () {
                return LotCustomFieldLoader::new()->loadInSettlements(true);
            };
        $optionals[self::OP_SHOW_HIGH_ESTIMATE] = $optionals[self::OP_SHOW_HIGH_ESTIMATE]
            ?? static function (int $settlementAccountId) {
                return SettingsManager::new()->get(Constants\Setting::SHOW_HIGH_EST, $settlementAccountId);
            };
        $optionals[self::OP_SHOW_LOW_ESTIMATE] = $optionals[self::OP_SHOW_LOW_ESTIMATE]
            ?? static function (int $settlementAccountId) {
                return SettingsManager::new()->get(Constants\Setting::SHOW_LOW_EST, $settlementAccountId);
            };
        $optionals[self::OP_SETTLEMENT_UNPAID_LOTS] = $optionals[self::OP_SETTLEMENT_UNPAID_LOTS]
            ?? static function (int $settlementAccountId) {
                return SettingsManager::new()->get(Constants\Setting::SETTLEMENT_UNPAID_LOTS, $settlementAccountId);
            };
        $this->setOptionals($optionals);
    }

    /**
     * @param LotItemCustField $lotCustomField
     * @param LotItemCustData|null $lotCustomData
     * @return LotItemCustomFieldValueDto
     */
    protected function makeLotCustomFieldValue(LotItemCustField $lotCustomField, ?LotItemCustData $lotCustomData): LotItemCustomFieldValueDto
    {
        $dto = LotItemCustomFieldValueDto::new();
        if (!$lotCustomData) {
            return $dto;
        }
        $value = $this->makeFinalLotCustomFieldValue($lotCustomField, $lotCustomData);
        $cssClassName = $this->createCustomFieldCssClassMaker()->makeCssClassByLotItemCustomField($lotCustomField);
        $customFieldName = ee($lotCustomField->Name);
        $dto->construct($customFieldName, $value, $cssClassName);
        return $dto;
    }

    /**
     * @param LotItemCustField $lotCustomField
     * @param LotItemCustData $lotCustomData
     * @return string
     */
    protected function makeFinalLotCustomFieldValue(LotItemCustField $lotCustomField, LotItemCustData $lotCustomData): string
    {
        // numeric type
        if ($lotCustomField->isNumeric()) {
            if ($lotCustomData->Numeric === null) {
                $value = '';
            } elseif ($lotCustomField->Type === Constants\CustomField::TYPE_DATE) {
                $dateHelper = $this->getDateHelper();
                $dateSys = $dateHelper->convertUtcToSysByTimestamp($lotCustomData->Numeric);
                $value = $dateHelper->formattedDate($dateSys);
            } elseif ($lotCustomField->Type === Constants\CustomField::TYPE_DECIMAL) {
                $precision = (int)$lotCustomField->Parameters;
                $realValue = $lotCustomData->calcDecimalValue($precision);
                $value = $this->getNumberFormatter()->format($realValue, $precision);
            } else {
                $value = (string)$lotCustomData->Numeric;
            }

            return $value;
        }

        if (
            $lotCustomField->Barcode
            && $lotCustomData->Text !== ''
        ) {
            $url = $this->getUrlBuilder()->build(
                BarcodeUrlConfig::new()->forWeb(ee($lotCustomData->Text), $lotCustomField->BarcodeType)
            );
            $value = "<img src=\"{$url}\"  alt=\"\"/>";
            return $value;
        }

        // general
        return ee($lotCustomData->Text);
    }

    /**
     * @param SettlementItemDto $settlementItemDto
     * @return InvoiceItem|null
     */
    protected function loadInvoiceItem(SettlementItemDto $settlementItemDto): ?InvoiceItem
    {
        if (!$settlementItemDto->auctionId) {
            return null;
        }
        $invoiceItem = $this->getInvoiceItemLoader()->loadByLotItemIdAndAuctionId(
            $settlementItemDto->lotItemId,
            $settlementItemDto->auctionId,
            true
        );
        return $invoiceItem;
    }
}
