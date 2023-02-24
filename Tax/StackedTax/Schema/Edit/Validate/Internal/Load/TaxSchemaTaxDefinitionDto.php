<?php
/**
 * SAM-10785: Create in Admin Web the "Tax Schema Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 13, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Schema\Edit\Validate\Internal\Load;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class TaxSchemaTaxDefinitionDto
 * @package Sam\Tax\StackedTax\Schema\Edit\Validate\Internal\Load
 */
class TaxSchemaTaxDefinitionDto extends CustomizableClass
{
    public readonly ?int $taxSchemaTaxDefinitionId;
    public readonly int $taxDefinitionId;
    public readonly string $name;
    public readonly ?int $geoType;
    public readonly string $country;
    public readonly string $state;
    public readonly string $county;
    public readonly string $city;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        ?int $taxSchemaTaxDefinitionId,
        int $taxDefinitionId,
        ?int $geoType,
        string $name,
        string $country,
        string $state,
        string $county,
        string $city,
    ): static {
        $this->taxSchemaTaxDefinitionId = $taxSchemaTaxDefinitionId;
        $this->taxDefinitionId = $taxDefinitionId;
        $this->geoType = $geoType;
        $this->country = $country;
        $this->state = $state;
        $this->county = $county;
        $this->name = $name;
        $this->city = $city;
        return $this;
    }

    public function fromDbRow(array $row): static
    {
        return self::new()->construct(
            taxSchemaTaxDefinitionId: Cast::toInt($row['tax_schema_tax_definition_id']),
            taxDefinitionId: (int)$row['tax_definition_id'],
            geoType: Cast::toInt($row['geo_type']),
            name: $row['name'],
            country: $row['country'],
            state: $row['state'],
            county: $row['county'],
            city: $row['city']
        );
    }
}
