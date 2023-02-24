<?php
/**
 * SAM-9264: Refactor \Lot_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\LotItem\Internal\Dto;

/**
 * Trait DtoFactoryCreateTrait
 * @package Sam\Import\Csv\Lot\LotItem\Internal\Dto
 * @internal
 */
trait DtoFactoryCreateTrait
{
    /**
     * @var DtoFactory|null
     */
    protected ?DtoFactory $dtoFactory = null;

    /**
     * @return DtoFactory
     */
    protected function createDtoFactory(): DtoFactory
    {
        return $this->dtoFactory ?: DtoFactory::new();
    }

    /**
     * @param DtoFactory $dtoFactory
     * @return static
     * @internal
     */
    public function setDtoFactory(DtoFactory $dtoFactory): static
    {
        $this->dtoFactory = $dtoFactory;
        return $this;
    }
}
