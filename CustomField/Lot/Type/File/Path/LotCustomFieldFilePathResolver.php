<?php
/**
 * Detect paths for files of lot custom field with file-type
 *
 * SAM-5608: Refactor lot custom field file download for web
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           07/01/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Type\File\Path;

use Sam\Core\Path\PathResolverCreateTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LotCustomFieldFilePathResolver
 * @package Sam\CustomField\Lot\Type\File\Path
 */
class LotCustomFieldFilePathResolver extends CustomizableClass
{
    use PathResolverCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * Return relative path to dir, that stores files of lot custom fields
     * @param int $accountId
     * @return string
     */
    public function detectPath(int $accountId): string
    {
        return substr($this->detectRootPath($accountId), strlen($this->path()->sysRoot()));
    }

    /**
     * Return relative path to file, that is served by lot custom field of file-type
     * @param int $accountId
     * @param string $fileName
     * @return string
     */
    public function detectFilePath(int $accountId, string $fileName): string
    {
        return $this->detectPath($accountId) . '/' . $fileName;
    }

    /**
     * Return absolute path to dir, that stores files of lot custom fields
     * @param int $accountId
     * @return string
     */
    public function detectRootPath(int $accountId): string
    {
        return $this->path()->uploadLotCustomFieldFile() . '/' . $accountId;
    }

    /**
     * Return full path to file, that is served by lot custom field of file-type
     * @param int $accountId
     * @param string $fileName
     * @return string
     */
    public function detectFileRootPath(int $accountId, string $fileName): string
    {
        return $this->detectRootPath($accountId) . '/' . $fileName;
    }
}
