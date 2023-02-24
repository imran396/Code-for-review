<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 11, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer;

/**
 * Trait EntityObserverHelperCreateTrait
 * @package Sam\Observer
 */
trait EntityObserverHelperCreateTrait
{
    protected ?EntityObserverHelper $entityObserverHelper = null;

    /**
     * @return EntityObserverHelper
     */
    protected function createEntityObserverHelper(): EntityObserverHelper
    {
        return $this->entityObserverHelper ?: EntityObserverHelper::new();
    }

    /**
     * @param EntityObserverHelper $entityObserverHelper
     * @return static
     * @internal
     */
    public function setEntityObserverHelper(EntityObserverHelper $entityObserverHelper): static
    {
        $this->entityObserverHelper = $entityObserverHelper;
        return $this;
    }
}
