<?php

/**
 * Packing slip pdf generator, which uses TCPDF library
 *
 * Document consists of 3 blocks:
 * 1) general info at the top: auctions, invoice#, buyer#, billing and shipping addresses,
 * 2) invoice items,
 * 3) confirmation block at the bottom with lines for signatures, etc.
 *
 * SAM-4556: Apply \Sam\Invoice\Legacy\PackingSlip namespace
 *
 * @author        Igors Kotlevskis
 * @package       com.swb.sam2
 * @version       SVN: $Id$
 * @since         Dec 18, 2013
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\PackingSlip;

use Invoice;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Storage\Entity\AwareTrait\InvoiceAwareTrait;
use TCPDF;

class LegacyInvoicePackingSlipTcpdf extends TCPDF
{
    use InvoiceAwareTrait;
    use TranslatorAwareTrait;

    protected string $cssFileRootPath = '';
    protected array $auctionInfoRows = [];
    protected array $billingInfoRows = [];
    protected array $shippingInfoRows = [];
    protected array $buyerInfoRows = [];
    protected array $itemRows = [];
    protected int $fontHeight = 10;
    protected int $confirmationHeight = 38;    // units, it is hard to calculate real height, let's manually assign value for now (because formatting is often changed)

    // text variables
    protected string $auctionCodeTitle = 'Auction Code:';
    protected string $invoiceNoTitle = 'Invoice #:';
    protected string $buyerNoTitle = 'Buyer #:';
    protected string $billingAddressTitle = 'Billing Address:';
    protected string $shipToTitle = 'Ship To:';
    protected string $totalItemsTitle = 'Total items:';
    protected string $confirmationTitle = 'Collection of the above item/s in good order as viewed/sold (as per the terms and conditions of sale)';
    /** @var string[] */
    protected array $confirmationLines = [
        'Client Name:',
        'Client Signature:',
        'Date:',
        '%s Name:',
        '%s Signature:',
        'Date:',
    ];
    protected string $packingSlipTitle = 'PACKING SLIP';
    protected string $quantityTitle = 'Lot';
    protected string $itemNoTitle = 'Item #';
    protected string $descriptionTitle = 'Description';

    /**
     * InvoicePackingSlipTcpdf constructor.
     * @param Invoice $invoice
     */
    public function __construct(Invoice $invoice)
    {
        parent::__construct();
        $this->setInvoice($invoice);
        $this->initTranslations();
    }

    /**
     * Create pdf
     */
    public function create(): void
    {
        $this->bMargin = 10;
        //         $this->SetFont('dejavuserif', '', $this->intFontHeight, '', true);    // IK: doesn't work anymore, idk why
        $this->SetFont('freesans', '', $this->fontHeight, '', true);
        $this->setPrintHeader(false);
        $this->setPrintFooter(false);
        $this->AddPage();
        $output = $this->getStyles();
        $output .= $this->getGeneralInfo();
        //        for($i=0;$i<4;$i++) $this->arrItemsInfo[] = $this->arrItemsInfo[count($this->arrAuctionInfo) - 1]; // un-comment and copy-paste for testing
        //        for($i=0;$i<19;$i++) $this->arrItemsInfo[] = array('qty' => '', 'lotNum' => '', 'name' => 'abcde abcde abcde abcde abcde abcde ');
        $output .= $this->getItemsInfo();
        $output .= $this->getReserveForConfirmation();
        $this->writeHTML($output, true, false, true);
        // Confirmation lines should be placed in the end of the last page
        $this->SetY(-1 * ($this->getConfirmationHeight() + $this->bMargin));
        $output = $this->getStyles();
        $output .= $this->getConfirmation();
        $this->writeHTML($output, true, false, true);
    }

    /**
     * Init text variables
     * possibly translations will be used in future
     */
    protected function initTranslations(): void
    {
        $companyName = $this->getTranslator()->translate('MYINVOICES_PACKINGSLIP_COMPANY_NAME', 'myinvoices');
        foreach ($this->confirmationLines as &$confirmationLine) {
            if (str_contains($confirmationLine, '%s')) {
                $confirmationLine = sprintf($confirmationLine, $companyName);
            }
        }
    }

    /**
     * Get styles from css file and write them in pdf
     * @return string
     */
    protected function getStyles(): string
    {
        $styles = file_get_contents($this->cssFileRootPath);
        $output = <<<HTML
<style>
{$styles}
</style>
HTML;
        return $output;
    }

    /**
     * General info about auctions and bidder
     * @return string
     */
    protected function getGeneralInfo(): string
    {
        $auctionInfo = '';
        $buyerNos = [];
        foreach ($this->auctionInfoRows as $infoRow) {
            $location = $infoRow['location'] !== null
                ? '<span class="auction-location">' . ee($infoRow['location']) . '</span>' : '';
            $auctionInfo .=
                '<p>' .
                '<span class="auction-name">' . ee($infoRow['name']) . '</span><br>' .
                '<span class="auction-code-title">' . $this->auctionCodeTitle . '</span> ' .
                '<span class="auction-code">' . $infoRow['code'] . '</span><br>' .
                '<span class="auction-date">' . $infoRow['date'] . '</span><br>' .
                $location .
                '</p>';
            if ($infoRow['buyerNo'] !== null) {
                $buyerNos[] = $infoRow['buyerNo'];
            }
        }
        $buyerNoList = implode(', ', array_unique($buyerNos));

        $this->billingInfoRows['name'] = ee($this->billingInfoRows['name']);
        $this->billingInfoRows['cityAddress'] = ee($this->billingInfoRows['cityAddress']);
        $this->billingInfoRows['countryAddress'] = ee($this->billingInfoRows['countryAddress']);
        $billingInfoList = implode('<br>', $this->billingInfoRows);

        $this->shippingInfoRows['name'] = ee($this->shippingInfoRows['name']);
        $this->shippingInfoRows['cityAddress'] = ee($this->shippingInfoRows['cityAddress']);
        $this->shippingInfoRows['countryAddress'] = ee($this->shippingInfoRows['countryAddress']);
        $shippingInfoList = implode('<br>', $this->shippingInfoRows);

        $invoiceAndBuyerNo =
            '<p><span class="invoice-no-title">' . $this->invoiceNoTitle . '</span> ' .
            '<span class="invoice-no">' . $this->getInvoice()->InvoiceNo . '</span></p>' .
            '<p><span class="buyer-no-title">' . $this->buyerNoTitle . '</span> ' .
            '<span class="buyer-no">' . $buyerNoList . '</span></p>';
        $billingInfoList = '<span class="billing-info">' . $billingInfoList . '</span>';
        $shippingInfoList = '<span class="shipping-info">' . $shippingInfoList . '</span>';

        $phone = ee($this->buyerInfoRows['phone']);

        $output = <<<HTML
<table class="general-info">
    <tr>
        <td>{$auctionInfo}</td>
        <td><table>
                <tr>
                    <td>{$invoiceAndBuyerNo}</td>
                    <td class="title-packing-slip">{$this->packingSlipTitle}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="bill-to">{$this->billingAddressTitle}</td><td class="ship-to">{$this->shipToTitle}</td>
    </tr>
    <tr>
        <td>{$billingInfoList}</td><td>{$shippingInfoList}</td>
    </tr>
    <tr>
        <td>{$phone}</td><td></td>
    </tr>
</table>
<br><br>
HTML;
        return $output;
    }

    /**
     * Sold items info
     * @return string
     */
    protected function getItemsInfo(): string
    {
        $output = '';
        $table = '<table class="items">%s</table>';
        $tr = '<tr><td class="items-qty">%s</td>' .
            '<td class="items-lotNum">%s</td>' .
            '<td class="items-name">%s</td></tr>';
        foreach ($this->itemRows as $itemRow) {
            $name = html_entity_decode(strip_tags($itemRow['name']), ENT_QUOTES, 'UTF-8');
            $output .= sprintf($tr, $itemRow['qty'], $itemRow['lotNum'], $name);
        }
        $output .= '<tr><td class="total-items-top-padding" colspan="3"></td></tr>';
        $output .= '<tr><td colspan="3"><span class="total-items-lbl">' . $this->totalItemsTitle . '</span> ' .
            '<span class="total-items-cnt">' . count($this->itemRows) . '</span></td></tr>';
        $output = sprintf($table, $output);
        return $output;
    }

    /**
     * We need to write empty block (with height of confirmation block) after sold items to understand,
     * if there is enough space at the page for confirmation block. Page break will happen, if page height exceeded.
     * @return string
     */
    protected function getReserveForConfirmation(): string
    {
        $output = '<table>'; // style="background-color:rgb(255,0,0);">';
        $height = round($this->confirmationHeight / $this->pixelsToUnits(1));
        $output .= '<tr><td style="height:' . $height . 'px">&nbsp;</td></tr>';
        $output .= '</table>';
        return $output;
    }

    /**
     * Confirmation lines, which are filled manually by auction manager
     * @return string
     */
    protected function getConfirmation(): string
    {
        $baseTable = <<<HTML
<table class="confirmation-base-table">
    <tr>
        <td class="confirmation-base-left">%s</td>
        <td class="confirmation-base-right">%s</td>
    </tr>
</table>
HTML;
        $tableLeft = '<table class="confirmation-left-table">%s</table>';
        $tableRight = '<table class="confirmation-right-table">%s</table>';
        $trLeft = '<tr><td class="confirmation-title-left">%s</td><td class="confirmation-line-left"></td></tr>';
        $trRight = '<tr><td class="confirmation-title-right">%s</td><td class="confirmation-line-right"></td></tr>';
        $output = '<div class="confirmation-prompt">' . $this->confirmationTitle . "<br></div>";
        $left = '';
        $left .= sprintf($trLeft, $this->confirmationLines[0]);
        $left .= sprintf($trLeft, $this->confirmationLines[1]);
        $left .= sprintf($trLeft, $this->confirmationLines[2]);
        $right = '';
        $right .= sprintf($trRight, $this->confirmationLines[3]);
        $right .= sprintf($trRight, $this->confirmationLines[4]);
        $right .= sprintf($trRight, $this->confirmationLines[5]);
        $left = sprintf($tableLeft, $left);
        $right = sprintf($tableRight, $right);
        $output .= sprintf($baseTable, $left, $right);
        return $output;
    }

    /**
     * Return height of confirmation block (units)
     * @return int
     */
    private function getConfirmationHeight(): int
    {
        $height = $this->confirmationHeight;
        return $height;
    }

    /** *******************
     * Getters and setters
     */

    /**
     * Set absolute path to css file
     * @param string $cssFileRootPath
     * @return static
     */
    public function setCssFileRootPath(string $cssFileRootPath): static
    {
        $this->cssFileRootPath = trim($cssFileRootPath);
        return $this;
    }

    /**
     * Set billing address info
     * @param array $billingInfoRows
     * @return static
     */
    public function setBillingInfo(array $billingInfoRows): static
    {
        $this->billingInfoRows = $billingInfoRows;
        return $this;
    }

    /**
     * Set shipping address info
     * @param array $shippingInfoRow
     * @return static
     */
    public function setShippingInfo(array $shippingInfoRow): static
    {
        $this->shippingInfoRows = $shippingInfoRow;
        return $this;
    }

    /**
     * Set auctions info
     * @param array $auctionInfoRows
     * @return static
     */
    public function setAuctionInfo(array $auctionInfoRows): static
    {
        $this->auctionInfoRows = $auctionInfoRows;
        return $this;
    }

    /**
     * Set buyer info
     * @param array $buyerInfoRows
     * @return static
     */
    public function setBuyerInfo(array $buyerInfoRows): static
    {
        $this->buyerInfoRows = $buyerInfoRows;
        return $this;
    }

    /**
     * Set sold items info
     * @param array $itemsInfoRows
     * @return static
     */
    public function setItemsInfo(array $itemsInfoRows): static
    {
        $this->itemRows = $itemsInfoRows;
        return $this;
    }
}
