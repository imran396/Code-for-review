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

namespace Sam\Invoice\Common\LineItem\Edit\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class InvoiceLineItemValidationResult
 * @package Sam\Invoice\Common\LineItem\Edit\Validate
 */
class InvoiceLineItemValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_LABEL_REQUIRED = 1;
    public const ERR_LABEL_EXIST = 2;
    public const ERR_AMOUNT_REQUIRED = 3;
    public const ERR_AMOUNT_INVALID = 4;
    public const ERR_AUCTION_TYPE_REQUIRED = 5;
    public const ERR_BREAKDOWN_REQUIRED = 6;
    public const ERR_INVOICE_LINE_ITEM_NOT_FOUND_BY_ID = 7;

    public const ERROR_MESSAGES = [
        self::ERR_LABEL_REQUIRED => 'Label required',
        self::ERR_LABEL_EXIST => 'Label exists',
        self::ERR_AMOUNT_REQUIRED => 'Amount required',
        self::ERR_AMOUNT_INVALID => 'Amount invalid',
        self::ERR_AUCTION_TYPE_REQUIRED => 'Auction Type required',
        self::ERR_BREAKDOWN_REQUIRED => 'Break down required',
        self::ERR_INVOICE_LINE_ITEM_NOT_FOUND_BY_ID => 'Invoice line item not found by id',
    ];

    protected array $labelErrors = [self::ERR_LABEL_REQUIRED, self::ERR_LABEL_EXIST];
    protected array $amountErrors = [self::ERR_AMOUNT_REQUIRED, self::ERR_AMOUNT_INVALID];
    protected array $auctionTypeErrors = [self::ERR_AUCTION_TYPE_REQUIRED];
    protected array $breakDownErrors = [self::ERR_BREAKDOWN_REQUIRED];

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
    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES);
        return $this;
    }

    /**
     * @param int $errorCode
     * @return $this
     */
    public function addError(int $errorCode): static
    {
        $this->getResultStatusCollector()->addError($errorCode);
        return $this;
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function getErrorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function getErrorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    /**
     * @return string
     */
    public function labelErrorMessage(): string
    {
        $searchErrors = $this->labelErrors;
        return (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes($searchErrors);
    }

    /**
     * @return bool
     */
    public function hasLabelError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($this->labelErrors);
    }

    /**
     * @return string
     */
    public function amountErrorMessage(): string
    {
        $searchErrors = $this->amountErrors;
        return (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes($searchErrors);
    }

    /**
     * @return bool
     */
    public function hasAmountError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($this->amountErrors);
    }

    /**
     * @return string
     */
    public function auctionTypeError(): string
    {
        $searchErrors = $this->auctionTypeErrors;
        return (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes($searchErrors);
    }

    /**
     * @return bool
     */
    public function hasAuctionTypeError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($this->auctionTypeErrors);
    }

    /**
     * @return string
     */
    public function breakDownErrorMessage(): string
    {
        $searchErrors = $this->breakDownErrors;
        return (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes($searchErrors);
    }

    /**
     * @return bool
     */
    public function hasBreakDownError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($this->breakDownErrors);
    }
}
