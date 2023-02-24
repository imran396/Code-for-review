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


namespace Sam\Email\Build\AbsenteeBid;

use Sam\Email\Build\EmailBuilderAbstract;
use Sam\Email\Email;
use Sam\Core\Constants;

/**
 * Class Builder
 * @package Sam\Email\Build\AbsenteeBid
 */
class Builder extends EmailBuilderAbstract
{
    use DataProviderAwareTrait;
    use PlaceholderBuilderAwareTrait;
    use LotReservePriceEmailMessageTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Dto $dto
     * @return Email
     */
    public function build($dto): Email
    {
        $this->getDataProvider()->prepare($dto);

        $email = Email::new();
        $email->setTo($this->getDataProvider()->getUser()->Email);
        $email->setFrom($this->getSupportEmail());
        $email->setSubject($this->getSubject());
        if ($this->getEmailFormat() === Constants\SettingSystem::EF_PLAIN) {
            $email->setBody($this->getBody());
        } else {
            $email->setHtmlBody($this->getBody());
        }
        $email->setBcc($this->getBcc($this->getDataProvider()));
        return $email;
    }

    /**
     * @return string
     */
    protected function getSubject(): string
    {
        $subjectBuilder = $this->getEmailSubjectBuilder();
        $placeholders = $this->getPlaceholderBuilder();
        $subject = $subjectBuilder->build($placeholders, $this->getEmailTemplate()->Subject);
        return $subject;
    }

    /**
     * @return string
     */
    protected function getBody(): string
    {
        $bodyBuilder = $this->getEmailBodyBuilder();
        $placeholders = $this->getPlaceholderBuilder();
        $placeholders->setDataProvider($this->getDataProvider());
        $lotReservePriceEmailMessage = $this->getLotReservePriceEmailMessage();
        $lotReservePriceEmailMessage->setDataProvider($this->getDataProvider());
        $message = $lotReservePriceEmailMessage->getMessage($this->getEmailTemplate()->Message);
        $body = $bodyBuilder->build($placeholders, $message);
        return $body;
    }

    /**
     * @param DataProvider $dataProvider
     * @return string
     */
    protected function getBcc(DataProvider $dataProvider): string
    {
        $bccBuilder = $this->getEmailBccBuilder();
        $bcc = $bccBuilder->build($this->getEmailTemplate()->CcSupportEmail, $dataProvider->getAuction()->Email);
        return $bcc;
    }
}
