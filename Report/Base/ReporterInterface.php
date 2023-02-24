<?php
/**
 * General interface for reporter classes
 *
 * SAM-4616: Reports code refactoring
 *
 * Notes: we recommend separate reporter logic to methods, eg.
 * sendHttpHeader() - for sending http headers by header(),
 * buildQuery() or prepareRepository() - initialize db access and filters, we may extract data fetching to separate class
 * outputTitles() - for report header titles,
 * outputBody() - for report content.
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/20/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Base;

/**
 * Interface ReporterInterface
 * @package Sam\Report\Base
 */
interface ReporterInterface
{
    /**
     * Return built report output
     * @return string
     */
    public function output(): string;
}
