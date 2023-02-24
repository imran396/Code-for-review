<?php
/**
 * SAM-6438: Refactor auction state validation on rtb console load
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 24, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Validate;

/**
 * Trait RtbAuctionValidatorCreateTrait
 * @package Sam\Rtb\Validate
 */
trait RtbAuctionValidatorCreateTrait
{
    /**
     * @var RtbAuctionValidator|null
     */
    protected ?RtbAuctionValidator $rtbAuctionValidator = null;

    /**
     * @return RtbAuctionValidator
     */
    protected function createRtbAuctionValidator(): RtbAuctionValidator
    {
        return $this->rtbAuctionValidator ?: RtbAuctionValidator::new();
    }

    /**
     * @param RtbAuctionValidator $rtbAuctionValidator
     * @return $this
     * @internal
     */
    public function setRtbAuctionValidator(RtbAuctionValidator $rtbAuctionValidator): static
    {
        $this->rtbAuctionValidator = $rtbAuctionValidator;
        return $this;
    }
}
