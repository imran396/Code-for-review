<?php
/**
 * SAM-9365: Refactor BidIncrementCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Base;

use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Container for the CSV import validation errors
 *
 * Class ImportCsvValidationResult
 * @package Sam\Import\Csv\Base\Validate
 */
class ImportCsvValidationResult extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    protected array $generalErrors = [];
    protected array $rowErrors = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Add row validation errors
     *
     * @param int $rowIndex
     * @param array $errorMessages
     * @return static
     */
    public function addRowErrors(int $rowIndex, array $errorMessages): static
    {
        $this->rowErrors[$rowIndex] = array_merge($this->rowErrors[$rowIndex] ?? [], $errorMessages);
        return $this;
    }

    /**
     * Add errors related not to a row, but to the entire file
     *
     * @param array $errorMessages
     * @return static
     */
    public function addGeneralErrors(array $errorMessages): static
    {
        $this->generalErrors = array_merge($this->generalErrors, $errorMessages);
        return $this;
    }

    /**
     * Checks if some errors have been added
     *
     * @return bool
     */
    public function hasError(): bool
    {
        return $this->generalErrors || $this->rowErrors;
    }

    /**
     * Render all error messages
     *
     * @return array
     */
    public function getErrorMessages(): array
    {
        $translator = $this->getAdminTranslator();
        $errorMessages = $this->generalErrors;
        ksort($this->rowErrors);
        foreach ($this->rowErrors as $rowIndex => $rowErrorMessages) {
            $errorMessages[] = $translator->trans(
                'import.csv.general.row_validation_error',
                [
                    'rowIndex' => $rowIndex,
                    'errorMessages' => implode('; ', $rowErrorMessages),
                ],
                'admin_validation'
            );
        }
        return $errorMessages;
    }

    public function toArray(): array
    {
        return [
            'general' => $this->generalErrors,
            'row' => $this->rowErrors,
        ];
    }
}
