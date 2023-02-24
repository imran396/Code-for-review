<?php
/**
 * SAM-11587: Refactor Qform_UploadHelper for v4.0
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 19, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\ServerConfiguration;

use Sam\Core\Service\CustomizableClass;

/**
 * Class ServerConfigurationReader
 * @package Sam\Application\ServerConfiguration
 */
class ServerConfigurationReader extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Upload max file size in bytes
     *
     * @return int
     */
    public function uploadMaxFileSize(): int
    {
        $uploadMaFileSize = ini_get('upload_max_filesize');
        return $this->parseIniSizeValue($uploadMaFileSize);
    }

    /**
     * Post max size in bytes
     *
     * @return int
     */
    public function postMaxSize(): int
    {
        $postMaxSize = ini_get('post_max_size');
        return $this->parseIniSizeValue($postMaxSize);
    }

    protected function parseIniSizeValue(string $sizeValue): int
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $sizeValue); // Remove the non-unit characters from the size.
        $size = (float)preg_replace('/[^0-9.]/', '', $sizeValue); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return (int)round($size * (1024 ** stripos('bkmgtpezy', $unit[0])));
        }
        return (int)round($size);
    }
}
