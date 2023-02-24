<?php
/**
 * SAM-10663: Remove core->observers from installation config
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 19, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer;

/**
 * Trait EntityObserverProviderCreateTrait
 * @package Sam\Observer
 */
trait EntityObserverProviderCreateTrait
{
    protected ?EntityObserverProvider $entityObserverProvider = null;

    /**
     * @return EntityObserverProvider
     */
    protected function createEntityObserverProvider(): EntityObserverProvider
    {
        return $this->entityObserverProvider ?: EntityObserverProvider::new();
    }

    /**
     * @param EntityObserverProvider $entityObserverProvider
     * @return static
     * @internal
     */
    public function setEntityObserverProvider(EntityObserverProvider $entityObserverProvider): static
    {
        $this->entityObserverProvider = $entityObserverProvider;
        return $this;
    }
}
