<?php
/**
 * SAM-4740: Avoid calling of load functions from data class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Load;

use RtbMessage;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\RtbMessage\RtbMessageReadRepositoryCreateTrait;

/**
 * Class RtbMessageLoader
 * @package Sam\Rtb\Load
 */
class RtbMessageLoader extends CustomizableClass
{
    use RtbMessageReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load a RtbMessage
     * @param int|null $rtbMessageId
     * @param bool $isReadOnlyDb query to read-only db
     * @return RtbMessage|null
     */
    public function load(?int $rtbMessageId, bool $isReadOnlyDb = false): ?RtbMessage
    {
        if (!$rtbMessageId) {
            return null;
        }
        $rtbMessage = $this->createRtbMessageReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($rtbMessageId)
            ->filterActive(true)
            ->loadEntity();
        return $rtbMessage;
    }
}
