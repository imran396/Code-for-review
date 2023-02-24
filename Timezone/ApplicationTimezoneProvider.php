<?php
/**
 * SAM-5925: Allow to set installation timezone and User preferred timezone
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 24, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Timezone;

use Sam\Core\Service\CustomizableClass;
use DateTimeZone;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;
use Timezone;

/**
 * Class ApplicationTimezoneProvider
 * @package Sam\Timezone
 */
class ApplicationTimezoneProvider extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use SettingsManagerAwareTrait;
    use TimezoneLoaderAwareTrait;
    use EditorUserAwareTrait;

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
     * @return Timezone
     */
    public function getUserPreferredTimezone(): Timezone
    {
        $tzId = $this->getUserTimezoneId();
        if ($tzId) {
            return $this->getTimezoneLoader()->load($tzId);
        }
        return $this->getSystemTimezone();
    }

    /**
     * @return Timezone
     */
    public function getSystemTimezone(): Timezone
    {
        $tzId = $this->getSettingsManager()->getForSystem(Constants\Setting::TIMEZONE_ID);
        if ($tzId) {
            return $this->getTimezoneLoader()->load($tzId);
        }
        return $this->getFallbackTimezone();
    }

    /**
     * @param int $accountId
     * @return Timezone
     */
    public function getAccountTimezone(int $accountId): Timezone
    {
        $tzId = $this->getSettingsManager()->get(Constants\Setting::TIMEZONE_ID, $accountId);
        if ($tzId) {
            return $this->getTimezoneLoader()->load($tzId);
        }
        return $this->getFallbackTimezone();
    }

    /**
     * @return array
     */
    public function getAvailableTimezoneLocations(): array
    {
        return DateTimeZone::listIdentifiers();
    }

    /**
     * @param string $timezoneLocation
     * @return bool
     */
    public function isTimezoneAvailable(string $timezoneLocation): bool
    {
        return in_array($timezoneLocation, $this->getAvailableTimezoneLocations(), true);
    }

    /**
     * @return Timezone
     */
    public function getFallbackTimezone(): Timezone
    {
        $fallbackTimezoneLocation = $this->getFallbackTimezoneLocation();
        return $this->getTimezoneLoader()->loadByLocationOrCreatePersisted($fallbackTimezoneLocation);
    }

    /**
     * @return int|null
     */
    private function getUserTimezoneId(): ?int
    {
        return $this->getEditorUserInfo()->TimezoneId ?? null;
    }

    /**
     * @return string
     */
    private function getFallbackTimezoneLocation(): string
    {
        return $this->cfg()->get('core->app->defaultTimezone');
    }
}
