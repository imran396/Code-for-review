<?php
/**
 * SAM-4699: Email template entity editor
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           03.03.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Email\Save;

use Sam\Core\Constants;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Email\Load\EmailTemplateLoaderAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Settings\Save\SettingsProducerCreateTrait;
use Sam\Storage\WriteRepository\Entity\EmailTemplate\EmailTemplateWriteRepositoryAwareTrait;

/**
 * Class EmailTemplateEditor
 * @package Sam\Email\Save
 */
class EmailTemplateEditor extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use EmailTemplateLoaderAwareTrait;
    use EmailTemplateWriteRepositoryAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use SettingsProducerCreateTrait;

    public const ERR_REQUIRED_TEMPLATE_ID = 1;
    public const ERR_INVALID_TEMPLATE_ID = 2;

    public const OK_SAVED = 1;

    /** @var int|null */
    protected ?int $id = null;

    /** @var int|null */
    protected ?int $accountId = null;

    /** @var string */
    protected string $subject = '';

    /** @var string */
    protected string $message = '';

    /** @var bool */
    protected bool $disabled = false;

    /** @var string */
    protected string $key = '';

    /** @var bool */
    protected bool $isCcSupportEmail = false;

    /** @var bool */
    protected bool $isCcAuctionSupportEmail = false;

    /** @var bool */
    protected bool $isNotifyConsignor = false;

    /** @var int */
    protected int $mixFrequencyinHoursPickup = 0;

    /** @var int */
    protected int $mixFrequencyinHoursPayment = 0;
    /** @var string */
    protected string $generalErrorMessage = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        // Init ResultStatusCollector
        $errorMessages = [
            self::ERR_REQUIRED_TEMPLATE_ID => 'Template id is required',
            self::ERR_INVALID_TEMPLATE_ID => 'Invalid email template id',
        ];
        $successMessages = [
            self::OK_SAVED => 'Email template saved',
        ];
        $this->getResultStatusCollector()->construct($errorMessages, $successMessages);
        return $this;
    }

    /**
     * Update emailTemplate
     *
     * @param int $editorUserId
     * @return void
     */
    public function update(int $editorUserId): void
    {
        $emailTemplate = $this->getEmailTemplateLoader()->load($this->id, true);
        if (!$emailTemplate) {
            log_error("Available email template not found on updating" . composeSuffix(['id' => $this->id]));
            return;
        }

        // we update subject and message fields if they are filled:
        if (strip_tags($this->getSubject()) !== '') {
            $emailTemplate->Subject = $this->getSubject();
        }
        if (strip_tags($this->getMessage()) !== '') {
            $emailTemplate->Message = $this->getMessage();
        }

        // the rest of fields updated by values provided via the editor props:
        $emailTemplate->Disabled = $this->isDisabled();

        $emailTemplate->CcSupportEmail =
            in_array($emailTemplate->Key, Constants\EmailKey::$noSupportKeys, true)
                ? false
                : $this->isCcSupportEmail();

        $emailTemplate->CcAuctionSupportEmail =
            in_array($emailTemplate->Key, Constants\EmailKey::$noAuctionSupportKeys, true)
                ? false
                : $this->isCcAuctionSupportEmail();

        $emailTemplate->NotifyConsignor =
            in_array($emailTemplate->Key, Constants\EmailKey::$consignorKeys, true)
                ? $this->isNotifyConsignor()
                : false;

        // If we change main account - update some systemParams with values from
        // the email template: PickupReminderEmailFrequency, PaymentReminderEmailFrequency
        if ($this->getAccountId() === $this->cfg()->get('core->portal->mainAccountId')) {
            $this->updateSystemParams($emailTemplate->Key, $editorUserId);
        }

        $this->getEmailTemplateWriteRepository()->saveWithModifier($emailTemplate, $editorUserId);

        $this->getResultStatusCollector()->addSuccess(self::OK_SAVED);
    }

    /**
     * @param string $emailTemplateKey
     * @param int $editorUserId
     * @return void
     */
    protected function updateSystemParams(string $emailTemplateKey, int $editorUserId): void
    {
        $interval = $this->cfg()->get('core->reminder->registration->interval');

        if ($emailTemplateKey === Constants\EmailKey::PICKUP_REMINDER) {
            $this->createSettingsProducer()->update(
                [
                    Constants\Setting::PICKUP_REMINDER_EMAIL_FREQUENCY => $interval === 24
                        ? $this->getMixFrequencyinHoursPickup() * $interval
                        : $this->getMixFrequencyinHoursPickup()
                ],
                $this->getAccountId(),
                $editorUserId
            );
        }

        if ($emailTemplateKey === Constants\EmailKey::PAYMENT_REMINDER) {
            $this->createSettingsProducer()->update(
                [
                    Constants\Setting::PAYMENT_REMINDER_EMAIL_FREQUENCY => $interval === 24
                        ? $this->getMixFrequencyinHoursPayment() * $interval
                        : $this->getMixFrequencyinHoursPayment()
                ],
                $this->getAccountId(),
                $editorUserId
            );
        }
    }

    /**
     * Validate email template editor params
     * @return bool
     */
    public function validate(): bool
    {
        $this->getResultStatusCollector()->clear();

        if (!$this->id) {
            $this->getResultStatusCollector()->addError(self::ERR_REQUIRED_TEMPLATE_ID);
        } elseif (!$this->getEmailTemplateLoader()->load($this->id, true)) {
            $this->getResultStatusCollector()->addError(self::ERR_INVALID_TEMPLATE_ID);
        }

        $isValid = !$this->getResultStatusCollector()->hasError();
        return $isValid;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return static
     */
    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getAccountId(): int
    {
        return (int)$this->accountId;
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
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return static
     */
    public function setMessage(string $message): static
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * @param bool $disabled
     * @return static
     */
    public function enableDisabled(bool $disabled): static
    {
        $this->disabled = $disabled;
        return $this;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     *
     * @return EmailTemplateEditor
     */
    public function setKey(string $key): EmailTemplateEditor
    {
        $this->key = trim($key);
        return $this;
    }

    /**
     * @return bool
     */
    public function isCcSupportEmail(): bool
    {
        return $this->isCcSupportEmail;
    }

    /**
     * @param bool $isCcSupportEmail
     * @return static
     */
    public function enableCcSupportEmail(bool $isCcSupportEmail): static
    {
        $this->isCcSupportEmail = $isCcSupportEmail;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCcAuctionSupportEmail(): bool
    {
        return $this->isCcAuctionSupportEmail;
    }

    /**
     * @param bool $isCcAuctionSupportEmail
     * @return static
     */
    public function enableCcAuctionSupportEmail(bool $isCcAuctionSupportEmail): static
    {
        $this->isCcAuctionSupportEmail = $isCcAuctionSupportEmail;
        return $this;
    }

    /**
     * @return bool
     */
    public function isNotifyConsignor(): bool
    {
        return $this->isNotifyConsignor;
    }

    /**
     * @param bool $notifyConsignor
     * @return static
     */
    public function enableNotifyConsignor(bool $notifyConsignor): static
    {
        $this->isNotifyConsignor = $notifyConsignor;
        return $this;
    }

    /**
     * @return int
     */
    public function getMixFrequencyinHoursPickup(): int
    {
        return $this->mixFrequencyinHoursPickup;
    }

    /**
     * @param int $mixFrequencyinHoursPickup
     * @return static
     */
    public function setMixFrequencyinHoursPickup(int $mixFrequencyinHoursPickup): static
    {
        $this->mixFrequencyinHoursPickup = $mixFrequencyinHoursPickup;
        return $this;
    }

    /**
     * @return int
     */
    public function getMixFrequencyinHoursPayment(): int
    {
        return $this->mixFrequencyinHoursPayment;
    }

    /**
     * @param int $mixFrequencyinHoursPayment
     * @return static
     */
    public function setMixFrequencyinHoursPayment(int $mixFrequencyinHoursPayment): static
    {
        $this->mixFrequencyinHoursPayment = $mixFrequencyinHoursPayment;
        return $this;
    }

    /**
     * @return string
     */
    public function getGeneralErrorMessage(): string
    {
        return $this->generalErrorMessage;
    }

    /**
     * @param string $generalErrorMessage
     * @return static
     */
    public function setGeneralErrorMessage(string $generalErrorMessage): static
    {
        $this->generalErrorMessage = $generalErrorMessage;
        return $this;
    }

    /**
     * @return string
     */
    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
    }

    /**
     * @return string
     */
    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

}
