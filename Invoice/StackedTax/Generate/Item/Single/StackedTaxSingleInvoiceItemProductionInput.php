<?php
/**
 * SAM-9721: Refactor and implement unit test for single invoice producer
 * SAM-4377: Invoice producer
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           01.08.2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\StackedTax\Generate\Item\Single;

use LotItem;
use Sam\Core\Service\CustomizableClass;

class StackedTaxSingleInvoiceItemProductionInput extends CustomizableClass
{
    public ?int $auctionId;
    public ?LotItem $lotItem;
    public ?int $invoiceId;
    public ?int $userId;
    public int $editorUserId;
    public string $language;
    public bool $isReadOnlyDb = false;
    public string $taxCountry;

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
        string $taxCountry,
        int $editorUserId,
        string $language,
        bool $isReadOnlyDb = false
    ): static {
        $this->auctionId = $auctionId;
        $this->invoiceId = $invoiceId;
        $this->lotItem = $lotItem;
        $this->userId = $userId;
        $this->taxCountry = $taxCountry;
        $this->editorUserId = $editorUserId;
        $this->language = $language;
        $this->isReadOnlyDb = $isReadOnlyDb;
        return $this;
    }

    /**
     * @return array
     */
    public function logData(): array
    {
        return [
            'a' => $this->auctionId,
            'i' => $this->invoiceId,
            'li' => $this->lotItem->Id ?? null,
            'tax country' => $this->taxCountry,
            'u' => $this->userId,
            'editor u' => $this->editorUserId,
            'language' => $this->language,
            'isReadOnlyDb' => $this->isReadOnlyDb,
        ];
    }
}
