<?php
/**
 * Validate bid increment (amount, increment)
 *
 * SAM-5516: Bid-Increments: Incorrect validation is displayed for entered blank space.
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           18 Now, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\BidIncrement\Validate;

use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class BidIncrementValidator
 * @package Sam\Bidding\BidIncrement\Validate
 */
class BidIncrementValidator extends CustomizableClass
{
    use BidIncrementExistenceCheckerAwareTrait;
    use NumberFormatterAwareTrait;

    private const ERROR_AMOUNT_REQUIRED = 'Required';
    private const ERROR_AMOUNT_EXIST = 'Start range already exist';
    private const ERROR_AMOUNT_INVALID = 'Must be a number';
    private const ERROR_AMOUNT_NEGATIVE = 'Must be positive';
    private const ERROR_INCREMENT_REQUIRED = 'Required';
    private const ERROR_INCREMENT_INVALID = 'Must be a number';
    private const ERROR_INCREMENT_NEGATIVE = 'Must be more than 0';

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $amount
     * @param int|null $auctionId
     * @param int|null $lotItemId
     * @param int|null $accountId
     * @param string|null $auctionType
     * @return null|string
     */
    public function validateAmount(
        string $amount,
        ?int $auctionId = null,
        ?int $lotItemId = null,
        ?int $accountId = null,
        ?string $auctionType = null
    ): ?string {
        if ($amount === '') {
            return self::ERROR_AMOUNT_REQUIRED;
        }
        if (!NumberValidator::new()->isReal($this->getNumberFormatter()->removeFormat($amount))) {
            return self::ERROR_AMOUNT_INVALID;
        }
        if (Floating::lt($this->getNumberFormatter()->parse($amount), 0.)) {
            return self::ERROR_AMOUNT_NEGATIVE;
        }
        if ($this->getBidIncrementExistenceChecker()->existByAmount(
            $this->getNumberFormatter()->parse($amount),
            $auctionId,
            $lotItemId,
            $accountId,
            $auctionType
        )) {
            return self::ERROR_AMOUNT_EXIST;
        }
        return null;
    }

    /**
     * @param string $increment
     * @return null|string
     */
    public function validateIncrement(string $increment): ?string
    {
        if ($increment === '') {
            return self::ERROR_INCREMENT_REQUIRED;
        }
        if (!NumberValidator::new()->isReal($this->getNumberFormatter()->removeFormat($increment))) {
            return self::ERROR_INCREMENT_INVALID;
        }
        if (Floating::lteq($this->getNumberFormatter()->parse($increment), 0)) {
            return self::ERROR_INCREMENT_NEGATIVE;
        }
        return null;
    }
}
