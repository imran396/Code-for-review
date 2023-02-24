<?php
/**
 * If bid amount is off increment then it provides us nearest and next on increment bid message.
 *
 * SAM-1878: Improved prevent off increment bids
 * SAM-6909: Refactor on-increment bid validator for v3.6
 *
 * On-increment bidding feature is configured at account level only.
 * Enable by cli:
 * php ./bin/install/setting.php set --key REQUIRE_ON_INC_BIDS --value 1 --account all
 * Enable "Require on increment bids" in web admin:
 * /admin/manage-system-parameter/auction
 *
 * @author        Imran Rahman, Igors Kotlevskis
 * @version       SVN: 3.0
 * @since         Feb 10, 2017
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Bidding\OnIncrementBid;

use Sam\Bidding\OnIncrementBid\Internal\Calculate\OnIncrementBidCalculator;
use Sam\Bidding\OnIncrementBid\Internal\Load\DataProvider;
use Sam\Bidding\OnIncrementBid\Internal\Load\Dto;
use Sam\Bidding\OnIncrementBid\Internal\Render\OnIncrementBidRendererCreateTrait;
use Sam\Core\Bidding\AskingBid\AskingBidPureChecker;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Settings\SettingsManager;
use Sam\Bidding\OnIncrementBid\OnIncrementBidValidationResult as Result;

/**
 * Class OnIncrementBidValidator
 * @package Sam\Bidding\OnIncrementBid
 */
class OnIncrementBidService extends CustomizableClass
{
    use OptionalsTrait;
    use OnIncrementBidRendererCreateTrait;

    // Incoming values

    protected const OP_INTERNAL_DTO = 'internalDto'; // Dto
    public const OP_ASKING_BID = 'askingBid'; // ?float
    public const OP_CURRENT_BID = 'currentMaxBid'; // ?float, see Dto
    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB; // bool
    public const OP_LOT_ACCOUNT_ID = 'lotAccountId'; // int, see Dto
    public const OP_QUANTIZED_HIGH_BID = 'quantizeHighBid'; // float
    public const OP_QUANTIZED_HIGH_BID_FOR_AMOUNT = 'quantizedHighBidForAmount'; // float, quantized bid after checking amount
    public const OP_QUANTIZED_LOW_BID = 'quantizedLowBid'; // float, quantized bid before checking amount
    public const OP_REQUIRE_ON_INC_BIDS = 'requireOnIncBids'; // bool
    public const OP_STARTING_BID = 'startingBid'; // ?float, see Dto
    public const OP_IS_REVERSE_AUCTION = 'isReverseAuction'; // bool

