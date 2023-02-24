<?php
/**
 * SAM-5412: Validations at controller layer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/24/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Auction\Validate\Registration;

/**
 * Trait AuctionRegistrationControllerValidatorCreateTrait
 * @package Sam\Application\Controller\Responsive\Auction
 */
trait AuctionRegistrationControllerValidatorCreateTrait
{
    /**
     * @var AuctionRegistrationControllerValidator|null
     */
    protected ?AuctionRegistrationControllerValidator $auctionRegistrationControllerValidator = null;

    /**
     * @return AuctionRegistrationControllerValidator
     */
    protected function createAuctionRegistrationControllerValidator(): AuctionRegistrationControllerValidator
    {
        return $this->auctionRegistrationControllerValidator ?: AuctionRegistrationControllerValidator::new();
    }

    /**
     * @param AuctionRegistrationControllerValidator $auctionRegistrationControllerValidator
     * @return static
     * @internal
     */
    public function setAuctionRegistrationControllerValidator(AuctionRegistrationControllerValidator $auctionRegistrationControllerValidator): static
    {
        $this->auctionRegistrationControllerValidator = $auctionRegistrationControllerValidator;
        return $this;
    }
}
