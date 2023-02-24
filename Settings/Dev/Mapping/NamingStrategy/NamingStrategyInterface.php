<?php
/**
 * SAM-4720: Refactor logic of System Parameters management pages to editor modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Dev\Mapping\NamingStrategy;

/**
 * Interface NamingStrategy
 * @package Sam\Settings\Dev\Mapping
 */
interface NamingStrategyInterface
{
    /**
     * Return constant name for table column
     *
     * @param string $columnName
     * @return string
     */
    public function columnToConstantName(string $columnName): string;

    /**
     * Return property name (constant value) for table column
     *
     * @param string $columnName
     * @return string
     */
    public function columnToPropertyName(string $columnName): string;
}
