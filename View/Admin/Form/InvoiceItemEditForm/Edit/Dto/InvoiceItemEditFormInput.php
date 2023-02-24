<?php
/**
 * SAM-11091: Stacked Tax. New Invoice Edit page: Invoice Item Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceItemEditForm\Edit\Dto;

use Sam\Core\Service\CustomizableClass;

/**
 * Class InvoiceItemEditFormInputDto
 * @package Sam\View\Admin\Form\InvoiceItemEditForm\Edit
 */
class InvoiceItemEditFormInput extends CustomizableClass
{
    public readonly ?int $bpTaxSchemaId;
    public readonly ?int $hpTaxSchemaId;
    public readonly string $bidderNum;
    public readonly string $buyersPremium;
    public readonly string $hammerPrice;
    public readonly string $itemNo;
    public readonly string $lotName;
    public readonly string $lotNo;
    public readonly string $quantity;
    public readonly int $quantityDigits;
    public int $invoiceItemId;
    public int $invoiceAccountId;
    public int $editorUserId;
    public string $language;
    public bool $isReadOnlyDb;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        ?int $bpTaxSchemaId,
        ?int $hpTaxSchemaId,
        string $bidderNum,
        string $buyersPremium,
        string $hammerPrice,
        string $itemNo,
        string $lotName,
        string $lotNo,
        string $quantity,
        int $quantityDigits,
        int $invoiceItemId,
        int $invoiceAccountId,
        int $editorUserId,
        string $language,
        bool $isReadOnlyDb
    ): static {
        $this->bidderNum = $bidderNum;
        $this->bpTaxSchemaId = $bpTaxSchemaId;
        $this->buyersPremium = $buyersPremium;
        $this->hammerPrice = $hammerPrice;
        $this->hpTaxSchemaId = $hpTaxSchemaId;
        $this->itemNo = $itemNo;
        $this->lotName = $lotName;
        $this->lotNo = $lotNo;
        $this->quantity = $quantity;
        $this->quantityDigits = $quantityDigits;
        $this->invoiceItemId = $invoiceItemId;
        $this->invoiceAccountId = $invoiceAccountId;
        $this->editorUserId = $editorUserId;
        $this->language = $language;
        $this->isReadOnlyDb = $isReadOnlyDb;
        return $this;
    }
}
