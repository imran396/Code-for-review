<?php
/**
 * SAM-9366: Refactor Sam\Bidder\AuctionBidder\CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Bidder\Internal\Dto;

/**
 * Trait DtoFactoryCreateTrait
 * @package Sam\Import\Csv\Bidder\Internal\Dto
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
