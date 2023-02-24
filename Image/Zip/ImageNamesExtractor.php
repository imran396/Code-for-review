<?php
/**
 * Extracting image file's names from archive
 *
 * Image zip upload improvement
 * @see https://bidpath.atlassian.net/browse/SAM-3409
 *
 * @copyright   2018 Bidpath, Inc.
 * @author      Maxim Lyubetskiy
 * @package     com.swb.sam2
 * @version     SVN: $Id$
 * @since       Sep 09, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Image\Zip;

use Sam\Core\Service\CustomizableClass;
use Sam\Validate\GeneralValidatorAwareTrait;
use ZipArchive;

/**
 * Class ImageNamesExtractor
 */
class ImageNamesExtractor extends CustomizableClass
{
    use GeneralValidatorAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $zipArchivePath
     * @return array
     */
    public function getNamesFrom(string $zipArchivePath): array
    {
        $zip = new ZipArchive();
        if (!$zip->open($zipArchivePath)) {
            return [];
        }

        if (!$zip->numFiles) {
            return [];
        }

        $images = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $entry = $zip->getNameIndex($i);
            $imageFilePath = sprintf('zip://%s#%s', $zipArchivePath, $entry);
            if ($this->getGeneralValidator()->isValidImageFile($imageFilePath)) {
                $images[] = $imageFilePath;
            }
        }
        $zip->close();
        return $images;
    }

}
