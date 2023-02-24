<?php
/**
 * SAM-4158: Move encryption logic and replace QCryptography with modern library
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Security\Crypt;

/**
 * Trait BlockCipherProviderCreateTrait
 * @package Sam\Security\Crypt
 */
trait BlockCipherProviderCreateTrait
{
    /**
     * @var BlockCipherProvider|null
     */
    protected ?BlockCipherProvider $blockCipherProvider = null;

    /**
     * @return BlockCipherProvider
     */
    protected function createBlockCipherProvider(): BlockCipherProvider
    {
        return $this->blockCipherProvider ?: BlockCipherProvider::new();
    }

    /**
     * @param BlockCipherProvider $blockCipherProvider
     * @return static
     * @internal
     */
    public function setBlockCipherProvider(BlockCipherProvider $blockCipherProvider): static
    {
        $this->blockCipherProvider = $blockCipherProvider;
        return $this;
    }
}
