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


namespace Sam\View\Admin\Form\LocationEditForm\TaxSchema\FormState;

use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\Qform\Longevity\FormStateLongevityAwareTrait;

/**
 * Class LocationTaxSchemaTableStateManager
 * @package  Sam\View\Admin\Form\LocationEditForm\TaxSchema
 */
class LocationTaxSchemaTableStateManager extends CustomizableClass
{
    use FormStateLongevityAwareTrait;

    public array $taxSchemaDtos;

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
    public function construct($taxSchemaDtos): static
    {
        $this->taxSchemaDtos = ArrayHelper::indexEntities($taxSchemaDtos, 'id');
        return $this;
    }

    public function getState(): array
    {
        return $this->taxSchemaDtos;
    }

    public function add($taxSchemaDtos): static
    {
        $this->taxSchemaDtos += ArrayHelper::indexEntities($taxSchemaDtos, 'id');
        return $this;
    }

    public function remove(int $id): static
    {
        unset($this->taxSchemaDtos[$id]);
        return $this;
    }

    /**
     * @return int[]
     */
    public function getTaxSchemaIds(): array
    {
        return array_keys($this->taxSchemaDtos);
    }
}
