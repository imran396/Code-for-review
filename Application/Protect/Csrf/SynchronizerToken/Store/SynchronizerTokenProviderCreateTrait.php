<?php
/**
 * SAM-5675: Refactor Synchronizer token related logic and implement unit tests
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/1/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Protect\Csrf\SynchronizerToken\Store;

/**
 * Trait SynchronizerTokenProviderCreateTrait
 * @package
 */
trait SynchronizerTokenProviderCreateTrait
{
    /**
     * @var SynchronizerTokenProvider|null
     */
    protected ?SynchronizerTokenProvider $synchronizerTokenProvider = null;

    /**
     * @return SynchronizerTokenProvider
     */
    protected function createSynchronizerTokenProvider(): SynchronizerTokenProvider
    {
        return $this->synchronizerTokenProvider ?: SynchronizerTokenProvider::new();
    }

    /**
     * @param SynchronizerTokenProvider $synchronizerTokenProvider
     * @return static
     * @internal
     */
    public function setSynchronizerTokenProvider(SynchronizerTokenProvider $synchronizerTokenProvider): static
    {
        $this->synchronizerTokenProvider = $synchronizerTokenProvider;
        return $this;
    }
}
