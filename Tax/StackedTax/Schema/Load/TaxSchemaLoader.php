<?php
/**
 * SAM-10785: Create in Admin Web the "Tax Schema Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 11, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Schema\Load;

use Location;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\TaxSchema\TaxSchemaReadRepository;
use Sam\Storage\ReadRepository\Entity\TaxSchema\TaxSchemaReadRepositoryCreateTrait;
use Sam\Tax\StackedTax\GeoType\Config\StackedTaxGeoTypeConfigProvider;
use TaxSchema;

/**
 * Class TaxSchemaLoader
 * @package Sam\Tax\StackedTax\Schema\Load
 */
class TaxSchemaLoader extends CustomizableClass
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
     * Load active tax schema by id.
     * @param int|null $taxSchemaId
     * @param bool $isReadOnlyDb
     * @return TaxSchema|null
     */
    public function load(?int $taxSchemaId, bool $isReadOnlyDb = false): ?TaxSchema
    {
        if (!$taxSchemaId) {
            return null;
        }

        return $this->prepareRepository($isReadOnlyDb)
            ->filterId($taxSchemaId)
            ->loadEntity();
    }

    public function loadLastSnapshotByName(string $name, ?int $accountId = null, bool $isReadOnlyDb = false): ?TaxSchema
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->filterActive(true)
            ->filterAccountId($accountId)
            ->likeName($name)
            ->orderById(false)
            ->loadEntity();
    }

    public function loadSelected(
        array $select,
        ?int $taxSchemaId,
        ?int $accountId = null,
        ?int $amountSource = null,
        bool $isReadOnlyDb = false
    ): array {
        if (!$taxSchemaId) {
            return [];
        }

        $repo = $this->prepareRepository($isReadOnlyDb)
            ->filterId($taxSchemaId)
            ->select($select);
        if ($accountId) {
            $repo->filterAccountId($accountId);
        }
        if ($amountSource) {
            $repo->filterAmountSource($amountSource);
        }
        return $repo->loadRow();
    }

    public function loadSelectedRows(array $select, array $taxSchemaIds, bool $isReadOnlyDb = false): array
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->filterId($taxSchemaIds)
            ->select($select)
            ->loadRows();
    }

    public function loadSelectedForInvoiceAdditional(array $select, int $invoiceAdditionalId, bool $isReadOnlyDb = false): array
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->filterInvoiceAdditionalId($invoiceAdditionalId)
            ->skipSourceTaxSchemaId(null)
            ->select($select)
            ->loadRows();
    }

    public function loadSelectedForInvoiceItem(array $select, int $invoiceItemId, int $amountSource, bool $isReadOnlyDb = false): array
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->filterAmountSource($amountSource)
            ->filterInvoiceItemId($invoiceItemId)
            ->select($select)
            ->skipSourceTaxSchemaId(null)
            ->loadRows();
    }

    /**
     * Load tax schemas filtered by geo fields defined in location and filtered by amount source, account id.
     * @param int $amountSource
     * @param int $accountId
     * @param Location $location
     * @param int|null $lotCategoryId null means skip filtering by category, 0 means filter by no-category tax schemas
     * @param bool $isIncludeSnapshot false means exclude snapshot tax schemas for invoices
     * @param bool $isReadOnlyDb
     * @return TaxSchema[]
     */
    public function loadByLocation(
        int $amountSource,
        int $accountId,
        Location $location,
        ?int $lotCategoryId = null,
        bool $isIncludeSnapshot = false,
        bool $isReadOnlyDb = false
    ): array {
        [$filterCountry, $filterState, $filterCounty, $filterCity] = $this->extractGeoFieldsFromLocation($location);
        return $this->loadByGeoFields(
            $amountSource,
            $accountId,
            $filterCountry,
            $filterState,
            $filterCounty,
            $filterCity,
            $lotCategoryId,
            $isIncludeSnapshot,
            $isReadOnlyDb
        );
    }

    /**
     * Load selected field-set of tax schemas filtered by geo fields and filtered by amount source, account id.
     * @param array $select
     * @param int $amountSource
     * @param int $accountId
     * @param Location $location
     * @param int|null $lotCategoryId null means skip filtering by category, 0 means filter by no-category tax schemas
     * @param bool $isIncludeSnapshot false means exclude snapshot tax schemas for invoices
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadSelectedByLocation(
        array $select,
        int $amountSource,
        int $accountId,
        Location $location,
        ?int $lotCategoryId = null,
        bool $isIncludeSnapshot = false,
        bool $isReadOnlyDb = false
    ): array {
        [$filterCountry, $filterState, $filterCounty, $filterCity] = $this->extractGeoFieldsFromLocation($location);
        return $this->loadSelectedByGeoFields(
            $select,
            $amountSource,
            $accountId,
            $filterCountry,
            $filterState,
            $filterCounty,
            $filterCity,
            $lotCategoryId,
            $isIncludeSnapshot,
            $isReadOnlyDb
        );
    }

    /**
     * Load tax schemas filtered by geo fields (country, state, county, city) and amount source, account id.
     * @param int $amountSource
     * @param int $accountId
     * @param string $country
     * @param string|null $state null means skip
     * @param string|null $county null means skip
     * @param string|null $city null means skip
     * @param int|null $lotCategoryId null means skip filtering by category, 0 means filter by no-category tax schemas
     * @param bool $isIncludeSnapshot false means exclude snapshot tax schemas for invoices
     * @param bool $isReadOnlyDb
     * @return TaxSchema[]
     */
    public function loadByGeoFields(
        int $amountSource,
        int $accountId,
        string $country,
        ?string $state = null,
        ?string $county = null,
        ?string $city = null,
        ?int $lotCategoryId = null,
        bool $isIncludeSnapshot = false,
        bool $isReadOnlyDb = false
    ): array {
        return $this->prepareRepositoryForLoadByGeoFields(
            $amountSource,
            $accountId,
            $country,
            $state,
            $county,
            $city,
            $lotCategoryId,
            $isIncludeSnapshot,
            $isReadOnlyDb
        )
            ->loadEntities();
    }

    /**
     * Load selected field-set of tax schemas filtered by geo fields (country, state, county, city) and amount source, account id.
     * @param array $select
     * @param int $amountSource
     * @param int $accountId
     * @param string $country
     * @param string|null $state null means skip
     * @param string|null $county null means skip
     * @param string|null $city null means skip
     * @param int|null $lotCategoryId null means skip filtering by category, 0 means filter by no-category tax schemas
     * @param bool $isIncludeSnapshot false means exclude snapshot tax schemas for invoices
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadSelectedByGeoFields(
        array $select,
        int $amountSource,
        int $accountId,
        string $country,
        ?string $state = null,
        ?string $county = null,
        ?string $city = null,
        ?int $lotCategoryId = null,
        bool $isIncludeSnapshot = false,
        bool $isReadOnlyDb = false
    ): array {
        return $this->prepareRepositoryForLoadByGeoFields(
            $amountSource,
            $accountId,
            $country,
            $state,
            $county,
            $city,
            $lotCategoryId,
            $isIncludeSnapshot,
            $isReadOnlyDb
        )
            ->select($select)
            ->loadRows();
    }

    /**
     * @param Location $location
     * @return array{0: string, 1: string, 2: string, 3: string}
     */
    protected function extractGeoFieldsFromLocation(Location $location): array
    {
        $locationCountry = $location->Country;
        $configProvider = StackedTaxGeoTypeConfigProvider::new();
        $filterCountry = $filterState = $filterCounty = $filterCity = null;
        if ($configProvider->isAvailable($locationCountry, Constants\StackedTax::GT_COUNTRY)) {
            $filterCountry = $locationCountry;
        }
        if ($configProvider->isAvailable($locationCountry, Constants\StackedTax::GT_STATE)) {
            $filterState = $location->State;
        }
        if ($configProvider->isAvailable($locationCountry, Constants\StackedTax::GT_COUNTY)) {
            $filterCounty = $location->County;
        }
        if ($configProvider->isAvailable($locationCountry, Constants\StackedTax::GT_CITY)) {
            $filterCity = $location->City;
        }
        return [$filterCountry, $filterState, $filterCounty, $filterCity];
    }

    /**
     * @param int $amountSource
     * @param int $accountId
     * @param string $country
     * @param string|null $state
     * @param string|null $county null means skip
     * @param string|null $city null means skip
     * @param int|null $lotCategoryId null means skip filtering by category, 0 means filter by no-category tax schemas
     * @param bool $isIncludeSnapshot false means exclude snapshot tax schemas for invoices
     * @param bool $isReadOnlyDb
     * @return TaxSchemaReadRepository
     */
    protected function prepareRepositoryForLoadByGeoFields(
        int $amountSource,
        int $accountId,
        string $country,
        ?string $state = null,
        ?string $county = null,
        ?string $city = null,
        ?int $lotCategoryId = null,
        bool $isIncludeSnapshot = false,
        bool $isReadOnlyDb = false
    ): TaxSchemaReadRepository {
        $repo = $this->prepareRepository($isReadOnlyDb)
            ->filterAmountSource($amountSource)
            ->filterAccountId($accountId)
            ->filterCountry($country);
        if ($state !== null) {
            $repo->filterState($state);
        }
        if ($county !== null) {
            $repo->filterCounty($county);
        }
        if ($city !== null) {
            $repo->filterCity($city);
        }
        if (!$isIncludeSnapshot) {
            $repo->filterSourceTaxSchemaId(null);
        }

        if ($lotCategoryId) {
            $repo->joinTaxSchemaLotCategoryFilterLotCategoryId($lotCategoryId);
        } elseif ($lotCategoryId === 0) {
            $repo->joinTaxSchemaLotCategoryFilterLotCategoryId([null]);
        }
        return $repo;
    }

    protected function prepareRepository(bool $isReadOnlyDb): TaxSchemaReadRepository
    {
        return $this->createTaxSchemaReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true);
    }
}
