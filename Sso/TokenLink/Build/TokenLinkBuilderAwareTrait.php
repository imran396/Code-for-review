<?php
/**
 * SAM-5397: Token Link
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/21/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\TokenLink\Build;

/**
 * Trait TokenLinkBuilderAwareTrait
 * @package
 */
trait TokenLinkBuilderAwareTrait
{
    protected ?TokenLinkBuilder $tokenLinkBuilder = null;

    /**
     * @return TokenLinkBuilder
     */
    protected function getTokenLinkBuilder(): TokenLinkBuilder
    {
        if ($this->tokenLinkBuilder === null) {
            $this->tokenLinkBuilder = TokenLinkBuilder::new();
        }
        return $this->tokenLinkBuilder;
    }

    /**
     * @param TokenLinkBuilder $tokenLinkBuilder
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setTokenLinkBuilder(TokenLinkBuilder $tokenLinkBuilder): static
    {
        $this->tokenLinkBuilder = $tokenLinkBuilder;
        return $this;
    }
}
