<?php
/**
 * SAM-5041: Soap API RegisterBidder improvement
 * SAM-10968: Reject bidder# assigning of flagged users
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\BidderNum\ChangeExisting;

use AuctionBidder;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

class ExistingBidderNumberChangingResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_BIDDER_NOT_APPROVED_IN_AUCTION = 1;
    public const ERR_BIDDER_NUMBER_IS_THE_SAME = 2;
    public const ERR_BIDDER_NUMBER_EXIST_IN_AUCTION = 3;
    public const ERR_BIDDER_NUMBER_RESERVED_AS_PERMANENT_CUSTOMER_NO = 4;
    public const ERR_FLAGGED_USER_MODIFICATION_DENIED = 5;

    public const OK_BIDDER_DISAPPROVED_FROM_AUCTION = 11;
    public const OK_CHANGE_BIDDER_NUMBER_FOR_APPROVED_BIDDER = 12;

    public const INFO_USER_NOT_REGISTERED_IN_AUCTION = 21;
    public const INFO_USER_HAS_TO_BE_REGISTERED_IN_AUCTION = 22;
    public const INFO_USER_HAS_TO_BE_REGISTERED_AND_APPROVED_IN_AUCTION = 23;

    /**
     * "Error" means operation failed without any result, we shouldn't continue.
     * @var string[]
     */
    protected const ERROR_MESSAGES = [
        self::ERR_BIDDER_NOT_APPROVED_IN_AUCTION => 'Auction bidder is not approved in auction.',
        self::ERR_BIDDER_NUMBER_EXIST_IN_AUCTION => 'Failed to change bidder number. Bidder number %s exists.',
        self::ERR_BIDDER_NUMBER_RESERVED_AS_PERMANENT_CUSTOMER_NO => 'Failed to change bidder number. Bidder number %s is already assigned as permanent customer# to a different user.',
        self::ERR_BIDDER_NUMBER_IS_THE_SAME => 'Bidder number %s is the same as passed.',
        self::ERR_FLAGGED_USER_MODIFICATION_DENIED => 'Flagged user modification denied',
    ];

    /**
     * "Success" means operation succeeded and completed, we shouldn't continue.
     * @var string[]
     */
    protected const SUCCESS_MESSAGES = [
        self::OK_BIDDER_DISAPPROVED_FROM_AUCTION => 'Bidder is disapproved from auction.',
        self::OK_CHANGE_BIDDER_NUMBER_FOR_APPROVED_BIDDER => 'Bidder# changed from %s to %s.',
    ];

    /**
     * "Info" means operation cannot be completed by the service, we should continue in caller.
     * @var string[]
     */
    protected const INFO_MESSAGES = [
        self::INFO_USER_NOT_REGISTERED_IN_AUCTION => 'User is not registered in auction',
        self::INFO_USER_HAS_TO_BE_REGISTERED_IN_AUCTION => 'User has to be registered in auction',
        self::INFO_USER_HAS_TO_BE_REGISTERED_AND_APPROVED_IN_AUCTION => 'User has to be registered and approved in auction',
    ];

    protected const GLUE_MESSAGE_DEF = "\n";

    /** @var AuctionBidder|null */
    public ?AuctionBidder $resultAuctionBidder = null;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(
            self::ERROR_MESSAGES,
            self::SUCCESS_MESSAGES,
            [],
            self::INFO_MESSAGES,
            self::GLUE_MESSAGE_DEF
        );
        return $this;
    }

    // --- Mutation methods ---

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    public function addErrorWithInjectedInMessageArguments(int $code, array $injectArgs = []): static
    {
        $this->getResultStatusCollector()->addErrorWithInjectedInMessageArguments($code, $injectArgs);
        return $this;
    }

    public function addSuccess(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addSuccess($code, $message);
        return $this;
    }

    public function addSuccessWithInjectedInMessageArguments(int $code, array $injectArgs = []): static
    {
        $this->getResultStatusCollector()->addSuccessWithInjectedInMessageArguments($code, $injectArgs);
        return $this;
    }

    public function addInfo(int $code): static
    {
        $this->getResultStatusCollector()->addInfo($code);
        return $this;
    }

    public function setResultAuctionBidder(AuctionBidder $auctionBidder): static
    {
        $this->resultAuctionBidder = $auctionBidder;
        return $this;
    }

    // --- Query methods ---

    public function hasStatus(): bool
    {
        return $this->hasError() || $this->hasSuccess() || $this->hasInfo();
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasSuccess(): bool
    {
        return $this->getResultStatusCollector()->hasSuccess();
    }

    public function hasInfo(): bool
    {
        return $this->getResultStatusCollector()->hasInfo();
    }

    public function statusCode(): ?int
    {
        if ($this->hasError()) {
            return $this->getResultStatusCollector()->getFirstErrorCode();
        }
        if ($this->hasSuccess()) {
            return $this->getResultStatusCollector()->getFirstSuccessCode();
        }
        if ($this->hasInfo()) {
            return $this->getResultStatusCollector()->getFirstInfoCode();
        }
        return null;
    }

    public function statusMessage(): string
    {
        if ($this->hasError()) {
            return $this->errorMessage();
        }
        if ($this->hasSuccess()) {
            return $this->successMessage();
        }
        if ($this->hasInfo()) {
            return $this->infoMessage();
        }
        return '';
    }

    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
    }

    public function infoMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedInfoMessage();
    }
}
