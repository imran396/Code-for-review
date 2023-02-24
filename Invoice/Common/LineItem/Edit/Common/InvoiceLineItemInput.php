<?php
/**
 * SAM-9454: Refactor Invoice Line item editor for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Dec 11, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\LineItem\Edit\Common;

use Sam\Core\Service\CustomizableClass;

/**
 * Class InvoiceLineItemInput
 * @package Sam\Invoice\Common\LineItem\Edit\Common
 */
class InvoiceLineItemInput extends CustomizableClass
{
    /** @var int */
    public int $accountId;
    /** @var int|null */
    public ?int $editorUserId;
    /** @var string|null */
    public ?string $amount;
    /** @var string */
    public string $auctionType;
    /** @var string|null */
    public ?string $breakDown;
    /** @var int|null */
    public ?int $invoiceLineItemId;
    /** @var string */
    public string $label;
    /** @var bool */
    public bool $isLeuOfTax;
    /** @var bool */
    public bool $isPercentage;
    /** @var bool */
    public bool $isPerLot;
    /** @var array<int|null> */
    public array $lotCategoryIds;

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
     * @param int|null $editorUserId
     * @param string|null $amount
     * @param string $auctionType
     * @param string|null $breakDown
     * @param int|null $invoiceLineItemId
     * @param string $label
     * @param bool $isLeuOfTax
     * @param bool $isPercentage
     * @param bool $isPerLot
     * @param array<int|null> $lotCategoryIds
     * @return $this
     */
    public function construct(
        int $accountId,
        ?int $editorUserId,
        ?string $amount,
        string $auctionType,
        ?string $breakDown,
        ?int $invoiceLineItemId,
        string $label,
        bool $isLeuOfTax,
        bool $isPercentage,
        bool $isPerLot,
        array $lotCategoryIds
    ): static {
        $this->accountId = $accountId;
        $this->editorUserId = $editorUserId;
        $this->amount = $amount;
        $this->auctionType = $auctionType;
        $this->breakDown = $breakDown;
        $this->invoiceLineItemId = $invoiceLineItemId;
        $this->label = $label;
        $this->isLeuOfTax = $isLeuOfTax;
        $this->isPercentage = $isPercentage;
        $this->isPerLot = $isPerLot;
        $this->lotCategoryIds = $lotCategoryIds;
        return $this;
    }
}
