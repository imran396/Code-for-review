<?php
/**
 * SAM-5296: CSRF/XSRF Cross Site Request Forgery vulnerability
 * SAM-5675: Refactor Synchronizer token related logic and implement unit tests
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/2/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Protect\Csrf\SynchronizerToken\Validate;

/**
 * Trait SynchronizerTokenValidatorCreateTrait
 * @package
 */
trait SynchronizerTokenValidatorCreateTrait
{
    /**
     * @var SynchronizerTokenValidator|null
     */
    protected ?SynchronizerTokenValidator $synchronizerTokenValidator = null;

    /**
     * @return SynchronizerTokenValidator
     */
    protected function createSynchronizerTokenValidator(): SynchronizerTokenValidator
    {
        return $this->synchronizerTokenValidator ?: SynchronizerTokenValidator::new();
    }

    /**
     * @param SynchronizerTokenValidator $synchronizerTokenValidator
     * @return static
     * @internal
     */
    public function setSynchronizerTokenValidator(SynchronizerTokenValidator $synchronizerTokenValidator): static
    {
        $this->synchronizerTokenValidator = $synchronizerTokenValidator;
        return $this;
    }
}
