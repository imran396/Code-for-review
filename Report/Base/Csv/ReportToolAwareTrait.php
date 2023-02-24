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

namespace Sam\Report\Base\Csv;

/**
 * Trait ReportToolAwareTrait
 * @package Sam\Report\Base\Csv
 */
trait ReportToolAwareTrait
{
    /**
     * @var ReportTool|null
     */
    protected ?ReportTool $csvReportTool = null;

    /**
     * @return ReportTool
     */
    protected function getReportTool(): ReportTool
    {
        if ($this->csvReportTool === null) {
            $this->csvReportTool = ReportTool::new();
        }
        return $this->csvReportTool;
    }

    /**
     * @param ReportTool $csvReportTool
     * @return static
     * @internal
     */
    public function setReportTool(ReportTool $csvReportTool): static
    {
        $this->csvReportTool = $csvReportTool;
        return $this;
    }
}
