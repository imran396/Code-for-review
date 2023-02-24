<?php
/**
 * SAM-10435: Add csv quick upload form to locations page
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 24, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Location\Statistic;

use InvalidArgumentException;
use Sam\Core\Service\CustomizableClass;
use Sam\Import\Csv\Base\ImportCsvProcessStatisticInterface;

/**
 * This class contains statistics of processed locations
 *
 * Class LocationImportCsvProcessStatistic
 * @package Sam\Import\Csv\Location\Statistic
 */
class LocationImportCsvProcessStatistic extends CustomizableClass implements ImportCsvProcessStatisticInterface
{
    public int $addedLocationsQuantity = 0;
    public int $updatedLocationsQuantity = 0;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param self $statistic
     * @return static
     */
    public function merge($statistic): static
    {
        if (!$statistic instanceof self) {
            throw new InvalidArgumentException('Can\'t merge with ' . get_class($statistic));
        }

        $this->addedLocationsQuantity += $statistic->addedLocationsQuantity;
        $this->updatedLocationsQuantity += $statistic->updatedLocationsQuantity;
        return $this;
    }
}
