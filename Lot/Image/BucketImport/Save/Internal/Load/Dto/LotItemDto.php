<?php
/**
 * SAM-1700: Walmart - Bulk image upload enhancements
 * SAM-7918: Refactor \LotImage_BucketManager and image associators
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 31, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\BucketImport\Save\Internal\Load\Dto;

use Sam\Core\Service\CustomizableClass;

/**
 * Class LotItemDto
 * @package Sam\Lot\Image\BucketImport\Save\Internal\Load\Dto
 * @internal
 */
class LotItemDto extends CustomizableClass
{
    public ?int $id = null;
    public string $name = '';
    public ?int $accountId = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $row
     * @return static
     */
    public function fromDbRow(array $row): static
    {
        $this->id = (int)$row['id'];
        $this->name = (string)$row['name'];
        $this->accountId = (int)$row['account_id'];
        return $this;
    }
}
