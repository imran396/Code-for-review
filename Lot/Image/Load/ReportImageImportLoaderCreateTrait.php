<?php
/**
 * SAM-4740: Avoid calling of load functions from data class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\Load;

/**
 * Trait ReportImageImportLoaderCreateTrait
 * @package Sam\Lot\Image\Load
 */
trait ReportImageImportLoaderCreateTrait
{
    /**
     * @var ReportImageImportLoader|null
     */
    protected ?ReportImageImportLoader $reportImageImportLoader = null;

    /**
     * @return ReportImageImportLoader
     */
    protected function createReportImageImportLoader(): ReportImageImportLoader
    {
        return $this->reportImageImportLoader ?: ReportImageImportLoader::new();
    }

    /**
     * @param ReportImageImportLoader $reportImageImportLoader
     * @return static
     * @internal
     */
    public function setReportImageImportLoader(ReportImageImportLoader $reportImageImportLoader): static
    {
        $this->reportImageImportLoader = $reportImageImportLoader;
        return $this;
    }
}
