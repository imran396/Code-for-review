<?php
/**
 * SAM-10785: Create in Admin Web the "Tax Schema Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 12, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\TaxSchemaSelectTaxDefinitionDialog\Load;

use Sam\Tax\StackedTax\GeoType\Config\StackedTaxGeoTypeConfigProvider;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\TaxDefinition\TaxDefinitionReadRepositoryCreateTrait;
use Sam\View\Admin\Form\TaxDefinitionListForm\Load\TaxDefinitionListFilterCondition;

/**
 * Class AvailableTaxDefinitionLoader
 * @package Sam\View\Admin\Form\TaxSchemaSelectTaxDefinitionDialog\Load
 */
class AvailableTaxDefinitionLoader extends CustomizableClass
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
     * @return TaxDefinitionDto[]
     */
    public function load(
        ?int $schemaGeoType,
        TaxDefinitionListFilterCondition $filterCondition,
        array $skipIds = [],
        bool $isReadOnlyDb = false,
    ): array {
        $condition = $this->makeFilterExpression($schemaGeoType, $filterCondition);
        $repository = $this->createTaxDefinitionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($filterCondition->accountIds)
            ->filterActive(true)
            ->filterSourceTaxDefinitionId(null)
            ->skipId($skipIds)
            ->inlineCondition($condition);
        if ($filterCondition->name) {
            $repository->filterName($filterCondition->name);
        }
        if ($filterCondition->taxType) {
            $repository->filterTaxType($filterCondition->taxType);
        }
        $rows = $repository
            ->select([
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
        $dtos = array_map(TaxDefinitionDto::new()->fromDbRow(...), $rows);
        return $dtos;
    }

    protected function makeFilterExpression(
        ?int $schemaGeoType,
        TaxDefinitionListFilterCondition $filterCondition,
    ): string {
        $repository = $this->createTaxDefinitionReadRepository();
        $allLocationConditions = [
            Constants\StackedTax::GT_COUNTRY => $filterCondition->country ? 'tdef.country = ' . $repository->escape($filterCondition->country) : null,
            Constants\StackedTax::GT_STATE => $filterCondition->state ? 'tdef.state = ' . $repository->escape($filterCondition->state) : null,
            Constants\StackedTax::GT_COUNTY => $filterCondition->county ? 'tdef.county = ' . $repository->escape($filterCondition->county) : null,
            Constants\StackedTax::GT_CITY => $filterCondition->city ? 'tdef.city = ' . $repository->escape($filterCondition->city) : null,
        ];

        if (
            $filterCondition->geoType
            && $filterCondition->geoType <= $schemaGeoType
        ) {
            return $this->makeLocationFilterExpression($allLocationConditions, $filterCondition->country, $filterCondition->geoType);
        }

        $conditions = [
            'tdef.geo_type IS NULL'
        ];
        foreach (Constants\StackedTax::GEO_TYPES as $geoType) {
            if ($geoType > $schemaGeoType) {
                break;
            }

            $locationFilterCondition = $this->makeLocationFilterExpression($allLocationConditions, $filterCondition->country, $geoType);
            $conditions[] = "({$locationFilterCondition})";
        }
        return implode(' OR ', $conditions);
    }

    protected function makeLocationFilterExpression(array $allLocationConditions, string $country, ?int $geoType): string
    {
        $geoTypeLocationConditions = array_filter(
            $allLocationConditions,
            function (?string $locationCondition, int $locationGeoType) use ($country, $geoType) {
                return $locationCondition && $this->isGeoTypeLocationFieldAvailable($locationGeoType, $country, $geoType);
            },
            ARRAY_FILTER_USE_BOTH
        );
        $geoTypeLocationConditions[] = 'tdef.geo_type = ' . $geoType;
        return implode(' AND ', $geoTypeLocationConditions);
    }

    protected function isGeoTypeLocationFieldAvailable(?int $fieldGeoType, string $country, ?int $schemaGeoType): bool
    {
        if ($fieldGeoType > $schemaGeoType) {
            return false;
        }

        return StackedTaxGeoTypeConfigProvider::new()->isAvailable($country, $fieldGeoType);
    }
}
