<?php
/**
 * SAM-3796: Bidder upload into auction
 * SAM-9366: Refactor Sam\Bidder\AuctionBidder\CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Bidder\Statistic;

use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * This class is responsible for generating messages about the results of the import of bidders
 *
 * Class BidderImportCsvFinalStatMaker
 * @package Sam\Import\Csv\Bidder\Statistic
 */
class BidderImportCsvFinalStatMaker extends CustomizableClass
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
     * Make a success message
     *
     * @return array[]
     */
    public function make(): array
    {
        return [['type' => 'success', 'text' => $this->getAdminTranslator()->trans('import.csv.bidder.stat.success', [], 'admin_message')]];
    }
}
