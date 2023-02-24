<?php
/**
 * General repository for ActionQueue entity
 *
 * SAM-3727 Application settings/workflow related repositories https://bidpath.atlassian.net/browse/SAM-3727
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          imran rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since          29 June, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of accounts filtered by criteria
 * $timezoneRepository = \Sam\Storage\ReadRepository\Entity\Timezone\TimezoneReadRepository::new()
 *     ->enableReadOnlyDb(true)
 *     ->filterId($id);
 * $isFound = $timezoneRepository->exist();
 * $count = $timezoneRepository->count();
 * $timezones = $timezoneRepository->loadEntities();
 *
 * // Sample2. Load single account
 * $timezoneRepository = \Sam\Storage\ReadRepository\Entity\Timezone\TimezoneReadRepository::new()
 *     ->filterId(1);
 * $timezone = $timezoneRepository->loadEntity();
 *
 */

namespace Sam\Storage\ReadRepository\Entity\Timezone;

/**
 * Class TimezoneReadRepository
 * @package Sam\Storage\ReadRepository\Entity\Timezone
 */
class TimezoneReadRepository extends AbstractTimezoneReadRepository
{
    /** @var string[] */
    protected array $joins = [
        'setting_system' => 'JOIN setting_system setsys ON setsys.timezone_id = tz.id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Join `setting_system` table
     * @return static
     */
    public function joinSettingSystem(): static
    {
        $this->join('setting_system');
        return $this;
    }

    /**
     * @param int|int[] $accountId
     * @return static
     */
    public function joinSettingSystemFilterAccountId(int|array|null $accountId): static
    {
        $this->joinSettingSystem();
        $this->filterArray('setsys.account_id', $accountId);
        return $this;
    }
}
