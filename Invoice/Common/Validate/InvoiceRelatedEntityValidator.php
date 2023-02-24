<?php
/**
 * SAM-4820: Invoice with deleted related entities behavior
 *
 * We want to lock invoice actions, like editing, emailing, when its related entities are deleted.
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/21/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Account\Validate\AccountExistenceCheckerAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Storage\Entity\AwareTrait\InvoiceAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class InvoiceRelatedEntityValidator
 * @package Sam\Invoice\Common\Validate
 */
class InvoiceRelatedEntityValidator extends CustomizableClass
{
    use AccountExistenceCheckerAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use InvoiceAwareTrait;
    use LotItemLoaderAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use UserLoaderAwareTrait;

    public const ERR_INVOICE_DELETED = 1;
    public const ERR_BIDDER_USER_DELETED = 2;
    public const ERR_BIDDER_USER_ACCOUNT_DELETED = 3;
    public const ERR_LOT_ITEM_DELETED = 4;
    public const ERR_AUCTION_LOT_DELETED = 5; // it may absent
    public const ERR_AUCTION_DELETED = 6;     // it may absent
    public const ERR_LOT_ACCOUNT_DELETED = 7;

    /** @var string[] */
    protected array $errorMessages = [
        self::ERR_INVOICE_DELETED => 'Invoice deleted',
        self::ERR_BIDDER_USER_DELETED => 'Bidder deleted',
        self::ERR_BIDDER_USER_ACCOUNT_DELETED => 'Bidder account deleted',
        self::ERR_LOT_ITEM_DELETED => 'Lot item deleted',
        self::ERR_AUCTION_LOT_DELETED => 'Auction lot deleted', // check disabled
        self::ERR_AUCTION_DELETED => 'Auction deleted',
        self::ERR_LOT_ACCOUNT_DELETED => 'Lot account deleted',
    ];

