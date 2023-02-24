<?php
/**
 * SAM-6473: Move "my_search" table related logic to separate module
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 14, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\MySearch\Load;


use MySearch;
use MySearchCustom;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\MySearch\MySearchReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\MySearchCategory\MySearchCategoryReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\MySearchCustom\MySearchCustomReadRepositoryCreateTrait;

/**
 * Class MySearchLoader
 * @package Sam\MySearch\Load
 */
class MySearchLoader extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;
    use MySearchCategoryReadRepositoryCreateTrait;
    use MySearchCustomReadRepositoryCreateTrait;
    use MySearchReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $id
     * @param bool $isReadOnlyDb
     * @return MySearch|null
     */
    public function load(int $id, bool $isReadOnlyDb = false): ?MySearch
    {
        $mySearchResult = $this->createMySearchReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($id)
            ->loadEntity();
        return $mySearchResult;
    }

    /**
     * @param int $userId
     * @return MySearch[]
     */
    public function loadMailable(int $userId): array
    {
        $mySearchResults = $this->createMySearchReadRepository()
            ->filterSendMail(true)
            ->filterUserId($userId)
            ->loadEntities();
        return $mySearchResults;
    }

    /**
     * @param int $userId
     * @param int $limit
     * @param bool $isReadOnlyDb
     * @return MySearch[]
     */
    public function loadByUserId(int $userId, int $limit, bool $isReadOnlyDb = false): array
    {
        $mySearches = $this->createMySearchReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterUserId($userId)
            ->orderById(false)
            ->orderByCreatedOn(false)
            ->limit($limit)
            ->loadEntities();
        return $mySearches;
    }

    /**
     * Count number of queries of a user
     * @param int|null $userId
     * @param bool $isReadOnlyDb
     * @return int
     */
    public function countByUserId(?int $userId, bool $isReadOnlyDb = false): int
    {
        $count = $this->createMySearchReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterUserId($userId)
            ->count();
        return $count;
    }

    /**
     * Return object my_search_custom
     *
     * @param int $mySearchId my_search.lot_item_cust_field_id
     * @param int $lotCustomFieldId
     * @return MySearchCustom|null
     */
    public function loadCustomSearchField(int $mySearchId, int $lotCustomFieldId): ?MySearchCustom
    {
        $mySearchCustomField = $this->createMySearchCustomReadRepository()
            ->filterMySearchId($mySearchId)
            ->filterLotItemCustFieldId($lotCustomFieldId)
            ->loadEntity();
        return $mySearchCustomField;
    }

    /**
     * @param int|null $option - (0 or 1) when the saved search cron job needs to be split into two schedules.
     * For installations with several thousand users, the cron job may be split to users with odd id on the first day, and users with even ids on the second day.
     * @return array
     */
    public function loadUserIdsWithMailableSearch(?int $option): array
    {
        $repo = $this->createMySearchReadRepository()
            ->enableDistinct(true)
            ->filterSendMail(true)
            ->joinAccountFilterActive(true)
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
            ->select(['ms.user_id']);

        if ($option !== null && in_array($option, [0, 1], false)) {
            $repo->inlineCondition("ms.user_id % 2 = {$option}");
        }

        $userIds = array_map(
            static function (array $result) {
                return $result['user_id'];
            },
            $repo->loadRows()
        );

        return $userIds;
    }

    /**
     * Return array of category ids
     *
     * @param int $mySearchId
     * @return int[]
     */
    public function loadLotCategoryIds(int $mySearchId): array
    {
        $rows = $this->createMySearchCategoryReadRepository()
            ->filterMySearchId($mySearchId)
            ->select(['category_id'])
            ->loadRows();
        $myAlertCategories = ArrayCast::arrayColumnInt($rows, 'category_id');
        return $myAlertCategories;
    }
}
