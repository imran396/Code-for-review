<?php
/**
 * General repository for CustomCsvExportConfigReadRepository Parameters entity
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
 * $customCsvExportConfigRepository = \Sam\Storage\ReadRepository\Entity\CustomCsvExportConfig\CustomCsvExportConfigReadRepository::new()
 *      ->enableReadOnlyDb(true)
 *      ->filterAccountId($accountIds);
 * $isFound = $customCsvExportConfigRepository->exist();
 * $count = $customCsvExportConfigRepository->count();
 * $item = $customCsvExportConfigRepository->loadEntity();
 * $items = $customCsvExportConfigRepository->loadEntities();
 */

namespace Sam\Storage\ReadRepository\Entity\CustomCsvExportConfig;

/**
 * Class CustomCsvExportConfigReadRepository
 */
class CustomCsvExportConfigReadRepository extends AbstractCustomCsvExportConfigReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
