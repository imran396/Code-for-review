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

namespace Sam\View\Admin\Form\InvoiceListForm\Load;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class InvoiceListFormDto
 * @package Sam\View\Admin\Form\InvoiceListForm\Load
 */
class InvoiceListFormDto extends CustomizableClass
{
    public readonly float $balance;
    /**
     * Available when MULTIPLE_SALE_INVOICE is Off
     */
    public readonly string $bidderNumber;
    public readonly string $bphone;
    public readonly string $createdOn;
    public readonly string $currSign;
    public readonly float $currExRate;
    public readonly float $export;
    public readonly float $extraCharges;
    public readonly string $firstName;
    public readonly int $id;
    public readonly int $invoiceNo;
    public readonly string $invoiceDate;
    public readonly int $invoiceStatusId;
    public readonly string $iphone;
    /**
     * Available when MULTIPLE_SALE_INVOICE is Off
     */
    public readonly bool $isTestAuction;
    public readonly string $lastName;
    public readonly float $noneTaxable;
    public readonly string $sphone;
    public readonly float $premium;
    /**
     * Available when MULTIPLE_SALE_INVOICE is Off
     */
    public readonly string $saleName;
    /**
     * Available when MULTIPLE_SALE_INVOICE is Off
     */
    public readonly ?int $saleNum;
    /**
     * Available when MULTIPLE_SALE_INVOICE is Off
     */
    public readonly string $saleNumExt;
    public readonly string $sent;
    public readonly string $state;
    public readonly float $shippingFees;
    public readonly float $tax;
    public readonly float $taxable;
    public readonly float $total;
    public readonly float $totalPayment;
    public readonly string $username;
    public readonly string $zip;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param float $balance
     * @param string $bidderNumber available when MULTIPLE_SALE_INVOICE is Off
     * @param string $bphone
     * @param string $createdOn
     * @param string $currSign
     * @param float $currExRate
     * @param float $export
     * @param float $extraCharges
     * @param string $firstName
     * @param int $id
     * @param int $invoiceNo
     * @param string $invoiceDate
     * @param int $invoiceStatusId
     * @param string $iphone
     * @param bool $isTestAuction available when MULTIPLE_SALE_INVOICE is Off
     * @param string $lastName
     * @param float $noneTaxable
     * @param string $sphone
     * @param float $premium
     * @param string $saleName available when MULTIPLE_SALE_INVOICE is Off
     * @param int|null $saleNum available when MULTIPLE_SALE_INVOICE is Off
     * @param string $saleNumExt available when MULTIPLE_SALE_INVOICE is Off
     * @param string $sent
     * @param string $state
     * @param float $shippingFees
     * @param float $tax
     * @param float $taxable
     * @param float $total
     * @param float $totalPayment
     * @param string $username
     * @param string $zip
     * @return $this
     */
    public function construct(
        float $balance,
        string $bidderNumber,
        string $bphone,
        string $createdOn,
        string $currSign,
        float $currExRate,
        float $export,
        float $extraCharges,
        string $firstName,
        int $id,
        int $invoiceNo,
        string $invoiceDate,
        int $invoiceStatusId,
        string $iphone,
        bool $isTestAuction,
        string $lastName,
        float $noneTaxable,
        string $sphone,
        float $premium,
        string $saleName,
        ?int $saleNum,
        string $saleNumExt,
        string $sent,
        string $state,
        float $shippingFees,
        float $tax,
        float $taxable,
        float $total,
        float $totalPayment,
        string $username,
        string $zip
    ): static {
        $this->balance = $balance;
        $this->bidderNumber = $bidderNumber;
        $this->bphone = $bphone;
        $this->createdOn = $createdOn;
        $this->currSign = $currSign;
        $this->currExRate = $currExRate;
        $this->export = $export;
        $this->extraCharges = $extraCharges;
        $this->firstName = $firstName;
        $this->id = $id;
        $this->invoiceNo = $invoiceNo;
        $this->invoiceDate = $invoiceDate;
        $this->invoiceStatusId = $invoiceStatusId;
        $this->iphone = $iphone;
        $this->isTestAuction = $isTestAuction;
        $this->lastName = $lastName;
        $this->noneTaxable = $noneTaxable;
        $this->sphone = $sphone;
        $this->premium = $premium;
        $this->saleName = $saleName;
        $this->saleNum = $saleNum;
        $this->saleNumExt = $saleNumExt;
        $this->sent = $sent;
        $this->state = $state;
        $this->shippingFees = $shippingFees;
        $this->tax = $tax;
        $this->taxable = $taxable;
        $this->total = $total;
        $this->totalPayment = $totalPayment;
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
            (float)$row['balance'],
            (string)($row['bidder_number'] ?? ''),
            (string)$row['bphone'],
            (string)$row['created_on'],
            (string)$row['curr_sign'],
            (float)$row['curr_ex_rate'],
            (float)$row['export'],
            (float)$row['extra_charges'],
            (string)$row['first_name'],
            (int)$row['id'],
            (int)$row['invoice_no'],
            (string)$row['invoice_date'],
            (int)$row['invoice_status_id'],
            (string)$row['iphone'],
            (bool)($row['test_auction'] ?? false),
            (string)$row['last_name'],
            (float)$row['none_taxable'],
            (string)$row['sphone'],
            (float)$row['premium'],
            (string)($row['sale_name'] ?? ''),
            Cast::toInt($row['sale_num'] ?? null),
            (string)($row['sale_num_ext'] ?? ''),
            (string)$row['sent'],
            (string)$row['state'],
            (float)$row['shipping_fees'],
            (float)$row['tax'],
            (float)$row['taxable'],
            (float)$row['total'],
            (float)$row['total_payment'],
            (string)$row['username'],
            (string)$row['zip']
        );
    }
}
