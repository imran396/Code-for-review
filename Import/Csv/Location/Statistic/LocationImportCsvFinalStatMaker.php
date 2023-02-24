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

use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * This class is responsible for generating messages about the result of the location import process
 *
 * Class BidIncrementImportCsvFinalStatMaker
 * @package Sam\Import\Csv\Location\Statistic
 */
class LocationImportCsvFinalStatMaker extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Make a messages based on the collected statistics
     *
     * @param LocationImportCsvProcessStatistic $statistic
     * @return array
     */
    public function make(LocationImportCsvProcessStatistic $statistic): array
    {
        $stat = [];

        $translator = $this->getAdminTranslator();
        $messages = [];
        if ($statistic->addedLocationsQuantity > 0) {
            $messages[] = $translator->trans('import.csv.location.stat.added_locations', ['qty' => $statistic->addedLocationsQuantity], 'admin_message');
        }
        if ($statistic->updatedLocationsQuantity > 0) {
            $messages[] = $translator->trans('import.csv.location.stat.updated_locations', ['qty' => $statistic->updatedLocationsQuantity], 'admin_message');
        }

        if ($messages) {
            $stat[] = ['type' => 'success', 'text' => implode(' ', $messages)];
        }

        return $stat;
    }
}
