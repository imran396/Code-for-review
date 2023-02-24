<?php
/**
 * SAM-9614: Refactor PartialUploadManager
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 01, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Base;

/**
 * Interface ImportCsvProcessStatisticInterface
 * @package Sam\Import\Csv
 */
interface ImportCsvProcessStatisticInterface
{
    /**
     * @param self $statistic
     * @return self
     */
    public function merge($statistic): self;
}
