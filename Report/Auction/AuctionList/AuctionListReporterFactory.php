<?php
/**
 * SAM-4627: Refactor auction list report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-05-02
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\AuctionList;

use InvalidArgumentException;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionListReportFactory
 */
class AuctionListReporterFactory extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $viewMode
     * @return Csv\Reporter
     */
    public function create(string $viewMode): Csv\Reporter
    {
        if ($viewMode === Constants\Report::CSV) {
            $reporter = Csv\Reporter::new();
        } else {
            throw new InvalidArgumentException("Unknown view mode: " . $viewMode);
        }
        return $reporter;
    }
}
