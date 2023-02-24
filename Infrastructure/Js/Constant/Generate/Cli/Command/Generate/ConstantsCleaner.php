<?php
/**
 * SAM-8867: Modularize JS constants generation script
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 18, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Js\Constant\Generate\Cli\Command\Generate;


use Sam\Core\Service\CustomizableClass;

/**
 * Class ConstantsCleaner
 * @package Sam\Infrastructure\Js\Constant\Generate\Cli\Command\Generate
 */
class ConstantsCleaner extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function clean(string $jsConstantsPath): void
    {
        if (basename($jsConstantsPath) === 'Constants') {
            // double check first (for safety reasons)
            $this->recursiveRemove($jsConstantsPath);
        }
    }

    protected function recursiveRemove(string $dir, int $level = 0): void
    {
        $structure = glob(rtrim($dir, "/") . '/*');
        if (is_array($structure)) {
            foreach ($structure as $file) {
                if (is_dir($file)) {
                    $this->recursiveRemove($file, $level + 1);
                }
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
        if ($level > 0) {
            rmdir($dir);
        }
    }
}
