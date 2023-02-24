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

/**
 * Trait LocationImportCsvFinalStatMakerCreateTrait
 * @package Sam\Import\Csv\Location\Statistic
 */
trait LocationImportCsvFinalStatMakerCreateTrait
{
    /**
     * @var LocationImportCsvFinalStatMaker|null
     */
    protected ?LocationImportCsvFinalStatMaker $locationImportCsvFinalStatMaker = null;

    /**
     * @return LocationImportCsvFinalStatMaker
     */
    protected function createLocationImportCsvFinalStatMaker(): LocationImportCsvFinalStatMaker
    {
        return $this->locationImportCsvFinalStatMaker ?: LocationImportCsvFinalStatMaker::new();
    }

    /**
     * @param LocationImportCsvFinalStatMaker $locationImportCsvFinalStatMaker
     * @return static
     * @internal
     */
    public function setLocationImportCsvFinalStatMaker(LocationImportCsvFinalStatMaker $locationImportCsvFinalStatMaker): static
    {
        $this->locationImportCsvFinalStatMaker = $locationImportCsvFinalStatMaker;
        return $this;
    }
}
