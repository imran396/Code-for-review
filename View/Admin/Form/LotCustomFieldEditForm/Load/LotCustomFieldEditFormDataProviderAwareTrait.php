<?php
/**
 * SAM-6308: Refactor custom field management to separate modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul. 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotCustomFieldEditForm\Load;

/**
 * Trait LotCustomFieldEditFormDataProviderAwareTrait
 * @package Sam\View\Admin\Form\LotCustomFieldEditForm\Load
 */
trait LotCustomFieldEditFormDataProviderAwareTrait
{
    protected ?LotCustomFieldEditFormDataProvider $lotCustomFieldEditFormDataProvider = null;

    /**
     * @return LotCustomFieldEditFormDataProvider
     */
    protected function getLotCustomFieldEditFormDataProvider(): LotCustomFieldEditFormDataProvider
    {
        if ($this->lotCustomFieldEditFormDataProvider === null) {
            $this->lotCustomFieldEditFormDataProvider = LotCustomFieldEditFormDataProvider::new();
        }
        return $this->lotCustomFieldEditFormDataProvider;
    }

    /**
     * @param LotCustomFieldEditFormDataProvider $lotCustomFieldEditFormDataProvider
     * @return static
     * @internal
     */
    public function setLotCustomFieldEditFormDataProvider(LotCustomFieldEditFormDataProvider $lotCustomFieldEditFormDataProvider): static
    {
        $this->lotCustomFieldEditFormDataProvider = $lotCustomFieldEditFormDataProvider;
        return $this;
    }
}
