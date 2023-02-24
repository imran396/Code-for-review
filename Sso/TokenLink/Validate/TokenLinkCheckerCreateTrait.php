<?php
/**
 * SAM-5397: Token Link SSO
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/19/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\TokenLink\Validate;

/**
 * Trait TokenLinkCheckerCreateTrait
 * @package
 */
trait TokenLinkCheckerCreateTrait
{
    protected ?TokenLinkChecker $tokenLinkChecker = null;

    /**
     * @return TokenLinkChecker
     */
    protected function createTokenLinkChecker(): TokenLinkChecker
    {
        $tokenLinkChecker = $this->tokenLinkChecker ?: TokenLinkChecker::new();
        return $tokenLinkChecker;
    }

    /**
     * @param TokenLinkChecker $tokenLinkChecker
     * @return static
     * @internal
     */
    public function setTokenLinkChecker(TokenLinkChecker $tokenLinkChecker): static
    {
        $this->tokenLinkChecker = $tokenLinkChecker;
        return $this;
    }
}
