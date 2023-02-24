<?php
/**
 * SAM-6798: Validations at controller layer for v3.5 - LotControllerValidator at responsive site
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

namespace Sam\Application\Controller\Responsive\Lot\Validate;


use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LotControllerValidationResult
 * @package Sam\Application\Controller\Responsive\Lot
 */
class LotControllerValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    // --- Outgoing values ---

    /**
     * Check lot item entity existence
     */
    public const ERR_LOT_ITEM_NOT_FOUND = 1;
    /**
     * Check auction lot item entity existence
     */
    public const ERR_AUCTION_LOT_NOT_FOUND = 2;
    /**
     * We allow to access auctions only with available status
     */
    public const ERR_AUCTION_NOT_FOUND = 3;
    /**
     * SAM-3051
     */
    public const ERR_DOMAIN_AUCTION_VISIBILITY = 4;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_LOT_ITEM_NOT_FOUND => 'Available lot item not found',
        self::ERR_AUCTION_LOT_NOT_FOUND => 'Available auction lot not found',
        self::ERR_AUCTION_NOT_FOUND => 'Available auction not found',
        self::ERR_DOMAIN_AUCTION_VISIBILITY => 'Failed domain auction visibility check',
    ];

    /**
     * When auction id is absent, no need in most of validations
     */
    public const OK_AUCTION_ID_IS_ABSENT = 5;

    /**
     * All validations completed successfully
     */
    public const OK_SUCCESS_VALIDATION = 6;

    /** @var string[] */
    protected const SUCCESS_MESSAGES = [
        self::OK_AUCTION_ID_IS_ABSENT => 'Auction id is not passed',
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
     * @return $this
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
