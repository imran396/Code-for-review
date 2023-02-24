<?php
/**
 * SAM-5018 : Refactor Email_Template to sub classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Apr 1, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Email\Build\BidderInfo;


use InvalidArgumentException;
use Sam\Email\Build\DataConverterAbstract;
use User;

/**
 * Class DataConverter
 * @package Sam\Email\Build\BidderInfo
 */
class DataConverter extends DataConverterAbstract
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param $data
     */
    public function convert($data): void
    {
        [$user] = $data;
        if (!$user instanceof User) {
            throw new InvalidArgumentException('Object must be instance of User');
        }
        $this->setUser($user);
    }
}
