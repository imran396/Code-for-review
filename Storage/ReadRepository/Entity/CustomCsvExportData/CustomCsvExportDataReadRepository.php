<?php
/**
 * General repository for CustomCsvExportDataReadRepository Parameters entity
 *
 * SAM-3682: Reports related repositories https://bidpath.atlassian.net/browse/SAM-3682
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           07 Apr, 2017
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
 * $customCsvExportDataRepository = \Sam\Storage\ReadRepository\Entity\CustomCsvExportData\CustomCsvExportDataReadRepository::new()
 *      ->enableReadOnlyDb(true)
 *      ->filterCategoryId($categoryIds);
 * $isFound = $customCsvExportDataRepository->exist();
 * $count = $customCsvExportDataRepository->count();
 * $item = $customCsvExportDataRepository->loadEntity();
 * $items = $customCsvExportDataRepository->loadEntities();
 */

namespace Sam\Storage\ReadRepository\Entity\CustomCsvExportData;

/**
 * Class CustomCsvExportDataReadRepository
 */
class CustomCsvExportDataReadRepository extends AbstractCustomCsvExportDataReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
