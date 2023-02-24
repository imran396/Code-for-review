<?php
/**
 * SAM-9360: Refactor \Lot_PostCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\PostAuction\Internal\Process;

/**
 * Trait RowProcessorCreateTrait
 */
trait RowProcessorCreateTrait
{
    /**
     * @var RowProcessor|null
     */
    protected ?RowProcessor $rowProcessor = null;

    /**
     * @return RowProcessor
     */
    protected function createRowProcessor(): RowProcessor
    {
        return $this->rowProcessor ?: RowProcessor::new();
    }

    /**
     * @param RowProcessor $rowProcessor
     * @return static
     * @internal
     */
    public function setRowProcessor(RowProcessor $rowProcessor): static
    {
        $this->rowProcessor = $rowProcessor;
        return $this;
    }
}
