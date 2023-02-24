<?php
/**
 * SAM-11084: Stacked Tax. Tax aggregation. Admin Invoice List CSV
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 15, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Invoice\StackedTax\InvoiceList\Csv;

use Sam\Core\Service\CustomizableClass;

/**
 * Class StackedTaxInvoiceListDto
 * @package Sam\Report\Invoice\StackedTax\InvoiceList\Csv
 */
class StackedTaxInvoiceListDto extends CustomizableClass
{
    public readonly int $invoiceId;
    public readonly string $itemNo;
    public readonly string $lotNo;
    public readonly float $quantity;
    public readonly int $quantityScale;
    public readonly int $invoiceStatusId;
    public readonly int $bidderId;
    public readonly int $saleId;
    public readonly string $saleNo;
    public readonly string $saleName;
    public readonly string $saleDescription;
    public readonly string $invoiceDate;
    public readonly string $createdDate;
    public readonly int $invoiceNo;
    public readonly string $internalNote;
    public readonly string $firstSentOn;
    public readonly string $bidderNumber;
    public readonly string $username;
    public readonly int $customerNo;
    public readonly string $email;
    public readonly string $firstName;
    public readonly string $lastName;
    public readonly string $iphone;
    public readonly string $referer;
    public readonly string $refererHost;
    public readonly string $state;
    public readonly string $zip;
    public readonly string $billingCompanyName;
    public readonly string $billingFirstName;
    public readonly string $billingLastName;
    public readonly string $billingPhoneNo;
    public readonly string $billingAddress;
    public readonly string $billingAddress2;
    public readonly string $billingAddress3;
    public readonly string $billingCity;
    public readonly string $billingState;
    public readonly string $billingZip;
    public readonly string $billingCountry;
    public readonly string $shippingCompanyName;
    public readonly string $shippingFirstName;
    public readonly string $shippingLastName;
    public readonly string $shippingPhoneNo;
    public readonly string $shippingAddress;
    public readonly string $shippingAddress2;
    public readonly string $shippingAddress3;
    public readonly string $shippingCity;
    public readonly string $shippingState;
    public readonly string $shippingZip;
    public readonly string $shippingCountry;
    public readonly float $grandTotal;
    public readonly float $balanceDue;
    public readonly float $totalPayments;
    public readonly float $hammerPrice;
    public readonly float $hammerPriceTax;
    public readonly float $buyerPremium;
    public readonly float $buyerPremiumTax;
    public readonly float $services;
    public readonly float $servicesTax;
    public readonly float $taxTotal;
    public readonly float $countryTaxTotal;
    public readonly float $stateTaxTotal;
    public readonly float $countyTaxTotal;
    public readonly float $cityTaxTotal;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function construct(
        int $invoiceId,
        string $itemNo,
        string $lotNo,
        float $quantity,
        int $quantityScale,
        int $invoiceStatusId,
        int $bidderId,
        int $saleId,
        string $saleNo,
        string $saleName,
        string $saleDescription,
        string $invoiceDate,
        string $createdDate,
        int $invoiceNo,
        string $internalNote,
        string $firstSentOn,
        string $bidderNumber,
        string $username,
        int $customerNo,
        string $email,
        string $firstName,
        string $lastName,
        string $iphone,
        string $referer,
        string $refererHost,
        string $state,
        string $zip,
        string $billingCompanyName,
        string $billingFirstName,
        string $billingLastName,
        string $billingPhoneNo,
        string $billingAddress,
        string $billingAddress2,
        string $billingAddress3,
        string $billingCity,
        string $billingState,
        string $billingZip,
        string $billingCountry,
        string $shippingCompanyName,
        string $shippingFirstName,
        string $shippingLastName,
        string $shippingPhoneNo,
        string $shippingAddress,
        string $shippingAddress2,
        string $shippingAddress3,
        string $shippingCity,
        string $shippingState,
        string $shippingZip,
        string $shippingCountry,
        float $grandTotal,
        float $balanceDue,
        float $totalPayments,
        float $hammerPrice,
        float $hammerPriceTax,
        float $buyerPremium,
        float $buyerPremiumTax,
        float $services,
        float $servicesTax,
        float $taxTotal,
        float $countryTaxTotal,
        float $stateTaxTotal,
        float $countyTaxTotal,
        float $cityTaxTotal
    ): static {
        $this->invoiceId = $invoiceId;
        $this->itemNo = $itemNo;
        $this->lotNo = $lotNo;
        $this->quantity = $quantity;
        $this->quantityScale = $quantityScale;
        $this->invoiceStatusId = $invoiceStatusId;
        $this->bidderId = $bidderId;
        $this->saleId = $saleId;
        $this->saleNo = $saleNo;
        $this->saleName = $saleName;
        $this->saleDescription = $saleDescription;
        $this->invoiceDate = $invoiceDate;
        $this->createdDate = $createdDate;
        $this->invoiceNo = $invoiceNo;
        $this->internalNote = $internalNote;
        $this->firstSentOn = $firstSentOn;
        $this->bidderNumber = $bidderNumber;
        $this->username = $username;
        $this->customerNo = $customerNo;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->iphone = $iphone;
        $this->referer = $referer;
        $this->refererHost = $refererHost;
        $this->state = $state;
        $this->zip = $zip;
        $this->billingCompanyName = $billingCompanyName;
        $this->billingFirstName = $billingFirstName;
        $this->billingLastName = $billingLastName;
        $this->billingPhoneNo = $billingPhoneNo;
        $this->billingAddress = $billingAddress;
        $this->billingAddress2 = $billingAddress2;
        $this->billingAddress3 = $billingAddress3;
        $this->billingCity = $billingCity;
        $this->billingState = $billingState;
        $this->billingZip = $billingZip;
        $this->billingCountry = $billingCountry;
        $this->shippingCompanyName = $shippingCompanyName;
        $this->shippingFirstName = $shippingFirstName;
        $this->shippingLastName = $shippingLastName;
        $this->shippingPhoneNo = $shippingPhoneNo;
        $this->shippingAddress = $shippingAddress;
        $this->shippingAddress2 = $shippingAddress2;
        $this->shippingAddress3 = $shippingAddress3;
        $this->shippingCity = $shippingCity;
        $this->shippingState = $shippingState;
        $this->shippingZip = $shippingZip;
        $this->shippingCountry = $shippingCountry;
        $this->grandTotal = $grandTotal;
        $this->balanceDue = $balanceDue;
        $this->totalPayments = $totalPayments;
        $this->hammerPrice = $hammerPrice;
        $this->hammerPriceTax = $hammerPriceTax;
        $this->buyerPremium = $buyerPremium;
        $this->buyerPremiumTax = $buyerPremiumTax;
        $this->services = $services;
        $this->servicesTax = $servicesTax;
        $this->taxTotal = $taxTotal;
        $this->countryTaxTotal = $countryTaxTotal;
        $this->stateTaxTotal = $stateTaxTotal;
        $this->countyTaxTotal = $countyTaxTotal;
        $this->cityTaxTotal = $cityTaxTotal;
        return $this;
    }

