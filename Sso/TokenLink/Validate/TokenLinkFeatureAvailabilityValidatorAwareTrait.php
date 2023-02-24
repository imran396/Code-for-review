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
 * Trait TokenLinkFeatureAvailabilityValidatorAwareTrait
 * @package
 */
trait TokenLinkFeatureAvailabilityValidatorAwareTrait
{
    protected ?TokenLinkFeatureAvailabilityValidator $tokenLinkFeatureAvailabilityValidator = null;

    /**
     * @return TokenLinkFeatureAvailabilityValidator
     */
    protected function getTokenLinkFeatureAvailabilityValidator(): TokenLinkFeatureAvailabilityValidator
    {
        if ($this->tokenLinkFeatureAvailabilityValidator === null) {
            $this->tokenLinkFeatureAvailabilityValidator = TokenLinkFeatureAvailabilityValidator::new();
        }
        return $this->tokenLinkFeatureAvailabilityValidator;
    }

    /**
     * @param TokenLinkFeatureAvailabilityValidator $tokenLinkFeatureAvailabilityValidator
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setTokenLinkFeatureAvailabilityValidator(TokenLinkFeatureAvailabilityValidator $tokenLinkFeatureAvailabilityValidator): static
    {
        $this->tokenLinkFeatureAvailabilityValidator = $tokenLinkFeatureAvailabilityValidator;
        return $this;
    }
}
