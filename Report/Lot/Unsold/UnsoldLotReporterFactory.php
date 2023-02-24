<?php
/**
 * SAM-4687: Refactor "Unsold Lots" report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/03/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * IMPORTANT NOTE: Report any changes of format to your manager and in the ticket you are working on!
 * This might include adding, changing, or moving columns, modifying header names, modifying data or data format
 */

namespace Sam\Report\Lot\Unsold;

use InvalidArgumentException;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Report\Lot\Unsold\Csv\Reporter as CsvReporter;
use Sam\Report\Lot\Unsold\Html\Reporter as HtmlReporter;

/**
 * @package Sam\Report\Lot\Unsold
 */
class UnsoldLotReporterFactory extends CustomizableClass
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
     * @return CsvReporter|HtmlReporter
     */
    public function create(string $viewMode): CsvReporter|HtmlReporter
    {
        if ($viewMode === Constants\Report::CSV) {
            $reporter = CsvReporter::new();
        } elseif ($viewMode === Constants\Report::HTML) {
            $reporter = HtmlReporter::new();
        } else {
            throw new InvalidArgumentException("Unknown view mode: " . $viewMode);
        }
        return $reporter;
    }
}
