<?php
/**
 * SAM-9365: Refactor BidIncrementCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\BidIncrement\Statistic;

use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * This class is responsible for generating messages about the result of the import bid increments process
 *
 * Class BidIncrementImportCsvFinalStatMaker
 * @package Sam\Import\Csv\BidIncrement\Internal\Statistic
 */
class BidIncrementImportCsvFinalStatMaker extends CustomizableClass
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
     * Make a success message based on the statistics collected
     *
     * @param BidIncrementImportCsvProcessStatistic $statistic
     * @return array[]
     */
    public function make(BidIncrementImportCsvProcessStatistic $statistic): array
    {
        $messages = [];

        $translator = $this->getAdminTranslator();
        if ($statistic->createdItemsQuantity > 0) {
            $messages[] = $translator->trans(
                'import.csv.bid_increment.stat.added_items',
                ['qty' => $statistic->createdItemsQuantity],
                'admin_message'
            );
        }
        if ($statistic->updatedItemsQuantity > 0) {
            $messages[] = $translator->trans(
                'import.csv.bid_increment.stat.updated_items',
                ['qty' => $statistic->updatedItemsQuantity],
                'admin_message'
            );
        }

        if ($messages) {
            return [
                ['type' => 'success', 'text' => implode(' ', $messages)]
            ];
        }

        return [];
    }
}
