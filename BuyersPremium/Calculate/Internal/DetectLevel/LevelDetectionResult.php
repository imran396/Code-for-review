<?php
/**
 * SAM-10463: Refactor BP calculator for v3-7 and cover with unit tests
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\BuyersPremium\Calculate\Internal\DetectLevel;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LevelDetectionResult
 * @package Sam\BuyersPremium\Calculate\Internal\DetectLevel
 */
class LevelDetectionResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_CANNOT_LOAD_LOT_NAMED_RULE = 1;
    public const ERR_CANNOT_LOAD_USER_NAMED_RULE = 2;
    public const ERR_CANNOT_LOAD_AUCTION_NAMED_RULE = 3;
    public const ERR_NOT_FOUND_ANY_RULE = 4;
    public const ERR_CANNOT_LOAD_LOT_ITEM = 5;
    public const ERR_CANNOT_LOAD_AUCTION = 6;

    public const OK_LOT_NAMED_RULE = 11;
    public const OK_LOT_INDIVIDUAL_RULE = 12;
    public const OK_USER_NAMED_RULE = 13;
    public const OK_USER_INDIVIDUAL_RULE = 14;
    public const OK_AUCTION_NAMED_RULE = 15;
    public const OK_AUCTION_INDIVIDUAL_RULE = 16;
    public const OK_ACCOUNT_AUCTION_TYPE_RULE = 17;

    public const ERROR_MESSAGES = [
        self::ERR_CANNOT_LOAD_LOT_NAMED_RULE => 'Failed on loading lot named rule',
        self::ERR_CANNOT_LOAD_USER_NAMED_RULE => 'Failed on loading user named rule',
        self::ERR_CANNOT_LOAD_AUCTION_NAMED_RULE => 'Failed on loading auction named rule',
        self::ERR_NOT_FOUND_ANY_RULE => 'Failed, because cannot find any available rule',
        self::ERR_CANNOT_LOAD_LOT_ITEM => 'Could not load lot item',
        self::ERR_CANNOT_LOAD_AUCTION => 'Could not load auction',
    ];

    public const SUCCESS_MESSAGES = [
        self::OK_LOT_NAMED_RULE => 'Calculate by Lot named rule',
        self::OK_LOT_INDIVIDUAL_RULE => 'Calculate by Lot individual rule',
        self::OK_USER_NAMED_RULE => 'Calculate by User named rule',
        self::OK_USER_INDIVIDUAL_RULE => 'Calculate by User individual rule',
        self::OK_AUCTION_NAMED_RULE => 'Calculate by Auction named rule',
        self::OK_AUCTION_INDIVIDUAL_RULE => 'Calculate by Auction individual rule',
        self::OK_ACCOUNT_AUCTION_TYPE_RULE => 'Calculate by Account & Auction type rule',
    ];

    public ?float $addPercent = null;
    public ?float $hammerPrice = null;
    public ?int $auctionAccountId = null;
    public ?int $auctionId = null;
    public ?int $bpId = null;
    public ?int $lotItemId = null;
    public ?int $userAccountId = null;
    public ?int $winningBidderUserId = null;
    public string $auctionType = '';
    public string $bpName = '';
    public string $rangeCalculation = '';

    // --- Construct ----

    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES, self::SUCCESS_MESSAGES);
        return $this;
    }

    // --- Mutate logic ---

    public function addError(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addError($code, $message);
        return $this;
    }

    public function addErrorWithAppendedMessage(int $code, string $append): static
    {
        $this->getResultStatusCollector()->addErrorWithAppendedMessage($code, $append);
        return $this;
    }

    public function addSuccess(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addSuccess($code, $message);
        return $this;
    }

    public function setLotItemId(int $lotItemId): static
    {
        $this->lotItemId = $lotItemId;
        return $this;
    }

    public function setHammerPrice(?float $hp): static
    {
        $this->hammerPrice = $hp;
        return $this;
    }

    public function setAuctionId(int $auctionId): static
    {
        $this->auctionId = $auctionId;
        return $this;
    }

    public function setAuctionType(string $auctionType): static
    {
        $this->auctionType = $auctionType;
        return $this;
    }

    public function setAuctionAccountId(int $auctionAccountId): static
    {
        $this->auctionAccountId = $auctionAccountId;
        return $this;
    }

    public function setBpId(int $bpId): static
    {
        $this->bpId = $bpId;
        return $this;
    }

    public function setBpName(string $bpName): static
    {
        $this->bpName = $bpName;
        return $this;
    }

    public function setRangeCalculation(string $rangeCalculation): static
    {
        $this->rangeCalculation = $rangeCalculation;
        return $this;
    }

    public function setAddPercent(float $addPercent): static
    {
        $this->addPercent = $addPercent;
        return $this;
    }

    public function setWinningBidderUserId(?int $userId): static
    {
        $this->winningBidderUserId = $userId;
        return $this;
    }

    public function setUserAccountId(?int $userAccountId): static
    {
        $this->userAccountId = $userAccountId;
        return $this;
    }

    // --- Query logic ---

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasSuccess(): bool
    {
        return !$this->hasError();
    }

    public function errorCode(): ?int
    {
        return $this->getResultStatusCollector()->getFirstErrorCode();
    }

    public function successCode(): ?int
    {
        return $this->getResultStatusCollector()->getFirstSuccessCode();
    }

    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage("\n");
    }

    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage("\n");
    }

    public function isLotNameRule(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteSuccess(self::OK_LOT_NAMED_RULE);
    }

    public function isLotIndividualRule(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteSuccess(self::OK_LOT_INDIVIDUAL_RULE);
    }

    public function isAuctionNameRule(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteSuccess(self::OK_AUCTION_NAMED_RULE);
    }

    public function isAuctionIndividualRule(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteSuccess(self::OK_AUCTION_INDIVIDUAL_RULE);
    }

    public function isUserNameRule(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteSuccess(self::OK_USER_NAMED_RULE);
    }

    public function isUserIndividualRule(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteSuccess(self::OK_USER_INDIVIDUAL_RULE);
    }

    public function isAccountAuctionTypeRule(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteSuccess(self::OK_ACCOUNT_AUCTION_TYPE_RULE);
    }

    public function levelName(): string
    {
        if ($this->isLotIndividualRule()) {
            return 'individual/lot';
        }

        if ($this->isLotNameRule()) {
            return 'named/lot';
        }

        if ($this->isUserIndividualRule()) {
            return 'individual/user';
        }

        if ($this->isUserNameRule()) {
            return 'named/user';
        }

        if ($this->isAuctionIndividualRule()) {
            return 'individual/auction';
        }

        if ($this->isAuctionNameRule()) {
            return 'individual/auction';
        }

        if ($this->isAccountAuctionTypeRule()) {
            return 'named/account';
        }

        return '';
    }

    public function logData(): array
    {
        $logData = [
            'li' => $this->lotItemId,
            'a' => $this->auctionId,
            'u' => $this->winningBidderUserId,
            'at' => $this->auctionType,
            'a acc' => $this->auctionAccountId,
            'u acc' => $this->userAccountId,
            'hp' => $this->hammerPrice,
            'level' => $this->levelName() . '/' . $this->rangeCalculation,
            'add percent' => $this->addPercent
        ];
        if ($this->bpName) {
            $logData['bp id'] = $this->bpId;
            $logData['bp name'] = $this->bpName;
        }
        return $logData;
    }
}