    protected float $checkingAmount;
    protected int $lotItemId;
    protected int $auctionId;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param float $checkingAmount
     * @param int $lotItemId
     * @param int $auctionId
     * @param array $optionals
     * @return $this
     */
    public function construct(
        float $checkingAmount,
        int $lotItemId,
        int $auctionId,
        array $optionals = []
    ): static {
        $this->checkingAmount = $checkingAmount;
        $this->lotItemId = $lotItemId;
        $this->auctionId = $auctionId;
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * Check incoming bid amount according this service rules.
     * Success means we can continue with this bid. It not necessary should have on-increment quantized amount.
     * Fail means checking amount violates some rule.
     * @return bool
     */
    public function validate(): bool
    {
        $result = $this->validateWithResult();
        $message = $result->hasSuccess()
            ? 'On increment bid validation successful'
            : 'On increment bid validation failed';
        log_debug($message . composeSuffix($result->logData()));
        return $result->hasSuccess();
    }

    /**
     * Check incoming bid amount according this service rules.
     * @return Result
     */
    public function validateWithResult(): Result
    {
        $result = OnIncrementBidValidationResult::new()->construct();

        /**
         * Success: On-increment bid feature disabled, no need to check.
         */
        if (!$this->isFeatureAvailable()) {
            return $result->addSuccess(Result::OK_CHECK_DISABLED);
        }

        /**
         * Fail: Incoming bid amount is not positive number.
         */
        if (Floating::lteq($this->checkingAmount, 0.)) {
            return $result->addError(Result::ERR_INCORRECT_AMOUNT);
        }

        /**
         * Success: Incoming bid may be out of increment range,
         * if it's the first bid and it's equal to the starting bid (SAM-4479).
         */
        $startingBid = $this->fetchStartingBid();
        $currentMaxBid = $this->fetchCurrentBid();
        if (
            Floating::eq($this->checkingAmount, $startingBid)
            && Floating::eq($currentMaxBid, 0.)
        ) {
            return $result->addSuccess(Result::OK_AMOUNT_EQUAL_STARTING_BID_WHEN_NO_BIDS);
        }

        /**
         * Fail: checking bid amount does not meet asking bid amount
         */
        $askingBid = $this->fetchAskingBid();
        $isReverseAuction = $this->fetchIsReverseAuction();
        if (!AskingBidPureChecker::new()->meet($this->checkingAmount, $askingBid, $isReverseAuction)) {
            return $result->addError(Result::ERR_CHECKING_BID_NOT_MEET_ASKING_BID);
        }

        /**
         * Fail: something wrong with quantized low bid amount detection.
         */
        $quantizedLowBid = $this->fetchQuantizedLowBid();
        if (Floating::eq($quantizedLowBid, 0.)) {
            return $result->addError(Result::ERR_QUANTIZED_LOW_BID_NOT_FOUND);
        }

        /**
         * Success: Incoming bid is already equal to quantized amount.
         */
        $isValid = Floating::eq($quantizedLowBid, $this->checkingAmount);
        if ($isValid) {
            return $result->addSuccess(Result::OK_AMOUNT_EQUAL_QUANTIZED_LOW_BID);
        }

        /**
         * Fail: something wrong with quantized high bid amount detection.
         */
        $quantizedHighBid = $this->fetchQuantizedHighBid();
        if (Floating::eq($quantizedHighBid, 0.)) {
            return $result->addError(Result::ERR_QUANTIZED_HIGH_BID_NOT_FOUND);
        }

        /**
         * Fail: off-increment amount detected
         */
        return $result->addError(Result::ERR_OFF_INCREMENT_AMOUNT);
    }

    /**
     * @return float[]
     */
    public function detectSurroundingBids(): array
    {
        $lowBid = $this->calculateLowEffectiveBid();
        $highBid = $this->detectHighEffectiveBidForAmount($lowBid);
        return [$lowBid, $highBid];
    }

    /**
     * @param string $controlId
     * @return string
     */
    public function buildHtmlMessage(string $controlId): string
    {
        [$lowBid, $highBid] = $this->detectSurroundingBids();
        return $this->createOnIncrementBidRenderer()
            ->construct()
            ->buildErrorMessageHtml(
                $lowBid,
                $highBid,
                $controlId,
                $this->auctionId,
                $this->fetchLotAccountId()
            );
    }

    /**
     * @return string
     */
    public function buildCleanMessage(): string
    {
        [$lowBid, $highBid] = $this->detectSurroundingBids();
        return $this->createOnIncrementBidRenderer()
            ->construct()
            ->buildErrorMessageClean($lowBid, $highBid);
    }

    // Internal business logic

    /**
     * Search for nearest to max bid on-increment bid.
     * @return float
     */
    protected function calculateLowEffectiveBid(): float
    {
        return OnIncrementBidCalculator::new()
            ->calculateLowEffective(
                $this->checkingAmount,
                $this->fetchCurrentBid(),
                $this->fetchQuantizedLowBid(),
                $this->fetchQuantizedHighBid(),
                $this->fetchStartingBid(),
                $this->fetchAskingBid(),
                $this->fetchIsReverseAuction()
            );
    }

    /**
     * @return bool
     */
    protected function isFeatureAvailable(): bool
    {
        return (bool)$this->fetchOptional(self::OP_REQUIRE_ON_INC_BIDS, [$this->fetchLotAccountId()]);
    }

    // Internal infrastructural logic

    /**
     * Get next higher on-increment bid.
     * @param float|null $amount
     * @return float
     */
    protected function detectHighEffectiveBidForAmount(?float $amount): float
    {
        return (float)$this->fetchOptional(self::OP_QUANTIZED_HIGH_BID_FOR_AMOUNT, [$amount]);
    }

    /**
     * Get account id of entity
     * @return int|null
     */
    protected function fetchLotAccountId(): ?int
    {
        return $this->fetchOptional(self::OP_LOT_ACCOUNT_ID);
    }

    /**
     * Get asking bid
     * @return float|null
     */
    protected function fetchAskingBid(): ?float
    {
        return $this->fetchOptional(self::OP_ASKING_BID);
    }

    /**
     * Get normalized starting bid
     * @return float|null
     */
    protected function fetchStartingBid(): ?float
    {
        return $this->fetchOptional(self::OP_STARTING_BID);
    }

    /**
     * Get current max bid of lot item
     * @return float|null
     */
    protected function fetchCurrentBid(): ?float
    {
        return $this->fetchOptional(self::OP_CURRENT_BID);
    }

    /**
     * @return bool
     */
    protected function fetchIsReverseAuction(): bool
    {
        return $this->fetchOptional(self::OP_IS_REVERSE_AUCTION);
    }

    /**
     * Find quantized value for incoming checking amount. It is lower or equal to it.
     * @return float
     */
    protected function fetchQuantizedLowBid(): float
    {
        return (float)$this->fetchOptional(self::OP_QUANTIZED_LOW_BID);
    }

    /**
     * Find next quantized value for checking amount.
     * @return float
     */
    protected function fetchQuantizedHighBid(): float
    {
        return (float)$this->fetchOptional(self::OP_QUANTIZED_HIGH_BID);
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $auctionId = $this->auctionId;
        $lotItemId = $this->lotItemId;
        $checkingAmount = $this->checkingAmount;
        $isReadOnlyDb = $optionals[self::OP_IS_READ_ONLY_DB] ?? false;

        $optionals[self::OP_QUANTIZED_LOW_BID] = $optionals[self::OP_QUANTIZED_LOW_BID]
            ?? static function () use ($checkingAmount, $lotItemId, $auctionId, $isReadOnlyDb): float {
                return DataProvider::new()
                    ->detectQuantizedLowBid($checkingAmount, $lotItemId, $auctionId, $isReadOnlyDb);
            };

        $optionals[self::OP_QUANTIZED_HIGH_BID] = $optionals[self::OP_QUANTIZED_HIGH_BID]
            ?? static function () use ($checkingAmount, $lotItemId, $auctionId, $isReadOnlyDb): float {
                return DataProvider::new()
                    ->detectQuantizedHighBid($checkingAmount, $lotItemId, $auctionId, $isReadOnlyDb);
            };

        $optionals[self::OP_QUANTIZED_HIGH_BID_FOR_AMOUNT] = $optionals[self::OP_QUANTIZED_HIGH_BID_FOR_AMOUNT]
            ?? static function (?float $amount) use ($lotItemId, $auctionId, $isReadOnlyDb): float {
                return DataProvider::new()
                    ->detectQuantizedHighBid($amount, $lotItemId, $auctionId, $isReadOnlyDb);
            };

        $optionals[self::OP_REQUIRE_ON_INC_BIDS] = $optionals[self::OP_REQUIRE_ON_INC_BIDS]
            ?? static function (int $accountId): bool {
                return (bool)SettingsManager::new()
                    ->get(Constants\Setting::REQUIRE_ON_INC_BIDS, $accountId);
            };

        /**
         * ---------------------------
         * Next are Dto related values
         * Dto cannot be injected as optional argument, only loaded by data provider
         */
        $optionals[self::OP_INTERNAL_DTO] = static function () use ($lotItemId, $auctionId, $isReadOnlyDb): Dto {
            return DataProvider::new()->loadDto($lotItemId, $auctionId, $isReadOnlyDb);
        };

        $optionals[self::OP_LOT_ACCOUNT_ID] = $optionals[self::OP_LOT_ACCOUNT_ID]
            ?? function (): ?int {
                /** @var Dto $dto */
                $dto = $this->fetchOptional(self::OP_INTERNAL_DTO);
                return $dto->accountId;
            };

        $optionals[self::OP_CURRENT_BID] = array_key_exists(self::OP_CURRENT_BID, $optionals)
            ? $optionals[self::OP_CURRENT_BID]
            : function (): ?float {
                /** @var Dto $dto */
                $dto = $this->fetchOptional(self::OP_INTERNAL_DTO);
                return $dto->currentBid;
            };

        $optionals[self::OP_STARTING_BID] = array_key_exists(self::OP_STARTING_BID, $optionals)
            ? $optionals[self::OP_STARTING_BID]
            : function (): ?float {
                /** @var Dto $dto */
                $dto = $this->fetchOptional(self::OP_INTERNAL_DTO);
                return $dto->startingBidNormalized;
            };

        $optionals[self::OP_ASKING_BID] = array_key_exists(self::OP_ASKING_BID, $optionals)
            ? $optionals[self::OP_ASKING_BID]
            : function (): ?float {
                /** @var Dto $dto */
                $dto = $this->fetchOptional(self::OP_INTERNAL_DTO);
                return $dto->askingBid;
            };

        $optionals[self::OP_IS_REVERSE_AUCTION] = array_key_exists(self::OP_IS_REVERSE_AUCTION, $optionals)
            ? (bool)$optionals[self::OP_IS_REVERSE_AUCTION]
            : function (): bool {
                /** @var Dto $dto */
                $dto = $this->fetchOptional(self::OP_INTERNAL_DTO);
                return $dto->isReverseAuction;
            };

        $this->setOptionals($optionals);
    }
}
