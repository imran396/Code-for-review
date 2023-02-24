<?php
/**
 * SAM-10551: Adjust SettingsManager for reading and caching data from different tables
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Repository;

/**
 * Class SettingsReadRepositoryInterface
 * @package Sam\Settings\Repository
 */
interface SettingsReadRepositoryInterface
{
    /**
     * @return string
     */
    public function getTable(): string;

    /**
     * Filter by account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue);

    /**
     * Load single entity (the first instance)
     * @return object|null
     */
    public function loadEntity(): ?object;

    /**
     * @return bool - check, if at least one entry is found
     */
    public function exist(): bool;
}
