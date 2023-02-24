<?php
/**
 * SAM-9547: Add default reportico reports tab to SAM reports page
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Reportico\Access\Management;

/**
 * Trait ReporticoManagementAccessCheckerCreateTrait
 * @package Sam\Report\Reportico\Access\Management
 */
trait ReporticoManagementAccessCheckerCreateTrait
{
    protected ?ReporticoManagementAccessChecker $reporticoManagementAccessChecker = null;

    /**
     * @return ReporticoManagementAccessChecker
     */
    protected function createReporticoManagementAccessChecker(): ReporticoManagementAccessChecker
    {
        return $this->reporticoManagementAccessChecker ?: ReporticoManagementAccessChecker::new();
    }

    /**
     * @param ReporticoManagementAccessChecker $reporticoManagementAccessChecker
     * @return $this
     * @internal
     */
    public function setReporticoManagementAccessChecker(ReporticoManagementAccessChecker $reporticoManagementAccessChecker): static
    {
        $this->reporticoManagementAccessChecker = $reporticoManagementAccessChecker;
        return $this;
    }
}
