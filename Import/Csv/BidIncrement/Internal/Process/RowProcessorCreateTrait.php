<?php
/**
 * SAM-9365: Refactor BidIncrementCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\BidIncrement\Internal\Process;

/**
 * Trait RowProcessorCreateTrait
 * @package Sam\Import\Csv\BidIncrement\Internal\Process
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
