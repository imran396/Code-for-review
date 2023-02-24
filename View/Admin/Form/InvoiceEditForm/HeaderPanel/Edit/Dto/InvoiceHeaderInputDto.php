<?php
/**
 * SAM-10995: Stacked Tax. New Invoice Edit page: Initial layout and header section
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceEditForm\HeaderPanel\Edit\Dto;

use DateTime;
use Sam\Core\Service\CustomizableClass;

/**
 * Class InvoiceHeaderInputDto
 * @package Sam\View\Admin\Form\InvoiceEditForm\HeaderPanel\Edit
 */
class InvoiceHeaderInputDto extends CustomizableClass
{
    public readonly ?DateTime $invoiceDate;
    /** @var array<int, string> */
    public readonly array $paymentMethods;
    public readonly bool $cashDiscount;
    public readonly bool $excludeInThreshold;
    public readonly int $status;
    public readonly string $invoiceNo;
    /** @var array<DateTime|null> */
    public readonly array $auctionDates;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array<int, string> $paymentMethods
     * @param array<DateTime|null> $auctionDates
     */
    public function construct(
        ?DateTime $invoiceDate,
        array $paymentMethods,
        bool $excludeInThreshold,
        int $status,
        string $invoiceNo,
        array $auctionDates
    ): static {
        $this->invoiceDate = $invoiceDate;
        $this->paymentMethods = $paymentMethods;
        $this->excludeInThreshold = $excludeInThreshold;
        $this->status = $status;
        $this->invoiceNo = $invoiceNo;
        $this->auctionDates = $auctionDates;
        return $this;
    }
}
