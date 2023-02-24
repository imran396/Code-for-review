<?php
/**
 * SAM-8837: Lot item entity maker module structural adjustments for v3-5
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Dto;

/**
 * Trait InputDtoAwareTrait
 * @package Sam\EntityMaker\Base\Dto
 */
trait InputDtoAwareTrait
{
    protected ?InputDto $inputDto = null;

    /**
     * @return InputDto
     */
    public function getInputDto(): InputDto
    {
        return $this->inputDto;
    }

    /**
     * @param InputDto $inputDto
     * @return $this
     */
    public function setInputDto(InputDto $inputDto): static
    {
        $this->inputDto = $inputDto;
        return $this;
    }
}
