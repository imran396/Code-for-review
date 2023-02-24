<?php
/**
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           12/16/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\User\Save;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\User\Translate\UserCustomFieldTranslationManagerAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserCustData\UserCustDataWriteRepositoryAwareTrait;
use UserCustData;
use UserCustField;

/**
 * Class UserCustomDataProducer
 * @package
 */
class UserCustomDataProducer extends CustomizableClass
{
    use UserCustDataWriteRepositoryAwareTrait;
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
     * Create a new instance, initialized with passed user, custom field ids and default values, then persist.
     *
     * @param UserCustField $userCustomField
     * @param int|null $userId can be null if user is not authenticated
     * @param int $editorUserId
     * @param bool $isTranslating
     * @return UserCustData
     */
    public function produce(
        UserCustField $userCustomField,
        ?int $userId,
        int $editorUserId,
        bool $isTranslating = false
    ): UserCustData {
        $userCustomData = $this->create($userCustomField, $userId, $isTranslating);
        $this->getUserCustDataWriteRepository()->saveWithModifier($userCustomData, $editorUserId);
        return $userCustomData;
    }

    /**
     * Create a new instance, initialized with passed user, custom field ids and default values. DO NOT PERSIST.
     *
     * @param UserCustField $userCustomField
     * @param int|null $userId can be null if user is not authenticated
     * @param bool $isTranslating
     * @return UserCustData
     */
    public function create(
        UserCustField $userCustomField,
        ?int $userId,
        bool $isTranslating = false
    ): UserCustData {
        $userCustomData = new UserCustData();
        $userCustomData->UserId = $userId;
        $userCustomData->UserCustFieldId = $userCustomField->Id;
        $userCustomData->Active = true;
        $parameters = $isTranslating
            ? $this->getUserCustomFieldTranslationManager()->translateParameters($userCustomField)
            : $userCustomField->Parameters;
        if (in_array(
            $userCustomField->Type,
            [Constants\CustomField::TYPE_INTEGER, Constants\CustomField::TYPE_CHECKBOX],
            true
        )
        ) {
            if (ctype_digit($parameters)) {
                $userCustomData->Numeric = (int)$parameters;
            }
        } elseif (in_array(
            $userCustomField->Type,
            [
                Constants\CustomField::TYPE_TEXT,
                Constants\CustomField::TYPE_FULLTEXT,
                Constants\CustomField::TYPE_PASSWORD,
            ],
            true
        )
        ) {
            $userCustomData->Text = $parameters;
        }

        return $userCustomData;
    }
}
