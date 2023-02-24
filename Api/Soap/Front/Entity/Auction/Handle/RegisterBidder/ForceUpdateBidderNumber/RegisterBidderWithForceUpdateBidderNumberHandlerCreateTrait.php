<?php
/**
 * SAM-5041: Soap API RegisterBidder improvement
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\ForceUpdateBidderNumber;

/**
 * Trait RegisterBidderWithForceUpdateBidderNumberHandlerCreateTrait
 * @package Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\ForceUpdateBidderNumber
 */
trait RegisterBidderWithForceUpdateBidderNumberHandlerCreateTrait
{
    protected ?RegisterBidderWithForceUpdateBidderNumberHandler $registerBidderWithForceUpdateBidderNumberHandler = null;

    /**
     * @return RegisterBidderWithForceUpdateBidderNumberHandler
     */
    protected function createRegisterBidderWithForceUpdateBidderNumberHandler(): RegisterBidderWithForceUpdateBidderNumberHandler
    {
        return $this->registerBidderWithForceUpdateBidderNumberHandler ?: RegisterBidderWithForceUpdateBidderNumberHandler::new();
    }

    /**
     * @param RegisterBidderWithForceUpdateBidderNumberHandler $handler
     * @return $this
     * @internal
     */
    public function setRegisterBidderWithForceUpdateBidderNumberHandler(RegisterBidderWithForceUpdateBidderNumberHandler $handler): static
    {
        $this->registerBidderWithForceUpdateBidderNumberHandler = $handler;
        return $this;
    }
}
