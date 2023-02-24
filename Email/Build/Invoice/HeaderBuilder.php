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

namespace Sam\Email\Build\Invoice;

use RuntimeException;
use Sam\Email\Email;
use Sam\Email\Build\EmailAbstractBuilder;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class Builder
 * @package Sam\Email\Build\Invoice
 */
class HeaderBuilder extends EmailAbstractBuilder
{
    use UserLoaderAwareTrait;

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
        $userId = $this->getDataConverter()->getInvoice()->BidderId;
        $user = $this->getUserLoader()->load($userId, true);
        if (!$user) {
            throw new RuntimeException("Available user not found, when sending invoice email" . composeSuffix(['u' => $userId]));
        }
        $email = Email::new();
        $email->setTo($user->Email);
        $email->setFrom($this->getSupportEmail());
        $email->setSubject($this->emailTemplate->Subject);
        return $email;
    }
}
