<?php
/**
 * SAM-10435: Add csv quick upload form to locations page
 *
 * @author        Victor Pautoff
 * @version       SVN: $Id: $
 * @since         Mar 22, 2022
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 *
 */

namespace Sam\Report\ImportSample\Location;

use Sam\Core\Constants;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Report\ImportSample\ImportSamplerBase;

/**
 * Class LocationImportSampler
 * @package Sam\Report\ImportSample\Location
 */
class LocationImportSampler extends ImportSamplerBase
{
    use ConfigRepositoryAwareTrait;

    protected int $bodyRowCount = 4;
    protected ?string $outputFileName = 'location.csv';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        /**
         * When array values - render each value per csv line, if not enough values in array, we render empty cell.
         * When single value - render the same value for each row.
         */
        $this->sampleValues = [
            Constants\Csv\Location::NAME => ['Statue of Liberty National Monument', 'CN Tower', 'Mexico City Metropolitan Cathedral', 'Tower of London'],
            Constants\Csv\Location::ADDRESS => ['1 Battery Place', '290 Bremner Blvd', 'P.za de la Constitucion S/N', 'Tower of London'],
            Constants\Csv\Location::COUNTRY => ['US', 'CA', 'MX', 'GB'],
            Constants\Csv\Location::CITY => ['New York', 'Toronto', 'Mexico', 'London'],
            Constants\Csv\Location::COUNTY => ['Hudson', 'Toronto', 'Mexico', 'Greater London'],
            Constants\Csv\Location::LOGO => ['1.png', '2.png', '3.png', '4.png'],
            Constants\Csv\Location::STATE => ['NY', 'ON', 'ME', 'London'],
            Constants\Csv\Location::ZIP => ['10004', 'M5V', '06000', 'EC3N 4AB'],
        ];
        return $this;
    }

    /**
     * @return string
     */
    public function output(): string
    {
        $this->setTitles($this->cfg()->get('csv->admin->location')->toArray());
        $this->sendHttpHeader();
        $this->outputContent();
        return '';
    }

    /**
     * @return void
     */
    protected function outputContent(): void
    {
        echo $this->produceContent();
    }

    /**
     * @return string
     */
    protected function produceContent(): string
    {
        $titles = $this->getTitles();
        $headerLine = $this->getReportTool()->makeLine($titles, $this->getEncoding());
        $contentRows[] = $headerLine;

        for ($i = 0; $i < $this->bodyRowCount; $i++) {
            $bodyRow = [];
            foreach (array_keys($titles) as $title) {
                $bodyRow[] = $this->produceValue($title, $i);
            }
            $bodyLine = $this->getReportTool()->makeLine($bodyRow, $this->getEncoding());
            $contentRows[] = $bodyLine;
        }
        $contentCsv = implode('', $contentRows);
        return $contentCsv;
    }
}
