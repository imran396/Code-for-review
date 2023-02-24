<?php
/**
 * General repository for ReportImageImportReadRepository Parameters entity
 *
 * SAM-3685:Image related repositories https://bidpath.atlassian.net/browse/SAM-3685
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           01 May, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * Usage example:
 * $reportImageImportRepository = \Sam\Storage\ReadRepository\Entity\ReportImageImport\ReportImageImportReadRepository::new()
 *      ->enableReadOnlyDb(true)
 *      ->filterAccountId($accountIds);
 * $isFound = $reportImageImportRepository->exist();
 * $count = $reportImageImportRepository->count();
 * $item = $reportImageImportRepository->loadEntity();
 * $items = $reportImageImportRepository->loadEntities();
 */

namespace Sam\Storage\ReadRepository\Entity\ReportImageImport;

/**
 * Class ReportImageImportReadRepository
 */
class ReportImageImportReadRepository extends AbstractReportImageImportReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
