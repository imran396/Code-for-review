<?php
/**
 * SAM-6592: Move lot item custom field logic to \Sam\CustomField\Lot namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 13, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Validate;


use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotItemCustData\LotItemCustDataReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotItemCustField\LotItemCustFieldReadRepository;
use Sam\Storage\ReadRepository\Entity\LotItemCustField\LotItemCustFieldReadRepositoryCreateTrait;

/**
 * Class LotCustomFieldExistenceChecker
 * @package Sam\CustomField\Lot\Validate
 */
class LotCustomFieldExistenceChecker extends CustomizableClass
{
    use LotCustomFieldLoaderCreateTrait;
    use LotItemCustDataReadRepositoryCreateTrait;
    use LotItemCustFieldReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check if custom field of type exists
     *
     * @param int $type
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByType(int $type, bool $isReadOnlyDb = false): bool
    {
        return $this->countByType($type, $isReadOnlyDb) > 0;
    }

    /**
     * Return count of custom fields of passed type
     *
     * @param int $type
     * @param bool $isReadOnlyDb
     * @return int
     */
    public function countByType(int $type, bool $isReadOnlyDb = false): int
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->filterType($type)
            ->count();
    }

    /**
     * Check if custom field with passed name exists
     *
     * @param string $name
     * @param int[] $skipIds
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByName(string $name, array $skipIds = [], bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->prepareRepository($isReadOnlyDb)
            ->filterName($name)
            ->skipId($skipIds)
            ->exist();
        return $isFound;
    }

    /**
     * Check if any existing custom field of type included in search
     *
     * @param int $type
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByTypeAmongSearchFields(int $type, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->prepareRepository($isReadOnlyDb)
            ->filterType($type)
            ->filterSearchField(true)
            ->exist();
        return $isFound;
    }

    /**
     * Check if order number already exists
     *
     * @param int $order
     * @param int[] $skipIds
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByOrder(int $order, array $skipIds = [], bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->prepareRepository($isReadOnlyDb)
            ->filterOrder($order)
            ->skipId($skipIds)
            ->exist();
        return $isFound;
    }

    /**
     * Check if field value is already existing (this function will be used on text custom fields that are unique)
     *
     * @param int $lotCustomFieldId
     * @param string $value
     * @param int|null $accountId null, when skip filtering by account of lot item
     * @param array $skipLotItemIds optional
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existValue(
        int $lotCustomFieldId,
        string $value,
        ?int $accountId = null,
        array $skipLotItemIds = [],
        bool $isReadOnlyDb = false
    ): bool {
        $repo = $this->createLotItemCustDataReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterLotItemCustFieldId($lotCustomFieldId)
            ->joinLotItemFilterActive(true)
            ->filterText($value);

        if ($accountId !== null) {
            $repo->joinLotItemFilterAccountId($accountId);
        }

        if ($skipLotItemIds) {
            $repo->skipLotItemId($skipLotItemIds);
        }

        $isFound = $repo->exist();
        return $isFound;
    }

    /**
     * Check if field edited with rich text editor exist
     *
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existRichText(bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->prepareRepository($isReadOnlyDb)
            ->filterFckEditor(true)
            ->exist();
        return $isFound;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return LotItemCustFieldReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb = false): LotItemCustFieldReadRepository
    {
        $repository = $this->createLotItemCustFieldReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true);
        return $repository;
    }
}
