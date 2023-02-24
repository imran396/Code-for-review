<?php
/**
 * SAM-9360: Refactor \Lot_PostCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\PostAuction\Internal\Process\Internal\Save;

/**
 * Trait DataSaverCreateTrait
 */
trait DataSaverCreateTrait
{
    /**
     * @var DataSaver|null
     */
    protected ?DataSaver $dataSaver = null;

    /**
     * @return DataSaver
     */
    protected function createDataSaver(): DataSaver
    {
        return $this->dataSaver ?: DataSaver::new();
    }

    /**
     * @param DataSaver $dataSaver
     * @return $this
     * @internal
     */
    public function setDataSaver(DataSaver $dataSaver): static
    {
        $this->dataSaver = $dataSaver;
        return $this;
    }
}
