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

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class TaxSchemaDto
 * @package Sam\View\Admin\Form\TaxSchemaListForm\Load
 */
class TaxSchemaDto extends CustomizableClass
{
    public readonly int $taxSchemaId;
    public readonly int $accountId;
    public readonly string $name;
    public readonly ?int $geoType;
    public readonly string $country;
    public readonly string $state;
    public readonly string $county;
    public readonly string $city;
    public readonly int $amountSource;
    public readonly string $description;
    public readonly string $note;
    public readonly bool $forInvoice;
    public readonly bool $forSettlement;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        int $taxSchemaId,
        int $accountId,
        string $name,
        ?int $geoType,
        string $country,
        string $state,
        string $county,
        string $city,
        bool $forInvoice,
        bool $forSettlement,
        int $amountSource,
        string $description,
        string $note
    ): static {
        $this->taxSchemaId = $taxSchemaId;
        $this->accountId = $accountId;
        $this->name = $name;
        $this->geoType = $geoType;
        $this->country = $country;
        $this->state = $state;
        $this->county = $county;
        $this->city = $city;
        $this->forInvoice = $forInvoice;
        $this->forSettlement = $forSettlement;
        $this->amountSource = $amountSource;
        $this->description = $description;
        $this->note = $note;
        return $this;
    }

    public function fromDbRow(array $row): static
    {
        return self::new()->construct(
            taxSchemaId: (int)$row['id'],
            accountId: (int)$row['account_id'],
            name: $row['name'],
            geoType: Cast::toInt($row['geo_type']),
            country: $row['country'],
            state: $row['state'],
            county: $row['county'],
            city: $row['city'],
            forInvoice: (bool)$row['for_invoice'],
            forSettlement: (bool)$row['for_settlement'],
            amountSource: (int)$row['amount_source'],
            description: $row['description'],
            note: $row['note'],
        );
    }
}
