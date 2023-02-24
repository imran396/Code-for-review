<?php
/**
 * SAM-5041: Soap API RegisterBidder improvement
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\Prerequisite;

/**
 * Trait RegisterBidderPrerequisiteInitializerCreateTrait
 * @package
 */
trait RegisterBidderPrerequisiteInitializerCreateTrait
{
    protected ?RegisterBidderPrerequisiteInitializer $registerBidderPrerequisiteInitializer = null;

    /**
     * @return RegisterBidderPrerequisiteInitializer
     */
    protected function createRegisterBidderPrerequisiteInitializer(): RegisterBidderPrerequisiteInitializer
    {
        return $this->registerBidderPrerequisiteInitializer ?: RegisterBidderPrerequisiteInitializer::new();
    }

    /**
     * @param RegisterBidderPrerequisiteInitializer $initializer
     * @return $this
     * @internal
     */
    public function setRegisterBidderPrerequisiteInitializer(RegisterBidderPrerequisiteInitializer $initializer): static
    {
        $this->registerBidderPrerequisiteInitializer = $initializer;
        return $this;
    }
}
