<?php
/**
 * SAM-9264: Refactor \Lot_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\Internal\Dto\LotItem;


trait LotItemEntityMakerDtoFactoryAwareTrait
{
    /**
     * @var LotItemEntityMakerDtoFactory|null
     */
    protected ?LotItemEntityMakerDtoFactory $lotItemEntityMakerDtoFactory = null;

    /**
     * @return LotItemEntityMakerDtoFactory
     */
    protected function getLotItemEntityMakerDtoFactory(): LotItemEntityMakerDtoFactory
    {
        if ($this->lotItemEntityMakerDtoFactory === null) {
            $this->lotItemEntityMakerDtoFactory = LotItemEntityMakerDtoFactory::new();
        }
        return $this->lotItemEntityMakerDtoFactory;
    }

    /**
     * @param LotItemEntityMakerDtoFactory $lotItemEntityMakerDtoFactory
     * @return static
     * @internal
     */
    public function setLotItemEntityMakerDtoFactory(LotItemEntityMakerDtoFactory $lotItemEntityMakerDtoFactory): static
    {
        $this->lotItemEntityMakerDtoFactory = $lotItemEntityMakerDtoFactory;
        return $this;
    }
}
