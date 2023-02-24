<?php
/**
 * SAM-6721: Apply WriteRepository and unit tests to Add New Bidder registrator command services
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Save;

/**
 * Trait UserMakerProducerCreateTrait
 * @package Sam\EntityMaker\User
 */
trait UserMakerProducerCreateTrait
{
    /**
     * @var UserMakerProducer|null
     */
    protected ?UserMakerProducer $userMakerProducer = null;

    /**
     * @return UserMakerProducer
     */
    protected function createUserMakerProducer(): UserMakerProducer
    {
        return $this->userMakerProducer ?: UserMakerProducer::new();
    }

    /**
     * @param UserMakerProducer $userMakerProducer
     * @return static
     * @internal
     */
    public function setUserMakerProducer(UserMakerProducer $userMakerProducer): static
    {
        $this->userMakerProducer = $userMakerProducer;
        return $this;
    }
}
