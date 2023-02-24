<?php
/**
 * SAM-6790: Validations at controller layer for v3.5 - ManageAuctionsController
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           06-28, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Auction\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionControllerValidationResult
 * @package Sam\Application\Controller\Admin\Auction
 */
class AuctionControllerValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    /**
     * We want to check passed auction id here too
     */
    public const ERR_INCORRECT_AUCTION_ID = 1;
    /**
     * Check of auction account
     */
    public const ERR_AUCTION_ACCOUNT_NOT_FOUND = 2;
    /**
     * Check access rights on portal
     */
    public const ERR_AUCTION_AND_PORTAL_ACCOUNTS_NOT_MATCH = 3;
    /**
     * Auction access denied
     */
    public const ERR_AUCTION_TYPE_NOT_AVAILABLE = 4;
    /**
     * Empty action name passed (TODO: do we want to make a list of available actions?)
     */
    public const ERR_EMPTY_ACTION = 5;
    /**
     * Empty controller name passed
     */
    public const ERR_EMPTY_CONTROLLER = 6;
    /**
     * Empty or unexpected controller name passed
     */
    public const ERR_INCORRECT_CONTROLLER = 7;
    /**
     * Some actions are available for concrete auction types only
     */
    public const ERR_ACTION_TO_AUCTION_TYPE = 8;
    /**
     * Check auction availability (not deleted)
     */
    public const ERR_UNAVAILABLE_AUCTION = 9;
    /*
     * Lot is not valid
     */
    public const ERR_UNAVAILABLE_AUCTION_LOT = 10;


    /** @var int[] */
    protected const ACCESS_ERROR_CODES = [
        self::ERR_AUCTION_ACCOUNT_NOT_FOUND,
        self::ERR_AUCTION_AND_PORTAL_ACCOUNTS_NOT_MATCH,
        self::ERR_AUCTION_TYPE_NOT_AVAILABLE
    ];

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_ACTION_TO_AUCTION_TYPE => 'Incorrect controller action for auction type',
        self::ERR_AUCTION_ACCOUNT_NOT_FOUND => 'Access denied: auction account not available',
        self::ERR_AUCTION_AND_PORTAL_ACCOUNTS_NOT_MATCH => 'Access denied: auction and portal accounts not match',
        self::ERR_AUCTION_TYPE_NOT_AVAILABLE => 'Access denied: auction type not available',
        self::ERR_EMPTY_ACTION => 'Empty action name',
        self::ERR_EMPTY_CONTROLLER => 'Empty controller name',
        self::ERR_INCORRECT_AUCTION_ID => 'Wrong auction id',
        self::ERR_INCORRECT_CONTROLLER => 'Incorrect controller name',
        self::ERR_UNAVAILABLE_AUCTION => 'Available auction not found (deleted)',
        self::ERR_UNAVAILABLE_AUCTION_LOT => 'Available lot not found',
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

    // --- Query state ---

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasSuccess(): bool
    {
        return $this->getResultStatusCollector()->hasSuccess();
    }

    public function hasLotUnavailableError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_UNAVAILABLE_AUCTION_LOT);
    }

    public function hasAccessError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ACCESS_ERROR_CODES);
    }

    public function hasUnavailableError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_UNAVAILABLE_AUCTION);
    }

    /**
     * @return int[]
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    /**
     * @return int[]
     */
    public function successCodes(): array
    {
        return $this->getResultStatusCollector()->getSuccessCodes();
    }

    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    /**
     * @return string[]
     */
    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    /**
     * @return string[]
     */
    public function successMessages(): array
    {
        return $this->getResultStatusCollector()->getSuccessMessages();
    }

    /**
     * @return string[]
     */
    public function warningMessages(): array
    {
        return $this->getResultStatusCollector()->getWarningMessages();
    }

    /**
     * @return string[]
     */
    public function infoMessages(): array
    {
        return $this->getResultStatusCollector()->getInfoMessages();
    }
}
