<?php
/**
 * Trait for TokenLinkSignatureProducer
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
 * Trait TokenLinkSignatureProducerCreateTrait
 * @package
 */
trait TokenLinkSignatureProducerCreateTrait
{
    protected ?TokenLinkSignatureProducer $tokenLinkSignatureProducer = null;

    /**
     * @return TokenLinkSignatureProducer
     */
    protected function createTokenLinkSignatureProducer(): TokenLinkSignatureProducer
    {
        $tokenLinkSignatureProducer = $this->tokenLinkSignatureProducer ?: TokenLinkSignatureProducer::new();
        return $tokenLinkSignatureProducer;
    }

    /**
     * @param TokenLinkSignatureProducer $tokenLinkSignatureProducer
     * @return static
     * @internal
     */
    public function setTokenLinkSignatureProducer(TokenLinkSignatureProducer $tokenLinkSignatureProducer): static
    {
        $this->tokenLinkSignatureProducer = $tokenLinkSignatureProducer;
        return $this;
    }
}
