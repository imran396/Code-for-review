<?php
/**
 * SAM-4616: Reports code refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/19/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Base\Tab;

/**
 * Trait ReportToolAwareTrait
 * @package Sam\Report\Base\Csv
 */
trait ReportToolAwareTrait
{
    /**
     * @var ReportTool|null
     */
    protected ?ReportTool $tabReportTool = null;

    /**
     * @return ReportTool
     */
    protected function getReportTool(): ReportTool
    {
        if ($this->tabReportTool === null) {
            $this->tabReportTool = ReportTool::new();
        }
        return $this->tabReportTool;
    }

    /**
     * @param ReportTool $tabReportTool
     * @return static
     */
    public function setReportTool(ReportTool $tabReportTool): static
    {
        $this->tabReportTool = $tabReportTool;
        return $this;
    }
}
