<?php
/**
 * SAM-6412: Token Link SSO session invalidation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\TokenLink\Auth;

/**
 * Trait TokenLinkAuthCallbackCreateTrait
 * @package Sam\Sso\TokenLink\Auth
 */
trait TokenLinkAuthCallbackCreateTrait
{
    protected ?TokenLinkAuthCallback $tokenLinkAuthCallback = null;

    /**
     * @return TokenLinkAuthCallback
     */
    protected function createTokenLinkAuthCallback(): TokenLinkAuthCallback
    {
        return $this->tokenLinkAuthCallback ?: TokenLinkAuthCallback::new();
    }

    /**
     * @param TokenLinkAuthCallback $tokenLinkAuthCallback
     * @return static
     * @internal
     */
    public function setTokenLinkAuthCallback(TokenLinkAuthCallback $tokenLinkAuthCallback): static
    {
        $this->tokenLinkAuthCallback = $tokenLinkAuthCallback;
        return $this;
    }
}
