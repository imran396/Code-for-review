<?php
/**
 * SAM-9437: Implement ./AuctionRegistrationControllerValidationResult and use it as return value for ./AuctionRegistrationControllerValidator::validate
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           07-26, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Auction\Validate\Registration;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionRegistrationControllerValidationResult
 * @package Sam\Application\Controller\Responsive\Auction\Validate\Registration
 */
class AuctionRegistrationControllerValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    /**
     * We want to check passed auction id here too
     */
    public const ERR_INCORRECT_AUCTION_ID = 1;
    /**
     * We allow to access auctions only with available status
     */
    public const ERR_UNAVAILABLE_AUCTION = 2;
    /**
     * Empty action name passed (TODO: do we want to make a list of available actions?)
     */
    public const ERR_INCORRECT_ACTION = 3;
    /**
     * SAM-3051
     */
    public const ERR_DOMAIN_AUCTION_VISIBILITY = 5;
    /**
     * Check settlement account
     */
    public const ERR_AUCTION_ACCOUNT_NOT_FOUND = 6;
    /**
     * Closed auction = should be redirected to auction-info page
     */
    public const ERR_AUCTION_CLOSED = 7;
    /**
     * User already registered in auction, so he cannot visit this page = user should be redirected to auction-info page
     */
    public const ERR_ALREADY_REGISTERED = 8;
    /**
     * Start registration date is in future
     */
    public const ERR_REGISTRATION_NOT_STARTED = 9;
    /**
     * Authorized user has unverified email address.
     */
    public const ERR_UNVERIFIED_EMAIL_FOR_AUTH_USER = 10;
    /**
     * Authorized user should be a bidder in auction.
     */
    public const ERR_EDITOR_USER_MUST_BE_BIDDER = 11;
    /**
     * Editor user id not defined.
     */
    public const ERR_ANONYMOUS_USER = 12;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_INCORRECT_AUCTION_ID => 'Wrong auction id',
        self::ERR_UNAVAILABLE_AUCTION => 'Available auction not found',
        self::ERR_INCORRECT_ACTION => 'Incorrect controller action',
        self::ERR_DOMAIN_AUCTION_VISIBILITY => 'Failed domain auction visibility check',
        self::ERR_AUCTION_ACCOUNT_NOT_FOUND => 'Access denied: auction account not available',
        self::ERR_AUCTION_CLOSED => 'Auction is closed',
        self::ERR_ALREADY_REGISTERED => 'Already registered in auction',
        self::ERR_REGISTRATION_NOT_STARTED => 'Auction registration is not started',
        self::ERR_UNVERIFIED_EMAIL_FOR_AUTH_USER => 'Access denied: please verify email address',
        self::ERR_EDITOR_USER_MUST_BE_BIDDER => 'Editor user must have bidder role',
        self::ERR_ANONYMOUS_USER => 'Anonymous user is not allowed',
    ];

    /**
     * All validations completed successfully
     */
    public const OK_SUCCESS_VALIDATION = 100;

    /** @var string[] */
    public const SUCCESS_MESSAGES = [
        self::OK_SUCCESS_VALIDATION => 'Successful validation',
    ];

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
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES, self::SUCCESS_MESSAGES);
        return $this;
    }

    // --- Mutate state ---

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    public function addSuccess(int $code): static
    {
        $this->getResultStatusCollector()->addSuccess($code);
        return $this;
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasSuccess(): bool
    {
        return $this->getResultStatusCollector()->hasSuccess();
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
     * @return string
     */
    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    /**
     * @return bool
     */
    public function hasClosedOrRegistrationRequiredError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(
            [self::ERR_ALREADY_REGISTERED, self::ERR_AUCTION_CLOSED]
        );
    }

    /**
     * @return bool
     */
    public function hasUnverifiedEmailError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_UNVERIFIED_EMAIL_FOR_AUTH_USER);
    }

    /**
     * @return bool
     */
    public function hasRegistrationNotStartedError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_REGISTRATION_NOT_STARTED);
    }
}
