<?php
/**
 * SAM-10782: Create in Admin Web the "Tax Definition List" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 05, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\TaxDefinitionListForm\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\TaxDefinition\TaxDefinitionReadRepository;
use Sam\Storage\ReadRepository\Entity\TaxDefinition\TaxDefinitionReadRepositoryCreateTrait;

/**
 * Class TaxDefinitionListDataLoader
 * @package Sam\View\Admin\Form\TaxDefinitionListForm\Load
 */
class TaxDefinitionListDataLoader extends CustomizableClass
{
    use TaxDefinitionReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param TaxDefinitionListFilterCondition $filterCondition
     * @param TaxDefinitionListOrderByColumn $orderColumn
     * @param bool $ascending
     * @param int $limit
     * @param int $offset
     * @param bool $isReadOnlyDb
     * @return TaxDefinitionDto[]
     */
    public function load(
        TaxDefinitionListFilterCondition $filterCondition,
        TaxDefinitionListOrderByColumn $orderColumn,
        bool $ascending,
        int $limit,
        int $offset,
        bool $isReadOnlyDb = false
    ): array {
        $rows = $this->prepareRepository($filterCondition, $isReadOnlyDb)
            ->limit($limit)
            ->offset($offset)
            ->order($orderColumn->value, $ascending)
            ->select([
                'account_id',
                'id',
                'name',
                'tax_type',
                'geo_type',
                'country',
                'city',
                'state',
                'county',
                'description',
                'note'
            ])
            ->loadRows();
        $result = array_map(TaxDefinitionDto::new()->fromDbRow(...), $rows);
        return $result;
    }

    public function count(TaxDefinitionListFilterCondition $filterCondition, bool $isReadOnlyDb = false): int
    {
        return $this->prepareRepository($filterCondition, $isReadOnlyDb)->count();
    }

    protected function prepareRepository(TaxDefinitionListFilterCondition $filterCondition, bool $isReadOnlyDb = false): TaxDefinitionReadRepository
    {
        $repository = $this->createTaxDefinitionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterAccountId($filterCondition->accountIds)
            ->filterSourceTaxDefinitionId(null);

        if ($filterCondition->country) {
            $repository = $repository->filterCountry($filterCondition->country);
        }
        if ($filterCondition->state) {
            $repository = $repository->filterState($filterCondition->state);
        }
        if ($filterCondition->county) {
            $repository = $repository->filterCounty($filterCondition->county);
        }
        if ($filterCondition->city) {
            $repository = $repository->filterCity($filterCondition->city);
        }
        if ($filterCondition->name) {
            $repository = $repository->filterName($filterCondition->name);
        }
        if ($filterCondition->taxType) {
            $repository = $repository->filterTaxType($filterCondition->taxType);
        }
        if ($filterCondition->geoType) {
            $repository = $repository->filterGeoType($filterCondition->geoType);
        }
        return $repository;
    }
}
