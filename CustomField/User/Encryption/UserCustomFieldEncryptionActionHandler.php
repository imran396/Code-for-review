<?php
/**
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 6, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\User\Encryption;

use ActionQueue;
use Sam\ActionQueue\ActionQueueManagerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\User\Load\UserCustomFieldLoaderAwareTrait;
use Sam\CustomField\User\Save\UserCustomDataUpdaterAwareTrait;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserCustData\UserCustDataReadRepositoryCreateTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * 2020-07, SAM-6507 i think this class should implement \Sam\ActionQueue\Base\ActionQueueHandlerInterface interface
 * as it implemented for all other *ActionHandler classes.
 * Class UserCustomFieldTranslationManager
 * @package Sam\CustomField\User\Encryption\UserCustomFieldEncryptionActionHandler
 */
class UserCustomFieldEncryptionActionHandler extends CustomizableClass
{
    use ActionQueueManagerAwareTrait;
    use BlockCipherProviderCreateTrait;
    use UserCustDataReadRepositoryCreateTrait;
    use UserCustomDataUpdaterAwareTrait;
    use UserCustomFieldLoaderAwareTrait;
    use UserLoaderAwareTrait;

    private const PROCESS_COUNT = 100;

    /**
     * Returns instance of UserCustomFieldEncryptionActionHandler
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Send email
     * @param ActionQueue $actionQueue
     * @return bool
     * @see ActionQueueHandlerInterface::process()
     */
    public function process(ActionQueue $actionQueue): bool
    {
        $needMore = false;
        $userCustomFieldId = $actionQueue->Data;
        $userCustomField = $this->getUserCustomFieldLoader()->load((int)$userCustomFieldId);
        $editorUserId = $this->getUserLoader()->loadSystemUserId();
        if ($userCustomField) {
            $userCustomDatas = $this->createUserCustDataReadRepository()
                ->filterActive(true)
                ->filterUserCustFieldId($userCustomField->Id)
                ->filterEncrypted(!$userCustomField->Encrypted)
                ->orderById()
                ->limit(self::PROCESS_COUNT)
                ->loadEntities();
            $needMore = count($userCustomDatas) === self::PROCESS_COUNT;
            foreach ($userCustomDatas as $userCustomData) {
                if (
                    !$userCustomField->Encrypted
                    && $userCustomData->Text !== ''
                ) {
                    $userCustomData->Text = $this->createBlockCipherProvider()
                        ->construct()
                        ->decrypt($userCustomData->Text);
                }
                $this->getUserCustomDataUpdater()->save($userCustomData, $userCustomField, $editorUserId);
            }
        }
        if ($needMore) {
            $this->getActionQueueManager()->addToQueue(
                self::class,
                $actionQueue->Data,
                $editorUserId,
                $actionQueue->Identifier,
                $actionQueue->Group,
                $actionQueue->Priority
            );
        }
        return true;
    }
}
