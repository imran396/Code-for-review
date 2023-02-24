<?php
/**
 * Helper class for custom field user data
 * SAM-3632: User delete class https://bidpath.atlassian.net/browse/SAM-3632
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: Data.php 14125 2013-08-12 09:21:26Z SWB\igors $
 * @since           May 12, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * @property string Encoding
 */

namespace Sam\CustomField\User\Delete;

use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityObserverProviderCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserCustData\UserCustDataReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\UserCustData\UserCustDataWriteRepositoryAwareTrait;
use UserCustData;

/**
 * Class DataDeleter
 * @package Sam\CustomField\User\Delete
 */
class UserCustomDataDeleter extends CustomizableClass
{
    use DbConnectionTrait;
    use EntityObserverProviderCreateTrait;
    use UserCustDataReadRepositoryCreateTrait;
    use UserCustDataWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Delete all data records for user
     *
     * @param int $userId
     * @param int $editorUserId
     * @return void
     */
    public function deleteForUserId(int $userId, int $editorUserId): void
    {
        if ($this->createEntityObserverProvider()->hasObservers(UserCustData::class)) {
            $this->deleteForUserIdWithObserver($userId, $editorUserId);
        } else {
            $this->deleteForUserIdSkipObserver($userId, $editorUserId);
        }
    }

    /**
     * @param int $userId
     * @param int $editorUserId
     * @return void
     */
    protected function deleteForUserIdWithObserver(int $userId, int $editorUserId): void
    {
        $userCustomDatas = $this->createUserCustDataReadRepository()
            ->filterUserId($userId)
            ->loadEntities();
        foreach ($userCustomDatas as $userCustomData) {
            $userCustomData->Active = false;
            $this->getUserCustDataWriteRepository()->saveWithModifier($userCustomData, $editorUserId);
        }
    }

    /**
     * @param int $userId
     * @param int $editorUserId
     * @return void
     */
    protected function deleteForUserIdSkipObserver(int $userId, int $editorUserId): void
    {
        $query = 'UPDATE user_cust_data SET `active` = FALSE, `modified_by` = ' . $this->escape($editorUserId)
            . ' WHERE user_id = ' . $this->escape($userId);
        $this->nonQuery($query);
    }

    /**
     * Delete all data records for user custom field
     * @param int $fieldId
     * @param int $editorUserId
     * @return void
     */
    public function deleteForFieldId(int $fieldId, int $editorUserId): void
    {
        if ($this->createEntityObserverProvider()->hasObservers(UserCustData::class)) {
            $this->deleteForFieldIdWithObserver($fieldId, $editorUserId);
        } else {
            $this->deleteForFieldIdSkipObserver($fieldId, $editorUserId);
        }
    }


    /**
     * @param int $fieldId
     * @param int $editorUserId
     * @return void
     */
    protected function deleteForFieldIdWithObserver(int $fieldId, int $editorUserId): void
    {
        $repo = $this->createUserCustDataReadRepository()
            ->filterUserCustFieldId($fieldId)
            ->setChunkSize(200);
        while ($userCustomDatas = $repo->loadEntities()) {
            foreach ($userCustomDatas as $userCustomData) {
                $userCustomData->Active = false;
                $this->getUserCustDataWriteRepository()->saveWithModifier($userCustomData, $editorUserId);
            }
        }
    }

    /**
     * @param int $fieldId
     * @param int $editorUserId
     * @return void
     */
    protected function deleteForFieldIdSkipObserver(int $fieldId, int $editorUserId): void
    {
        $query = 'UPDATE user_cust_data SET `active` = FALSE, `modified_by` = ' . $this->escape($editorUserId)
            . ' WHERE user_cust_field_id = ' . $this->escape($fieldId);
        $this->nonQuery($query);
    }
}
