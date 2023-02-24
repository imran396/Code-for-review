<?php
/**
 * Validate absentee bid amount.
 * We don't validate here, if lot is open for bidding, if user has sufficient rights, only absentee bid amount.
 *
 * SAM-5178: Refactor Absentee Bid Amount Validator for v3.5
 * SAM-4152: Absentee bid manager and validator
 *
 * @author        Igors Kotlevskis
 * @since         Mar 19, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Bidding\AbsenteeBid\Validate;

use AbsenteeBid;
use Auction;
use AuctionLotItem;
use LotItem;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Bidding\AbsenteeBid\Load\AbsenteeBidLoaderAwareTrait;
use Sam\Bidding\OnIncrementBid\OnIncrementBidServiceCreateTrait;
use Sam\Bidding\ReservePrice\ReservePriceSimpleCheckerAwareTrait;
use Sam\Core\Bidding\StartingBid\StartingBidPureChecker;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Math\Floating;
use Sam\Core\Platform\Constant\Base\ConstantNameResolver;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use User;

/**
 * Class AbsenteeBidAmountValidator
 * @package Sam\Bidding\AbsenteeBid\Validate
 */
class AbsenteeBidAmountValidator extends CustomizableClass
{
    use AbsenteeBidLoaderAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use CurrentDateTrait;
    use EditorUserAwareTrait;
    use LotItemLoaderAwareTrait;
    use OnIncrementBidServiceCreateTrait;
    use ReservePriceSimpleCheckerAwareTrait;
    use TranslatorAwareTrait;
    use UserLoaderAwareTrait;

    public const ERR_INVALID_BID = 1;
    public const ERR_SMALL_FOR_FIRST_BID = 2;
    public const ERR_SMALL_FOR_EXISTING_BID = 3;
    public const ERR_ALREADY_BID_AMOUNT = 4;
    public const ERR_LOWER_USER_MAX_BID = 5;
    public const ERR_RESERVE_NOT_MET = 6;
    public const ERR_STARTING_NOT_MET = 7;
    public const ERR_OFF_INCREMENT_BID = 8;
    public const ERR_AUCTION_LOT_INVALID = 9;
    public const ERR_LOT_ITEM_INVALID = 10;
    public const ERR_AUCTION_INVALID = 11;

    protected ?AbsenteeBid $absenteeBid = null;
    protected ?Auction $auction = null;
    protected ?AuctionLotItem $auctionLot = null;
    protected ?LotItem $lotItem = null;
    protected ?User $user = null;
    protected ?float $maxBid = null;
    protected ?float $currentMaxBid = null;
    protected ?string $maxBidTextboxControlId = null;
    protected ?bool $isNew = null;
    /**
     * @var int[]
     */
    protected array $errors = [];
    /**
     * @var string[]
     */
    protected array $messagesDefault = [
        self::ERR_INVALID_BID => "Invalid amount: {max_bid}",
        self::ERR_SMALL_FOR_FIRST_BID => "Too small amount {max_bid} for the first bid",
        self::ERR_SMALL_FOR_EXISTING_BID => "Too small amount: {max_bid}",
        self::ERR_ALREADY_BID_AMOUNT => "User already bid that amount: {max_bid}",
        self::ERR_LOWER_USER_MAX_BID => "Too small amount. Is lower than user's max bid: {current_max_bid}. Amount: {max_bid}",
        self::ERR_RESERVE_NOT_MET => "Reserve not met: {reserve_price}. Amount: {max_bid}",
        self::ERR_STARTING_NOT_MET => "Starting bid not met: {starting_bid}. Amount: {max_bid}",
        self::ERR_AUCTION_LOT_INVALID => "Invalid auction lot",
        self::ERR_LOT_ITEM_INVALID => "Invalid lot item",
        self::ERR_AUCTION_INVALID => "Invalid auction",
    ];
    /**
     * @var string[]
     */
    protected array $messagesForWebPublic = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        // $collector = $this->getResultStatusCollector();
        // $collector->init($this->messagesDefault);

