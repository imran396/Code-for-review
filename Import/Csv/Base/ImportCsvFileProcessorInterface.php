<?php
/**
 * Common interface for import csv processor classes
 *
 * SAM-5263: CSV upload process change for better UX and reverse proxy timeout handling
 *
 * @package         com.swb.sam
 * @author          Victor Pautoff
 * @since           Sep 02, 2020
 * @copyright       Copyright 2018 by Bidpath, Inc. All rights reserved.
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Base;

/**
 * Interface ImportCsvFileProcessorInterface
 * @package Sam\Upload
 */
interface ImportCsvFileProcessorInterface
{
    /**
     * Process rows chunk from csv file
     *
     * @param int $offset
     * @return ImportCsvProcessResult
     */
    public function process(int $offset): ImportCsvProcessResult;

    /**
     * Validate rows from csv file
     *
     * @return ImportCsvValidationResult
     */
    public function validate(): ImportCsvValidationResult;

    /**
     * Returns the number of non-empty rows without a header
     *
     * @return int
     */
    public function count(): int;
}
