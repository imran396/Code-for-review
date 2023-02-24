<?php
/**
 * SAM-10589: Supply uniqueness of lot item fields: lot custom fields
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 09, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Common;

/**
 * Trait CustomFieldHelperCreateTrait
 * @package Sam\EntityMaker\Base\Common
 */
trait CustomFieldHelperCreateTrait
{
    protected ?CustomFieldHelper $customFieldHelper = null;

    /**
     * @return CustomFieldHelper
     */
    protected function createCustomFieldHelper(): CustomFieldHelper
    {
        return $this->customFieldHelper ?: CustomFieldHelper::new();
    }

    /**
     * @param CustomFieldHelper $customFieldHelper
     * @return static
     * @internal
     */
    public function setCustomFieldHelper(CustomFieldHelper $customFieldHelper): static
    {
        $this->customFieldHelper = $customFieldHelper;
        return $this;
    }
}
