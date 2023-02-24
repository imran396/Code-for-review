<?php
/**
 * Post producing of Account entity
 *
 * SAM-3942: Account entity maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 25, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Account\Save;

use Account;
use Exception;
use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Bidding\BidIncrement\Save\BidIncrementProducerAwareTrait;
use Sam\BuyersPremium\Clone\BuyersPremiumClonerCreateTrait;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Email\Save\EmailTemplateProducerAwareTrait;
use Sam\File\FilePathHelperAwareTrait;
use Sam\File\Manage\LocalFileManagerCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lang\TranslationManagerAwareTrait;
use Sam\Lang\ViewLanguage\Load\ViewLanguageLoaderAwareTrait;
use Sam\Settings\Save\SettingsProducerCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Settings\TermsAndConditionsManagerAwareTrait;
use Sam\Settings\Validate\SettingsExistenceCheckerCreateTrait;
use Sam\Storage\Entity\Copy\EntityClonerCreateTrait;

/**
 * Class AccountMakerPostProducer
 * @package Sam\EntityMaker\Account
 */
class AccountMakerPostProducer extends CustomizableClass
{
    use AccountLoaderAwareTrait;
    use BidIncrementProducerAwareTrait;
    use BuyersPremiumClonerCreateTrait;
    use ConfigRepositoryAwareTrait;
    use CurrentDateTrait;
    use DbConnectionTrait;
    use EmailTemplateProducerAwareTrait;
    use EntityClonerCreateTrait;
    use FilePathHelperAwareTrait;
    use LocalFileManagerCreateTrait;
    use SettingsExistenceCheckerCreateTrait;
    use SettingsManagerAwareTrait;
    use SettingsProducerCreateTrait;
    use TermsAndConditionsManagerAwareTrait;
    use TranslationManagerAwareTrait;
    use ViewLanguageLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Account $account
     * @param int $editorUserId
     *
     */
    public function create(Account $account, int $editorUserId): void
    {
        $mainAccountId = $this->cfg()->get('core->portal->mainAccountId');
        $this->getBidIncrementProducer()->cloneFromAccount($mainAccountId, $account->Id, $editorUserId);
        $this->createBuyersPremiumCloner()->cloneFromAccount($mainAccountId, $account->Id, $editorUserId);
        $this->getEmailTemplateProducer()->cloneFromAccount($mainAccountId, $account->Id, $editorUserId);
        $this->getTermsAndConditionsManager()->cloneFromAccount($mainAccountId, $account->Id, $editorUserId);
        $this->addSystemParameters($account->Id, $editorUserId);
        $this->addTranslation($account);
        $this->syncTranslations($account->Id);
    }

    /**
     * @param int $accountId
     * @param int $editorUserId
     */
    public function update(int $accountId, int $editorUserId): void
    {
        if (!$this->createSettingsExistenceChecker()->exist($accountId)) {
            $this->addSystemParameters($accountId, $editorUserId);
        }
        $this->syncTranslations($accountId);
    }

    /**
     * @param int $accountId
     * @param int $editorUserId
     */
    protected function addSystemParameters(int $accountId, int $editorUserId): void
    {
        $this->createSettingsProducer()->createForAccount($accountId, $editorUserId);
    }

    /**
     * @param Account $account
     */
    protected function addTranslation(Account $account): void
    {
        $mainAccount = $this->getAccountLoader()->loadMain();
        foreach ($this->getTranslationManager()->getFileList(path()->translationMaster(), true) as $masterFileName) {
            $langFileName = $this->getTranslationManager()->toLanguageFilename($masterFileName, $mainAccount);
            /**
             * Check if the same file exists in the main language dir.
             * Do not copy language translation from main account only the default translation.
             */
            if (file_exists(path()->translation() . $langFileName)) {
                $this->copyMainTranslation($account, $masterFileName, path()->translation() . $langFileName);
            }
        }
    }

    /**
     * @param Account $account
     * @param string $masterFileName
     * @param string $mainLangFile
     * @param string|null $lang
     */
    protected function copyMainTranslation(Account $account, string $masterFileName, string $mainLangFile, string $lang = null): void
    {
        $langFileName = $this->getTranslationManager()->toLanguageFilename($masterFileName, $account, $lang);
        try {
            $filePath = path()->translation() . $langFileName;
            copy($mainLangFile, $filePath);
            $this->createLocalFileManager()->applyDefaultPermissions($filePath);
        } catch (Exception) {
            log_error("Failed to copy $mainLangFile to " . path()->translation() . $langFileName);
        }
    }

    /**
     * Sync translations for account
     * @param int $accountId
     */
    protected function syncTranslations(int $accountId): void
    {
        $this->getTranslationManager()->syncMasterFiles($accountId);
        $viewLanguages = $this->getViewLanguageLoader()->loadByAccountId($accountId, true);
        foreach ($viewLanguages as $viewLanguage) {
            $this->getTranslationManager()->syncMasterFiles($accountId, $this->getFilePathHelper()->toFilename($viewLanguage->Name));
        }
    }
}
