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

namespace Sam\Email\Build;


use AuctionEmailTemplate;
use Sam\Core\Service\CustomizableClass;
use Sam\Email\Email;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\AccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use EmailTemplate;
use Sam\Core\Constants;

/**
 * Abstract class that generates Email entity. We inherit from this class on each specific case.
 *
 * Class EmailAbstractBuilder
 * @package Sam\Email
 */
abstract class EmailBuilderAbstract extends CustomizableClass
{
    use SettingsManagerAwareTrait;
    use AccountAwareTrait;
    use AuctionAwareTrait;
    use EmailBodyBuilderAwareTrait;
    use EmailSubjectBuilderAwareTrait;
    use EmailBccBuilderAwareTrait;

    protected EmailTemplate|AuctionEmailTemplate $emailTemplate;
    protected ?string $supportEmail = null;
    protected ?string $emailFormat = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param $dto
     * @return Email
     */
    abstract public function build($dto): Email;

    /**
     * @return AuctionEmailTemplate|EmailTemplate
     */
    public function getEmailTemplate(): AuctionEmailTemplate|EmailTemplate
    {
        return $this->emailTemplate;
    }

    /**
     * @param AuctionEmailTemplate|EmailTemplate $emailTemplate
     * @return static
     */
    public function setEmailTemplate(AuctionEmailTemplate|EmailTemplate $emailTemplate): static
    {
        $this->emailTemplate = $emailTemplate;
        return $this;
    }

    /**
     * @return string
     */
    public function getSupportEmail(): string
    {
        if ($this->supportEmail === null) {
            $this->supportEmail = (string)$this->getSettingsManager()->get(
                Constants\Setting::SUPPORT_EMAIL,
                $this->getAccountId()
            );
        }
        return $this->supportEmail;
    }

    /**
     * @param string $supportEmail
     * @return static
     */
    public function setSupportEmail(string $supportEmail): static
    {
        $this->supportEmail = $supportEmail;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmailFormat(): string
    {
        if ($this->emailFormat === null) {
            $this->emailFormat = (string)$this->getSettingsManager()->get(
                Constants\Setting::EMAIL_FORMAT,
                $this->getAccountId()
            );
        }
        return $this->emailFormat;
    }

    /**
     * @param string $emailFormat
     * @return static
     */
    public function setEmailFormat(string $emailFormat): static
    {
        $this->emailFormat = $emailFormat;
        return $this;
    }


}
