<?php
/**
 * SAM-6627: Extract "Add to Bulk" updating functionality from Admin Auction Lot List page
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\LotBulkGrouping\Save;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionLotListLotBulkGroupingUpdateResult
 * @package Sam\View\Admin\Form\AuctionLotListForm\LotBulkGrouping\Save
 */
class AuctionLotListLotBulkGroupingUpdateResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_MASTER_LOT_NOT_FOUND = 1;
    public const ERR_TARGET_LOT_NOT_FOUND = 2;

    public const OK_PIECEMEAL_ADDED = 11;
    public const OK_PIECEMEAL_REMOVED = 12;

    public const WARN_MASTER_DENIED = 21;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_MASTER_LOT_NOT_FOUND => 'Unable to find master auction lot id by lot# and auction',
        self::ERR_TARGET_LOT_NOT_FOUND => 'Lots not found for bulk grouping changes',
    ];

    /** @var string[] */
    protected const SUCCESS_MESSAGES = [
        self::OK_PIECEMEAL_ADDED => 'Piecemeal lots added to bulk group',
        self::OK_PIECEMEAL_REMOVED => 'Piecemeal lots removed from bulk group',
    ];

    /** @var string[] */
    protected const WARNING_MESSAGES = [
        self::WARN_MASTER_DENIED => 'Unable to add master lots to bulk group',
    ];

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
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES, self::SUCCESS_MESSAGES, self::WARNING_MESSAGES);
        return $this;
    }

    // --- Mutate ---

    public function addErrorWithAppendedMessage(int $code, string $append): static
    {
        $this->getResultStatusCollector()->addErrorWithAppendedMessage($code, $append);
        return $this;
    }

    public function addSuccessWithAppendedMessage(int $code, string $append): static
    {
        $this->getResultStatusCollector()->addSuccessWithAppendedMessage($code, $append);
        return $this;
    }

    public function addWarningWithAppendedMessage(int $code, string $append): static
    {
        $this->getResultStatusCollector()->addWarningWithAppendedMessage($code, $append);
        return $this;
    }

    // --- Query ---

    public function lastAddedErrorMessage(): string
    {
        return $this->getResultStatusCollector()->lastAddedErrorMessage();
    }

    public function lastAddedSuccessMessage(): string
    {
        return $this->getResultStatusCollector()->lastAddedSuccessMessage();
    }

    public function lastAddedWarningMessage(): string
    {
        return $this->getResultStatusCollector()->lastAddedWarningMessage();
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    public function hasWarning(): bool
    {
        return $this->getResultStatusCollector()->hasWarning();
    }

    public function warningCodes(): array
    {
        return $this->getResultStatusCollector()->getWarningCodes();
    }

    public function warningMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedWarningMessage();
    }

    public function hasSuccess(): bool
    {
        return $this->getResultStatusCollector()->hasSuccess();
    }

    public function successCodes(): array
    {
        return $this->getResultStatusCollector()->getSuccessCodes();
    }

    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
    }

}