    /** @var bool */
    protected bool $shouldBreakOnFirstError = false;
    /** @var bool */
    protected bool $isAuctionLotCheck = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->getResultStatusCollector()->construct($this->errorMessages);
        return $this;
    }

    /**
     * Enable, when you don't need full error list. Disabled by default.
     * @param bool $enabled
     * @return static
     * @noinspection PhpUnused
     */
    public function enableBreakOnFirstError(bool $enabled): static
    {
        $this->shouldBreakOnFirstError = $enabled;
        return $this;
    }

    /**
     * @param bool $isAuctionLotCheck
     * @return static
     * @noinspection PhpUnused
     */
    public function enableAuctionLotCheck(bool $isAuctionLotCheck): static
    {
        $this->isAuctionLotCheck = $isAuctionLotCheck;
        return $this;
    }

    /**
     * Check, if invoice is available for user actions, e.g. editing, e-mailing.
     * @return bool
     */
    public function validate(): bool
    {
        $collector = $this->getResultStatusCollector()->clear();
        $invoice = $this->getInvoice();
        // Check Invoice
        if (!$invoice || $invoice->isDeleted()) {
            $collector->addError(self::ERR_INVOICE_DELETED);
        }
        if ($this->shouldBreakOnFirstError && $collector->hasError()) {
            $this->log();
            return false;
        }

        if (!$invoice) {
            // When invoice absent in DB, then no need to check other entities
            return false;
        }

        // Check User
        $user = $this->getUserLoader()->clear()->load($invoice->BidderId, true);
        if (!$user || $user->isDeleted()) {
            $collector->addError(self::ERR_BIDDER_USER_DELETED);
        }
        if ($this->shouldBreakOnFirstError && $collector->hasError()) {
            $this->log();
            return false;
        }

        // Check User's Account
        if ($user) {
            $isUserAccountActive = $this->getAccountExistenceChecker()->existById($user->AccountId);
            if (!$isUserAccountActive) {
                $collector->addError(self::ERR_BIDDER_USER_ACCOUNT_DELETED);
            }
        }
        if ($this->shouldBreakOnFirstError && $collector->hasError()) {
            $this->log();
            return false;
        }

        // Check InvoiceItem[]
        $invoiceItems = $this->getInvoiceItems();
        foreach ($invoiceItems as $invoiceItem) {
            $lotItemId = $invoiceItem->LotItemId;
            $auctionId = $invoiceItem->AuctionId;
            $lotItem = $this->getLotItemLoader()->clear()->load($lotItemId, true);
            $accountId = $lotItem->AccountId ?? null;
            $payload = [$invoiceItem->Id, $invoiceItem->LotItemId, $invoiceItem->AuctionId, $accountId];
            // Check lot item
            if (!$lotItem || $lotItem->isDeleted()) {
                $collector->addError(self::ERR_LOT_ITEM_DELETED, null, $payload);
            }
            if ($this->shouldBreakOnFirstError && $collector->hasError()) {
                $this->log();
                return false;
            }

            // Check LotItem's Account
            if ($lotItem) {
                $isLotAccountActive = $this->getAccountExistenceChecker()->existById($lotItem->AccountId);
                if (!$isLotAccountActive) {
                    $collector->addError(self::ERR_LOT_ACCOUNT_DELETED, null, $payload);
                }
            }
            if ($this->shouldBreakOnFirstError && $collector->hasError()) {
                $this->log();
                return false;
            }

            // Auction isn't required property of invoice
            if ($auctionId) {
                // Check Auction
                $auction = $this->getAuctionLoader()->clear()->load($auctionId, true);
                if (!$auction || $auction->isDeleted()) {
                    $collector->addError(self::ERR_AUCTION_DELETED, null, $payload);
                }
                if ($this->shouldBreakOnFirstError && $collector->hasError()) {
                    $this->log();
                    return false;
                }

                /**
                 * Check AuctionLotItem. Disabled by default.
                 * AuctionLotItem record may absent, admin can assign any "Sale Sold In" auction,
                 * where lot isn't assigned, or where lot was assigned before, but later deleted
                 * and we still have `auction_lot_item` record with deleted status (lot_status_id = LS_DELETED).
                 */
                if ($this->isAuctionLotCheck) {
                    if ($auction) {
                        // First load with available status filtering
                        $auctionLot = $this->getAuctionLotLoader()->load($lotItemId, $auction->Id, true);
                        if (!$auctionLot) {
                            // Give second try to load with dropped filtering
                            $auctionLot = $this->getAuctionLotLoader()
                                ->clear()
                                ->load($lotItemId, $auction->Id, true);
                        }
                        // It should be deleted lot
                        if (
                            $auctionLot
                            && $auctionLot->isDeleted()
                        ) {
                            $collector->addError(self::ERR_AUCTION_LOT_DELETED, null, $payload);
                        }
                    }
                    if ($this->shouldBreakOnFirstError && $collector->hasError()) {
                        $this->log();
                        return false;
                    }
                }
            }
        }

        $success = !$collector->hasError();
        if (!$success) {
            $this->log();
        }
        return $success;
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        $has = $this->getResultStatusCollector()->hasError();
        return $has;
    }

    public function hasInvoiceDeletedError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([self::ERR_INVOICE_DELETED]);
    }

    /**
     * @return bool
     */
    public function hasBidderError(): bool
    {
        $has = $this->getResultStatusCollector()
            ->hasConcreteError(
                [
                    self::ERR_BIDDER_USER_DELETED,
                    self::ERR_BIDDER_USER_ACCOUNT_DELETED,
                ]
            );
        return $has;
    }

    public function getBidderErrorCodes(): array
    {
        $codes = $this->getResultStatusCollector()
            ->getConcreteErrorCodes(
                [
                    self::ERR_BIDDER_USER_DELETED,
                    self::ERR_BIDDER_USER_ACCOUNT_DELETED,
                ]
            );
        return $codes;
    }

    /**
     * @return string[]
     */
    public function getBidderErrorMessages(): array
    {
        $errors = $this->getResultStatusCollector()
            ->findErrorResultStatusesByCodes(
                [
                    self::ERR_BIDDER_USER_DELETED,
                    self::ERR_BIDDER_USER_ACCOUNT_DELETED,
                ]
            );
        $errorMessages = array_map(
            static function (ResultStatus $resultStatus) {
                return $resultStatus->getMessage();
            },
            $errors
        );
        return $errorMessages;
    }

    /**
     * @return ResultStatus[]
     */
    public function getInvoiceItemErrors(): array
    {
        $errors = $this->getResultStatusCollector()
            ->findErrorResultStatusesByCodes(
                [
                    self::ERR_LOT_ITEM_DELETED,
                    self::ERR_LOT_ACCOUNT_DELETED,
                    self::ERR_AUCTION_LOT_DELETED,
                    self::ERR_AUCTION_DELETED,
                ]
            );
        return $errors;
    }

    protected function log(): void
    {
        $errorStatuses = $this->getResultStatusCollector()->getErrorStatuses();
        $causes = [];
        foreach ($errorStatuses as $errorStatus) {
            $code = $errorStatus->getCode();
            $message = $errorStatus->getMessage();
            $payload = $errorStatus->getPayload();
            if ($payload) {
                [, $lotItemId, $auctionId, $accountId] = $payload;
                $attrs['li'] = $lotItemId;
                if ($code === self::ERR_AUCTION_DELETED) {
                    $attrs['a'] = $auctionId;
                } elseif ($code === self::ERR_LOT_ACCOUNT_DELETED) {
                    $attrs['acc'] = $accountId;
                }
                $message .= composeSuffix($attrs);
            }
            $causes[] = $message;
        }
        $messageList = implode(', ', $causes);
        $breakOnFirst = (int)$this->shouldBreakOnFirstError;
        log_debug(
            "Invoice related entities problem detected"
            . composeSuffix(['i' => $this->getInvoiceId(), 'err' => $messageList, 'breakOnFirst' => $breakOnFirst])
        );
    }
}
