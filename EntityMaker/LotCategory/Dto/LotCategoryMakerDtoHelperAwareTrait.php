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

namespace Sam\EntityMaker\LotCategory\Dto;

/**
 * Trait LotCategoryMakerDtoHelperAwareTrait
 * @package Sam\EntityMaker\LotCategory\Dto
 */
trait LotCategoryMakerDtoHelperAwareTrait
{
    protected ?LotCategoryMakerDtoHelper $lotCategoryMakerDtoHelper = null;

    /**
     * @return LotCategoryMakerDtoHelper
     */
    protected function getLotCategoryMakerDtoHelper(): LotCategoryMakerDtoHelper
    {
        if ($this->lotCategoryMakerDtoHelper === null) {
            $this->lotCategoryMakerDtoHelper = LotCategoryMakerDtoHelper::new();
        }
        return $this->lotCategoryMakerDtoHelper;
    }

    /**
     * @param LotCategoryMakerDtoHelper $lotCategoryMakerDtoHelper
     * @return static
     * @internal
     */
    public function setLotCategoryMakerDtoHelper(LotCategoryMakerDtoHelper $lotCategoryMakerDtoHelper): static
    {
        $this->lotCategoryMakerDtoHelper = $lotCategoryMakerDtoHelper;
        return $this;
    }
}
