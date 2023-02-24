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

namespace Sam\Email\Transport;

use Sam\Core\Service\CustomizableClass;
use Exception;
use InvalidArgumentException;
use Sam\Core\Constants;
use Sam\Email\Email;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Laminas\Mail\Message;
use Laminas\Mail\Transport\Smtp;
use Laminas\Mail\Transport\SmtpOptions;
use Laminas\Mime\Message as MimeMessage;
use Laminas\Mime\Mime;
use Laminas\Mime\Part as MimePart;

/**
 * Class Smpt
 * @package Sam\Email\Transport
 */
class Smpt extends CustomizableClass implements TransportInterface
{
    use BlockCipherProviderCreateTrait;
    use SettingsManagerAwareTrait;

    /**
     * @var int|null
     */
    private ?int $accountId = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Email $email
     * @return bool
     */
    public function send(Email $email): bool
    {
        try {
            $from = $this->filterEmailAddress($email->getFrom());
            if (empty($from[0])) {
                throw new InvalidArgumentException("Empty Email Address");
            }

            $email->setFrom($from[0]);

            $log = "from:{$email->getFrom()};to:{$email->getTo()};subject:{$email->getSubject()};";

            $body = new MimeMessage();
            if ($email->getHtmlBody()) {
                $log .= 'message:' . substr($email->getHtmlBody(), 0, 100) . ';';
                $htmlBody = new MimePart($email->getHtmlBody());
                $htmlBody->type = Mime::TYPE_HTML;
                $body->addPart($htmlBody);
            }
            if ($email->getBody()) {
                $log .= 'message:' . substr($email->getBody(), 0, 100) . ';';
                $bodyText = new MimePart($email->getBody());
                $bodyText->type = Mime::TYPE_TEXT;
                $body->addPart($bodyText);
            }
            if ($email->getFiles()) {
                foreach ($email->getFiles() as $file) {
                    $attachment = new MimePart(fopen($file['location'], 'rb'));
                    $attachment->type = $file['mimeType'];
                    $attachment->filename = $file['name'];
                    $attachment->disposition = Mime::DISPOSITION_ATTACHMENT;
                    $attachment->encoding = Mime::ENCODING_BASE64;
                    $body->addPart($attachment);
                }
            }
            $log .= "cc:{$email->getCc()};bcc:{$email->getBcc()};";
            log_info($log);

            $message = new Message();
            $message->setEncoding("UTF-8");
            $message->setSubject($email->getSubject());
            $message->setBody($body);
            if ($email->getReplyTo()) {
                $message->setReplyTo($email->getReplyTo());
            }
            $name = $from[1] ?? null;
            $message->setFrom($email->getFrom(), $name);

            if ($email->getTo()) {
                $message->addTo(explode(',', $email->getTo()));
            }
            if ($email->getCc()) {
                $message->addCc(explode(',', $email->getCc()));
            }
            if ($email->getBcc()) {
                $message->addBcc(explode(',', $email->getBcc()));
            }
            $this->getTransport()->send($message);
        } catch (Exception $e) {
            log_error('Error sending email: (AccountId: ' . $this->accountId . ') - ' . $e->getMessage());
            return false;
        }
        return true;
    }


    /**
     * @return Smtp
     */
    public function getTransport(): Smtp
    {
        $transport = new Smtp();
        $transport->setOptions(new SmtpOptions($this->getOptions()));
        return $transport;
    }

    /**
     * Init SMTP Transport's options
     *
     * This method was moved from \Email_Base::getSmptTransport() at dev@34119
     * and removed from \Email_Base at dev@34701. Where \Email_Base was initialized
     * with proper \Email_Base::acoountId always.
     *
     * @return array
     */
    private function getOptions(): array
    {
        $sm = $this->getSettingsManager();
        $smtpSslType = (int)$sm->get(Constants\Setting::SMTP_SSL_TYPE, $this->accountId);
        $isSmtpAuth = (bool)$sm->get(Constants\Setting::SMTP_AUTH, $this->accountId);

        // YV, SAM-7957, 04.04.2021: We can use SmtpOptionsDto here
        $options['host'] = $sm->get(Constants\Setting::SMTP_SERVER, $this->accountId);
        $options['port'] = (int)$sm->get(Constants\Setting::SMTP_PORT, $this->accountId);
        if ($isSmtpAuth) {
            $options['connection_class'] = 'login';
            $options['connection_config']['username'] = $sm->get(Constants\Setting::SMTP_USERNAME, $this->accountId);
            $smtpPassword = $sm->get(Constants\Setting::SMTP_PASSWORD, $this->accountId);
            $options['connection_config']['password'] = $this->createBlockCipherProvider()->construct()->decrypt($smtpPassword);
        }
        if ($smtpSslType) {
            $options['connection_config'] ['ssl'] = $smtpSslType === 1 ? 'tls' : 'ssl';
        }

        return $options;
    }


    /**
     * Filter name and email address from whole receiver address
     * @param string $email
     * @return string[]
     */
    private function filterEmailAddress(string $email): array
    {
        $results = [];
        $result = '';
        foreach (preg_split('/\s/', $email) as $token) {
            $filteredMail = filter_var(filter_var($token, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
            if ($filteredMail !== false) {
                $results[0] = $filteredMail;
            }
            if (preg_match('/<([^"]+)>/', $email, $result)) {
                if (isset($result[1])) {
                    $results[1] = $result[1];
                }
            }
        }
        return $results;
    }

    /**
     * @return int|null
     */
    public function getAccountId(): ?int
    {
        return $this->accountId;
    }

    /**
     * @param int $accountId
     * @return static
     */
    public function setAccountId(int $accountId): static
    {
        $this->accountId = $accountId;
        return $this;
    }
}
