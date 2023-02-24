<?php
/**
 * SAM-6308: Refactor custom field management to separate modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul. 21, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Dto;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Data\TypeCast\Cast;

/**
 * Helper methods for reading and normalizing form input data from request
 *
 * Class FormDataReader
 * @package Sam\Core\Dto
 */
class FormDataReader extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Read and normalize string from the server request
     *
     * @param string $cid
     * @param array $body Server request parsed body
     * @return string|null
     */
    public function readString(string $cid, array $body): ?string
    {
        return Cast::toString($body[$cid] ?? null);
    }

    /**
     * Read and normalize form checkbox value from the server request
     *
     * @param string $cid
     * @param array $body Server request parsed body
     * @return string|null
     */
    public function readCheckbox(string $cid, array $body): ?string
    {
        return (string)$this->readString($cid, $body);
    }

    /**
     * Read and normalize array of strings from the server request
     *
     * @param string $cid
     * @param array $body Server request parsed body
     * @return array|null
     */
    public function readMultipleStrings(string $cid, array $body): ?array
    {
        $value = $body[$cid] ?? null;
        if (!$value && !is_array($value)) {
            $value = null;
        }
        return $value;
    }
}
