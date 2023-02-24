<?php
/**
 * SAM-9721: Refactor and implement unit test for single invoice producer
 * SAM-4377: Invoice producer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           19.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Legacy\Generate\Item\Single;

use LotItem;
use Sam\Core\Service\CustomizableClass;

/**
 * Class PreInvoiceItemDto
 * @package Sam\Invoice\Legacy\Generate\Item
 */
class LegacySingleInvoiceItemProductionInput extends CustomizableClass
{
    /** @var int|null */
    public ?int $auctionId;
    /** @var LotItem|null */
    public ?LotItem $lotItem;
    /** @var int|null */
    public ?int $invoiceId;
    /** @var int|null */
    public ?int $userId;
    /** @var int */
    public int $editorUserId;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        int $invoiceId,
        LotItem $lotItem,
        ?int $auctionId,
        ?int $userId,
        int $editorUserId
    ): static {
        $this->auctionId = $auctionId;
        $this->invoiceId = $invoiceId;
        $this->lotItem = $lotItem;
        $this->userId = $userId;
        $this->editorUserId = $editorUserId;
        return $this;
    }

    /**
     * Clear instance properties
     * @return static
     */
    public function clear(): static
    {
        $this->auctionId = null;
        $this->invoiceId = null;
        $this->lotItem = null;
        $this->userId = null;
        return $this;
    }

    /**
     * @return array
     */
    public function getLogData(): array
    {
        return [
            'a' => $this->auctionId,
            'i' => $this->invoiceId,
            'li' => $this->lotItem->Id ?? null,
            'u' => $this->userId,
        ];
    }
}
