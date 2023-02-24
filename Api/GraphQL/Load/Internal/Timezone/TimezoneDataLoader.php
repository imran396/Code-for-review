<?php
/**
 * SAM-10467: Implement a GraphQL nested structure for a single auction
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Load\Internal\Timezone;

use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\Timezone\TimezoneReadRepositoryCreateTrait;

/**
 * Class TimezoneDataLoader
 * @package Sam\Api\GraphQL\Load\Internal\Timezone
 */
class TimezoneDataLoader extends CustomizableClass
{
    use TimezoneReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function load(array $ids, array $fields, bool $isReadOnlyDb = false): array
    {
        if (!in_array('id', $fields, true)) {
            $fields[] = 'id';
        }
        $timezones = $this->createTimezoneReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterId($ids)
            ->select($fields)
            ->loadRows();
        return ArrayHelper::produceIndexedArray($timezones, 'id');
    }
}
