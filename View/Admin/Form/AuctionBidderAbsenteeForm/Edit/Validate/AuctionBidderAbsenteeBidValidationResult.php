<?php
/**
 * SAM-9530: "User Absentee Bid" page - extract logic and cover with unit test for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Edit\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class AuctionBidderAbsenteeBidResult
 * @package Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Edit\Validate
 */
class AuctionBidderAbsenteeBidValidationResult extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use ResultStatusCollectorAwareTrait;

    public const OK_SAVED = 1;

    public const ERR_BIDDER_REMOVED = 1;
    public const ERR_BIDDER_NOT_APPROVED = 2;
    public const ERR_LOT_FULL_NUM_REQUIRED = 3;
    public const ERR_LOT_FULL_NUM_INVALID = 4;
    public const ERR_LOT_FULL_NUM_TOO_BIG = 5;
    public const ERR_LOT_FULL_NUM_NOT_EXISTS = 6;
    public const ERR_LOT_NUM_REQUIRED = 7;
    public const ERR_LOT_NUM_INVALID = 8;
    public const ERR_LOT_NUM_PREFIX = 9;
    public const ERR_LOT_NUM_EXT = 10;
    public const ERR_LOT_NUM_TOO_BIG = 11;
    public const ERR_LOT_NUM_NOT_EXISTS = 12;
    public const ERR_MAX_BID_REQUIRED = 13;
    public const ERR_MAX_BID_INVALID = 14;
    public const ERR_DATE_REQUIRED = 15;

    /** @var int[] */
    public const BIDDER_ERROR_CODES = [
        self::ERR_BIDDER_REMOVED,
        self::ERR_BIDDER_NOT_APPROVED
    ];

    /** @var int[] */
    public const FULL_NUM_ERROR_CODES = [
        self::ERR_LOT_FULL_NUM_REQUIRED,
        self::ERR_LOT_FULL_NUM_INVALID,
        self::ERR_LOT_FULL_NUM_TOO_BIG,
        self::ERR_LOT_FULL_NUM_NOT_EXISTS,
    ];

    /** @var int[] */
    public const LOT_NUM_ERROR_CODES = [
        self::ERR_LOT_NUM_REQUIRED,
        self::ERR_LOT_NUM_INVALID,
        self::ERR_LOT_NUM_TOO_BIG,
        self::ERR_LOT_NUM_NOT_EXISTS,
    ];

    /** @var int[] */
    public const LOT_NUM_PREFIX_ERROR_CODES = [
        self::ERR_LOT_NUM_PREFIX
    ];

    /** @var int[] */
    public const LOT_NUM_EXT_ERROR_CODES = [
        self::ERR_LOT_NUM_EXT
    ];

    /** @var int[] */
    public const MAX_BID_ERROR_CODES = [
        self::ERR_MAX_BID_REQUIRED,
        self::ERR_MAX_BID_INVALID
    ];

    /** @var int[] */
    public const DATE_ERROR_CODES = [
        self::ERR_DATE_REQUIRED
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Initialize error messages
     */
    public function initResult(): void
    {
        $errorMessages = [
            self::ERR_BIDDER_REMOVED => 'Failed to add absentee bid. Bidder has been removed!',
            self::ERR_BIDDER_NOT_APPROVED => 'Failed to add absentee bid. Bidder was not approved yet!',
            self::ERR_LOT_FULL_NUM_REQUIRED => 'Required',
            self::ERR_LOT_FULL_NUM_INVALID => 'Invalid',
            self::ERR_LOT_NUM_REQUIRED => 'Required',
            self::ERR_LOT_NUM_INVALID => 'Invalid',
            self::ERR_LOT_NUM_PREFIX => 'Invalid',
            self::ERR_LOT_NUM_EXT => 'Invalid',
            self::ERR_LOT_FULL_NUM_TOO_BIG => 'The item number is higher than the max available value. Please enter a value below '
                . $this->cfg()->get('core->db->mysqlMaxInt'),
            self::ERR_LOT_NUM_TOO_BIG => 'The item number is higher than the max available value. Please enter a value below '
                . $this->cfg()->get('core->db->mysqlMaxInt'),
            self::ERR_MAX_BID_INVALID => 'Invalid',
            self::ERR_MAX_BID_REQUIRED => 'Required',
            self::ERR_LOT_FULL_NUM_NOT_EXISTS => 'Not exists',
            self::ERR_LOT_NUM_NOT_EXISTS => 'Not exists',
            self::ERR_DATE_REQUIRED => 'Required',
        ];

        $successMessages = [
            self::OK_SAVED => 'Absentee bid saved',
        ];

        $this->getResultStatusCollector()->construct($errorMessages, $successMessages);
    }

    public function hasSuccess(): bool
    {
        return !$this->hasError();
    }

    /**
     * @param int $code
     * @param string|null $message null - for initial message
     * @return $this
     */
    public function addError(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addError($code, $message);
        return $this;
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    /**
     * Return error message array
     * @return string[]
     */
    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    /**
     * Get list of error codes
     * @return array
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    /**
     * Get Bidder Error Message
     * @return string|null
     */
    public function bidderErrorMessage(): ?string
    {
        return $this->getResultStatusCollector()->findFirstErrorMessageAmongCodes(self::BIDDER_ERROR_CODES);
    }

    /**
     * Check Bidder error
     * @return bool
     */
    public function hasBidderError(): bool
    {
        return $this->bidderErrorMessage() !== null;
    }

    /**
     * Get Lot Full Num Error Message
     * @return string|null
     */
    public function fullNumErrorMessage(): ?string
    {
        return $this->getResultStatusCollector()->findFirstErrorMessageAmongCodes(self::FULL_NUM_ERROR_CODES);
    }

    /**
     * Check Full Lot Num error
     * @return bool
     */
    public function hasFullLotNumError(): bool
    {
        return $this->fullNumErrorMessage() !== null;
    }

    /**
     * Get Lot Num Error Message
     * @return string|null
     */
    public function lotNumErrorMessage(): ?string
    {
        return $this->getResultStatusCollector()->findFirstErrorMessageAmongCodes(self::LOT_NUM_ERROR_CODES);
    }

    /**
     * Check Lot Num error
     * @return bool
     */
    public function hasLotNumError(): bool
    {
        return $this->lotNumErrorMessage() !== null;
    }

    /**
     * Get Lot Num Prefix Error Message
     * @return string|null
     */
    public function lotNumPrefixErrorMessage(): ?string
    {
        return $this->getResultStatusCollector()->findFirstErrorMessageAmongCodes(self::LOT_NUM_PREFIX_ERROR_CODES);
    }

    /**
     * Check Lot Num Prefix error
     * @return bool
     */
    public function hasLotNumPrefixError(): bool
    {
        return $this->lotNumPrefixErrorMessage() !== null;
    }

    /**
     * Get Lot Num Prefix Error Message
     * @return string|null
     */
    public function lotNumExtErrorMessage(): ?string
    {
        return $this->getResultStatusCollector()->findFirstErrorMessageAmongCodes(self::LOT_NUM_EXT_ERROR_CODES);
    }

    /**
     * Check Lot Num Ext error
     * @return bool
     */
    public function hasLotNumExtError(): bool
    {
        return $this->lotNumExtErrorMessage() !== null;
    }

    /**
     * Get Max Bid Error Message
     * @return string|null
     */
    public function maxBidErrorMessage(): ?string
    {
        return $this->getResultStatusCollector()->findFirstErrorMessageAmongCodes(self::MAX_BID_ERROR_CODES);
    }

    /**
     * Get Max Bid Error Message
     * @return string|null
     */
    public function dateErrorMessage(): ?string
    {
        return $this->getResultStatusCollector()->findFirstErrorMessageAmongCodes(self::DATE_ERROR_CODES);
    }

    /**
     * Check Max Bid error
     * @return bool
     */
    public function hasMaxBidError(): bool
    {
        return $this->maxBidErrorMessage() !== null;
    }

    /**
     * Check Date error
     * @return bool
     */
    public function hasDateError(): bool
    {
        return $this->dateErrorMessage() !== null;
    }
}
