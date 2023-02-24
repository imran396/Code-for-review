<?php
/**
 * SAM-9614: Refactor PartialUploadManager
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\User\Statistic;

use InvalidArgumentException;
use Sam\Core\Service\CustomizableClass;
use Sam\Import\Csv\Base\ImportCsvProcessStatisticInterface;

/**
 * This class contains statistics of processed users
 *
 * Class UserImportCsvProcessStatistic
 * @package Sam\Import\Csv\User\Statistic
 */
class UserImportCsvProcessStatistic extends CustomizableClass implements ImportCsvProcessStatisticInterface
{
    public int $addedUsersQuantity = 0;
    public int $updatedUsersQuantity = 0;
    public array $customFieldFiles = [];

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

        $this->addedUsersQuantity += $statistic->addedUsersQuantity;
        $this->updatedUsersQuantity += $statistic->updatedUsersQuantity;
        $this->customFieldFiles = array_merge($this->customFieldFiles, $statistic->customFieldFiles);
        return $this;
    }
}
