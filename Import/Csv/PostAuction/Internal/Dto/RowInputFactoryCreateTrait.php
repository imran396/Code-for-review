<?php
/**
 * SAM-9360: Refactor \Lot_PostCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\PostAuction\Internal\Dto;

/**
 * Trait RowInputFactoryCreateTrait
 */
trait RowInputFactoryCreateTrait
{
    /**
     * @var RowInputFactory|null
     */
    protected ?RowInputFactory $rowInputFactory = null;

    /**
     * @return RowInputFactory
     */
    protected function createRowInputFactory(): RowInputFactory
    {
        return $this->rowInputFactory ?: RowInputFactory::new();
    }

    /**
     * @param RowInputFactory $rowInputFactory
     * @return static
     * @internal
     */
    public function setRowInputFactory(RowInputFactory $rowInputFactory): static
    {
        $this->rowInputFactory = $rowInputFactory;
        return $this;
    }
}
