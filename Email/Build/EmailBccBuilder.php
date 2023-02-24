<?php
/**
 * SAM-5018 Refactor Email_Template to sub classes
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 10, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Email\Build;


use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\AccountAwareTrait;
use Sam\Core\Constants;

/**
 * Class EmailBccBuilder
 * @package Sam\Email\Build
 */
class EmailBccBuilder extends CustomizableClass
{
    use SettingsManagerAwareTrait;
    use AccountAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param bool $emailTplCcSupportEmail
     * @param string|null $auctionEmail
     * @return string
     */
    public function build(bool $emailTplCcSupportEmail, ?string $auctionEmail = null): string
    {
        $supportEmail = (string)$this->getSettingsManager()->get(Constants\Setting::SUPPORT_EMAIL, $this->getAccountId());
        $bcc = '';
        if (
            $emailTplCcSupportEmail
            && $supportEmail !== ''
        ) {
            log_debug('bcc support email');
            /* !no need to switch the To and Bcc as Sir Nima advice it is ok to bcc support on client
             * switch the To and Bcc fields if support email is flagged
             * $this->Bcc = $this->to;
             * $this->to = $systemParams->SupportEmail;
             * */
            $bcc = $supportEmail;
        }

        if ($auctionEmail) {
            log_debug('bcc auction email');
            $bcc .= ',' . $auctionEmail;
        }

        $bcc = ltrim($bcc, ',');
        return $bcc;
    }
}
