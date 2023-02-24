<?php
/**
 * SAM-10435: Add csv quick upload form to locations page
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Location;

/**
 * Trait LocationImportCsvDtoFactoryCreateTrait
 * @package Sam\Location\Import
 */
trait LocationImportCsvDtoFactoryCreateTrait
{
    protected ?LocationImportCsvDtoFactory $locationImportCsvDtoFactory = null;

    /**
     * @return LocationImportCsvDtoFactory
     */
    protected function createLocationImportCsvDtoFactory(): LocationImportCsvDtoFactory
    {
        return $this->locationImportCsvDtoFactory ?: LocationImportCsvDtoFactory::new();
    }

    /**
     * @param LocationImportCsvDtoFactory $locationImportCsvDtoFactory
     * @return static
     * @internal
     */
    public function setLocationImportCsvDtoFactory(LocationImportCsvDtoFactory $locationImportCsvDtoFactory): static
    {
        $this->locationImportCsvDtoFactory = $locationImportCsvDtoFactory;
        return $this;
    }
}
