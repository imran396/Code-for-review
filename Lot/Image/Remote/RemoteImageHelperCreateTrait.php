<?php
/**
 * SAM-1700: Walmart - Bulk image upload enhancements
 * SAM-7918: Refactor \LotImage_BucketManager and image associators
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\Remote;

/**
 * Trait RemoteImageHelperCreateTrait
 * @package Sam\Lot\Image\Remote
 */
trait RemoteImageHelperCreateTrait
{
    /**
     * @var RemoteImageHelper|null
     */
    protected ?RemoteImageHelper $remoteImageHelper = null;

    /**
     * @return RemoteImageHelper
     */
    protected function createRemoteImageHelper(): RemoteImageHelper
    {
        return $this->remoteImageHelper ?: RemoteImageHelper::new();
    }

    /**
     * @param RemoteImageHelper $remoteImageHelper
     * @return static
     * @internal
     */
    public function setRemoteImageHelper(RemoteImageHelper $remoteImageHelper): static
    {
        $this->remoteImageHelper = $remoteImageHelper;
        return $this;
    }
}
