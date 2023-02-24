<?php
/**
 * SAM-3566: Refactoring for user authorization logic
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           3/21/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */


namespace Sam\User\Auth;


/**
 * Trait IpBlockerAwareTrait
 * @package
 */
trait IpBlockerAwareTrait
{
    protected ?IpBlocker $ipBlocker = null;

    /**
     * @return IpBlocker
     */
    protected function getIpBlocker(): IpBlocker
    {
        if ($this->ipBlocker === null) {
            $this->ipBlocker = IpBlocker::new();
        }
        return $this->ipBlocker;
    }

    /**
     * @param IpBlocker $ipBlocker
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setIpBlocker(IpBlocker $ipBlocker): static
    {
        $this->ipBlocker = $ipBlocker;
        return $this;
    }
}
