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

namespace Sam\Import\Csv\Lot\Statistic;

use Sam\Core\Service\CustomizableClass;
use Sam\Import\Csv\Base\ImportCsvProcessStatisticInterface;

/**
 * This class contains statistics of processed lots
 *
 * Class LotImportCsvProcessStatistic
 * @package Sam\Import\Csv\Lot\Statistic
 */
class LotImportCsvProcessStatistic extends CustomizableClass implements ImportCsvProcessStatisticInterface
{
    public int $addedImagesQuantity = 0;
    public int $addedLotsQuantity = 0;
    public array $customFieldFiles = [];
    public int $rejectedImagesQuantity = 0;
    public int $updatedLotsQuantity = 0;

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
        if (!$statistic instanceof static) {
            throw new \InvalidArgumentException('Can\'t merge with ' . get_class($statistic));
        }

        $this->addedImagesQuantity += $statistic->addedImagesQuantity;
        $this->addedLotsQuantity += $statistic->addedLotsQuantity;
        $this->customFieldFiles += $statistic->customFieldFiles;
        $this->rejectedImagesQuantity += $statistic->rejectedImagesQuantity;
        $this->updatedLotsQuantity += $statistic->updatedLotsQuantity;

        return $this;
    }
}
