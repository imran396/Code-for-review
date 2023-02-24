<?php
/**
 * SAM-10493: Implement a GraphQL nested structure for a lot
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Load\Internal\User;

use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepositoryCreateTrait;

/**
 * Class UserDataLoader
 * @package Sam\Api\GraphQL\Load\Internal\User
 */
class UserDataLoader extends CustomizableClass
{
    use UserReadRepositoryCreateTrait;

    protected const USER_INFO_FIELDS = [
        'first_name',
        'last_name',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function load(array $ids, array $fields, bool $isReadOnlyDb = false): array
    {
        $idArrayIndex = array_search('id', $fields, true);
        if ($idArrayIndex !== false) {
            unset($fields[$idArrayIndex]);
        }
        $fields[] = 'u.id';
        $repository = $this->createUserReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($ids)
            ->filterUserStatusId(Constants\User::AVAILABLE_USER_STATUSES)
            ->select($fields);
        if (array_intersect($fields, self::USER_INFO_FIELDS)) {
            $repository->joinUserInfo();
        }
        $users = $repository->loadRows();
        return ArrayHelper::produceIndexedArray($users, 'id');
    }
}
