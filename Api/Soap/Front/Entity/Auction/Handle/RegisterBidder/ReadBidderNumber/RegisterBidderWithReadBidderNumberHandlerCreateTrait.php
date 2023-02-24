<?php
/**
 * SAM-5041: Soap API RegisterBidder improvement
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\ReadBidderNumber;

/**
 * Trait RegisterBidderWithReadBidderNumberHandlerCreateTrait
 * @package Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\ReadBidderNumber
 */
trait RegisterBidderWithReadBidderNumberHandlerCreateTrait
{
    protected ?RegisterBidderWithReadBidderNumberHandler $registerBidderWithReadBidderNumberHandler = null;

    /**
     * @return RegisterBidderWithReadBidderNumberHandler
     */
    protected function createRegisterBidderWithReadBidderNumberHandler(): RegisterBidderWithReadBidderNumberHandler
    {
        return $this->registerBidderWithReadBidderNumberHandler ?: RegisterBidderWithReadBidderNumberHandler::new();
    }

    /**
     * @param RegisterBidderWithReadBidderNumberHandler $handler
     * @return $this
     * @internal
     */
    public function setRegisterBidderWithReadBidderNumberHandler(RegisterBidderWithReadBidderNumberHandler $handler): static
    {
        $this->registerBidderWithReadBidderNumberHandler = $handler;
        return $this;
    }
}
