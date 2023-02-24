<?php
/**
 * SAM-5397: Token Link SSO
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/20/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\TokenLink\Auth;

/**
 * Trait TokenLinkAuthenticatorCreateTrait
 * @package
 */
trait TokenLinkAuthenticatorCreateTrait
{
    protected ?TokenLinkAuthenticator $tokenLinkAuthenticator = null;

    /**
     * @return TokenLinkAuthenticator
     */
    protected function createTokenLinkAuthenticator(): TokenLinkAuthenticator
    {
        $tokenLinkAuthenticator = $this->tokenLinkAuthenticator ?: TokenLinkAuthenticator::new();
        return $tokenLinkAuthenticator;
    }

    /**
     * @param TokenLinkAuthenticator $tokenLinkAuthenticator
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setTokenLinkAuthenticator(TokenLinkAuthenticator $tokenLinkAuthenticator): static
    {
        $this->tokenLinkAuthenticator = $tokenLinkAuthenticator;
        return $this;
    }
}
