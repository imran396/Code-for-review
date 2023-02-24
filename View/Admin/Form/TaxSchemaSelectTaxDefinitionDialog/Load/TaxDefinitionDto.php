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

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class TaxDefinitionDto
 * @package Sam\Tax\StackedTax\TaxSchemaSelectTaxDefinitionDialog\Load
 */
class TaxDefinitionDto extends CustomizableClass
{
    public readonly int $id;
    public readonly string $name;
    public readonly int $taxType;
    public readonly ?int $geoType;
    public readonly string $country;
    public readonly string $state;
    public readonly string $county;
    public readonly string $city;
    public readonly string $description;
    public readonly string $note;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        int $id,
        string $name,
        int $taxType,
        ?int $geoType,
        string $country,
        string $state,
        string $county,
        string $city,
        string $description,
        string $note,
    ): static {
        $this->id = $id;
        $this->name = $name;
        $this->taxType = $taxType;
        $this->geoType = $geoType;
        $this->country = $country;
        $this->state = $state;
        $this->county = $county;
        $this->city = $city;
        $this->description = $description;
        $this->note = $note;
        return $this;
    }

    public function fromDbRow(array $row): static
    {
        return self::new()->construct(
            id: (int)$row['id'],
            name: $row['name'],
            taxType: (int)$row['tax_type'],
            geoType: Cast::toInt($row['geo_type']),
            country: $row['country'],
            state: $row['state'],
            county: $row['county'],
            city: $row['city'],
            description: $row['description'],
            note: $row['note']
        );
    }
}
