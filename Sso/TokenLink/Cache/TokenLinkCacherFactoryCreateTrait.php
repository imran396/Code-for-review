<?php
/**
 * SAM-5397: Token Link SSO
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/18/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\TokenLink\Cache;

/**
 * Trait TokenLinkCacherFactoryCreateTrait
 * @package
 */
trait TokenLinkCacherFactoryCreateTrait
{
    protected ?TokenLinkCacherFactory $tokenLinkCacherFactory = null;

    /**
     * @return TokenLinkCacherFactory
     */
    protected function createTokenLinkCacherFactory(): TokenLinkCacherFactory
    {
        $tokenLinkCacherFactory = $this->tokenLinkCacherFactory ?: TokenLinkCacherFactory::new();
        return $tokenLinkCacherFactory;
    }

    /**
     * @param TokenLinkCacherFactory $tokenLinkCacherFactory
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setTokenLinkCacherFactory(TokenLinkCacherFactory $tokenLinkCacherFactory): static
    {
        $this->tokenLinkCacherFactory = $tokenLinkCacherFactory;
        return $this;
    }
}