        /**
         * First validate required and available entities
         */
        $auctionLot = $this->getAuctionLot();
        if (!$auctionLot) {
            $this->errors[] = self::ERR_AUCTION_LOT_INVALID;
        }
        $lotItem = $this->getLotItem();
        if (!$lotItem) {
            $this->errors[] = self::ERR_LOT_ITEM_INVALID;
        }
        $auction = $this->getAuction();
        if (!$auction) {
            $this->errors[] = self::ERR_AUCTION_INVALID;
        }
        $success = count($this->errors) === 0;
        if (!$success) {
            $this->logError();
            return false;
        }

        /**
         * Amount validations
         */
        $maxBid = $this->getMaxBid();
        $absenteeBid = $this->getAbsenteeBid();
        $isPositive = $auction->AboveStartingBid ? Floating::gt($maxBid, 0) : Floating::gteq($maxBid, 0);
        $isMeetStartingBid = $auction->AboveStartingBid
            ? StartingBidPureChecker::new()->meet($maxBid, $lotItem->StartingBid, $auction->Reverse)
            : true;
        if (!$this->validateBidIsNumeric()) {
            $this->errors[] = self::ERR_INVALID_BID;
        } elseif (
            $this->isNew()
            && !$isPositive
        ) {
            $this->errors[] = self::ERR_SMALL_FOR_FIRST_BID;
        } elseif (
            !$this->isNew()
            && !$isPositive
        ) {
            $this->errors[] = self::ERR_SMALL_FOR_EXISTING_BID;
        } /** for not new $absenteeBid @noinspection NullPointerExceptionInspection */
        elseif (
            !$this->isNew()
            && Floating::eq($absenteeBid->MaxBid, $maxBid)
        ) {
            $this->errors[] = self::ERR_ALREADY_BID_AMOUNT;
        } /** for not new $absenteeBid @noinspection NullPointerExceptionInspection */
        elseif (
            !$this->isNew()
            && $auction->NoLowerMaxbid
            && Floating::lt($maxBid, $absenteeBid->MaxBid)
        ) {
            $this->errors[] = self::ERR_LOWER_USER_MAX_BID;
        } elseif (
            $auction->AboveReserve
            && Floating::gt($lotItem->ReservePrice, 0)
            && !$this->getReservePriceSimpleChecker()
                ->meet($maxBid, $lotItem->ReservePrice, $auction->Reverse)
        ) {
            $this->errors[] = self::ERR_RESERVE_NOT_MET;
        } elseif (!$isMeetStartingBid) {
            $this->errors[] = self::ERR_STARTING_NOT_MET;
        } else {
            $this->checkOnIncrementBid();
        }

