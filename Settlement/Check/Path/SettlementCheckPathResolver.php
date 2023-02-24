<?php
/**
 * SAM-7984: Adjustments for Settlement printable with responsive layout [dev only]
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           06-13, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Path;

use Sam\Core\Path\PathResolverCreateTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class SettlementCheckPathResolver
 * @package Sam\Settlement\Check
 */
class SettlementCheckPathResolver extends CustomizableClass
{
    use PathResolverCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return root path for settlement check image file with passed name
     * @param int $accountId
     * @param string $fileName
     * @return string
     */
    public function makeFileRootPath(int $accountId, string $fileName): string
    {
        $fileRootPath = sprintf('%s/%s/%s', $this->path()->uploadSetting(), $accountId, $fileName);
        return $fileRootPath;
    }

    /**
     * Return relative path to settlement check image file
     * @param int $accountId
     * @param string $fileName
     * @return string
     */
    public function makeFilePath(int $accountId, string $fileName): string
    {
        $filePath = substr($this->makeFileRootPath($accountId, $fileName), strlen($this->path()->sysRoot()));
        return $filePath;
    }

    /**
     * Return root path for settlement check image file
     * @param int $accountId
     * @return string
     */
    public function makeRootPath(int $accountId): string
    {
        $rootPath = $this->makeFileRootPath($accountId, '');
        return $rootPath;
    }
}
