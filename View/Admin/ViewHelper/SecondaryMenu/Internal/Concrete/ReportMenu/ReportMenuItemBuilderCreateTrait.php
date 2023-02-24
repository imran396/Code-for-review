<?php
/**
 * SAM-9573: Refactor admin secondary menu for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\ReportMenu;

/**
 * Trait ReportMenuItemBuilderCreateTrait
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\ReportMenu
 */
trait ReportMenuItemBuilderCreateTrait
{
    protected ?ReportMenuItemBuilder $reportMenuItemBuilder = null;

    /**
     * @return ReportMenuItemBuilder
     */
    protected function createReportMenuItemBuilder(): ReportMenuItemBuilder
    {
        return $this->reportMenuItemBuilder ?: ReportMenuItemBuilder::new();
    }

    /**
     * @param ReportMenuItemBuilder $reportMenuItemBuilder
     * @return $this
     * @internal
     */
    public function setReportMenuItemBuilder(ReportMenuItemBuilder $reportMenuItemBuilder): static
    {
        $this->reportMenuItemBuilder = $reportMenuItemBuilder;
        return $this;
    }
}
