<?php
/**
 * SAM-10948: Stacked Tax. Invoice Management pages. Prepare Invoice Generation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 12, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Generate\Chunk\Internal\Report\Build;

use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Application\Url\Build\Config\AuctionLot\AdminLotEditUrlConfig;
use Sam\Application\Url\Build\Config\Invoice\AnySingleInvoiceUrlConfig;
use Sam\Application\Url\Build\Config\LotItem\AnySingleLotItemUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Invoice\Render\InvoicePureRenderer;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Legacy\Generate\Chunk\LegacyInvoiceChunkGenerationResult;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Translation\AdminTranslatorAwareTrait;

class ReportBuilder extends CustomizableClass
{
    use AccountLoaderAwareTrait;
    use AdminTranslatorAwareTrait;
    use AuctionRendererAwareTrait;
    use LotRendererAwareTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $invoiceItemRows
     * @return string[]
     */
    public function buildMessagesForBillableItems(array $invoiceItemRows): array
    {
        $reportMessages = [];
        $urlBuilder = $this->getUrlBuilder();
        foreach ($invoiceItemRows as $row) {
            $invoiceId = (int)$row['invoice_id'];
            $lotItemId = (int)$row['lot_item_id'];
            $lotName = $row['lot_name'];
            $auctionLotId = (int)$row['auction_lot_id'];
            $invoiceNo = InvoicePureRenderer::new()->makeInvoiceNo((int)$row['invoice_no']);
            if ($auctionLotId) {
                $lotNum = Cast::toInt($row['lot_num']);
                $lotNumExt = $row['lot_num_ext'];
                $lotNumPrefix = $row['lot_num_prefix'];
                $saleNum = Cast::toInt($row['sale_num']);
                $saleNumExt = $row['sale_num_ext'];
                $targetAuctionId = (int)$row['target_auction_id'];
                $lotNo = $this->getLotRenderer()->makeLotNo($lotNum, $lotNumExt, $lotNumPrefix);
                $resultSaleNo = $this->getAuctionRenderer()->makeSaleNo($saleNum, $saleNumExt);
                $url = $urlBuilder->build(
                    AdminLotEditUrlConfig::new()->forWeb($lotItemId, $targetAuctionId)
                );
                $langLotInSale = $this->translate(
                    "invoice_generation.report.lot_in_sale",
                    ['lotNo' => $lotNo, 'lotName' => $lotName, 'saleNo' => $resultSaleNo]
                );
                $lotItemHtml = $this->makeLink($url, $langLotInSale);
            } else {
                $url = $urlBuilder->build(
                    AnySingleLotItemUrlConfig::new()->forWeb(Constants\Url::A_INVENTORY_LOT_EDIT, $lotItemId)
                );
                $itemNo = $this->getLotRenderer()->makeItemNo(Cast::toInt($row['item_num']), $row['item_num_ext']);
                $langItemInSale = $this->translate(
                    "invoice_generation.report.item_in_sale",
                    ['itemNo' => $itemNo, 'lotName' => $lotName]
                );
                $lotItemHtml = $this->makeLink($url, $langItemInSale);
            }

            $url = $urlBuilder->build(
                AnySingleInvoiceUrlConfig::new()->forWeb(Constants\Url::A_INVOICES_EDIT, $invoiceId)
            );
            $langInvoiceNo = $this->translate(
                "invoice_generation.report.already_on_invoice.link.invoice_no.text",
                ['invoiceNo' => $invoiceNo]
            );
            $invoiceLink = $this->makeLink($url, $langInvoiceNo);
            $reportMessages[] = $this->translate(
                "invoice_generation.report.already_on_invoice",
                ['lot' => $lotItemHtml, 'invoiceNo' => $invoiceNo, 'invoice' => $invoiceLink]
            );
        }
        return $reportMessages;
    }

    public function completeReport(bool $isAccountFiltering, LegacyInvoiceChunkGenerationResult $result): string
    {
        $glue = ' <br />';
        $output = '';
        $accountNames = $this->loadAccountNames(array_keys($result->generatedCounts), true);
        foreach ($result->generatedCounts as $accountId => $generatedCount) {
            $reportLines = [];
            if ($generatedCount === 1) {
                $reportLines[] = $this->translate("invoice_generation.report.one_invoice_generated");
            } elseif ($generatedCount > 1) {
                $reportLines[] = $this->translate(
                    "invoice_generation.report.multiple_invoice_generated_in_total",
                    ['count' => $generatedCount]
                );
            }

            if ($result->hasReportMessagesForAccount($accountId)) {
                $reportMessages = $result->getReportMessagesForAccount($accountId);
                $reportLines = array_merge($reportLines, $reportMessages);
            }

            if ($result->hasErrorMessagesForAccount($accountId)) {
                $errorMessages = $result->getErrorMessagesForAccount($accountId);
                $reportLines = array_merge($reportLines, $errorMessages);
            }

            if ($reportLines) {
                if ($isAccountFiltering) {
                    $output .= $this->translate(
                        "invoice_generation.report.account_filtering_prefix",
                        ['name' => $accountNames[$accountId]['name'] ?? '', 'id' => $accountId]
                    );
                }
                $output .= implode($glue, $reportLines) . $glue;
            }
        }
        return $output;
    }

    protected function makeLink(string $url, string $text): string
    {
        return sprintf('<a href="%s">%s</a> ', $url, $text);
    }

    protected function translate(string $key, array $params = [], string $domain = 'admin_invoice_list'): string
    {
        return $this->getAdminTranslator()->trans($key, $params, $domain);
    }

    protected function loadAccountNames(array $accountIds, bool $isReadOnlyDb = false): array
    {
        $rows = $this->getAccountLoader()->loadSelectedByIds(['id', 'name'], $accountIds, $isReadOnlyDb);
        return ArrayHelper::indexRows($rows, 'id');
    }
}
