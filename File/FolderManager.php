<?php
/**
 * A helper to work with folders.
 *
 * @copyright   2018 Bidpath, Inc.
 * @author      Maxim Lyubetskiy
 * @package     com.swb.sam2
 * @version     SVN: $Id$
 * @since       Sep 13, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\File;

use DirectoryIterator;
use Exception;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class FolderManager
 */
class FolderManager extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Remove folder recursively
     * @param string $src
     * @return bool
     */
    public function rrmdir(string $src): bool
    {
        if (!is_dir($src) || !is_readable($src)) {
            return false;
        }

        $files = glob($src . '/*');
        foreach ($files as $file) {
            try {
                is_dir($file) ? $this->rrmdir($file) : unlink($file);
            } catch (Exception) {
                log_error('Unable to delete ' . $file);
            }
        }
        rmdir($src);
        return true;
    }

    /**
     * Remove files and folder in directory recursively
     * @param string $src
     * @return bool
     */
    public function clearDir(string $src): bool
    {
        if (!is_dir($src) || !is_readable($src)) {
            return false;
        }
        $dir = opendir($src);
        while (false !== ($file = readdir($dir))) {
            if (($file !== '.') && ($file !== '..')) {
                $full = $src . '/' . $file;
                if (is_dir($full)) {
                    $this->rrmdir($full);
                } else {
                    try {
                        unlink($full);
                    } catch (Exception) {
                        log_error('Unable to delete ' . $full);
                    }
                }
            }
        }
        closedir($dir);
        rmdir($src);
        return true;
    }

    /**
     * Apply permissions to directory recursively
     *
     * @param string $path
     * @param int|null $permissions null - apply default permissions for directory
     */
    public function chmodRecursively(string $path, ?int $permissions = null): void
    {
        if (!$permissions) {
            $permissions = $this->defaultPermissions();
        }
        $dir = new DirectoryIterator($path);
        foreach ($dir as $item) {
            $pathName = $item->getPathname();
            if (strpos($pathName, '..')) {
                continue;
            }
            $oldMask = umask(0);
            @chmod($item->getPathname(), $permissions);
            umask($oldMask);
            if ($item->isDir() && !$item->isDot()) {
                $this->chmodRecursively($item->getPathname(), $permissions);
            }
        }
    }

    /**
     * Reads default permissions for directory creation from installation configuration, where they are represented as octal number in string.
     * Then transform permissions value to decimal integer, that is accepted by chmod() function.
     * @return int
     */
    public function defaultPermissions(): int
    {
        $octalPermissions = $this->cfg()->get('core->filesystem->permissions->directory');
        return (int)octdec($octalPermissions);
    }

    /**
     * Reads permissions for directory creation that stores static thumbnail images.
     * Permissions in installation configuration are represented as octal number in string.
     * Then transform permissions value to decimal integer, that is accepted by chmod() function.
     * @return int
     */
    public function thumbnailPermissions(): int
    {
        $octalPermissions = $this->cfg()->get('core->filesystem->permissions->thumbnailDirectory');
        return (int)octdec($octalPermissions);
    }
}
