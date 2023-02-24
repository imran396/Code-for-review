<?php
/**
 * SAM-9134: Refactor \User_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 15, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Read\Internal;

use Sam\Core\Service\CustomizableClass;

/**
 * This class is responsible for mapping CSV row data by the column names.
 *
 * Class CsvRowDataMapper
 * @package Sam\Import\Csv\Read
 * @internal
 */
class CsvRowDataMapper extends CustomizableClass
{
    protected array $mapping;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $mapping
     * @return static
     */
    public function construct(array $mapping): static
    {
        $this->mapping = $mapping;
        return $this;
    }

    /**
     * Map a CSV row data by the column names
     *
     * @param array $data
     * @return array
     */
    public function map(array $data): array
    {
        $mappedData = [];
        foreach ($data as $columnName => $value) {
            $key = $this->detectKey($columnName);
            $mappedData[$key] = $value;
        }
        return $mappedData;
    }

    /**
     * @param string $columnName
     * @return string
     */
    protected function detectKey(string $columnName): string
    {
        $key = trim($this->mapping[$columnName] ?? $columnName);
        return $key;
    }
}
