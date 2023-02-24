<?php
/**
 * Trait for TokenLinkDecrypter
 *
 * SAM-5397: Token Link SSO
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/31/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\TokenLink\Build;

/**
 * Trait TokenLinkDecrypterCreateTrait
 * @package
 */
trait TokenLinkDecrypterCreateTrait
{
    protected ?TokenLinkDecrypter $tokenLinkDecrypter = null;

    /**
     * @return TokenLinkDecrypter
     */
    protected function createTokenLinkDecrypter(): TokenLinkDecrypter
    {
        $tokenLinkDecrypter = $this->tokenLinkDecrypter ?: TokenLinkDecrypter::new();
        return $tokenLinkDecrypter;
    }

    /**
     * @param TokenLinkDecrypter $tokenLinkDecrypter
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setTokenLinkDecrypter(TokenLinkDecrypter $tokenLinkDecrypter): static
    {
        $this->tokenLinkDecrypter = $tokenLinkDecrypter;
        return $this;
    }
}
