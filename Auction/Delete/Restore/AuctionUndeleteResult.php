<?php
/**
 * SAM-6856: Soft-deleted Auction restore
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Delete\Restore;

use Auction;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Auction\Delete\Restore\AuctionCustomDataRestoreResult;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class AuctionRestoreResult
 * @package Sam\Auction\Delete\Restore
 */
class AuctionUndeleteResult extends CustomizableClass
{
    use AdminTranslatorAwareTrait;
    use ResultStatusCollectorAwareTrait;

    public const ERR_AUCTION_NOT_FOUND = 1;
    public const ERR_AUCTION_IS_NOT_DELETED = 2;

    /**
     * @var Auction|null
     */
    protected ?Auction $restoredAuction = null;
    /**
     * @var AuctionCustomDataRestoreResult|null
     */
    protected ?AuctionCustomDataRestoreResult $customDataRestoreResult = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        $translator = $this->getAdminTranslator();
        $errorMessage = [
            self::ERR_AUCTION_NOT_FOUND => $translator->trans('auction.delete.restore.err_auction_not_found', [], 'admin_message'),
            self::ERR_AUCTION_IS_NOT_DELETED => $translator->trans('auction.delete.restore.err_auction_is_not_deleted', [], 'admin_message')
        ];
        $this->getResultStatusCollector()->construct($errorMessage);
        return $this;
    }


    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasSuccess(): bool
    {
        return !$this->hasError();
    }

    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    /**
     * @return int[]
     * @internal
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    /**
     * @param Auction $restoredAuction
     * @return static
     */
    public function setRestoredAuction(Auction $restoredAuction): static
    {
        $this->restoredAuction = $restoredAuction;
        return $this;
    }

    /**
     * @param AuctionCustomDataRestoreResult $customDataRestoreResult
     * @return static
     */
    public function setCustomDataRestoreResult(AuctionCustomDataRestoreResult $customDataRestoreResult): static
    {
        $this->customDataRestoreResult = $customDataRestoreResult;
        return $this;
    }

    /**
     * @return Auction|null
     */
    public function getRestoredAuction(): ?Auction
    {
        return $this->restoredAuction;
    }

    /**
     * @return AuctionCustomDataRestoreResult|null
     */
    public function getCustomDataRestoreResult(): ?AuctionCustomDataRestoreResult
    {
        return $this->customDataRestoreResult;
    }
}
