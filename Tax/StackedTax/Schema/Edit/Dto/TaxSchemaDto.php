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

namespace Sam\Tax\StackedTax\Schema\Edit\Dto;

use Sam\Core\Service\CustomizableClass;

/**
 * Class TaxSchemaDto
 * @package Sam\Tax\StackedTax\Schema\Edit\Dto
 */
class TaxSchemaDto extends CustomizableClass
{
    public readonly ?int $geoType;
    public readonly string $city;
    public readonly string $country;
    public readonly string $county;
    public readonly string $description;
    public readonly string $name;
    public readonly string $note;
    public readonly string $state;
    public readonly bool $forInvoice;
    public readonly bool $forSettlement;
    public readonly int $amountSource;
    public readonly array $lotCategoryIds;
    public readonly array $taxDefinitionIds;

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
        ?int $geoType,
        string $country,
        string $state,
        string $county,
        string $city,
        string $description,
        string $note,
        bool $forInvoice,
        bool $forSettlement,
        int $amountSource,
        array $lotCategoryIds,
        array $taxDefinitionIds
    ): static {
        $this->name = trim($name);
        $this->geoType = $geoType;
        $this->country = trim($country);
        $this->state = trim($state);
        $this->county = trim($county);
        $this->city = trim($city);
        $this->description = trim($description);
        $this->note = trim($note);
        $this->forInvoice = $forInvoice;
        $this->forSettlement = $forSettlement;
        $this->amountSource = $amountSource;
        $this->lotCategoryIds = $lotCategoryIds;
        $this->taxDefinitionIds = $taxDefinitionIds;
        return $this;
    }
}
