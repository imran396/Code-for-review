<?php
/**
 * SAM-4626: Refactor settlement line item report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-12-26
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Settlement\LineItem;

use Sam\Application\Access\ApplicationAccessCheckerCreateTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Entity\Model\SettlementItem\SellInfo\SettlementItemSellInfoPureChecker;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterCurrencyAwareTrait;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Report\Base\Csv\ReporterBase;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class SettlementLineItemReporter
 * @package Sam\Report\Settlement\LineItem
 */
class SettlementLineItemReporter extends ReporterBase
{
    use ApplicationAccessCheckerCreateTrait;
    use AuctionRendererAwareTrait;
    use FilterAccountAwareTrait;
    use FilterCurrencyAwareTrait;
    use FilterDatePeriodAwareTrait;
    use InvoiceItemLoaderAwareTrait;
    use InvoiceLoaderAwareTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use TranslatorAwareTrait;

    protected ?DataLoader $dataLoader = null;
    protected bool $isQuantityInSettlement = false;
    protected bool $isChargeConsignorCommission = false;
    protected ?int $editorUserId = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function setEditorUserId(?int $editorUserId): static
    {
        $this->editorUserId = $editorUserId;
        return $this;
    }

    /**
     * @return string
     */
    public function getOutputFileName(): string
    {
        if ($this->outputFileName === null) {
            $this->outputFileName = "settlement-line-items-" . date('m-d-Y-His') . ".csv";
        }
        return $this->outputFileName;
    }

    /**
     * @param DataLoader $dataLoader
     * @return static
     */
    public function setDataLoader(DataLoader $dataLoader): static
    {
        $this->dataLoader = $dataLoader;
        return $this;
    }

    /**
     * @return DataLoader
     */
    public function getDataLoader(): DataLoader
    {
        if ($this->dataLoader === null) {
            $this->dataLoader = DataLoader::new()
                ->enableAccountFiltering($this->isAccountFiltering())
                ->filterAccountId($this->getFilterAccountId())
                ->filterEndDateUtcIso($this->getFilterEndDateUtcIso())
                ->filterStartDateUtcIso($this->getFilterStartDateUtcIso())
                ->setSystemAccountId($this->getSystemAccountId());
        }
        return $this->dataLoader;
    }

    /**
     * Filter IsQuantityInSettlement
     * @param bool $isQuantityInSettlement
     * @return static
     */
    public function filterIsQuantityInSettlement(bool $isQuantityInSettlement): static
    {
        $this->isQuantityInSettlement = $isQuantityInSettlement;
        return $this;
    }

    /**
     * Get $isQuantityInSettlement
     * @return bool
     */
    public function isQuantityInSettlement(): bool
    {
        return $this->isQuantityInSettlement;
    }

    /**
     * Filter IsChargeConsignorCommission
     * @param bool $isChargeConsignorCommission
     * @return static
     */
    public function filterIsChargeConsignorCommission(bool $isChargeConsignorCommission): static
    {
        $this->isChargeConsignorCommission = $isChargeConsignorCommission;
        return $this;
    }

    /**
     * Get $isChargeConsignorCommission
     * @return bool
     */
    public function isChargeConsignorCommission(): bool
    {
        return $this->isChargeConsignorCommission;
    }

    /**
     * @return string
     */
    protected function outputBody(): string
    {
        $rows = $this->getDataLoader()->load();
        $output = '';
        foreach ($rows as $row) { //cycle through auction lots
            $bodyLine = $this->buildBodyLine($row);
            $output .= $this->processOutput($bodyLine);
        }
        return $output;
    }

