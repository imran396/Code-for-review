<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           12/25/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Qform\LotList;

/**
 * Trait LotListCustomFieldAdminFilterHelperAwareTrait
 * @package Sam\Qform\LotList
 */
trait LotListCustomFieldAdminFilterHelperAwareTrait
{
    protected ?LotListCustomFieldAdminFilterHelper $lotListCustomFieldAdminFilterHelper = null;

    /**
     * @return LotListCustomFieldAdminFilterHelper
     */
    public function getLotListCustomFieldAdminFilterHelper(): LotListCustomFieldAdminFilterHelper
    {
        if ($this->lotListCustomFieldAdminFilterHelper === null) {
            $this->lotListCustomFieldAdminFilterHelper = LotListCustomFieldAdminFilterHelper::new();
        }
        return $this->lotListCustomFieldAdminFilterHelper;
    }

    /**
     * @param LotListCustomFieldAdminFilterHelper $lotListCustomFieldAdminFilterHelper
     * @return static
     */
    public function setLotListCustomFieldAdminFilterHelper(LotListCustomFieldAdminFilterHelper $lotListCustomFieldAdminFilterHelper): static
    {
        $this->lotListCustomFieldAdminFilterHelper = $lotListCustomFieldAdminFilterHelper;
        return $this;
    }
}
