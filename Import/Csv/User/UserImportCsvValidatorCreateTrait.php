<?php
/**
 * SAM-9134: Refactor \User_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\User;

/**
 * Trait UserImportCsvValidatorCreateTrait
 * @package Sam\User\Import\Validate
 */
trait UserImportCsvValidatorCreateTrait
{
    /**
     * @var UserImportCsvValidator|null
     */
    protected ?UserImportCsvValidator $userImportCsvValidator = null;

    /**
     * @return UserImportCsvValidator
     */
    protected function createUserImportCsvValidator(): UserImportCsvValidator
    {
        return $this->userImportCsvValidator ?: UserImportCsvValidator::new();
    }

    /**
     * @param UserImportCsvValidator $userImportCsvValidator
     * @return static
     * @internal
     */
    public function setUserImportCsvValidator(UserImportCsvValidator $userImportCsvValidator): static
    {
        $this->userImportCsvValidator = $userImportCsvValidator;
        return $this;
    }
}
