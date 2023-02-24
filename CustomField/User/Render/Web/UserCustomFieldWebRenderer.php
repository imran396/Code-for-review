<?php
/**
 *
 * SAM-5404: User custom field data rendering at web side
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-09-24
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\User\Render\Web;

use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\User\Help\UserCustomFieldHelperAwareTrait;
use Sam\CustomField\User\Qform\ViewControls;
use UserCustData;
use UserCustField;

/**
 * Class UserCustomFieldWebRenderer
 * @package Sam\CustomField\User\Render\Web
 *
 * Custom methods can be used there or in customized class (SAM-1573)
 *
 * Optional method called when rendering exported value
 * param UserCustField $userCustomField the custom user field object
 * param UserCustData $userCustomData the custom user field data
 * return string the exported value
 * public function UserCustomField_{Field name}_Render(UserCustField $userCustomField, UserCustData $userCustomData)
 *
 * {Field name} - Camel cased name of custom field
 */
class UserCustomFieldWebRenderer extends CustomizableClass
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
     * @param UserCustField $userCustomField
     * @param UserCustData $userCustomData
     * @return string
     */
    public function render(UserCustField $userCustomField, UserCustData $userCustomData): string
    {
        $renderMethod = $this->getUserCustomFieldHelper()->makeCustomMethodName($userCustomField->Name, 'Render');
        if (method_exists($this, $renderMethod)) {
            $output = (string)$this->$renderMethod($userCustomField, $userCustomData);
        } else {
            $output = ViewControls::new()
                ->enableTranslating(true)
                ->renderByCustData($userCustomField, $userCustomData);
        }
        return $output;
    }
}
