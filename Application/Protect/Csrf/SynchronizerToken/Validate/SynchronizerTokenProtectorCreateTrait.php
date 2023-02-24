<?php
/**
 * Trait for SynchronizerTokenProtector
 *
 * SAM-5296: CSRF/XSRF Cross Site Request Forgery vulnerability
 * SAM-5675: Refactor Synchronizer token related logic and implement unit tests
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Tom Blondeau
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           12/10/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Protect\Csrf\SynchronizerToken\Validate;

/**
 * Trait SynchronizerTokenProtectorCreateTrait
 * @package Sam\Application\Protect\Csrf\SynchronizerToken;
 */
trait SynchronizerTokenProtectorCreateTrait
{
    /**
     * @var SynchronizerTokenProtector|null
     */
    protected ?SynchronizerTokenProtector $synchronizerTokenProtector = null;

    /**
     * @return SynchronizerTokenProtector
     */
    protected function createSynchronizerTokenProtector(): SynchronizerTokenProtector
    {
        return $this->synchronizerTokenProtector ?: SynchronizerTokenProtector::new();
    }

    /**
     * @param SynchronizerTokenProtector
     * @return static
     * @internal
     */
    public function setSynchronizerTokenProtector(SynchronizerTokenProtector $synchronizerTokenProtector): static
    {
        $this->synchronizerTokenProtector = $synchronizerTokenProtector;
        return $this;
    }
}
