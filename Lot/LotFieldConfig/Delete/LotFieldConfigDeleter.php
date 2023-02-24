<?php
/**
 * SAM-9741: Admin options Inventory page - Add "Required" property for all fields
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\LotFieldConfig\Delete;

use LotFieldConfig;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\LotFieldConfig\Load\LotFieldConfigLoaderCreateTrait;
use Sam\Storage\WriteRepository\Entity\LotFieldConfig\LotFieldConfigWriteRepositoryAwareTrait;

/**
 * Class LotFieldConfigDeleter
 * @package Sam\Lot\LotFieldConfig\Delete
 */
class LotFieldConfigDeleter extends CustomizableClass
{
    use LotFieldConfigWriteRepositoryAwareTrait;
    use LotFieldConfigLoaderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function delete(LotFieldConfig $lotFieldConfig, int $editorUserId): void
    {
        $lotFieldConfig->Active = false;
        $this->getLotFieldConfigWriteRepository()->saveWithModifier($lotFieldConfig, $editorUserId);
    }

    /**
     * Mark LotFieldConfig records as deleted for passed index.
     * @param string $index
     * @param int $editorUserId
     */
    public function deleteAllByIndex(string $index, int $editorUserId): void
    {
        $lotFieldConfigs = $this->createLotFieldConfigLoader()->loadByIndex($index);
        foreach ($lotFieldConfigs as $lotFieldConfig) {
            $this->delete($lotFieldConfig, $editorUserId);
        }
    }
}
