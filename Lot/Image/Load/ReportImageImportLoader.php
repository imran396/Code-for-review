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

use ReportImageImport;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\ReportImageImport\ReportImageImportReadRepositoryCreateTrait;

/**
 * Class ReportImageImportLoader
 * @package Sam\Lot\Image\Load
 */
class ReportImageImportLoader extends CustomizableClass
{
    use ReportImageImportReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load a ReportImageImport
     * @param int|null $id
     * @param bool $isReadOnlyDb query to read-only db
     * @return ReportImageImport|null
     */
    public function load(?int $id, bool $isReadOnlyDb = false): ?ReportImageImport
    {
        if (!$id) {
            return null;
        }

        $reportImageImport = $this->createReportImageImportReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($id)
            ->loadEntity();
        return $reportImageImport;
    }
}
