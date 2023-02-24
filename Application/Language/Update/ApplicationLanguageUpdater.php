<?php
/**
 * This service handles updating of view language in web session for public site in the next scenarios:
 * - Update language on login;
 * - Update language on logout;
 * - Update language when it is changed via site language selector at the bottom of each page;
 * - Update language when it is changed via preferred user language selector at the Profile page;
 *
 * SAM-10418: Extract public site view language updating to separate service
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 08, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Language\Update;

use Sam\Application\Cookie\CookieHelperCreateTrait;
use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lang\ViewLanguage\Load\ViewLanguageLoaderAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use ViewLanguage;

/**
 * Class ApplicationLanguageUpdater
 * @package Sam\Application\Language\Update
 */
class ApplicationLanguageUpdater extends CustomizableClass
{
    use CookieHelperCreateTrait;
    use EditorUserAwareTrait;
    use ParamFetcherForGetAwareTrait;
    use SettingsManagerAwareTrait;
    use ViewLanguageLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Update language in web session when user authorizes at public site.
     * @return void
     */
    public function updateOnLogin(): void
    {
        $newLanguageId = $this->getEditorUserInfo()->ViewLanguage ?? null;
        if ($newLanguageId === null) {
            log_debug(function () {
                $viewLanguageId = $this->createCookieHelper()->getLanguageId();
                $message = 'Public site view language is kept as it was before authorization'
                    . composeSuffix($this->loadLanguageLogInfo($viewLanguageId));
                return $message;
            });
        } else {
            $oldLanguageId = $this->createCookieHelper()->getLanguageId();
            $this->createCookieHelper()->setLanguageId($newLanguageId);
            log_debug(function () use ($newLanguageId, $oldLanguageId) {
                $message = 'Public site view language is updated by preferred user language for web session after login'
                    . composeSuffix($this->loadLanguageLogInfo($newLanguageId, $oldLanguageId));
                return $message;
            });
        }
    }

    /**
     * Update language in web session, when user logs out.
     * @return void
     */
    public function updateOnLogout(): void
    {
        $oldLanguageId = $this->createCookieHelper()->getLanguageId();
        $newLanguageId = (int)$this->getSettingsManager()->getForSystem(Constants\Setting::VIEW_LANGUAGE);
        $this->createCookieHelper()->setLanguageId($newLanguageId);
        log_debug(function () use ($newLanguageId, $oldLanguageId) {
            $message = 'Public site view language is updated by default system language for web session after logout'
                . composeSuffix($this->loadLanguageLogInfo($newLanguageId, $oldLanguageId));
            return $message;
        });
    }

    /**
     * Update language in web session, when it is changed via site language selector at the bottom of public site pages;
     * @return void
     */
    public function updateOnSiteChange(): void
    {
        $oldLanguageId = $this->createCookieHelper()->getLanguageId();
        $newLanguageId = $this->getParamFetcherForGet()->getIntPositiveOrZero(Constants\UrlParam::LANGUAGE) ?: 0;
        if ($oldLanguageId === $newLanguageId) {
            return; // Language is not changed
        }

        $this->createCookieHelper()->setLanguageId($newLanguageId);
        log_debug(function () use ($newLanguageId, $oldLanguageId) {
            $message = 'Public site view language is changed via language selection control'
                . composeSuffix($this->loadLanguageLogInfo($newLanguageId, $oldLanguageId));
            return $message;
        });
    }

    /**
     * Update language in web session, when it is changed via preferred user language selector at the Profile page of public site;
     * @param int $newLanguageId
     * @return void
     */
    public function updateOnPreferredChange(int $newLanguageId): void
    {
        $oldLanguageId = $this->createCookieHelper()->getLanguageId();
        if ($oldLanguageId === $newLanguageId) {
            return; // Language is not changed
        }

        $this->createCookieHelper()->setLanguageId($newLanguageId);
        log_debug(function () use ($newLanguageId, $oldLanguageId) {
            $message = 'Public site view language is update, because user preferred language is changed'
                . composeSuffix($this->loadLanguageLogInfo($newLanguageId, $oldLanguageId));
            return $message;
        });
    }

    protected function loadLanguageLogInfo(?int $newLanguageId, ?int $oldLanguageId = null): string
    {
        $newViewLanguage = $this->getViewLanguageLoader()->load($newLanguageId, null, true);
        $newLogInfo = $this->makeLogInfo($newLanguageId, $newViewLanguage);

        if ($oldLanguageId === null) {
            return $newLogInfo;
        }

        $oldViewLanguage = $this->getViewLanguageLoader()->load($oldLanguageId, null, true);
        $oldLogInfo = $this->makeLogInfo($oldLanguageId, $oldViewLanguage);
        $message = sprintf("%s => %s", $oldLogInfo, $newLogInfo);
        return $message;
    }

    protected function makeLogInfo(?int $viewLanguageId, ?ViewLanguage $viewLanguage): string
    {
        return sprintf(
            "%s (id: %d)%s",
            $viewLanguage->Name ?? 'Default',
            $viewLanguageId,
            $viewLanguage ? ' acc: ' . $viewLanguage->AccountId : ''
        );
    }
}
