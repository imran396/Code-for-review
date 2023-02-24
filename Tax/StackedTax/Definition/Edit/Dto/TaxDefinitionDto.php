<?php
/**
 * SAM-10775: Create in Admin Web the "Tax Definition Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Definition\Edit\Dto;

use Sam\Core\Service\CustomizableClass;

/**
 * Class TaxDefinitionDto
 * @package Sam\Tax\StackedTax\Definition\Edit
 */
class TaxDefinitionDto extends CustomizableClass
{
    public readonly ?int $geoType;
    public readonly ?int $taxType;
    public readonly string $city;
    public readonly string $country;
    public readonly string $county;
    public readonly string $description;
    public readonly string $name;
    public readonly string $note;
    public readonly string $rangeCalculationMethod;
    public readonly string $state;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        string $name,
        ?int $taxType,
        ?int $geoType,
        string $country,
        string $state,
        string $county,
        string $city,
        string $description,
        string $note,
        string $rangeCalculationMethod
    ): static {
        $this->name = trim($name);
        $this->taxType = $taxType;
        $this->geoType = $geoType;
        $this->country = trim($country);
        $this->state = trim($state);
        $this->county = trim($county);
        $this->city = trim($city);
        $this->description = trim($description);
        $this->note = trim($note);
        $this->rangeCalculationMethod = trim($rangeCalculationMethod);
        return $this;
    }
}
