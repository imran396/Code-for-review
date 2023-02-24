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

namespace Sam\Settings;

/**
 * Interface SettingsManagerInterface
 * @package Sam\Settings
 */
interface SettingsManagerInterface
{
    /**
     * Get a particular system parameter
     *
     * @param string $name System parameter name
     * @param int $accountId
     * @return mixed
     */
    public function get(string $name, int $accountId): mixed;

    /**
     * @param string $name
     * @return mixed
     */
    public function getForMain(string $name): mixed;

    /**
     * @param string $name
     * @return mixed
     */
    public function getForSystem(string $name): mixed;
}
