<?php
/**
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Internal\Resolve;

/**
 * Trait AccountFromUrlConfigResolverCreateTrait
 * @package Sam\Application\Url\Internal\Resolve
 */
trait AccountFromUrlConfigResolverCreateTrait
{
    protected ?AccountFromUrlConfigResolver $accountFromUrlConfigResolver = null;

    /**
     * @return AccountFromUrlConfigResolver
     */
    protected function createAccountFromUrlConfigResolver(): AccountFromUrlConfigResolver
    {
        return $this->accountFromUrlConfigResolver ?: AccountFromUrlConfigResolver::new();
    }

    /**
     * @param AccountFromUrlConfigResolver $accountFromUrlConfigResolver
     * @return $this
     * @internal
     */
    public function setAccountFromUrlConfigResolver(AccountFromUrlConfigResolver $accountFromUrlConfigResolver): static
    {
        $this->accountFromUrlConfigResolver = $accountFromUrlConfigResolver;
        return $this;
    }
}
