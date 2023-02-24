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

namespace Sam\EntityMaker\LotItem\Lock\CustomField\Internal\Detect;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomDataLoaderCreateTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\EntityMaker\Base\Common\CustomFieldHelperCreateTrait;
use Sam\EntityMaker\LotItem\Lock\CustomField\Internal\Detect\LotItemUniqueCustomFieldLockRequirementCheckingResult as Result;

/**
 * Class LotItemUniqueCustomFieldLockRequirementChecker
 * @package Sam\EntityMaker\LotItem\Lock\CustomField\Internal\Detect
 */
class LotItemUniqueCustomFieldLockRequirementChecker extends CustomizableClass
{
    use CustomFieldHelperCreateTrait;
    use LotCustomDataLoaderCreateTrait;
    use LotCustomFieldLoaderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function check(?int $lotItemId, array $customFieldsInput): Result
    {
        $result = Result::new()->construct($lotItemId, $customFieldsInput);
        $uniqueCustomFields = $this->createLotCustomFieldLoader()->loadForLotByType([Constants\CustomField::TYPE_TEXT], true);
        $customFieldHelper = $this->createCustomFieldHelper();
        $isUniqueCustomFieldsPresentInInput = false;
        foreach ($uniqueCustomFields as $uniqueCustomField) {
            $dtoFieldName = $customFieldHelper->makeCustomFieldsTagName($uniqueCustomField->Name);
            $customFieldValue = $customFieldsInput[$dtoFieldName] ?? null;
            if (!$customFieldValue) {
                continue;
            }

            $isUniqueCustomFieldsPresentInInput = true;

            if ($this->isCustomFieldValueModified($lotItemId, $uniqueCustomField->Id, $customFieldValue)) {
                $result->addSuccess(Result::OK_LOCK_BECAUSE_UNIQUE_CUSTOM_FIELDS_VALUE_CHANGED, $uniqueCustomField->Name);
            } else {
                $result->addInfo(Result::INFO_DO_NOT_LOCK_BECAUSE_UNIQUE_CUSTOM_FIELDS_VALUE_EQUAL_TO_EXISTING, $uniqueCustomField->Name);
            }
        }

        if (!$isUniqueCustomFieldsPresentInInput) {
            $result->addInfo(Result::INFO_DO_NOT_LOCK_BECAUSE_UNIQUE_CUSTOM_FIELDS_ABSENT_IN_INPUT);
        }

        return $result;
    }

    protected function isCustomFieldValueModified(?int $lotItemId, int $customFieldId, string $value): bool
    {
        if (!$lotItemId) {
            return true;
        }
        $customData = $this->createLotCustomDataLoader()->load($customFieldId, $lotItemId);
        return !$customData || $customData->Text !== $value;
    }
}