    /**
     * @param array $row
     * @return string
     */
    protected function buildBodyLine(array $row): string
    {
        $isQuantityInSettlement = $this->isQuantityInSettlement();
        $isChargeConsignorCommission = $this->isChargeConsignorCommission();

        $bodyRow = [];
        $settlementNo = $row['settlement_no'];
        $itemNum = $this->getLotRenderer()->makeItemNo($row['item_num'], $row['item_num_ext']);

        $auctionName = '-';
        $saleNo = '-';
        $lotNo = '-';

        $auctionId = (int)$row['auction_id'];
        $auctionStatus = (int)$row['auction_status_id'];
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if (
            $auctionId > 0
            && !$auctionStatusPureChecker->isDeleted($auctionStatus)
        ) {
            $auctionName = $this->getAuctionRenderer()->makeName($row['auction_name'], (bool)$row['test_auction']);
            $saleNo = $this->getAuctionRenderer()->makeSaleNo($row['sale_num'], $row['sale_num_ext']);
            if ($row['alid']) {
                $lotNo = $this->getLotRenderer()->makeLotNo($row['lot_num'], $row['lot_num_ext'], $row['lot_num_prefix']);
            }
        }

        $quantity = (float)$row['quantity'];
        $lotName = $this->getLotRenderer()->makeName($row['lot_name'], (bool)$row['test_auction']);

        if ($isQuantityInSettlement) {
            $quantityFormatted = '-';
            if ($quantity > 0) {
                $quantityFormatted = $this->getNumberFormatter()->formatNto($quantity, (int)$row['quantity_scale']);
            }
        }

        $hammerPrice = Cast::toFloat($row['hammer_price']);
        $hammerPriceFormatted = SettlementItemSellInfoPureChecker::new()->isHammerPrice($hammerPrice)
            ? $this->getNumberFormatter()->formatMoneyNto($hammerPrice)
            : '';
        $commissionFormatted = $this->getNumberFormatter()->formatMoneyNto($row['commission']);
        $subtotalFormatted = $this->getNumberFormatter()->formatMoneyNto($row['subtotal']);

        $invoiceItem = null;
        if ($auctionId > 0) {
            $invoiceItem = $this->getInvoiceItemLoader()->loadByLotItemIdAndAuctionId((int)$row['lot_item_id'], $auctionId);
        }

        $taxExclusive = $row['tax_exclusive'] * -1;
        $taxExclusiveFormatted = $this->getNumberFormatter()->formatMoneyNto($taxExclusive);

        $isYes = true;
        if (!$invoiceItem) {
            $isYes = false;
        } else {
            $invoice = $this->getInvoiceLoader()
                ->clear()
                ->load($invoiceItem->InvoiceId, true);
            if (!$invoice) {
                log_error("Available invoice not found" . composeSuffix(['i' => $invoiceItem->InvoiceId]));
                $isYes = false;
            } elseif (!$invoice->isPaidOrShipped()) {
                $isYes = false;
            }
        }

        $bodyRow[] = $settlementNo;
        $bodyRow[] = $itemNum;
        $bodyRow[] = $saleNo . '/' . $lotNo;
        $bodyRow[] = $lotName;
        if ($isQuantityInSettlement) {
            $bodyRow[] = $quantityFormatted;
        }
        $bodyRow[] = $auctionName;
        $bodyRow[] = $hammerPriceFormatted;
        if ($isChargeConsignorCommission) {
            $bodyRow[] = $subtotalFormatted;
            $bodyRow[] = $commissionFormatted;
        } else {
            $bodyRow[] = $commissionFormatted;
            $bodyRow[] = $subtotalFormatted;
        }
        $bodyRow[] = $taxExclusiveFormatted;
        $bodyRow[] = $this->getReportTool()->renderBool($isYes);

        $bodyLine = $this->makeLine($bodyRow);

        return $bodyLine;
    }

    /**
     * Output titles for csv header (string or echo)
     * @return string
     */
    protected function outputTitles(): string
    {
        $isQuantityInSettlement = $this->isQuantityInSettlement();
        $isChargeConsignorCommission = $this->isChargeConsignorCommission();
        $currencySign = $this->getFilterCurrencySign();

        $headerTitles = [
            "Settlement #",
            "Item #",
            "Sale #/Lot #",
            "Item Name"
        ];
        if ($isQuantityInSettlement) {
            $headerTitles[] = $this->getTranslator()
                ->translate('MYSETTLEMENTS_DETAIL_QUANTITY', 'mysettlements', $this->getSystemAccountId());
        }
        array_push(
            $headerTitles,
            "Auction",
            "Hammer price[" . $currencySign . "]"
        );
        if ($isChargeConsignorCommission) {
            array_push(
                $headerTitles,
                "Subtotal[" . $currencySign . "]",
                "Commission[" . $currencySign . "]"
            );
        } else {
            array_push(
                $headerTitles,
                "Commission[" . $currencySign . "]",
                "Subtotal[" . $currencySign . "]"
            );
        }
        array_push(
            $headerTitles,
            "Tax[" . $currencySign . "]",
            "Paid?"
        );

        $headerLine = $this->makeLine($headerTitles);

        return $this->processOutput($headerLine);
    }

    /**
     * @return bool
     */
    public function isAccountFiltering(): bool
    {
        return $this->createApplicationAccessChecker()->isCrossDomainAdminOnMainAccountForMultipleTenant(
            $this->editorUserId,
            $this->getSystemAccountId(),
            true
        );
    }
}
