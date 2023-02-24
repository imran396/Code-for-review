<?php
/**
 * SAM-6375: Failed authentication attempt response delay
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 07, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Auth\FailedLogin\Delay;

/**
 * Trait FailedLoginDelayerCreateTrait
 * @package
 */
trait FailedLoginDelayerCreateTrait
{
    protected ?FailedLoginDelayer $failedLoginDelayer = null;

    /**
     * @return FailedLoginDelayer
     */
    protected function createFailedLoginDelayer(): FailedLoginDelayer
    {
        return $this->failedLoginDelayer ?: FailedLoginDelayer::new();
    }

    /**
     * @param FailedLoginDelayer $failedLoginDelayer
     * @return $this
     * @internal
     */
    public function setFailedLoginDelayer(FailedLoginDelayer $failedLoginDelayer): static
    {
        $this->failedLoginDelayer = $failedLoginDelayer;
        return $this;
    }
}
