<?php
/**
 * SAM-5925: Allow to set installation timezone and User preferred timezone
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 23, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Timezone\Load\Autocomplete;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Timezone\ApplicationTimezoneProviderAwareTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;

/**
 * Class TimezoneAutocompleteDataProvider
 * @package Sam\Timezone\Load\Autocomplete
 */
class TimezoneAutocompleteDataProvider extends CustomizableClass
{
    use ApplicationTimezoneProviderAwareTrait;
    use ConfigRepositoryAwareTrait;
    use TimezoneLoaderAwareTrait;

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
     * @param string $query
     * @param string|null $suggestTimezone
     * @return array
     */
    public function search(string $query, ?string $suggestTimezone): array
    {
        $availableTimezoneLocations = $this->getApplicationTimezoneProvider()->getAvailableTimezoneLocations();

        if (
            !$suggestTimezone
            || !in_array($suggestTimezone, $availableTimezoneLocations, true)
        ) {
            $suggestTimezone = null;
        }
        $locations = $this->makeSortedTimezoneLocations($availableTimezoneLocations, $suggestTimezone);
        $result = array_filter(
            $locations,
            static function ($timezone) use ($query) {
                return stripos($timezone, $query) !== false;
            }
        );

        if (count($result) === 0) {
            $result = $this->getDefaultTimezoneLocations($suggestTimezone);
        }

        return array_values($result);
    }

    /**
     * @param string|null $priorityTimezoneLocation
     * @return array
     */
    private function getDefaultTimezoneLocations(?string $priorityTimezoneLocation): array
    {
        $locations = $priorityTimezoneLocation ? [$priorityTimezoneLocation] : [];
        $locations[] = $this->cfg()->get('core->app->defaultTimezone');
        return array_unique($locations);
    }

    /**
     * @param array $availableTimezoneLocations
     * @param string|null $priorityTimezoneLocation
     * @return array
     */
    private function makeSortedTimezoneLocations(
        array $availableTimezoneLocations,
        ?string $priorityTimezoneLocation
    ): array {
        $locations = $priorityTimezoneLocation ? [$priorityTimezoneLocation] : [];
        $locations[] = $this->getApplicationTimezoneProvider()->getUserPreferredTimezone()->Location;
        $locations[] = $this->getApplicationTimezoneProvider()->getSystemTimezone()->Location;
        $locations[] = $this->getApplicationTimezoneProvider()->getFallbackTimezone()->Location;
        $locations = array_merge($locations, $this->loadDbTimezoneLocations(), $availableTimezoneLocations);
        $locations = array_unique($locations);
        return $locations;
    }

    /**
     * @return array
     */
    private function loadDbTimezoneLocations(): array
    {
        $timezones = $this->getTimezoneLoader()->loadAll(true);
        return array_map(
            static function (\Timezone $timezone) {
                return $timezone->Location;
            },
            $timezones
        );
    }
}
