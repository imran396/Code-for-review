<?php
/**
 * SAM-2776: Optimize user csv export
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\User\UserList\Csv\Internal\Load;

/**
 * Trait ChunkDataProviderCreateTrait
 * @package Sam\Report\User\UserList\Csv\Internal\Load
 */
trait ChunkDataProviderCreateTrait
{
    protected ?ChunkDataProvider $chunkDataProvider = null;

    /**
     * @return ChunkDataProvider
     */
    protected function createChunkDataProvider(): ChunkDataProvider
    {
        return $this->chunkDataProvider ?: ChunkDataProvider::new();
    }

    /**
     * @param ChunkDataProvider $chunkDataProvider
     * @return static
     * @internal
     */
    public function setChunkDataProvider(ChunkDataProvider $chunkDataProvider): static
    {
        $this->chunkDataProvider = $chunkDataProvider;
        return $this;
    }
}
