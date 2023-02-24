<?php
/**
 * SAM-9561: Refactor support logger
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Log\Support\Internal\Write;

use Sam\Core\Service\CustomizableClass;

/**
 * Class LogWriter
 * @package Sam\Log\Support\Internal\Write
 */
class LogWriter extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $fullMessage
     * @param string $fileRootPath
     * @return void
     */
    public function write(string $fullMessage, string $fileRootPath): void
    {
        error_log($fullMessage, 3, $fileRootPath);
    }

}
