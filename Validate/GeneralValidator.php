<?php
/**
 *
 * SAM-4737: General Validator
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-03-08
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class GeneralValidator
 * @package Sam\Validate
 */
class GeneralValidator extends CustomizableClass
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
     * Checks if the string has allowed html tags
     * @param string $string Checked string
     * @return bool @c true if the string has allowed html tags, @c false otherwise
     */
    public function hasAllowedHtmlTags(string $string): bool
    {
        return $string === strip_tags($string, implode('', $this->cfg()->get('core->entity->htmlTagWhitelist')->toArray()));
    }

    /**
     * Returns whether or not image file.
     * Impure function, because reads files.
     *
     * @param string $fileName
     * @return bool
     */
    public function isValidImageFile(string $fileName): bool
    {
        $mimeType = ['image/gif', 'image/jpeg', 'image/png'];
        $imgInfo = @getimagesize($fileName);
        return $imgInfo && in_array($imgInfo["mime"], $mimeType, true);
    }
}