        $success = count($this->errors) === 0;
        if (!$success) {
            $this->logError();
            return false;
        }
        return true;
    }

    /**
     * @return int[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return string
     */
    public function getErrorMessageForSoap(): string
    {
        $output = implode("\n", $this->getMessages($this->messagesDefault));
        return $output;
    }

    /**
     * @return string
     * @noinspection PhpUnused
     */
    public function getErrorMessageForWebAdmin(): string
    {
        $output = implode("<br>", $this->getMessages($this->messagesDefault));
        return $output;
    }

    /**
     * @return string
     */
    public function getErrorMessageForWebPublic(): string
    {
        $isReverse = $this->getAuction()->Reverse; // reverse not available for live now
        $allMessages = [
            self::ERR_INVALID_BID => $this->getTranslator()
                ->translateByAuctionReverse('GENERAL_INVALID_MAXBID', 'general', $isReverse),
            self::ERR_SMALL_FOR_FIRST_BID => $this->getTranslator()
                ->translateByAuctionReverse('CATALOG_BID_TOOSMALL', 'catalog', $isReverse),
            self::ERR_SMALL_FOR_EXISTING_BID => $this->getTranslator()
                ->translateByAuctionReverse('CATALOG_BID_TOOSMALL', 'catalog', $isReverse),
            self::ERR_ALREADY_BID_AMOUNT => $this->getTranslator()
                ->translate('GENERAL_ALREADY_BID', 'general'),
            self::ERR_LOWER_USER_MAX_BID => $this->getTranslator()
                ->translateByAuctionReverse('CATALOG_BID_TOOSMALL', 'catalog', $isReverse),
            self::ERR_RESERVE_NOT_MET => $this->getTranslator()
                ->translate('CATALOG_RESERVENOTMET', 'catalog'),
            self::ERR_STARTING_NOT_MET => $this->getTranslator()
                ->translate('CATALOG_STARTINGBIDNOTMET', 'catalog'),
        ];
        $this->messagesForWebPublic += $allMessages;
        $output = implode("<br>", $this->getMessages($this->messagesForWebPublic));
        return $output;
    }

    /**
     * @return string
     */
    public function getErrorType(): string
    {
        if (array_intersect(
            [self::ERR_SMALL_FOR_FIRST_BID, self::ERR_SMALL_FOR_EXISTING_BID, self::ERR_LOWER_USER_MAX_BID],
            $this->errors
        )) {
            return 'too small';
        }
        return 'declined';
    }

    /**
     * @param string[] $allMessages
     * @return array
     */
    protected function getMessages(array $allMessages): array
    {
        $messages = [];
        foreach ($this->errors as $error) {
            $messages[] = $this->replacePlaceholders($allMessages[$error]);
        }
        return $messages;
    }

    /**
     * @param string $template
     * @return string
     */
    protected function replacePlaceholders(string $template): string
    {
        $placeholders = [
            'current_max_bid' => $this->getAbsenteeBid() ? $this->getAbsenteeBid()->MaxBid : null,
            'max_bid' => $this->getMaxBid(),
            'reserve_price' => $this->getLotItem()->ReservePrice,
            'starting_bid' => $this->getLotItem()->StartingBid,
        ];
        $output = $template;
        foreach ($placeholders as $key => $value) {
            $output = str_replace('{' . $key . '}', (string)$value, $output);
        }
        return $output;
    }

    /**
     * Check if new bid was placed from this user to this lot, not updated previous bid amount
     * @return bool
     */
    public function isNew(): bool
    {
        if ($this->isNew === null) {
            $this->isNew = !$this->getAbsenteeBid()
                || !$this->getAbsenteeBid()->__Restored;
        }
        return $this->isNew;
    }

    /**
     * @return AbsenteeBid|null
     */
    public function getAbsenteeBid(): ?AbsenteeBid
    {
        if ($this->absenteeBid === null) {
            if (
                $this->getLotItem()
                && $this->getAuction()
                && $this->getUser()
            ) {
                $this->absenteeBid = $this->getAbsenteeBidLoader()->load(
                    $this->getLotItem()->Id,
                    $this->getAuction()->Id,
                    $this->getUser()->Id
                );
            }
        }
        return $this->absenteeBid;
    }

    /**
     * @param AbsenteeBid|null $absenteeBid
     * @return static
     * @noinspection PhpUnused
     */
    public function setAbsenteeBid(?AbsenteeBid $absenteeBid): static
    {
        $this->absenteeBid = $absenteeBid;
        return $this;
    }

    /**
     * @return Auction|null
     */
    public function getAuction(): ?Auction
    {
        if ($this->auction === null) {
            $this->auction = $this->getAuctionLot()
                ? $this->getAuctionLoader()->load($this->getAuctionLot()->AuctionId, true)
                : null;
        }
        return $this->auction;
    }

    /**
     * @param Auction|null $auction
     * @return static
     */
    public function setAuction(?Auction $auction): static
    {
        $this->auction = $auction;
        return $this;
    }

    /**
     * @return LotItem|null
     */
    public function getLotItem(): ?LotItem
    {
        if ($this->lotItem === null) {
            $this->lotItem = $this->getAuctionLot()
                ? $this->getLotItemLoader()->load($this->getAuctionLot()->LotItemId, true)
                : null;
        }
        return $this->lotItem;
    }

    /**
     * @param LotItem|null $lotItem
     * @return static
     */
    public function setLotItem(?LotItem $lotItem): static
    {
        $this->lotItem = $lotItem;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        if ($this->user === null) {
            $this->user = $this->getEditorUser();
        }
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return static
     */
    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getMaxBid(): ?float
    {
        return $this->maxBid;
    }

    /**
     * @param float|null $maxBid
     * @return static
     */
    public function setMaxBid(?float $maxBid): static
    {
        $this->maxBid = Cast::toFloat($maxBid, Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
        return $this;
    }

    /**
     * @return float|null
     * @noinspection PhpUnused
     */
    public function getCurrentMaxBid(): ?float
    {
        return $this->currentMaxBid;
    }

    /**
     * @param float|null $currentMaxBid
     * @return static
     */
    public function setCurrentMaxBid(?float $currentMaxBid): static
    {
        $this->currentMaxBid = Cast::toFloat($currentMaxBid, Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
        return $this;
    }

    /**
     * @return AuctionLotItem|null
     */
    public function getAuctionLot(): ?AuctionLotItem
    {
        if ($this->auctionLot === null) {
            $this->auctionLot = $this->getAuctionLotLoader()
                ->load($this->getLotItem()->Id, $this->getAuction()->Id);
        }
        return $this->auctionLot;
    }

    /**
     * @param AuctionLotItem|null $auctionLot
     * @return static
     */
    public function setAuctionLot(?AuctionLotItem $auctionLot): static
    {
        $this->auctionLot = $auctionLot;
        return $this;
    }

    /**
     * We need it for error for On Increment enabled function
     * @return string
     */
    public function getMaxBidTextboxControlId(): string
    {
        return $this->maxBidTextboxControlId ?? '';
    }

    /**
     * @param string $controlId
     * @return static
     */
    public function setMaxBidTextboxControlId(string $controlId): static
    {
        $this->maxBidTextboxControlId = $controlId;
        return $this;
    }

    /**
     * @return bool
     */
    protected function validateBidIsNumeric(): bool
    {
        $maxBid = $this->getMaxBid();
        $success = is_numeric($maxBid);
        return $success;
    }

    // /**
    //  * @return bool
    //  */
    protected function validateHighEnoughForFirstBid(): void
    {
        // TODO: implement reverse checking of initial condition:
        // See, I added negative sign to initial condition from validate().
        // You should get rid of it, but reverse condition
        // (($this->isNew()
        //     && (Floating::lteq($maxBid, 0)
        //         || !Lot_Bidding::meetStartingBid($maxBid, $lotItem->StartingBid, $auction->Reverse))
        // ));
        // It should return true, when it isn't first bid. In result something like that:
        // But first implement tests, then perform refactoring and check by tests, that refactoring is done successfully.
        // Something like this:
        // $maxBid = $this->getMaxBid();
        // $lotItem = $this->getLotItem();
        // $auction = $this->getAuction();
        // $success = true;
        // if ($this->isNew()) {
        //     $success = Floating::gt($maxBid, 0)
        //         && Lot_Bidding::meetStartingBid($maxBid, $lotItem->StartingBid, $auction->Reverse);
        // }
        // return $success;
    }

    // /**
    //  * @return bool
    //  */
    protected function validateHighEnoughForExistingBid(): void
    {
        // reverse condition:
        // !$this->isNew()
        //     && Floating::lt($maxBid, 0)
        // Something like this:
        // $maxBid = $this->getMaxBid();
        // $success = true;
        // if (!$this->isNew()) {
        //     $success = Floating::gteq($maxBid, 0);
        // }
        // return $success;
    }

    // /**
    //  * @return bool
    //  */
    protected function validateAmountIsUnique(): void
    {
        // reverse condition:
        // (!$this->isNew()
        //     && Floating::eq($absenteeBid->MaxBid, $maxBid)
        // $maxBid = $this->getMaxBid();
        // $absenteeBid = $this->getAbsenteeBid();
        // $success = true;
        // if (!$this->isNew()) {
        //     $success = Floating::neq($absenteeBid->MaxBid, $maxBid);
        // }
        // return $success;
    }

    // /**
    //  * @return bool
    //  */
    protected function validateHighEnoughForOwnMaxBid(): void
    {
        // reverse condition:
        // !$this->isNew()
        //     && $auction->NoLowerMaxbid
        //     && Floating::lt($maxBid, $absenteeBid->MaxBid)
        // Something like this:
        //
        // $maxBid = $this->getMaxBid();
        // $auction = $this->getAuction();
        // $absenteeBid = $this->getAbsenteeBid();
        // $success = true;
        // if (!$this->isNew()
        //     && $auction->NoLowerMaxbid
        // ) {
        //     $success = Floating::gteq($maxBid, $absenteeBid->MaxBid);
        // }
        // return $success;
    }

    // /**
    //  * @return bool
    //  */
    protected function validateReservePriceIsMet(): void
    {
        // reverse condition:
        // ($auction->AboveReserve
        //     && Floating::gt($lotItem->ReservePrice, 0)
        //     && !$this->getReservePriceSimpleChecker()
        //         ->meet($maxBid, $lotItem->ReservePrice, $auction->Reverse)
        // )
        // Something like this:
        //
        // $maxBid = $this->getMaxBid();
        // $lotItem = $this->getLotItem();
        // $auction = $this->getAuction();
        // $success = true;
        // if ($auction->AboveReserve
        //     && Floating::gt($lotItem->ReservePrice, 0)
        // ) {
        //     $success = $this->getReservePriceSimpleChecker()
        //         ->meet($maxBid, $lotItem->ReservePrice, $auction->Reverse);
        // }
        // return $success;
    }

    protected function checkOnIncrementBid(): void
    {
        $maxBid = $this->getMaxBid();
        $auctionLot = $this->getAuctionLot();
        if (!$auctionLot) {
            log_error("Available auctionLot not found" . composeSuffix(['li' => $this->getLotItem()->Id, 'a' => $this->getAuction()->Id]));
            return;
        }

        $onIncrementBidService = $this->createOnIncrementBidService()
            ->construct($maxBid, $auctionLot->LotItemId, $auctionLot->AuctionId);
        if (!$onIncrementBidService->validate()) {
            $this->errors[] = self::ERR_OFF_INCREMENT_BID;
            $this->messagesDefault[self::ERR_OFF_INCREMENT_BID]
                = $onIncrementBidService->buildCleanMessage();
            $this->messagesForWebPublic[self::ERR_OFF_INCREMENT_BID]
                = $onIncrementBidService->buildHtmlMessage($this->getMaxBidTextboxControlId());
        }
    }

    // /**
    //  * @return bool
    //  */
    protected function validateStartingBidIsMet(): void
    {
        // reverse condition:
        // ($auction->AboveStartingBid
        //     && Floating::gt($lotItem->StartingBid, 0)
        //     && Floating::gt($lotItem->StartingBid, $maxBid)
        // )
        // Something like this:
        //
        // $maxBid = $this->getMaxBid();
        // $lotItem = $this->getLotItem();
        // $auction = $this->getAuction();
        // $success = true;
        // if ($auction->AboveStartingBid
        //     && Floating::gt($lotItem->StartingBid, 0)
        // ) {
        //     $success = Floating::lteq($lotItem->StartingBid, $maxBid);
        // }
        // return $success;
    }

    protected function logError(): void
    {
        [$foundNamesToCodes, $notFoundCodes] = ConstantNameResolver::new()
            ->construct()
            ->resolveManyFromClass($this->errors, self::class);
        $errorNames = array_map(
            static function ($tuple) {
                return "{$tuple[1]} ({$tuple[0]})";
            },
            $foundNamesToCodes
        );
        $errorNo = implode(',', array_merge($errorNames, $notFoundCodes));
        $isNew = (int)$this->isNew();
        $noLowerMaxbid = (int)$this->getAuction()->NoLowerMaxbid;
        $aboveReserve = (int)$this->getAuction()->AboveReserve;
        $aboveStartingBid = (int)$this->getAuction()->AboveStartingBid;
        $message = "Absentee bid amount validation failed with error ({$errorNo}): \"{$this->getErrorMessageForSoap()}\""
            . composeSuffix(
                [
                    'li' => $this->getLotItem() ? $this->getLotItem()->Id : null,
                    'a' => $this->getAuction() ? $this->getAuction()->Id : null,
                    'u' => $this->getUser() ? $this->getUser()->Id : null,
                    'amount' => $this->getMaxBid(),
                    'new' => $isNew,
                    'NoLowerMaxbid' => $noLowerMaxbid,
                    'AboveReserve' => $aboveReserve,
                    'ReservePrice' => $this->getLotItem()->ReservePrice,
                    'AboveStartingBid' => $aboveStartingBid,
                    'StartingBid' => $this->getLotItem()->StartingBid,
                ]
            );
        log_debug($message);
    }
}
