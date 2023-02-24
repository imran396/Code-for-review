<?php
/**
 * SAM-10335: Allow to adjust CC surcharge per account: Implementation (Dev)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SystemParameterPaymentForm\CreditCard\Edit\Common;


enum CreditCardEditingMode: string
{
    case MAIN = 'main';
    case PORTAL = 'portal';

    public function isMain(): bool
    {
        return $this === self::MAIN;
    }

    public function isPortal(): bool
    {
        return $this === self::PORTAL;
    }
}
