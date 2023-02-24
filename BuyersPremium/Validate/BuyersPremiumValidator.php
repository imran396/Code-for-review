<?php
/**
 * Validate buyers premium (amount, fixed, percent)
 *
 * SAM-8107: Issues related to Validation and Values of Buyer's Premium
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           26 Apr, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\BuyersPremium\Validate;

use Sam\Bidding\BidIncrement\Validate\BidIncrementExistenceCheckerAwareTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class BuyersPremiumValidator
 * @package Sam\BuyersPremium\Validate
 */
class BuyersPremiumValidator extends CustomizableClass
{
    use BidIncrementExistenceCheckerAwareTrait;
    use BuyersPremiumRangeExistenceCheckerCreateTrait;
    use NumberFormatterAwareTrait;

    private const ERROR_AMOUNT_EXIST = 'Start range already exist';
    private const ERROR_AMOUNT_INVALID = 'Should be numeric value';
    private const ERROR_AMOUNT_NEGATIVE = 'Must be more than or equal to 0';
    private const ERROR_AMOUNT_REQUIRED = 'Required';
    private const ERROR_FIXED_INVALID = 'Should be numeric value';
    private const ERROR_FIXED_NEGATIVE = 'Must be more than or equal to 0';
    private const ERROR_FIXED_OR_PERCENT_REQUIRED = 'Fixed and % cannot be both empty or zero';
    private const ERROR_PERCENT_INVALID = 'Should be numeric value';
    private const ERROR_PERCENT_NEGATIVE = 'Must be more than or equal to 0';

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
     * @param int|null $buyersPremiumId
     * @param int|null $lotItemId
     * @return null|string
     */
    public function validateAmount(
        string $amount,
        ?int $auctionId = null,
        ?int $buyersPremiumId = null,
        ?int $lotItemId = null
    ): ?string {
        $amount = $this->getNumberFormatter()->removeFormat($amount);
        if ($amount === '') {
            return self::ERROR_AMOUNT_REQUIRED;
        }

        if (!NumberValidator::new()->isReal($amount)) {
            return self::ERROR_AMOUNT_INVALID;
        }

        if (Floating::lt($amount, 0)) {
            return self::ERROR_AMOUNT_NEGATIVE;
        }

        $bpRangeExistenceChecker = $this->createBuyersPremiumRangeExistenceChecker();
        if (
            $auctionId
            && $bpRangeExistenceChecker->existAmountInAuctionBp((float)$amount, $auctionId)
        ) {
            return self::ERROR_AMOUNT_EXIST;
        }

        if (
            $buyersPremiumId
            && $bpRangeExistenceChecker->existAmountInGlobalBp((float)$amount, $buyersPremiumId)
        ) {
            return self::ERROR_AMOUNT_EXIST;
        }

        if (
            $lotItemId
            && $bpRangeExistenceChecker->existAmountInLotItemBp((float)$amount, $lotItemId)
        ) {
            return self::ERROR_AMOUNT_EXIST;
        }

        return null;
    }

    /**
     * @param string $fixed
     * @param string $percent
     * @return null|string
     */
    public function validateFixed(string $fixed, string $percent): ?string
    {
        $fixed = $this->getNumberFormatter()->removeFormat($fixed);
        $percent = $this->getNumberFormatter()->removeFormat($percent);

        if (
            $fixed === ''
            && $percent === ''
        ) {
            return self::ERROR_FIXED_OR_PERCENT_REQUIRED;
        }
        if (!NumberValidator::new()->isReal($fixed)) {
            return self::ERROR_FIXED_INVALID;
        }
        if (Floating::lt($fixed, 0)) {
            return self::ERROR_FIXED_NEGATIVE;
        }
        return null;
    }

    /**
     * @param string $percent
     * @return null|string
     */
    public function validatePercent(string $percent): ?string
    {
        $percent = $this->getNumberFormatter()->removeFormat($percent);

        if (!NumberValidator::new()->isReal($percent)) {
            return self::ERROR_PERCENT_INVALID;
        }
        if (Floating::lt($percent, 0)) {
            return self::ERROR_PERCENT_NEGATIVE;
        }
        return null;
    }
}
