<?php
/**
 * SAM-10823: Stacked Tax. Location reference with Tax Schema (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LocationEditForm\TaxSchema\Load;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class TaxSchemaDto
 * @package Sam\View\Admin\Form\LocationEditForm\TaxSchema\Load
 */
class TaxSchemaDto extends CustomizableClass
{
    public readonly int $id;
    public readonly string $name;
    public readonly bool $active;
    public readonly ?int $sourceTaxSchemaId;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function construct(int $id, string $name, bool $active, ?int $sourceTaxSchemaId): static
    {
        $this->id = $id;
        $this->name = $name;
        $this->active = $active;
        $this->sourceTaxSchemaId = $sourceTaxSchemaId;

        return $this;
    }

    public function fromDbRow(array $row): TaxSchemaDto
    {
        return self::new()->construct(
            (int)$row['id'],
            $row['name'],
            (bool)$row['active'],
            Cast::toInt($row['source_tax_schema_id'])
        );
    }

}
