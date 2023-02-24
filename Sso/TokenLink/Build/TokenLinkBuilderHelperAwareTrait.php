<?php
/**
 * SAM-5397: Token Link
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
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
 * Trait TokenLinkBuilderHelperAwareTrait
 * @package
 */
trait TokenLinkBuilderHelperAwareTrait
{
    protected ?TokenLinkBuilderHelper $tokenLinkBuilderHelper = null;

    /**
     * @return TokenLinkBuilderHelper
     */
    protected function getTokenLinkBuilderHelper(): TokenLinkBuilderHelper
    {
        if ($this->tokenLinkBuilderHelper === null) {
            $this->tokenLinkBuilderHelper = TokenLinkBuilderHelper::new();
        }
        return $this->tokenLinkBuilderHelper;
    }

    /**
     * @param TokenLinkBuilderHelper $tokenLinkBuilderHelper
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setTokenLinkBuilderHelper(TokenLinkBuilderHelper $tokenLinkBuilderHelper): static
    {
        $this->tokenLinkBuilderHelper = $tokenLinkBuilderHelper;
        return $this;
    }
}
