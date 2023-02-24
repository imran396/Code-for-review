<?php
/**
 * Help methods for auction custom field data loading
 *
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 18, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\User\Help;

use Sam\Core\Constants;
use Sam\CustomField\Base\Help\BaseCustomFieldHelper;
use UserCustField;

/**
 * Class UserCustomFieldHelper
 * @package Sam\CustomField\User\Help
 */
class UserCustomFieldHelper extends BaseCustomFieldHelper
{
    protected string $customMethodPrefix = 'UserCustomField_';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array of auction custom field types: key: type id, value: type name
     */
    public function getTypeNames(): array
    {
        $typeNames = array_intersect_key(Constants\CustomField::$typeNames, array_flip(Constants\UserCustomField::$availableTypes));
        return $typeNames;
    }

    /**
     * Check if field is encrypted and support encryption
     *
     * @param UserCustField $userCustomField
     * @return bool
     */
    public function isEncrypted(UserCustField $userCustomField): bool
    {
        return $userCustomField->Encrypted
            && in_array($userCustomField->Type, Constants\UserCustomField::$encryptedTypes, true);
    }

    /**
     * @return array of panels: key: panel id, value: panel name
     */
    public function getPanels(): array
    {
        return Constants\UserCustomField::$panelNames;
    }
}
