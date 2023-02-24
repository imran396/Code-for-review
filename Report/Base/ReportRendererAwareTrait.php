<?php
/**
 * SAM-4616: Reports code refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/22/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Base;

/**
 * Trait ReportRendererAwareTrait
 * @package Sam\Report\Base
 */
trait ReportRendererAwareTrait
{
    /**
     * @var ReportRenderer|null
     */
    protected ?ReportRenderer $reportRenderer = null;

    /**
     * @return ReportRenderer
     */
    protected function getReportRenderer(): ReportRenderer
    {
        if ($this->reportRenderer === null) {
            $this->reportRenderer = ReportRenderer::new();
        }
        return $this->reportRenderer;
    }

    /**
     * @param ReportRenderer $reportRenderer
     * @return static
     * @internal
     */
    public function setReportRenderer(ReportRenderer $reportRenderer): static
    {
        $this->reportRenderer = $reportRenderer;
        return $this;
    }
}
