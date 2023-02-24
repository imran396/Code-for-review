<?php
/**
 * SAM-7705: Invoice > View Invoice : Letter head logo is not getting updated at PDF Invoice
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           05-15, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Pdf\Transalation;

use Sam\Core\Service\CustomizableClass;
use Sam\Lang\TranslatorAwareTrait;


class TranslationDto extends CustomizableClass
{
    use TranslatorAwareTrait;

    protected int $languageId;
    public string $invoiceNum;
    public string $dateCreated;
    public string $billingInfo;
    public string $shippingInfo;
    public string $userCustomFieldInfo;
    public string $sale;
    public string $detailSaleDate;
    public string $cashDiscount;
    public string $shipping;
    public string $extraCharges;
    public string $salesTax;
    public string $total;
    public string $paymentMade;
    public string $balance;
    public string $notes;
    public string $saleNum;
    public string $lotNum;
    public string $itemNum;
    public string $itemName;
    public string $quantity;
    public string $category;
    public string $hammerPrice;
    public string $buyersPremium;
    public string $subTotal;
    public string $taxOnGoods;
    public string $taxOnServices;
    public string $invoiceData;
    public string $saleDate;
    public string $auctionEventType;
    public string $invoiceBidderNumber;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $accountId
     * @param int $viewLanguageId
     * @return $this
     */
    public function construct(int $accountId, int $viewLanguageId): static
    {
        $this->languageId = $viewLanguageId;
        $tr = $this->getTranslator()
            ->setAccountId($accountId)
            ->setLanguageId($viewLanguageId);
        $section = 'myinvoices';

        $this->invoiceNum = $tr->translate('MYINVOICES_DETAIL_INVOICENUM', $section);
        $this->dateCreated = $tr->translate('MYINVOICES_DETAIL_DATECREATED', $section);
        $this->billingInfo = $tr->translate('MYINVOICES_DETAIL_BILLINGINFO', $section);
        $this->shippingInfo = $tr->translate('MYINVOICES_DETAIL_SHIPPINGINFO', $section);
        $this->userCustomFieldInfo = $tr->translate('MYINVOICES_DETAIL_USER_CUST_FIELDS', $section);
        $this->sale = $tr->translate('MYINVOICES_DETAIL_SALE', $section);
        $this->detailSaleDate = $tr->translate('MYINVOICES_DETAIL_SALEDATE', $section);
        $this->cashDiscount = $tr->translate('MYINVOICES_DETAIL_CASHDISCOUNT', $section);
        $this->subTotal = $tr->translate('MYINVOICES_DETAIL_SUBTOTAL', $section);
        $this->shipping = $tr->translate('MYINVOICES_DETAIL_SHIPPING', $section);
        $this->extraCharges = $tr->translate('MYINVOICES_DETAIL_EXTRACHARGES', $section);
        $this->salesTax = $tr->translate('MYINVOICES_DETAIL_SALESTAX', $section);
        $this->total = $tr->translate('MYINVOICES_DETAIL_TOTAL', $section);
        $this->paymentMade = $tr->translate('MYINVOICES_DETAIL_PAYMENTSMADE', $section);
        $this->balance = $tr->translate('MYINVOICES_DETAIL_BALANCE', $section);
        $this->notes = $tr->translate('MYINVOICES_DETAIL_NOTES', $section);
        $this->saleNum = $tr->translate('MYINVOICES_DETAIL_SALENUM', $section)
            . ' / ' . $tr->translate('MYINVOICES_DETAIL_LOTNUM', $section);
        $this->lotNum = $tr->translate('MYINVOICES_DETAIL_LOTNUM', $section);
        $this->itemNum = $tr->translate('MYINVOICES_DETAIL_ITEMNUM', $section);
        $this->itemName = $tr->translate('MYINVOICES_DETAIL_ITEMNAME', $section);
        $this->quantity = $tr->translate('MYINVOICES_DETAIL_QUANTITY', $section);
        $this->category = $tr->translate('MYINVOICES_DETAIL_CATEGORY', $section);
        $this->hammerPrice = $tr->translate('MYINVOICES_DETAIL_HAMMER', $section);
        $this->buyersPremium = $tr->translate('MYINVOICES_DETAIL_BUYERSPREMIUM', $section);
        $this->taxOnGoods = $tr->translate('TAX_ON_GOODS', $section);
        $this->taxOnServices = $tr->translate('TAX_ON_SERVICES', $section);

        $this->invoiceData = $tr->translate('MYINVOICES_INVOICE_DATE', 'myinvoices');
        $this->saleDate = $tr->translate('MYINVOICES_SALE_DATE', 'myinvoices');
        $this->auctionEventType = $tr->translate('AUCTIONS_EVENT_TYPE', 'auctions');
        $this->invoiceBidderNumber = mb_convert_encoding($tr->translate("MYINVOICES_BIDDER_NUMBER", "myinvoices"), "CP1252", "UTF-8");

        // Headers decoding from UTF8 to ISO-8859-1 that handle special characters mostly ( �,�,�,�,�,�,�,�,�,�,�,�,�,� )
        $this->invoiceNum = mb_convert_encoding($this->invoiceNum, "CP1252", "UTF-8");
        $this->dateCreated = mb_convert_encoding($this->dateCreated, "CP1252", "UTF-8");
        $this->billingInfo = mb_convert_encoding($this->billingInfo, "CP1252", "UTF-8");
        $this->shippingInfo = mb_convert_encoding($this->shippingInfo, "CP1252", "UTF-8");
        $this->userCustomFieldInfo = mb_convert_encoding($this->userCustomFieldInfo, "CP1252", "UTF-8");
        $this->sale = mb_convert_encoding($this->sale, "CP1252", "UTF-8");
        $this->detailSaleDate = mb_convert_encoding($this->detailSaleDate, "CP1252", "UTF-8");
        $this->cashDiscount = mb_convert_encoding($this->cashDiscount, "CP1252", "UTF-8");
        $this->subTotal = mb_convert_encoding($this->subTotal, "CP1252", "UTF-8");
        $this->shipping = mb_convert_encoding($this->shipping, "CP1252", "UTF-8");
        $this->extraCharges = mb_convert_encoding($this->extraCharges, "CP1252", "UTF-8");
        $this->salesTax = mb_convert_encoding($this->salesTax, "CP1252", "UTF-8");
        $this->total = mb_convert_encoding($this->total, "CP1252", "UTF-8");
        $this->paymentMade = mb_convert_encoding($this->paymentMade, "CP1252", "UTF-8");
        $this->balance = mb_convert_encoding($this->balance, "CP1252", "UTF-8");
        $this->notes = mb_convert_encoding($this->notes, "CP1252", "UTF-8");
        $this->saleNum = mb_convert_encoding($this->saleNum, "CP1252", "UTF-8");
        $this->lotNum = mb_convert_encoding($this->lotNum, "CP1252", "UTF-8");
        $this->lotNum = html_entity_decode($this->lotNum);
        $this->itemNum = mb_convert_encoding($this->itemNum, "CP1252", "UTF-8");
        $this->itemName = mb_convert_encoding($this->itemName, "CP1252", "UTF-8");
        $this->itemName = html_entity_decode($this->itemName);
        $this->quantity = mb_convert_encoding($this->quantity, "CP1252", "UTF-8");
        $this->category = mb_convert_encoding($this->category, "CP1252", "UTF-8");
        $this->hammerPrice = mb_convert_encoding($this->hammerPrice, "CP1252", "UTF-8");
        $this->buyersPremium = mb_convert_encoding($this->buyersPremium, "CP1252", "UTF-8");

        return $this;
    }
}
