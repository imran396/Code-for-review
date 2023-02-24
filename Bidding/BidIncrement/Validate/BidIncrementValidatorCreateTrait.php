<?php
/**
 * SAM-5516: Bid-Increments: Incorrect validation is displayed for entered blank space.
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\BidIncrement\Validate;

/**
 * Trait BidIncrementValidatorCreateTrait
 * @package Sam\Bidding\BidIncrement\Validate
 */
trait BidIncrementValidatorCreateTrait
{
    /**
     * @var BidIncrementValidator|null
     */
    protected ?BidIncrementValidator $bidIncrementValidator = null;

    /**
     * @return BidIncrementValidator
     */
    protected function createBidIncrementValidator(): BidIncrementValidator
    {
        return $this->bidIncrementValidator ?: BidIncrementValidator::new();
    }

    /**
     * @param BidIncrementValidator $bidIncrementValidator
     * @return static
     * @internal
     */
    public function setBidIncrementValidator(BidIncrementValidator $bidIncrementValidator): static
    {
        $this->bidIncrementValidator = $bidIncrementValidator;
        return $this;
    }
}
