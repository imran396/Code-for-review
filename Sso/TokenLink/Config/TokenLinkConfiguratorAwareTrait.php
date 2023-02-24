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

namespace Sam\Sso\TokenLink\Config;

/**
 * Trait TokenLinkConfiguratorAwareTrait
 * @package
 */
trait TokenLinkConfiguratorAwareTrait
{
    protected ?TokenLinkConfigurator $tokenLinkConfigurator = null;

    /**
     * @return TokenLinkConfigurator
     */
    protected function getTokenLinkConfigurator(): TokenLinkConfigurator
    {
        if ($this->tokenLinkConfigurator === null) {
            $this->tokenLinkConfigurator = TokenLinkConfigurator::new();
        }
        return $this->tokenLinkConfigurator;
    }

    /**
     * @param TokenLinkConfigurator $tokenLinkConfigurator
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setTokenLinkConfigurator(TokenLinkConfigurator $tokenLinkConfigurator): static
    {
        $this->tokenLinkConfigurator = $tokenLinkConfigurator;
        return $this;
    }
}
