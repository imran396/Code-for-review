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

namespace Sam\Email;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants\Setting;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class Email - DTO class that contains an email's data.
 * @package Sam\Email
 */
class Email extends CustomizableClass
{
    use SettingsManagerAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @var string
     */
    protected string $from = '';
    /**
     * @var string
     */
    protected string $to = '';
    /**
     * @var string
     */
    protected string $subject = '';
    /**
     * @var string
     */
    protected string $body = '';
    /**
     * @var string
     */
    protected string $htmlBody = '';
    /**
     * @var string
     */
    protected string $cc = '';
    /**
     * @var string
     */
    protected string $bcc = '';
    /**
     * @var string
     */
    protected string $replyTo = '';
    /**
     * @var array (name,mimeType,location)
     */
    protected array $files = [];

    public function toArray(): array
    {
        return [
            'from' => $this->from,
            'to' => $this->to,
            'subject' => $this->formattedString($this->subject),
            'body' => $this->formattedString($this->body),
            'htmlBody' => $this->formattedString($this->htmlBody),
            'cc' => $this->cc,
            'bcc' => $this->bcc,
            'replyTo' => $this->replyTo,
            'files' => $this->files,
        ];
    }

    /**
     * @param array $data
     */
    public function fromArray(array $data): void
    {
        if (isset($data['from'])) {
            $this->from = $data['from'];
        }

        if (isset($data['to'])) {
            $this->to = $data['to'];
        }

        if (isset($data['to'])) {
            $this->to = $data['to'];
        }

        if (isset($data['subject'])) {
            $this->subject = $data['subject'];
        }

        if (isset($data['body'])) {
            $this->body = $data['body'];
        }

        if (isset($data['htmlBody'])) {
            $this->htmlBody = $data['htmlBody'];
        }

        if (isset($data['cc'])) {
            $this->cc = $data['cc'];
        }

        if (isset($data['bcc'])) {
            $this->bcc = $data['bcc'];
        }

        if (isset($data['replyTo'])) {
            $this->replyTo = $data['replyTo'];
        }

        if (isset($data['files'])) {
            $this->files = $data['files'];
        }
    }

    /**
     * Get identifier
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->to . '~' . $this->subject;
    }

    /**
     * @param string $value
     * @return string
     */
    private function formattedString(string $value): string
    {
        $value = str_replace("\r", '', $value);
        $value = str_replace("\n", "\r\n", $value);
        return $value;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        if (!$this->from) {
            $this->from = (string)$this->getSettingsManager()->getForMain(Setting::SUPPORT_EMAIL);
        }
        return $this->from;
    }

    /**
     * @param string $from
     * @return static
     */
    public function setFrom(string $from): static
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @param string $to
     * @return static
     */
    public function setTo(string $to): static
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return static
     */
    public function setSubject(string $subject): static
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return static
     */
    public function setBody(string $body): static
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return string
     */
    public function getHtmlBody(): string
    {
        return $this->htmlBody;
    }

    /**
     * @param string $htmlBody
     * @return static
     */
    public function setHtmlBody(string $htmlBody): static
    {
        $this->htmlBody = $htmlBody;
        return $this;
    }

    /**
     * @return string
     */
    public function getCc(): string
    {
        return $this->cc;
    }

    /**
     * @param string $cc
     * @return static
     */
    public function setCc(string $cc): static
    {
        $this->cc = $cc;
        return $this;
    }

    /**
     * @return string
     */
    public function getBcc(): string
    {
        return $this->bcc;
    }

    /**
     * @param string $bcc
     * @return static
     */
    public function setBcc(string $bcc): static
    {
        $this->bcc = $bcc;
        return $this;
    }

    /**
     * @return string
     */
    public function getReplyTo(): string
    {
        return $this->replyTo;
    }

    /**
     * @param string $replyTo
     * @return static
     */
    public function setReplyTo(string $replyTo): static
    {
        $this->replyTo = $replyTo;
        return $this;
    }

    /**
     * @return array
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @param array $files
     * @return static
     */
    public function setFiles(array $files): static
    {
        $this->files = $files;
        return $this;
    }
}
