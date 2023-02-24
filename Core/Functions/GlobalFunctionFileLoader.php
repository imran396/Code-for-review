<?php
/**
 * SAM-5727: Move includes/auto_includes global functions
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Functions;

/**
 * Responsible for including files that contain global functions
 *
 * Class GlobalFunctionFileLoader
 * @package Sam\Core\Functions
 */
class GlobalFunctionFileLoader
{
    private const IMPLEMENTATION_PATH = 'Global/Implementation';

    /**
     * List of files that contain global functions
     * @var array
     */
    private const FILES = ['path', 'cfg', 'html', 'inlinehelp', 'logger', 'translation'];

    /**
     * Load all global functions
     */
    public static function load(): void
    {
        foreach (self::FILES as $file) {
            self::loadFile($file);
        }
    }

    /**
     * load path resolver function
     */
    public static function loadPath(): void
    {
        self::loadFile('path');
    }

    /**
     * @param string $file
     */
    private static function loadFile(string $file): void
    {
        require_once sprintf(
            '%s/%s/%s.inc.php',
            __DIR__,
            self::IMPLEMENTATION_PATH,
            $file
        );
    }
}
