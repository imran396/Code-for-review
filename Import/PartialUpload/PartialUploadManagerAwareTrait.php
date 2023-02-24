<?php
/**
 * SAM-9134: Refactor \User_CsvUpload
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\PartialUpload;


/**
 * Trait PartialUploadManagerAwareTrait
 * @package Sam\Upload
 */
trait PartialUploadManagerAwareTrait
{
    /**
     * @var PartialUploadManager|null
     */
    protected ?PartialUploadManager $partialUploadManager = null;

    /**
     * @return PartialUploadManager
     */
    protected function getPartialUploadManager(): PartialUploadManager
    {
        if ($this->partialUploadManager === null) {
            $this->partialUploadManager = PartialUploadManager::new();
        }
        return $this->partialUploadManager;
    }

    /**
     * @param PartialUploadManager $partialUploadManager
     * @return static
     * @internal
     */
    public function setPartialUploadManager(PartialUploadManager $partialUploadManager): static
    {
        $this->partialUploadManager = $partialUploadManager;
        return $this;
    }
}
