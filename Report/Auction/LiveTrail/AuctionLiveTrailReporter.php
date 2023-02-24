<?php
/**
 * SAM-4619: Refactor live trail report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/22/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\LiveTrail;

use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Report\Base\Csv\ReporterBase;

/**
 * Class AuctionLiveTrailReporter
 * @package Sam\Report\Auction\LiveTrail
 */
class AuctionLiveTrailReporter extends ReporterBase
{
    use FilterAuctionAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return string
     */
    public function output(): string
    {
        $logFileRootPath = $this->getLogFileRootPath();
        if (file_exists($logFileRootPath)) {
            $this->sendHttpHeader();
            $this->outputBody();
        } else {
            echo "Live Trail log file not found" . composeSuffix(['filename' => $logFileRootPath]);
        }
        return '';
    }

    /**
     * @return string
     */
    public function getOutputFileName(): string
    {
        if ($this->outputFileName === null) {
            $this->outputFileName = "live_auction_trail_" . $this->getFilterAuctionId() . ".csv";
        }
        return $this->outputFileName;
    }

    /**
     * It outputs file
     * @return string
     */
    protected function outputBody(): string
    {
        readfile($this->getLogFileRootPath());
        return '';
    }

    /**
     * @return string
     */
    protected function getLogFileRootPath(): string
    {
        $logFileRootPath = path()->docRoot() . '/lot-info/rtb-' . $this->getFilterAuctionId() . '.log';
        return $logFileRootPath;
    }
}
