<?php
/**
 * SAM-4829: Settlement behavior with related deleted entities
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 24, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Validate;

use Sam\Account\Validate\AccountExistenceCheckerAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Storage\Entity\AwareTrait\SettlementAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class SettlementRelatedEntityValidator
 * @package Sam\Settlement\Validate
 */
class SettlementRelatedEntityValidator extends CustomizableClass
{
    use AccountExistenceCheckerAwareTrait;
    use AuctionLoaderAwareTrait;
    use LotItemLoaderAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use SettlementAwareTrait;
    use UserLoaderAwareTrait;

    public const ERR_AUCTION_DELETED = 1;
    public const ERR_CONSIGNOR_DELETED = 2;
    public const ERR_LOT_ITEM_DELETED = 3;

    /** @var string[] */
    protected array $errorMessages = [
        self::ERR_AUCTION_DELETED => 'Auction deleted',
        self::ERR_CONSIGNOR_DELETED => 'Consignor deleted',
        self::ERR_LOT_ITEM_DELETED => 'Lot item deleted',
    ];

    protected bool $isBreakOnFirstError = false;

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
        $this->isBreakOnFirstError = $enabled;
        return $this;
    }

    /**
     * Check, if settlement is available for user actions, e.g. editing, e-mailing.
     * @return bool
     */
    public function validate(): bool
    {
        $collector = $this->getResultStatusCollector()->clear();
        $settlement = $this->getSettlement();
        if (!$settlement) {
            log_error(
                "Available settlement not found, when checking availability of related entities"
                . composeSuffix(['s' => $this->getSettlementId()])
            );
            return false;
        }

        $user = $this->getUserLoader()->load($settlement->ConsignorId, true);
        if (!$user) {
            $collector->addError(self::ERR_CONSIGNOR_DELETED);
            $this->log();
        }
        if ($this->isBreakOnFirstError && $collector->hasError()) {
            $this->log();
            return false;
        }

        $settlementItems = $this->getSettlementItems();
        foreach ($settlementItems as $settlementItem) {
            $lotItemId = $settlementItem->LotItemId;
            $auctionId = $settlementItem->AuctionId;
            $lotItem = $this->getLotItemLoader()->clear()->load($lotItemId, true);
            $accountId = $lotItem->AccountId ?? null;
            $payload = [$settlementItem->Id, $settlementItem->LotItemId, $settlementItem->AuctionId, $accountId];
            // Check lot item
            if (!$lotItem || $lotItem->isDeleted()) {
                $collector->addError(self::ERR_LOT_ITEM_DELETED, null, $payload);
            }
            if ($this->isBreakOnFirstError && $collector->hasError()) {
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
                if ($this->isBreakOnFirstError && $collector->hasError()) {
                    $this->log();
                    return false;
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
        return $this->getResultStatusCollector()->hasError();
    }

    /**
     * @return bool
     */
    public function hasConsignorError(): bool
    {
        $has = $this->getResultStatusCollector()
            ->hasConcreteError(
                [
                    self::ERR_CONSIGNOR_DELETED,
                ]
            );
        return $has;
    }

    /**
     * @return string[]
     */
    public function getConsignorErrorMessages(): array
    {
        $errors = $this->getResultStatusCollector()
            ->findErrorResultStatusesByCodes(
                [
                    self::ERR_CONSIGNOR_DELETED,
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
    public function getSettlementItemErrors(): array
    {
        $errors = $this->getResultStatusCollector()
            ->findErrorResultStatusesByCodes(
                [
                    self::ERR_AUCTION_DELETED,
                    self::ERR_LOT_ITEM_DELETED,
                ]
            );
        return $errors;
    }

    /**
     * @return void
     */
    protected function log(): void
    {
        $errorStatuses = $this->getResultStatusCollector()->getErrorStatuses();
        $causes = [];
        foreach ($errorStatuses as $errorStatus) {
            $code = $errorStatus->getCode();
            $message = $errorStatus->getMessage();
            $payload = $errorStatus->getPayload();
            if ($payload) {
                [, $lotItemId, $auctionId] = $payload;
                $attrs['li'] = $lotItemId;
                if ($code === self::ERR_AUCTION_DELETED) {
                    $attrs['a'] = $auctionId;
                }
                $message .= composeSuffix($attrs);
            }
            $causes[] = $message;
        }
        $messageList = implode(', ', $causes);
        $breakOnFirst = (int)$this->isBreakOnFirstError;
        log_debug(
            "Settlement related entities problem detected"
            . composeSuffix(['i' => $this->getSettlementId(), 'err' => $messageList, 'breakOnFirst' => $breakOnFirst])
        );
    }
}
