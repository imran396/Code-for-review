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
 * @since           Sep 29, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\User\Save;

use Sam\Core\Service\CustomizableClass;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Storage\WriteRepository\Entity\UserCustData\UserCustDataWriteRepositoryAwareTrait;
use UserCustData;
use UserCustField;

/**
 * Class UserCustomDataUpdater
 * @package Sam\CustomField\User\Save
 */
class UserCustomDataUpdater extends CustomizableClass
{
    use BlockCipherProviderCreateTrait;
    use UserCustDataWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Save custom field data and perform related actions
     *
     * @param UserCustData $userCustomData
     * @param UserCustField $userCustomField
     * @param int $editorUserId
     * @return void
     */
    public function save(UserCustData $userCustomData, UserCustField $userCustomField, int $editorUserId): void
    {
        if ($userCustomField->Encrypted) {
            $userCustomData->Text = $this->createBlockCipherProvider()
                ->construct()
                ->encrypt($userCustomData->Text);
        }
        $userCustomData->Encrypted = $userCustomField->Encrypted;
        $this->getUserCustDataWriteRepository()->saveWithModifier($userCustomData, $editorUserId);
    }
}
