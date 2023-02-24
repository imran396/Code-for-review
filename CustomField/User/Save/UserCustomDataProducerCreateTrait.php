<?php
/**
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           12/16/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\User\Save;

/**
 * Trait UserCustomDataProducerCreateTrait
 * @package
 */
trait UserCustomDataProducerCreateTrait
{
    /**
     * @var UserCustomDataProducer|null
     */
    protected ?UserCustomDataProducer $userCustomDataProducer = null;

    /**
     * @return UserCustomDataProducer
     */
    protected function createUserCustomDataProducer(): UserCustomDataProducer
    {
        return $this->userCustomDataProducer ?: UserCustomDataProducer::new();
    }

    /**
     * @param UserCustomDataProducer $userCustomDataProducer
     * @return static
     * @internal
     */
    public function setUserCustomDataProducer(UserCustomDataProducer $userCustomDataProducer): static
    {
        $this->userCustomDataProducer = $userCustomDataProducer;
        return $this;
    }
}
