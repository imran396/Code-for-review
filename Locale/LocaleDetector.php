<?php
/**
 * SAM-5884: Admin side internationalization
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 06, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Locale;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class LocaleDetector
 * @package Sam\Locale
 */
class LocaleDetector extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use EditorUserAwareTrait;
    use SettingsManagerAwareTrait;
    use SystemAccountAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * Detect locale based on user and account configuration or return default if not set
     * @return string
     */
    public function getLocale(): string
    {
        $locale = $this->getEditorUserInfo()->Locale ?? null;
        if ($locale) {
            return $locale;
        }

        return $this->detectAccountLocale($this->getSystemAccountId());
    }

    public function detectAccountLocale(int $accountId): string
    {
        $locale = $this->getSettingsManager()->get(Constants\Setting::LOCALE, $accountId);
        if ($locale) {
            return $locale;
        }

        return $this->cfg()->get('core->app->locale->default');
    }
}
