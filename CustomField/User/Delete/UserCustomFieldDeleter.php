<?php
/**
 * Helper class for custom field user fields
 * SAM-3632: User delete class https://bidpath.atlassian.net/browse/SAM-3632
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @since           May 11, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * @property string Encoding
 */

namespace Sam\CustomField\User\Delete;

use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\User\Translate\UserCustomFieldTranslationManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\UserCustField\UserCustFieldReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\UserCustField\UserCustFieldWriteRepositoryAwareTrait;
use UserCustField;

/**
 * Class DataDeleter
 * @package Sam\CustomField\User\Delete
 */
class UserCustomFieldDeleter extends CustomizableClass
{
    use UserCustFieldReadRepositoryCreateTrait;
    use UserCustFieldWriteRepositoryAwareTrait;
    use UserCustomDataDeleterCreateTrait;
    use UserCustomFieldTranslationManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Perform deleting actions for custom field and related data
     *
     * @param int $userCustomFieldId
     * @param int $editorUserId
     * @return void
     */
    public function deleteById(int $userCustomFieldId, int $editorUserId): void
    {
        $userCustomField = $this->createUserCustFieldReadRepository()
            ->filterId($userCustomFieldId)
            ->loadEntity();
        if ($userCustomField) {
            $this->delete($userCustomField, $editorUserId);
        }
    }

    /**
     * Perform deleting actions for custom field and related data
     *
     * @param UserCustField $userCustomField
     * @param int $editorUserId
     * @return void
     */
    public function delete(UserCustField $userCustomField, int $editorUserId): void
    {
        $userCustomField->Active = false;
        $this->getUserCustFieldWriteRepository()->saveWithModifier($userCustomField, $editorUserId);
        $this->createUserCustomDataDeleter()->deleteForFieldId($userCustomField->Id, $editorUserId);
        $this->getUserCustomFieldTranslationManager()->delete($userCustomField);
    }
}
