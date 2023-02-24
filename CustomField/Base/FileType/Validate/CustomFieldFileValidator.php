<?php
/**
 * SAM-7846: Refactor \Lot_Upload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Base\FileType\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Contains methods for validating custom field data of type file
 *
 * Class FileTypeValidator
 * @package Sam\CustomField\Base\FileType\Validate
 */
class CustomFieldFileValidator extends CustomizableClass
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
     * Custom file_exists function
     *
     * @param string|null $fileName the filename
     * @param bool $isCaseSensitive optional case-sensitive or case-insensitive file exists checking
     * @return bool
     */
    public function isFileExist(?string $fileName, bool $isCaseSensitive = true): bool
    {
        $fileManager = call_user_func([$this->cfg()->get('core->filesystem->managerClass'), 'new']);
        $filePath = substr($fileName, strlen(path()->sysRoot()));

        if ($fileManager->exist($filePath)) {
            return true;
        }
        if ($isCaseSensitive) {
            return false;
        }

        // Case insensitive search
        $lowerFileName = strtolower($fileName);
        foreach (glob(dirname($fileName) . '/*') as $file) {
            if (strtolower($file) === $lowerFileName) {
                return true;
            }
        }
        return false;
    }

    /**
     * Validate uploaded file type by its extension
     *
     * @param string|null $fileName
     * @param string|null $allowedTypeList
     * @return bool
     */
    public function isValidFileType(?string $fileName, ?string $allowedTypeList = ''): bool
    {
        return $allowedTypeList === ''
            || in_array(pathinfo($fileName, PATHINFO_EXTENSION), explode('|', $allowedTypeList), true);
    }
}