    /**
     * @param array $row
     * @return $this
     */
    public function fromDbRow(array $row): static
    {
        return $this->construct(
            (int)$row['id'],
            (string)$row['item_no'],
            (string)$row['lot_no'],
            (float)$row['quantity'],
            (int)$row['quantity_scale'],
            (int)$row['invoice_status_id'],
            (int)$row['bidder_id'],
            (int)$row['sale_id'],
            (string)($row['sale_no'] ?? ''),
            (string)($row['sale_name'] ?? ''),
            (string)($row['sale_desc'] ?? ''),
            (string)$row['invoice_date'],
            (string)$row['created_on'],
            (int)$row['invoice_no'],
            (string)$row['internal_note'],
            (string)$row['first_sent_on'],
            (string)($row['bidder_num'] ?? ''),
            (string)$row['username'],
            (int)$row['customer_no'],
            (string)$row['email'],
            (string)$row['first_name'],
            (string)$row['last_name'],
            (string)$row['iphone'],
            (string)$row['referrer'],
            (string)$row['referrer_host'],
            (string)$row['state'],
            (string)$row['zip'],
            (string)$row['bcompany_name'],
            (string)$row['bfirst_name'],
            (string)$row['blast_name'],
            (string)$row['bphone'],
            (string)$row['baddress'],
            (string)$row['baddress2'],
            (string)$row['baddress3'],
            (string)$row['bcity'],
            (string)$row['bstate'],
            (string)$row['bzip'],
            (string)$row['bcountry'],
            (string)$row['scompany_name'],
            (string)$row['sfirst_name'],
            (string)$row['slast_name'],
            (string)$row['sphone'],
            (string)$row['saddress'],
            (string)$row['saddress2'],
            (string)$row['saddress3'],
            (string)$row['scity'],
            (string)$row['sstate'],
            (string)$row['szip'],
            (string)$row['scountry'],
            (float)$row['invoice_total'],
            (float)$row['balance_due'],
            (float)$row['total_payment'],
            (float)$row['bid_total'],
            (float)$row['hp_tax_total'],
            (float)$row['buyers_premium'],
            (float)$row['bp_tax_total'],
            (float)$row['extra_charges'],
            (float)$row['services_tax_total'],
            (float)$row['tax_total'],
            (float)$row['country_tax_total'],
            (float)$row['state_tax_total'],
            (float)$row['county_tax_total'],
            (float)$row['city_tax_total']
        );
    }
}
