<?php
/**
 * SAM-9134: Refactor \User_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Read;

use Sam\Core\Service\CustomizableClass;

/**
 * Contains mapped data from a CSV row
 *
 * Class Row
 * @package Sam\Import\Csv\Read
 */
class CsvRow extends CustomizableClass
{
    protected array $data;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $data
     * @return static
     */
    public function construct(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Returns cell value or an empty string if the column is absent
     *
     * @param string $key
     * @return string
     */
    public function getCell(string $key): string
    {
        if (!$this->hasCell($key)) {
            return '';
        }
        return trim($this->data[$key]);
    }

    /**
     * Checks if there is at least one filled cell in a row
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        $isEmpty = empty(array_filter($this->data, 'strlen'));
        return $isEmpty;
    }

    /**
     * Returns mapped data keys
     *
     * @return array
     */
    public function getColumnNames(): array
    {
        return array_keys($this->data);
    }

    /**
     * Checks if a cell exists
     *
     * @param string $key
     * @return bool
     */
    public function hasCell(string $key): bool
    {
        return isset($this->data[$key]);
    }
}
