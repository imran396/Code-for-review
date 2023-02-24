<?php
/**
 *
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 19, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Qform\ErrorHandler;

use Sam\Core\Service\CustomizableClass;

class QformErrorHandlerHelper extends CustomizableClass
{
    public const ERROR_REPORT_FILE_NAME_PREFIX = 'qcodo_error_';
    public const ERROR_REPORT_FILE_NAME_EXTENSION = 'html';
    public const ERROR_REPORT_FILE_NAME_TPL = self::ERROR_REPORT_FILE_NAME_PREFIX . '%s_%s.' . self::ERROR_REPORT_FILE_NAME_EXTENSION;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function makeErrorReportFileName(int $timestamp, string $microtime): string
    {
        return sprintf(self::ERROR_REPORT_FILE_NAME_TPL, date('Y-m-d_His', $timestamp), $microtime);
    }

    public function isErrorReportFileNameCorrect(string $fileName): bool
    {
        $prefix = self::ERROR_REPORT_FILE_NAME_PREFIX;
        $extension = self::ERROR_REPORT_FILE_NAME_EXTENSION;
        $pattern = '/^' . preg_quote($prefix, '/') . '[\w\d-]+\.' . preg_quote($extension, '/') . '$/';
        $success = (bool)preg_match($pattern, $fileName, $matches);
        return $success;
    }
}
