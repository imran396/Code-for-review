<?php
/**
 * SAM-3693: Rtb related repositories  https://bidpath.atlassian.net/browse/SAM-3693
 *
 * @copyright      2018 Bidpath, Inc.
 * @author           Oleg Kovalyov
 * @package        com.swb.sam2
 * @version         SVN: $Id$
 * @since            14 Jul, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\ReadRepository\Entity\RtbCurrentSnapshot;

/**
 * Class RtbCurrentSnapshotReadRepository
 * @package Sam\Storage\ReadRepository\Entity\RtbCurrentSnapshot
 */
class RtbCurrentSnapshotReadRepository extends AbstractRtbCurrentSnapshotReadRepository
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
