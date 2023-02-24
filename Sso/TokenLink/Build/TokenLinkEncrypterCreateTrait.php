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

namespace Sam\Sso\TokenLink\Build;

/**
 * Trait TokenLinkEncrypterCreateTrait
 * @package
 */
trait TokenLinkEncrypterCreateTrait
{
    protected ?TokenLinkEncrypter $tokenLinkEncrypter = null;

    /**
     * @return TokenLinkEncrypter
     */
    protected function createTokenLinkEncrypter(): TokenLinkEncrypter
    {
        $tokenLinkEncrypter = $this->tokenLinkEncrypter ?: TokenLinkEncrypter::new();
        return $tokenLinkEncrypter;
    }

    /**
     * @param TokenLinkEncrypter $tokenLinkEncrypter
     * @return static
     * @internal
     */
    public function setTokenLinkEncrypter(TokenLinkEncrypter $tokenLinkEncrypter): static
    {
        $this->tokenLinkEncrypter = $tokenLinkEncrypter;
        return $this;
    }
}
