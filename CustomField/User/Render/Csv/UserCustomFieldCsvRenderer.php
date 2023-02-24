<?php
/**
 *
 * SAM-4814: User Custom Field renderer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-01-22
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\User\Render\Csv;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\CustomField\Base\Render\Csv\CustomFieldCsvRendererBase;
use Sam\CustomField\User\Help\UserCustomFieldHelperAwareTrait;

/**
 * Class UserCustomFieldCsvRenderer
 * @package Sam\CustomField\User\Render\Csv
 *
 * Custom methods can be used there or in customized class (SAM-1573)
 *
 * Optional method called when rendering exported value
 * param UserCustField $userCustomField the custom user field object
 * param UserCustData $userCustomData the custom user field data
 * return string the exported value
 * public function UserCustomField_{Field name}_Render(UserCustField $userCustomField, UserCustData $userCustomData)
 *
 * {Field name} - Camel cased name of custom field (see TextTransformer::toCamelCase())
 */
class UserCustomFieldCsvRenderer extends CustomFieldCsvRendererBase
{
    use UserCustomFieldHelperAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param \UserCustField $userCustomField
     * @param \UserCustData $userCustomData
     * @return string
     */
    public function render(\UserCustField $userCustomField, \UserCustData $userCustomData): string
    {
        $renderMethod = $this->getUserCustomFieldHelper()->makeCustomMethodName($userCustomField->Name, 'Render');
        if (method_exists($this, $renderMethod)) {
            $output = (string)$this->$renderMethod($userCustomField, $userCustomData);
        } else {
            $value = $userCustomField->isNumeric()
                ? $userCustomData->Numeric : Cast::toInt($userCustomData->Text, Constants\Type::F_INT_POSITIVE);
            $output = $this->renderByValue($userCustomField->Type, $value, $userCustomField->Parameters);
        }
        return $output;
    }
}
