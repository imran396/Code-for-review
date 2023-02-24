<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/25/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Server\Auth;

/**
 * Trait AuthenticatorCreateTrait
 * @package
 */
trait AuthenticatorAwareTrait
{
    /**
     * @var Authenticator|null
     */
    protected ?Authenticator $authenticator = null;

    /**
     * @return Authenticator
     */
    protected function getAuthenticator(): Authenticator
    {
        if ($this->authenticator === null) {
            $this->authenticator = Authenticator::new();
        }
        return $this->authenticator;
    }

    /**
     * @param Authenticator $authenticator
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setAuthenticator(Authenticator $authenticator): static
    {
        $this->authenticator = $authenticator;
        return $this;
    }
}
