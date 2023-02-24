<?php
/**
 * SAM-5096 : #2 Extract COUNTER_OFFER, OFFER_ACCEPTED, OFFER_DECLINED, OFFER_SUBMITTED, COUNTER_DECLINED, COUNTER_ACCEPT
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Jun 1, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Email\Build\OfferSubmitted;

use Sam\Email\Build\EmailAbstractBuilder;
use Sam\Email\Email;

/**
 * Class HeaderBuilder
 * @package Sam\Email\Build\OfferSubmitted
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
