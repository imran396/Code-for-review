<?php
/**
 * SAM-5018 : Refactor Email_Template to sub classes
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           May 27, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Email\Queue;

use AuctionEmailTemplate;
use EmailTemplate;
use InvalidArgumentException;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Email\Email;

/**
 * Class ActionQueueDto
 * @package Sam\Email
 */
class ActionQueueDto extends CustomizableClass
{
    protected ?int $accountId = null;
    private ?Email $email = null;
    private EmailTemplate|AuctionEmailTemplate|null $emailTemplate = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        if ($this->email === null) {
            throw new InvalidArgumentException("Email is invalid");
        }
        return $this->email;
    }

    /**
     * @param Email $email
     * @return ActionQueueDto
     */
    public function setEmail(Email $email): ActionQueueDto
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return AuctionEmailTemplate|EmailTemplate|null
     */
    public function getEmailTemplate(): AuctionEmailTemplate|EmailTemplate|null
    {
        return $this->emailTemplate;
    }

    /**
     * @param AuctionEmailTemplate|EmailTemplate|null $emailTemplate
     * @return static
     */
    public function setEmailTemplate(AuctionEmailTemplate|EmailTemplate|null $emailTemplate): static
    {
        $this->emailTemplate = $emailTemplate;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getAccountId(): ?int
    {
        return $this->accountId;
    }

    /**
     * @param int|null $accountId
     * @return static
     */
    public function setAccountId(?int $accountId): static
    {
        $this->accountId = $accountId;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'emailTemplateId' => $this->emailTemplate->Id ?? null,
            'emailTemplateTable' => $this->detectEmailTemplateTable(),
            'email' => $this->email->toArray(),
            'accountId' => $this->accountId,
        ];
    }

    /**
     * Determine value that identifies emailTemplateTable by class of emailTemplate property. The value isn't mandatory.
     * @return int|null null - when we serve emails that are not built by defined template.
     */
    private function detectEmailTemplateTable(): ?int
    {
        if ($this->emailTemplate instanceof AuctionEmailTemplate) {
            return Constants\Email::AUCTION_EMAIL_TEMPLATE;
        }
        if ($this->emailTemplate instanceof EmailTemplate) {
            return Constants\Email::EMAIL_TEMPLATE;
        }
        return null;
    }
}
