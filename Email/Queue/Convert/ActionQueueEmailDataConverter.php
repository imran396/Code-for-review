<?php
/**
 * SAM-5018 : Refactor Email_Template to sub classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Jun 19, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Email\Queue\Convert;


use AuctionEmailTemplate;
use EmailTemplate;
use Sam\Core\Service\CustomizableClass;
use InvalidArgumentException;
use Sam\Core\Constants;
use Sam\Email\Email;
use Sam\Email\Load\AuctionEmailTemplateLoaderAwareTrait;
use Sam\Email\Load\EmailTemplateLoaderAwareTrait;
use Sam\Email\Queue\ActionQueueDto;

class ActionQueueEmailDataConverter extends CustomizableClass
{
    use AuctionEmailTemplateLoaderAwareTrait;
    use EmailTemplateLoaderAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Convert ActionQueueDto to formatted string for DB or any external service
     * @param ActionQueueDto $dto
     * @return false|string
     */
    public function toFormat(ActionQueueDto $dto): false|string
    {
        return json_encode($dto->toArray());
    }

    /**
     * Convert formatted string from DB to ActionQueueDto
     * @param string $data
     * @return ActionQueueDto
     */
    public function fromFormat(string $data): ActionQueueDto
    {
        $emailContents = json_decode($data, true);

        $attributes = ['emailTemplateId', 'emailTemplateTable', 'email', 'accountId'];
        foreach ($attributes as $attribute) {
            if (!array_key_exists($attribute, $emailContents)) {
                throw new InvalidArgumentException(sprintf('Missing attribute in the data array: %s', $attribute));
            }
        }

        $dto = ActionQueueDto::new();
        $dto->setEmail($this->buildEmailFromDbData($emailContents['email']));
        $dto->setEmailTemplate($this->buildEmailTemplate($emailContents));
        $dto->setAccountId($emailContents['accountId']);
        return $dto;
    }

    /**
     * Create Email entity from data array
     * @param array $emailData
     * @return Email
     */
    private function buildEmailFromDbData(array $emailData): Email
    {
        $attributes = ['from', 'to', 'subject', 'body', 'htmlBody', 'cc', 'bcc', 'replyTo', 'files'];
        foreach ($attributes as $attribute) {
            if (!array_key_exists($attribute, $emailData)) {
                throw new InvalidArgumentException(sprintf('Missing email attribute in the data array: %s', $attribute));
            }
        }
        $email = Email::new();
        $email->fromArray($emailData);
        return $email;
    }

    /**
     * Recreate a Template class from db data
     * @param array $emailContents
     * @return AuctionEmailTemplate|EmailTemplate|null - null means that we don't use any EmailTemplate
     */
    private function buildEmailTemplate(array $emailContents): AuctionEmailTemplate|EmailTemplate|null
    {
        if (
            $emailContents['emailTemplateId']
            && $emailContents['emailTemplateTable']
        ) {
            if ($emailContents['emailTemplateTable'] === Constants\Email::AUCTION_EMAIL_TEMPLATE) {
                return $this->getAuctionEmailTemplateLoader()->load((int)$emailContents['emailTemplateId']);
            }
            if ($emailContents['emailTemplateTable'] === Constants\Email::EMAIL_TEMPLATE) {
                return $this->getEmailTemplateLoader()->load($emailContents['emailTemplateId']);
            }
        }
        return null;
    }
}
