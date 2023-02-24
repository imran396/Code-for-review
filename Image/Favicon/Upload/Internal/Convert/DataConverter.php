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

namespace Sam\Image\Favicon\Upload\Internal\Convert;

use Elphin\IcoFileLoader\IcoFileService;
use League\Csv\Exception;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use SVG\SVG;


class DataConverter extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function isIco(string $mimeType): bool
    {
        return $mimeType === 'image/vnd.microsoft.icon';
    }

    public function convertIcoToPngBlob(string $binaryData): string
    {
        $extractorService = new IcoFileService();
        $image = $extractorService->extractIcon($binaryData, 100, 100);
        ob_start();
        imagepng($image);
        $blobImage = ob_get_clean();
        return $blobImage;
    }

    public function convertSvgToPngBlob(string $svg): string
    {
        $blobImage = '';
        try {
            $image = SVG::fromString($svg);
            if ($image) {
                $width = $this->cfg()->get('core->image->favicon->maxWidth');
                $height = $this->cfg()->get('core->image->favicon->maxHeight');
                $gdImage = $image->toRasterImage($width, $height);
                ob_start();
                imagepng($gdImage);
                $blobImage = ob_get_clean();
            }
        } catch (Exception $e) {
            log_error('Failed to convert svg to png', composeSuffix(['errorMessage' => $e->getMessage()]));
        }
        return $blobImage;
    }
}
