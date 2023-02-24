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

namespace Sam\View\Admin\Form\TaxSchemaEditForm;

use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\Qform\Longevity\FormStateLongevityAwareTrait;
use Sam\View\Admin\Form\TaxSchemaEditForm\Load\TaxDefinitionDto;

/**
 * Class TaxSchemaTaxDefinitionTableStateManager
 * @package Sam\View\Admin\Form\TaxSchemaEditForm
 */
class TaxSchemaTaxDefinitionTableStateManager extends CustomizableClass
{
    use FormStateLongevityAwareTrait;

    /**
     * @var TaxDefinitionDto[]
     */
    protected array $definitionDtos;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param TaxDefinitionDto[] $definitionDtos
     * @return static
     */
    public function construct(array $definitionDtos): static
    {
        $this->definitionDtos = ArrayHelper::indexEntities($definitionDtos, 'id');
        return $this;
    }

    public function add(array $definitionDtos): static
    {
        $this->definitionDtos += ArrayHelper::indexEntities($definitionDtos, 'id');
        return $this;
    }

    public function remove(int $id): static
    {
        unset($this->definitionDtos[$id]);
        return $this;
    }

    /**
     * @return TaxDefinitionDto[]
     */
    public function getState(): array
    {
        return $this->definitionDtos;
    }

    /**
     * @return int[]
     */
    public function getDefinitionIds(): array
    {
        return array_keys($this->definitionDtos);
    }

    public function count(): int
    {
        return count($this->definitionDtos);
    }
}
