<?php
/**
 * SAM-10316: Decouple DtoHelperAwareTrait from BaseMakerValidator and BaseMakerProducer
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 21, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Dto;

/**
 * Trait LotItemMakerDtoHelperAwareTrait
 * @package Sam\EntityMaker\LotItem
 */
trait LotItemMakerDtoHelperAwareTrait
{
    protected ?LotItemMakerDtoHelper $lotItemMakerDtoHelper = null;

    /**
     * @return LotItemMakerDtoHelper
     */
    protected function getLotItemMakerDtoHelper(): LotItemMakerDtoHelper
    {
        if ($this->lotItemMakerDtoHelper === null) {
            $this->lotItemMakerDtoHelper = LotItemMakerDtoHelper::new();
        }
        return $this->lotItemMakerDtoHelper;
    }

    /**
     * @param LotItemMakerDtoHelper $lotItemMakerDtoHelper
     * @return static
     * @internal
     */
    public function setLotItemMakerDtoHelper(LotItemMakerDtoHelper $lotItemMakerDtoHelper): static
    {
        $this->lotItemMakerDtoHelper = $lotItemMakerDtoHelper;
        return $this;
    }
}
