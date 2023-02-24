<?php
/**
 * SAM-7670: Make responsive Translator independent of web application context
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Language\Detect;

use Sam\Application\Cookie\CookieHelperCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class LanguageDetector
 * @package Sam\Application\Language
 */
class ApplicationLanguageDetector extends CustomizableClass
{
    use CookieHelperCreateTrait;
    use SettingsManagerAwareTrait;
    use SystemAccountAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Detect language id from application context - defined in cookie or default set in system parameters.
     * Since this service is intended for web application, we must use the account of visiting domain,
     * because the system account defines available languages in the selection drop-down list at the bottom of the public site pages.
     * In other words, we can't pass another domain account, because it has translations of another languages.
     * "0" is legal language id, that has the first installation language. It has "Default" name. It isn't obligatory actual default language.
     * @param int|null $systemAccountId null - use system account.
     * @return int
     */
    public function detectActiveLanguageId(?int $systemAccountId = null): int
    {
        $languageId = $this->createCookieHelper()->getLanguageId();
        if ($languageId !== null) { // It can be "0" for default installation language (not obligatory main)
            return $languageId;
        }

        $systemAccountId = $systemAccountId ?? $this->getSystemAccountId();
        return (int)$this->getSettingsManager()
            ->get(Constants\Setting::VIEW_LANGUAGE, $systemAccountId);
    }
}
