<?php
/**
 *
 * SAM-4748: Mailing List Template management classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-01-07
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

/**
 * Class MailingListTemplate
 * @package Sam\Core\Constants
 */
class MailingListTemplate
{
    public const UT_BIDDER = 1;
    public const UT_CONSIGNOR = 2;
    /** @var int[] */
    public static array $userTypes = [self::UT_BIDDER, self::UT_CONSIGNOR];
    /** @var string[] */
    public static array $userTypeNames = [self::UT_BIDDER => 'Bidder', self::UT_CONSIGNOR => 'Consignor'];
}
