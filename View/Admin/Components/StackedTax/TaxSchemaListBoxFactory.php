<?php
/**
 * SAM-11014: Stacked Tax. Invoice settings management. Add tax schema at account level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 07, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Components\StackedTax;

use QForm;
use QListBox;
use QPanel;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Service\CustomizableClass;
use Sam\Location\Render\LocationRendererAwareTrait;
use Sam\Storage\ReadRepository\Entity\TaxSchema\TaxSchemaReadRepository;
use Sam\Storage\ReadRepository\Entity\TaxSchema\TaxSchemaReadRepositoryCreateTrait;

/**
 * Class TaxSchemaListBoxFactory
 * @package Sam\View\Admin\Components\StackedTax
 */
class TaxSchemaListBoxFactory extends CustomizableClass
{
    use LocationRendererAwareTrait;
    use TaxSchemaReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function createAndPopulate(
        QForm|QPanel $parentObject,
        string $controlId,
        int $accountId,
        int $amountSource,
        string $country = '',
        array $prioritySchemaIds = []
    ): QListBox {
        $listBox = $this->create($parentObject, $controlId);
        $this->populate($listBox, $accountId, $amountSource, $country, $prioritySchemaIds);
        return $listBox;
    }

    public function create(QForm|QPanel $parentObject, string $controlId): QListBox
    {
        $listBox = new QListBox($parentObject, $controlId);
        return $listBox;
    }

    public function populate(
        QListBox $listBox,
        int $accountId,
        int $amountSource,
        string $country = '',
        array $prioritySchemaIds = []
    ): void {
        $listBox->RemoveAllItems();
        $listBox->AddItem('-None-');
        $taxSchemas = $this->loadTaxSchemas($accountId, $amountSource, $country, $prioritySchemaIds);
        foreach ($taxSchemas as $taxSchemaRow) {
            $listBox->AddItem($this->makeOptionName($taxSchemaRow), (int)$taxSchemaRow['id']);
        }
    }

    protected function makeOptionName(array $taxSchemaRow): string
    {
        $optionName = $taxSchemaRow['name'];
        if ($taxSchemaRow['geo_type']) {
            $optionName = $this->getLocationRenderer()->makeGeoTypeNameTranslated($taxSchemaRow['country'], $taxSchemaRow['geo_type']) . ' - ' . $taxSchemaRow['name'];
        }

        $locationValuesRendered = [];
        $addressRenderer = AddressRenderer::new();
        if ($taxSchemaRow['country']) {
            $locationValuesRendered[] = $addressRenderer->countryName((string)$taxSchemaRow['country']);
        }

        if ($taxSchemaRow['state']) {
            $locationValuesRendered[] = $addressRenderer->stateName((string)$taxSchemaRow['state'], (string)$taxSchemaRow['country']);
        }
        if ($taxSchemaRow['county']) {
            $locationValuesRendered[] = $taxSchemaRow['county'];
        }
        if ($taxSchemaRow['city']) {
            $locationValuesRendered[] = $taxSchemaRow['city'];
        }

        if ($locationValuesRendered) {
            $optionName .= ' (' . implode(', ', $locationValuesRendered) . ')';
        }
        return $optionName;
    }

    protected function loadTaxSchemas(
        int $accountId,
        int $amountSource,
        string $country,
        array $prioritySchemaIds,
        bool $isReadOnlyDb = false
    ): array {
        $repo = $this->prepareRepository($isReadOnlyDb)
            ->skipId($prioritySchemaIds)
            ->filterAccountId($accountId)
            ->filterSourceTaxSchemaId(null)
            ->filterAmountSource($amountSource);
        if ($country) {
            $repo->filterCountry($country);
        }
        $taxSchemas = $repo->loadRows();

        if ($prioritySchemaIds) {
            $prioritySchemas = $this->prepareRepository($isReadOnlyDb)
                ->filterId($prioritySchemaIds)
                ->loadRows();
            array_unshift($taxSchemas, ...$prioritySchemas);
        }
        return $taxSchemas;
    }

    protected function prepareRepository(bool $isReadOnlyDb = false): TaxSchemaReadRepository
    {
        return $this->createTaxSchemaReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->orderByName()
            ->select(['id', 'name', 'geo_type', 'country', 'state', 'county', 'city']);
    }
}
