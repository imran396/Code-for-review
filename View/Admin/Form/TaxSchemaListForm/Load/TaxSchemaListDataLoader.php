<?php
/**
 * SAM-10787: Create in Admin Web the "Tax Schema List" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 18, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\TaxSchemaListForm\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\TaxSchema\TaxSchemaReadRepository;
use Sam\Storage\ReadRepository\Entity\TaxSchema\TaxSchemaReadRepositoryCreateTrait;

/**
 * Class TaxSchemaListDataLoader
 * @package Sam\View\Admin\Form\TaxSchemaListForm\Load
 */
class TaxSchemaListDataLoader extends CustomizableClass
{
    use TaxSchemaReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return TaxSchemaDto[]
     */
    public function load(
        TaxSchemaListFilterCondition $filterCondition,
        TaxSchemaListOrderByColumn $orderColumn,
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
                'geo_type',
                'country',
                'city',
                'state',
                'county',
                'amount_source',
                'description',
                'for_invoice',
                'for_settlement',
                'note'
            ])
            ->loadRows();
        $result = array_map(TaxSchemaDto::new()->fromDbRow(...), $rows);
        return $result;
    }

    public function count(TaxSchemaListFilterCondition $filterCondition, bool $isReadOnlyDb = false): int
    {
        return $this->prepareRepository($filterCondition, $isReadOnlyDb)->count();
    }

    protected function prepareRepository(TaxSchemaListFilterCondition $filterCondition, bool $isReadOnlyDb = false): TaxSchemaReadRepository
    {
        $repository = $this->createTaxSchemaReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterSourceTaxSchemaId(null)
            ->filterAccountId($filterCondition->accountIds);

        $repository = $repository->filterAmountSource($filterCondition->amountSource);
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
        if ($filterCondition->geoType) {
            $repository = $repository->filterGeoType($filterCondition->geoType);
        }
//        if ($filterCondition->forInvoice) {
//            $repository = $repository->filterForInvoice($filterCondition->forInvoice);
//        }
//        if ($filterCondition->forSettlement) {
//            $repository = $repository->filterForSettlement($filterCondition->forSettlement);
//        }
        return $repository;
    }
}
