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

namespace Sam\Email\Build\Outbid;

use Sam\Email\Email;
use Sam\Email\Build\EmailAbstractBuilder;

/**
 * Class Builder
 * @package Sam\Email\Build\BidderInfo
 */
class HeaderBuilder extends EmailAbstractBuilder
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return Email
     */
    public function buildEmail(): Email
    {
        $email = Email::new();
        $email->setTo($this->getDataConverter()->getUser()->Email);
        $email->setFrom($this->getSupportEmail());
        $email->setSubject($this->emailTemplate->Subject);
        return $email;
    }
}
