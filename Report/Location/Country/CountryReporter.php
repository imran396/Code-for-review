<?php
/**
 * SAM-10541: Add list of viable country and state names and respective abbreviations for soap api documentation
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2022-05-30
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Location\Country;

use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Constants;
use Sam\Report\Base\Csv\ReporterBase;

/**
 * Class CountryReporter
 * @package Sam\Report\Location\Country
 */
class CountryReporter extends ReporterBase
{
    protected ?string $outputFileName = 'CountriesAndStates.csv';

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    protected function outputTitles(): string
    {
        $headerLine = $this->makeLine(['Code', 'Country']);
        return $this->processOutput($headerLine);
    }

    protected function outputBody(): string
    {
        $output = '';
        foreach (AddressRenderer::new()->allCountries() as $code => $country) {
            $output .= $this->processOutput($this->makeLine([$code, $country]));
        }
        $this->buildStates(AddressRenderer::new()->allUsaStates(), Constants\Location::USA_STATES);
        $this->buildStates(AddressRenderer::new()->allCanadaStates(), Constants\Location::CANADIAN_PROVINCES);
        $this->buildStates(AddressRenderer::new()->allMexicoStates(), Constants\Location::MEXICO_STATES);
        return $output;
    }

    protected function buildStates(array $states, string $title): string
    {
        $output = '';
        $output .= $this->processOutput($this->makeLine(['', '']));
        $output .= $this->processOutput($this->makeLine([$title, '']));
        foreach ($states as $code => $state) {
            $output .= $this->processOutput($this->makeLine([$code, $state]));
        }
        return $output;
    }
}
