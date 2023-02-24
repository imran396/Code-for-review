<?php
/**
 * SAM-11239: Stacked Tax. Store invoice tax amounts per definition
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 15, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\ServiceFeeEditForm\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class TaxDefinitionDto
 * @package Sam\View\Admin\Form\ServiceFeeEditForm\Load
 */
class TaxDefinitionDto extends CustomizableClass
{
    public readonly int $id;
    public readonly string $name;
    public readonly float $collectedAmount;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(int $id, string $name, float $collectedAmount): static
    {
        $this->id = $id;
        $this->name = $name;
        $this->collectedAmount = $collectedAmount;
        return $this;
    }

    public function fromDbRow(array $row): static
    {
        $dto = self::new()->construct(
            id: (int)$row['id'],
            name: (string)$row['name'],
            collectedAmount: (float)$row['collected_amount']
        );
        return $dto;
    }
}
