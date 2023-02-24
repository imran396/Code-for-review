<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotCategory\Validate;

/**
 * Trait LotCategoryMakerValidatorCreateTrait
 * @package Sam\EntityMaker\LotCategory\Save
 */
trait LotCategoryMakerValidatorCreateTrait
{
    /**
     * @var LotCategoryMakerValidator|null
     */
    protected ?LotCategoryMakerValidator $lotCategoryMakerValidator = null;

    /**
     * @return LotCategoryMakerValidator
     */
    protected function createLotCategoryMakerValidator(): LotCategoryMakerValidator
    {
        return $this->lotCategoryMakerValidator ?: LotCategoryMakerValidator::new();
    }

    /**
     * @param LotCategoryMakerValidator $lotCategoryMakerValidator
     * @return $this
     * @internal
     */
    public function setLotCategoryMakerValidator(LotCategoryMakerValidator $lotCategoryMakerValidator): static
    {
        $this->lotCategoryMakerValidator = $lotCategoryMakerValidator;
        return $this;
    }
}
