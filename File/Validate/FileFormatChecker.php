<?php

namespace Sam\File\Validate;

use Sam\Core\Service\CustomizableClass;

/**
 * Class FileFormatChecker
 * @package Sam\File\Validate
 */
class FileFormatChecker extends CustomizableClass
{
    /**
     * Return instance of self
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check if file has pdf format (simple checking)
     * @param string $fileRootPath
     * @return bool
     */
    public static function isPdf(string $fileRootPath): bool
    {
        $is = false;
        if ($resFh = @fopen($fileRootPath, 'rb')) {
            $chars = @fread($resFh, 5);
            @fclose($resFh);
            $is = ($chars === '%PDF-');
        }
        return $is;
    }
}
