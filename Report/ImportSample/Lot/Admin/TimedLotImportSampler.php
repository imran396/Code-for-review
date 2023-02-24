<?php
/**
 * SAM-4647: Refactor csv import sample builders
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/23/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\ImportSample\Lot\Admin;

use DateInterval;
use DateTimeImmutable;
use Sam\Core\Constants;
use Sam\Date\DateHelperAwareTrait;
use Sam\Report\ImportSample\Lot\LotImportSamplerBase;

/**
 * Class TimedLotImportSampler
 * @package Sam\Report\ImportSample\Lot\Admin
 */
class TimedLotImportSampler extends LotImportSamplerBase
{
    use DateHelperAwareTrait;

    protected ?string $outputFileName = 'timed-items.csv';

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
        $this->setTitles($this->cfg()->get('csv->admin->timed')->toArray());
        $this->sendHttpHeader();
        $this->outputContent();
        return '';
    }

    /**
     * @return void
     */
    protected function outputContent(): void
    {
        // Item num
        $this->sampleValues[Constants\Csv\Lot::ITEM_NUM] = ['767691', '767688', '767689'];
        // Start bidding date
        $startBiddingDate = new DateTimeImmutable("now");
        $sbDateFormatted = $this->getDateHelper()->formatUtcDate($startBiddingDate, null, 'UTC', null, Constants\Date::ISO);
        $this->sampleValues[Constants\Csv\Lot::START_BIDDING_DATE] = [$sbDateFormatted, $sbDateFormatted, $sbDateFormatted];
        // Start closing date
        $startClosingDate = (new DateTimeImmutable("now"))->add(new DateInterval('P100D'));
        $scDateFormatted = $this->getDateHelper()->formatUtcDate($startClosingDate, null, 'UTC', null, Constants\Date::ISO);
        $this->sampleValues[Constants\Csv\Lot::START_CLOSING_DATE] = [$scDateFormatted, $scDateFormatted, $scDateFormatted];
        echo $this->produceContent();
    }
}
