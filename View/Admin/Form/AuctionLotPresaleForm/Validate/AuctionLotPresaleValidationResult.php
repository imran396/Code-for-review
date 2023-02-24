<?php
/**
 * SAM-8763 : Lot Absentee Bid List page - Add bid amount validation
 * https://bidpath.atlassian.net/browse/SAM-8763
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotPresaleForm\Validate;

use AbsenteeBid;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionLotPresaleValidationResult
 * @package Sam\View\Admin\Form\AuctionLotPresaleForm\Validate
 */
class AuctionLotPresaleValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_MAX_BID_REQUIRED = 1;
    public const ERR_MAX_BID_NOT_NUMERIC = 2;
    public const ERR_BID_DATE_INVALID_FORMAT = 3;
    public const ERR_BID_USER_ID_ABSENT = 4;
    public const ERR_BID_USER_NOT_FOUND = 5;
    public const ERR_BID_NOT_FOUND = 6;

    /** @var int[] */
    protected const MAX_BID_ERRORS = [self::ERR_MAX_BID_REQUIRED, self::ERR_MAX_BID_NOT_NUMERIC];
    /** @var int[] */
    protected const BIDDER_ERRORS = [self::ERR_BID_USER_ID_ABSENT, self::ERR_BID_USER_NOT_FOUND];
    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_MAX_BID_REQUIRED => 'Required',
        self::ERR_MAX_BID_NOT_NUMERIC => 'Invalid max bid',
        self::ERR_BID_DATE_INVALID_FORMAT => 'Invalid date format',
        self::ERR_BID_USER_ID_ABSENT => 'No bidder selected',
        self::ERR_BID_USER_NOT_FOUND => 'User not found',
        self::ERR_BID_NOT_FOUND => 'Failed to load absentee bid',
    ];

    public ?AbsenteeBid $updatedAbsenteeBid = null;

    /**
     * Class instantiation method
     * @return $this
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
        $this->getResultStatusCollector()->construct(static::ERROR_MESSAGES);
        return $this;
    }

    // --- Mutate ---

    /**
     * @param int $code
     * @return $this
     */
    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    // --- Query ---

    /**
     * @return bool
     */
    public function hasSuccess(): bool
    {
        return !$this->hasError();
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    /**
     * @return string
     */
    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    /**
     * Get max bid error message
     * @return string
     */
    public function maxBidErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes(self::MAX_BID_ERRORS);
    }

    /**
     * @return bool
     */
    public function hasMaxBidError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::MAX_BID_ERRORS);
    }

    /**
     * @return bool
     */
    public function hasBiddingDateError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([self::ERR_BID_DATE_INVALID_FORMAT]);
    }

    /**
     * Get date error message
     * @return string
     */
    public function biddingDateErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes([self::ERR_BID_DATE_INVALID_FORMAT]);
    }

    /**
     * @return bool
     */
    public function hasBidderError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::BIDDER_ERRORS);
    }

    /**
     * Get bidder error message
     * @return string
     */
    public function bidderErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes(self::BIDDER_ERRORS);
    }

    /**
     * @return bool
     */
    public function hasAbsenteeBidError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([self::ERR_BID_NOT_FOUND]);
    }

    /**
     * Get absentee bid error message
     * @return string
     */
    public function absenteeBidErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes([self::ERR_BID_NOT_FOUND]);
    }

    /**
     * @return int[]
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }
}
