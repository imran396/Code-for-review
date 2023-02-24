<?php
/**
 * SAM-8837: Lot item entity maker module structural adjustments for v3-5
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Common;

use Sam\EntityMaker\LotCategory\Common\LotCategoryMakerCustomFieldManager;

/**
 * Trait CustomFieldManagerAwareTrait
 * @package Sam\EntityMaker\Base\Common
 */
trait CustomFieldManagerAwareTrait
{
    protected CustomFieldManager|LotCategoryMakerCustomFieldManager|null $customFieldManager = null;

    /**
     * @return CustomFieldManager|LotCategoryMakerCustomFieldManager|null
     */
    protected function getCustomFieldManager(): CustomFieldManager|LotCategoryMakerCustomFieldManager|null
    {
        return $this->customFieldManager;
    }

    /**
     * @param CustomFieldManager|LotCategoryMakerCustomFieldManager $customFieldManager
     * @return static
     * @internal
     */
    public function setCustomFieldManager(CustomFieldManager|LotCategoryMakerCustomFieldManager $customFieldManager): static
    {
        $this->customFieldManager = $customFieldManager;
        return $this;
    }
}
