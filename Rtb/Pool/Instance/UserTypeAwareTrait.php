<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/29/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Instance;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;

/**
 * Trait UserTypeAwareTrait
 * @package
 */
trait UserTypeAwareTrait
{
    /**
     * @var int
     */
    protected int $userType = Constants\Rtb::UT_VIEWER;

    /**
     * @return int
     */
    public function getUserType(): int
    {
        return $this->userType;
    }

    /**
     * @param int $userType
     * @return static
     */
    public function setUserType(int $userType): static
    {
        $this->userType = Cast::toInt($userType, Constants\Rtb::$rtbConsoleUserTypes) ?? Constants\Rtb::UT_VIEWER;
        return $this;
    }
}
