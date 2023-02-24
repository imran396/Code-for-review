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
 * Trait LocationImportCsvValidatorCreateTrait
 * @package Sam\Location\Import\Validate
 */
trait LocationImportCsvValidatorCreateTrait
{
    /**
     * @var LocationImportCsvValidator|null
     */
    protected ?LocationImportCsvValidator $locationImportCsvValidator = null;

    /**
     * @return LocationImportCsvValidator
     */
    protected function createLocationImportCsvValidator(): LocationImportCsvValidator
    {
        return $this->locationImportCsvValidator ?: LocationImportCsvValidator::new();
    }

    /**
     * @param LocationImportCsvValidator $locationImportCsvValidator
     * @return static
     * @internal
     */
    public function setLocationImportCsvValidator(LocationImportCsvValidator $locationImportCsvValidator): static
    {
        $this->locationImportCsvValidator = $locationImportCsvValidator;
        return $this;
    }
}
