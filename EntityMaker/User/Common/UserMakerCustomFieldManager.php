<?php
/**
 * Contains all the logic of working with custom fields
 *
 * SAM-8841: User entity-maker module structural adjustments for v3-5
 * SAM-4989: User Entity Maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 12, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Common;

use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\EntityMaker\Base\Common\CustomFieldManager;
use Sam\CustomField\User\Load\UserCustomDataLoaderAwareTrait;
use Sam\CustomField\User\Load\UserCustomFieldLoaderAwareTrait;
use Sam\EntityMaker\User\Dto\UserMakerConfigDto;
use Sam\EntityMaker\User\Dto\UserMakerInputDto;
use Sam\Settings\User\UserSettingCheckerCreateTrait;
use UserCustData;
use UserCustField;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class UserMakerCustomFieldManager
 * @package Sam\EntityMaker\User
 * @method UserMakerInputDto getInputDto()
 * @method UserMakerConfigDto getConfigDto()
 */
class UserMakerCustomFieldManager extends CustomFieldManager
{
    use EntityFactoryCreateTrait;
    use UserCustomDataLoaderAwareTrait;
    use UserCustomFieldLoaderAwareTrait;
    use UserLoaderAwareTrait;
    use UserSettingCheckerCreateTrait;

    /**
     * @var string[]
     */
    protected array $errorMessages = [
        self::CUSTOM_FIELD_DATE_ERROR => 'Invalid date',
        self::CUSTOM_FIELD_DECIMAL_ERROR => 'Should be numeric',
        self::CUSTOM_FIELD_FILE_ERROR => 'Invalid file extension',
        self::CUSTOM_FIELD_INTEGER_ERROR => 'Should be numeric integer',
        self::CUSTOM_FIELD_POSTAL_CODE_ERROR => 'Invalid format',
        self::CUSTOM_FIELD_REQUIRED_ERROR => 'Required',
        self::CUSTOM_FIELD_SELECT_INVALID_OPTION_ERROR => 'Has invalid option',
        self::CUSTOM_FIELD_TEXT_UNIQUE_ERROR => 'Value must be unique',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function findCustomFieldByName(string $name): ?UserCustField
    {
        foreach ($this->getAllCustomFields() as $customField) {
            if ($this->getCustomFieldsTagName($customField->Name) === $name) {
                return $customField;
            }
        }
        return null;
    }

    /**
     * Override parent checkCustomFieldRequired, don't check required billing/shipping custom fields in web-responsive
     * mode if billing/shipping panels are not showed
     * @param UserCustField $customField CustomField
     * @param string $value CustomField value
     */
    protected function checkCustomFieldRequired($customField, string $value): void
    {
        $configDto = $this->getConfigDto();
        /**
         * Required User custom fields should not be checked to be filled in admin web (SAM-7921)
         */
        if (
            $configDto->mode->isWebAdmin()
            || $configDto->mode->isSsoResponsive()
        ) {
            return;
        }

        if (
            $configDto->isConfirmPage
            && $customField->Panel !== Constants\UserCustomField::PANEL_SHIPPING
        ) {
            return;
        }

        if ($configDto->isSignupPage) {
            $userSettingChecker = $this->createUserSettingChecker();
            $isSimplifiedSignup = $userSettingChecker->isSimplifiedSignup();
            if (
                $isSimplifiedSignup
                && $customField->Panel === Constants\UserCustomField::PANEL_SHIPPING
            ) {
                return;
            }

            $isBillingShowed = $userSettingChecker->isIncludeBasicInfo()
                || $userSettingChecker->isIncludeBillingInfo()
                || $userSettingChecker->isIncludeCcInfo()
                || $userSettingChecker->isIncludeAchInfo();

            if (
                $isSimplifiedSignup
                && $customField->Panel === Constants\UserCustomField::PANEL_BILLING
                && !$isBillingShowed
            ) {
                return;
            }
        }

        parent::checkCustomFieldRequired($customField, $value);
    }

    /**
     * Load custom fields from user_cust_field table
     * @return UserCustField[]
     */
    protected function loadCustomFields(): array
    {
        return $this->getUserCustomFieldLoader()->loadAll();
    }

    /**
     * Load or create user custom data
     * @param int $userId
     * @param int $customFieldId
     * @return UserCustData
     */
    protected function loadCustomDataOrCreate(int $userId, int $customFieldId): UserCustData
    {
        if ($this->allCustomData === null) {
            $this->allCustomData = $this->getUserCustomDataLoader()->loadForUser($userId);
        }
        $customData = current(
            array_filter(
                $this->allCustomData,
                static function ($data) use ($customFieldId) {
                    return $data->UserCustFieldId === $customFieldId;
                }
            )
        );
        if ($customData) {
            return $customData;
        }

        $customData = $this->createEntityFactory()->userCustData();
        $customData->UserId = $userId;
        $customData->UserCustFieldId = $customFieldId;
        return $customData;
    }
}
