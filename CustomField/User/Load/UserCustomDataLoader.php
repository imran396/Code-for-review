<?php
/**
 * Help methods for auction custom field data loading
 *
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 29, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\User\Load;

use Sam\Core\Load\EntityLoaderBase;
use Sam\CustomField\User\Save\UserCustomDataProducerCreateTrait;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserCustData\UserCustDataReadRepository;
use Sam\Storage\ReadRepository\Entity\UserCustData\UserCustDataReadRepositoryCreateTrait;
use UserCustData;
use UserCustField;

/**
 * Class UserCustomDataLoader
 * @package Sam\CustomField\User\Load
 */
class UserCustomDataLoader extends EntityLoaderBase
{
    use BlockCipherProviderCreateTrait;
    use UserCustDataReadRepositoryCreateTrait;
    use UserCustomDataProducerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load data of custom user field for user
     *
     * @param int $userCustomFieldId
     * @param int $userId
     * @param bool $isReadOnlyDb
     * @return UserCustData|null
     */
    public function load(int $userCustomFieldId, int $userId, bool $isReadOnlyDb = false): ?UserCustData
    {
        $userCustomData = $this->prepareRepository($isReadOnlyDb)
            ->filterUserId($userId)
            ->filterUserCustFieldId($userCustomFieldId)
            ->loadEntity();

        if (
            $userCustomData
            && $userCustomData->Encrypted
        ) {
            $userCustomData->Text = $this->createBlockCipherProvider()->construct()->decrypt($userCustomData->Text);
        }

        return $userCustomData;
    }

    /**
     * Load custom user field data object
     * or create a new (NOT PERSISTED) instance, initialized with passed user, custom field ids and default values
     *
     * @param UserCustField $userCustomField
     * @param int|null $userId can be null if user is not authenticated
     * @param bool $isTranslating
     * @param bool $isReadOnlyDb
     * @return UserCustData
     */
    public function loadOrCreate(
        UserCustField $userCustomField,
        ?int $userId,
        bool $isTranslating = false,
        bool $isReadOnlyDb = false
    ): UserCustData {
        $userCustomData = null;
        if ($userId) {
            $userCustomData = $this->load($userCustomField->Id, $userId, $isReadOnlyDb);
        }
        if (!$userCustomData) {
            $userCustomData = $this->createUserCustomDataProducer()
                ->create($userCustomField, $userId, $isTranslating);
        }
        return $userCustomData;
    }

    /**
     * Load a UserCustData
     * @param int $id
     * @param bool $isReadOnlyDb query to read-only db
     * @return UserCustData|null
     */
    public function loadById(int $id, bool $isReadOnlyDb = false): ?UserCustData
    {
        if (!$id) {
            return null;
        }

        return $this->prepareRepository($isReadOnlyDb)
            ->filterId($id)
            ->loadEntity();
    }

    /**
     * Load all user custom data for user
     * @param int $userId
     * @param bool $isReadOnlyDb
     * @return UserCustData[]
     */
    public function loadForUser(int $userId, bool $isReadOnlyDb = false): array
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->filterUserId($userId)
            ->loadEntities();
    }

    // /**
    //  * Find user custom data object by file name
    //  * @param string $fileName file name
    //  * @param int|array $skipUserIds excluded user ids
    //  * @param bool $isReadOnlyDb
    //  * @return \UserCustData[]
    //  */
    // public function loadByFileName($fileName, $skipUserIds = null, bool $isReadOnlyDb = false): array
    // {
    //     $userCustDataRepository = $this->createUserCustDataReadRepository()
    //         ->joinUserCustFieldFilterActive(true)
    //         ->joinUserCustFieldFilterType(Constants\CustomField::TYPE_FILE)
    //         ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
    //         ->likeText('%' . $fileName . '%');
    //     if ($skipUserIds !== null) {
    //         $skipUserIds = is_array($skipUserIds) ? $skipUserIds : [$skipUserIds];
    //         $userCustDataRepository->skipUserId($skipUserIds);
    //     }
    //     $userCustomDatas = $userCustDataRepository->loadEntities();
    //     return $userCustomDatas;
    // }

    /**
     * @param bool $isReadOnlyDb
     * @return UserCustDataReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb = false): UserCustDataReadRepository
    {
        return $this->createUserCustDataReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true);
    }
}
