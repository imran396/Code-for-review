<?php
/**
 * SAM-10589: Supply uniqueness of lot item fields: lot custom fields
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 09, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Common;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Text\TextTransformer;

/**
 * Class CustomFieldHelper
 * @package Sam\EntityMaker\Base\Common
 */
class CustomFieldHelper extends CustomizableClass
{
    public const CUSTOM_FIELD_PREFIX = 'customField';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return custom fields tag name
     * @param string $customFieldName
     * @return string
     */
    public function makeCustomFieldsTagName(string $customFieldName): string
    {
        return self::CUSTOM_FIELD_PREFIX . TextTransformer::new()->toCamelCase(strtolower($customFieldName));
    }
}
