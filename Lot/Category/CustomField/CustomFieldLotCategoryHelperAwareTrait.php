<?php

namespace Sam\Lot\Category\CustomField;


/**
 * Trait CustomFieldLotCategoryHelperAwareTrait
 * @package Sam\Lot\Category\CustomField
 */
trait CustomFieldLotCategoryHelperAwareTrait
{
    /**
     * @var CustomFieldLotCategoryHelper|null
     */
    protected ?CustomFieldLotCategoryHelper $customFieldLotCategoryHelper = null;

    /**
     * @return CustomFieldLotCategoryHelper
     */
    protected function getCustomFieldLotCategoryHelper(): CustomFieldLotCategoryHelper
    {
        if ($this->customFieldLotCategoryHelper === null) {
            $this->customFieldLotCategoryHelper = CustomFieldLotCategoryHelper::new();
        }
        return $this->customFieldLotCategoryHelper;
    }

    /**
     * @param CustomFieldLotCategoryHelper $customFieldLotCategoryHelper
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setCustomFieldLotCategoryHelper(CustomFieldLotCategoryHelper $customFieldLotCategoryHelper): static
    {
        $this->customFieldLotCategoryHelper = $customFieldLotCategoryHelper;
        return $this;
    }
}
