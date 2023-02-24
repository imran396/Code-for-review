<?php
/**
 * SAM-6592: Move lot item custom field logic to \Sam\CustomField\Lot namespace
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\LotItemCustData\LotItemCustDataReadRepository;
use Sam\Storage\ReadRepository\Entity\LotItemCustData\LotItemCustDataReadRepositoryCreateTrait;

/**
 * Class LotCustomDataExistenceChecker
 * @package Sam\CustomField\Lot\Validate
 */
class LotCustomDataExistenceChecker extends CustomizableClass
{
    use LotItemCustDataReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
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
        $repo = $this->prepareRepository($isReadOnlyDb)
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
     * @param bool $isReadOnlyDb
     * @return LotItemCustDataReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb = false): LotItemCustDataReadRepository
    {
        $repo = $this->createLotItemCustDataReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true);
        return $repo;
    }
}
