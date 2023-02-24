<?php
/**
 * SAM-9139: Apply DTO for Invoice List page at admin side
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\StackedTaxInvoiceListForm\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class InvoiceListFormDto
 * @package Sam\View\Admin\Form\InvoiceListForm\Load
 */
class StackedTaxInvoiceListFormDto extends CustomizableClass
{
    public readonly float $hp;
    public readonly float $bp;
    public readonly float $extraCharges;
    public readonly float $hpTaxTotal;
    public readonly float $bpTaxTotal;
    public readonly float $servicesTaxTotal;
    public readonly float $countryTaxTotal;
    public readonly float $stateTaxTotal;
    public readonly float $countyTaxTotal;
    public readonly float $cityTaxTotal;
    public readonly float $paymentTotal;
    public readonly float $invoiceTotal;
    public readonly float $balanceDue;

    public readonly string $currSign;
    public readonly float $currExRate;

    public readonly int $invoiceId;
    public readonly int $invoiceStatusId;
    public readonly int $invoiceNo;
    public readonly string $invoiceDateIso;
    public readonly string $createdOnIso;
    public readonly string $sentDateIso;

    public readonly string $username;
    public readonly string $firstName;
    public readonly string $lastName;
    public readonly string $state;
    public readonly string $zip;
    public readonly string $bphone;
    public readonly string $iphone;
    public readonly string $sphone;

    public readonly string $bidderNumList;

    public array $auctionRows = [];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $invoiceId
     * @param int $invoiceStatusId
     * @param int $invoiceNo
     * @param string $createdOnIso
     * @param string $invoiceDateIso
     * @param string $sentDateIso
     * @param float $hp
     * @param float $hpTaxTotal
     * @param float $bp
     * @param float $bpTaxTotal
     * @param float $countryTaxTotal
     * @param float $stateTaxTotal
     * @param float $countyTaxTotal
     * @param float $cityTaxTotal
     * @param float $extraCharges
     * @param float $servicesTaxTotal
     * @param float $paymentTotal
     * @param float $invoiceTotal
     * @param float $balanceDue
     * @param float $currExRate
     * @param string $currSign
     * @param string $username
     * @param string $firstName
     * @param string $lastName
     * @param string $bphone
     * @param string $sphone
     * @param string $iphone
     * @param string $state
     * @param string $zip
     * @param string $bidderNumList
     * @return $this
     */
    public function construct(
        int $invoiceId,
        int $invoiceStatusId,
        int $invoiceNo,
        string $createdOnIso,
        string $invoiceDateIso,
        string $sentDateIso,
        float $hp,
        float $hpTaxTotal,
        float $bp,
        float $bpTaxTotal,
        float $countryTaxTotal,
        float $stateTaxTotal,
        float $countyTaxTotal,
        float $cityTaxTotal,
        float $extraCharges,
        float $servicesTaxTotal,
        float $paymentTotal,
        float $invoiceTotal,
        float $balanceDue,
        float $currExRate,
        string $currSign,
        string $username,
        string $firstName,
        string $lastName,
        string $bphone,
        string $sphone,
        string $iphone,
        string $state,
        string $zip,
        string $bidderNumList
    ): static {
        $this->balanceDue = $balanceDue;
        $this->bidderNumList = $bidderNumList;
        $this->bp = $bp;
        $this->bpTaxTotal = $bpTaxTotal;
        $this->bphone = $bphone;
        $this->cityTaxTotal = $cityTaxTotal;
        $this->countryTaxTotal = $countryTaxTotal;
        $this->countyTaxTotal = $countyTaxTotal;
        $this->createdOnIso = $createdOnIso;
        $this->currExRate = $currExRate;
        $this->currSign = $currSign;
        $this->extraCharges = $extraCharges;
        $this->firstName = $firstName;
        $this->hp = $hp;
        $this->hpTaxTotal = $hpTaxTotal;
        $this->invoiceDateIso = $invoiceDateIso;
        $this->invoiceId = $invoiceId;
        $this->invoiceNo = $invoiceNo;
        $this->invoiceStatusId = $invoiceStatusId;
        $this->invoiceTotal = $invoiceTotal;
        $this->iphone = $iphone;
        $this->lastName = $lastName;
        $this->paymentTotal = $paymentTotal;
        $this->sentDateIso = $sentDateIso;
        $this->servicesTaxTotal = $servicesTaxTotal;
        $this->sphone = $sphone;
        $this->state = $state;
        $this->stateTaxTotal = $stateTaxTotal;
        $this->username = $username;
        $this->zip = $zip;
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
            (int)$row['invoice_status_id'],
            (int)$row['invoice_no'],
            (string)$row['created_on'],
            (string)$row['invoice_date'],
            (string)$row['first_sent_on'],
            (float)$row['bid_total'],
            (float)$row['hp_tax_total'],
            (float)$row['buyers_premium'],
            (float)$row['bp_tax_total'],
            (float)$row['country_tax_total'],
            (float)$row['state_tax_total'],
            (float)$row['county_tax_total'],
            (float)$row['city_tax_total'],
            (float)$row['extra_charges'],
            (float)$row['services_tax_total'],
            (float)$row['total_payment'],
            (float)$row['invoice_total'],
            (float)$row['balance_due'],
            (float)$row['curr_ex_rate'],
            (string)$row['curr_sign'],
            (string)$row['username'],
            (string)$row['first_name'],
            (string)$row['last_name'],
            (string)$row['bphone'],
            (string)$row['sphone'],
            (string)$row['iphone'],
            (string)$row['state'],
            (string)$row['zip'],
            (string)$row['bidder_num_list'],
        );
    }
}
