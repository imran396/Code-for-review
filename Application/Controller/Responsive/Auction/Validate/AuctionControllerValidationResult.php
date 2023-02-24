<?php
/**
 * SAM-6796: Validations at controller layer for v3.5 - AuctionControllerValidator at responsive site
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 15, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Auction\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionControllerValidationResult
 * @package Sam\Application\Controller\Responsive\Auction
 */
class AuctionControllerValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    // --- Output values ---

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
     * Some actions are available for concrete auction types only (SAM-3311)
     */
    public const ERR_ACTION_TO_AUCTION_TYPE = 4;
    /**
     * SAM-3051
     */
    public const ERR_DOMAIN_AUCTION_VISIBILITY = 5;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_INCORRECT_AUCTION_ID => 'Wrong auction id',
        self::ERR_UNAVAILABLE_AUCTION => 'Available auction not found',
        self::ERR_INCORRECT_ACTION => 'Incorrect controller action',
        self::ERR_ACTION_TO_AUCTION_TYPE => 'Incorrect controller action for auction type',
        self::ERR_DOMAIN_AUCTION_VISIBILITY => 'Failed domain auction visibility check',
    ];

    /**
     * All validations completed successfully
     */
    public const OK_SUCCESS_VALIDATION = 6;

    /** @var string[] */
    protected const SUCCESS_MESSAGES = [
        self::OK_SUCCESS_VALIDATION => 'Successful validation'
    ];

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

    // --- Outgoing results ---

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
}
