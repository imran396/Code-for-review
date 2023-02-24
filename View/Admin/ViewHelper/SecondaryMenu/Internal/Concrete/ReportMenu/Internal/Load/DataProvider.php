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

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\ReportMenu\Internal\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Report\Reportico\Access\Management\ReporticoManagementAccessCheckerCreateTrait;

/**
 * Class DataProvider
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\ReportMenu\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use ReporticoManagementAccessCheckerCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $editorUserId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function hasAccessForReporticoManagement(?int $editorUserId, bool $isReadOnlyDb = false): bool
    {
        return $this->createReporticoManagementAccessChecker()->hasAccess($editorUserId, $isReadOnlyDb);
    }
}
