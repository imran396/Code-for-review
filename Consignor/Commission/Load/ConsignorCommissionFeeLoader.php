<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Load;

use ConsignorCommissionFee;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\ConsignorCommissionFee\ConsignorCommissionFeeReadRepository;
use Sam\Storage\ReadRepository\Entity\ConsignorCommissionFee\ConsignorCommissionFeeReadRepositoryCreateTrait;

/**
 * This class contains methods for fetching consignor commission fee from DB
 *
 * Class ConsignorCommissionLoader
 * @package Sam\Consignor\Commission\Load
 */
class ConsignorCommissionFeeLoader extends CustomizableClass
{
    use ConsignorCommissionFeeReadRepositoryCreateTrait;
    use EntityMemoryCacheManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Fetch consignor commission fee by id
     *
     * @param int|null $id
     * @param bool $isReadOnlyDb
     * @return ConsignorCommissionFee|null
     */
    public function load(?int $id, bool $isReadOnlyDb = false): ?ConsignorCommissionFee
    {
        if (!$id) {
            return null;
        }

        $fn = function () use ($id, $isReadOnlyDb) {
            $consignorCommissionFee = $this->prepareRepository($isReadOnlyDb)
                ->filterId($id)
                ->loadEntity();
            return $consignorCommissionFee;
        };

        $entityKey = $this->getEntityMemoryCacheManager()
            ->makeEntityCacheKey(Constants\MemoryCache::CONSIGNOR_COMMISSION_FEE_ID, $id);
        $consignorCommissionFee = $this->getEntityMemoryCacheManager()->load($entityKey, $fn);

        return $consignorCommissionFee;
    }

    /**
     * Fetch account-level consignor commission fee list
     *
     * @param int|null $accountId
     * @param bool $isReadOnlyDb
     * @return ConsignorCommissionFee[]
     */
    public function loadByAccountId(?int $accountId, bool $isReadOnlyDb = false): array
    {
        if (!$accountId) {
            return [];
        }

        $consignorCommissionFee = $this->prepareRepository($isReadOnlyDb)
            ->filterLevel(Constants\ConsignorCommissionFee::LEVEL_ACCOUNT)
            ->filterRelatedEntityId($accountId)
            ->loadEntities();
        return $consignorCommissionFee;
    }

    /**
     * @param array $selected
     * @param int|null $accountId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadSelectedByAccountId(array $selected, ?int $accountId, bool $isReadOnlyDb = false): array
    {
        if (!$accountId) {
            return [];
        }

        $consignorCommissionFee = $this->prepareRepository($isReadOnlyDb)
            ->select($selected)
            ->filterLevel(Constants\ConsignorCommissionFee::LEVEL_ACCOUNT)
            ->filterRelatedEntityId($accountId)
            ->loadRows();
        return $consignorCommissionFee;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return ConsignorCommissionFeeReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb = false): ConsignorCommissionFeeReadRepository
    {
        $repository = $this->createConsignorCommissionFeeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true);
        return $repository;
    }
}
