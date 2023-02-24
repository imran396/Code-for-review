<?php
/**
 * SAM-9614: Refactor PartialUploadManager
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 01, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Base;

use Sam\Core\Service\CustomizableClass;

/**
 * Contains the processing status of the imported CSV, object with statistic info and error messages
 * with the row numbers where they occurred.
 *
 * Class ImportCsvProcessResult
 * @package Sam\Import\Csv\Base
 */
class ImportCsvProcessResult extends CustomizableClass
{
    public bool $success;
    public ?ImportCsvProcessStatisticInterface $statistic = null;
    public array $errors = [];
    public ?int $errorRowIndex = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function success(?ImportCsvProcessStatisticInterface $statistic = null): static
    {
        $this->success = true;
        $this->statistic = $statistic;
        return $this;
    }

    public function error(array $errors, ?ImportCsvProcessStatisticInterface $statistic = null): static
    {
        $this->success = false;
        $this->errors = $errors;
        $this->statistic = $statistic;
        return $this;
    }

    public function errorOnRow(array $errors, int $rowIndex): static
    {
        $this->success = false;
        $this->errors = $errors;
        $this->errorRowIndex = $rowIndex;
        return $this;
    }
}
