<?php
/**
 * SAM-11607: Custom favicon
 *
 * @copyright       2023 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Jan 14, 2023
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Image\Favicon\Save\Internal\Generate;

use Exception;
use Imagick;
use Sam\Core\Service\CustomizableClass;


class IconGenerator extends CustomizableClass
{
    protected const MEDIUM_SIZE = 32;
    protected const SMALL_SIZE = 16;
    protected const APPLE_SIZE_ICON = 180;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function generateBaseIcon(string $blobImage): string
    {
        $imagick = new Imagick();
        try {
            $imagick->readImageBlob($blobImage);
            $imagick->setImageFormat('png');
            return $imagick->getImageBlob();
        } catch (Exception $e) {
            log_error("Failed to generate base favicon" . composeSuffix(['error' => $e->getMessage()]));
            return '';
        }
    }

    public function generateMediumIcon(string $blobImage): string
    {
        $imagick = new Imagick();
        try {
            $imagick->readImageBlob($blobImage);
            $imagick->setImageFormat('png');
            $imagick->resizeImage(self::MEDIUM_SIZE, self::MEDIUM_SIZE, Imagick::FILTER_POINT, 1);
            return $imagick->getImageBlob();
        } catch (Exception $e) {
            log_error("Failed to generate medium favicon" . composeSuffix(['error' => $e->getMessage()]));
            return '';
        }
    }

    public function generateSmallIcon(string $blobImage): string
    {
        $imagick = new Imagick();
        try {
            $imagick->readImageBlob($blobImage);
            $imagick->setImageFormat('png');
            $imagick->resizeImage(self::SMALL_SIZE, self::SMALL_SIZE, Imagick::FILTER_POINT, 1);
            return $imagick->getImageBlob();
        } catch (Exception $e) {
            log_error("Failed to generate small favicon" . composeSuffix(['error' => $e->getMessage()]));
            return '';
        }
    }

    public function generateAppleIcon(string $blobImage): string
    {
        $imagick = new Imagick();
        try {
            $imagick->readImageBlob($blobImage);
            $imagick->setImageFormat('png');
            $imagick->resizeImage(self::APPLE_SIZE_ICON, self::APPLE_SIZE_ICON, Imagick::FILTER_POINT, 1);
            return $imagick->getImageBlob();
        } catch (Exception $e) {
            log_error("Failed to generate apple favicon" . composeSuffix(['error' => $e->getMessage()]));
            return '';
        }
    }
}
