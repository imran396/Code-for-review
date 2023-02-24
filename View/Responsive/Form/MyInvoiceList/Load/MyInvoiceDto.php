<?php
/**
 * SAM-9321: Apply DTOs for My Invoice List page at client side
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\MyInvoiceList\Load;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class MyInvoiceDto
 * @package Sam\View\Responsive\Form\MyInvoiceList\Load
 */
class MyInvoiceDto extends CustomizableClass
{
    public readonly int $accountId;
    public readonly string $createdOn;
    public readonly int $id;
    public readonly string $invoiceDate;
    public readonly ?int $invoiceNo;
    public readonly int $invoiceStatusId;
    public readonly string $numItem;
    public readonly int $saleId;
    public readonly int $taxDesignation;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $accountId
     * @param string $createdOn
     * @param int $id
     * @param string $invoiceDate
     * @param int|null $invoiceNo
     * @param int $invoiceStatusId
     * @param string $numItem
     * @param int $saleId
     * @param int $taxDesignation
     * @return $this
     */
    public function construct(
        int $accountId,
        string $createdOn,
        int $id,
        string $invoiceDate,
        ?int $invoiceNo,
        int $invoiceStatusId,
        string $numItem,
        int $saleId,
        int $taxDesignation
    ): static {
        $this->accountId = $accountId;
        $this->createdOn = $createdOn;
        $this->id = $id;
        $this->invoiceDate = $invoiceDate;
        $this->invoiceNo = $invoiceNo;
        $this->invoiceStatusId = $invoiceStatusId;
        $this->numItem = $numItem;
        $this->saleId = $saleId;
        $this->taxDesignation = $taxDesignation;
        return $this;
    }

    /**
     * @param array $row
     * @return $this
     */
    public function fromDbRow(array $row): static
    {
        return $this->construct(
            (int)$row['account_id'],
            (string)$row['created_on'],
            (int)$row['id'],
            (string)$row['invoice_date'],
            Cast::toInt($row['invoice_no'], Constants\Type::F_INT_POSITIVE),
            (int)$row['invoice_status_id'],
            (string)$row['num_item'],
            (int)$row['sale_id'],
            (int)$row['tax_designation']
        );
    }
}
