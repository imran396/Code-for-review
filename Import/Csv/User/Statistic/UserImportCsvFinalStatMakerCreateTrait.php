<?php
/**
 * SAM-9614: Refactor PartialUploadManager
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\User\Statistic;

/**
 * Trait UserImportCsvFinalStatMakerCreateTrait
 * @package Sam\Import\Csv\User\Statistic
 */
trait UserImportCsvFinalStatMakerCreateTrait
{
    /**
     * @var UserImportCsvFinalStatMaker|null
     */
    protected ?UserImportCsvFinalStatMaker $userImportCsvFinalStatMaker = null;

    /**
     * @return UserImportCsvFinalStatMaker
     */
    protected function createUserImportCsvFinalStatMaker(): UserImportCsvFinalStatMaker
    {
        return $this->userImportCsvFinalStatMaker ?: UserImportCsvFinalStatMaker::new();
    }

    /**
     * @param UserImportCsvFinalStatMaker $userImportCsvFinalStatMaker
     * @return static
     * @internal
     */
    public function setUserImportCsvFinalStatMaker(UserImportCsvFinalStatMaker $userImportCsvFinalStatMaker): static
    {
        $this->userImportCsvFinalStatMaker = $userImportCsvFinalStatMaker;
        return $this;
    }
}
