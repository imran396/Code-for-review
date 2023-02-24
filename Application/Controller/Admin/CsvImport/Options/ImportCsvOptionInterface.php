<?php
/**
 * SAM-9614: Refactor PartialUploadManager
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 31, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\CsvImport\Options;

use Sam\Application\RequestParam\RequestParamFetcher;

/**
 * Interface ImportCsvOptionInterface
 * @package Sam\Application\Controller\Admin\CsvImport\Options
 */
interface ImportCsvOptionInterface
{
    /**
     * Construct option object with data from the request
     *
     * @param RequestParamFetcher $paramFetcher
     * @return self
     */
    public function fromRequest(RequestParamFetcher $paramFetcher): self;
}
