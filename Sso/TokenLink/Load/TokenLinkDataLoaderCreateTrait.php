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

namespace Sam\Sso\TokenLink\Load;

/**
 * Trait TokenLinkDataLoaderCreateTrait
 * @package
 */
trait TokenLinkDataLoaderCreateTrait
{
    protected ?TokenLinkDataLoader $tokenLinkDataLoader = null;

    /**
     * @return TokenLinkDataLoader
     */
    protected function createTokenLinkDataLoader(): TokenLinkDataLoader
    {
        $tokenLinkDataLoader = $this->tokenLinkDataLoader ?: TokenLinkDataLoader::new();
        return $tokenLinkDataLoader;
    }

    /**
     * @param TokenLinkDataLoader $tokenLinkDataLoader
     * @return static
     * @internal
     */
    public function setTokenLinkDataLoader(TokenLinkDataLoader $tokenLinkDataLoader): static
    {
        $this->tokenLinkDataLoader = $tokenLinkDataLoader;
        return $this;
    }
}
