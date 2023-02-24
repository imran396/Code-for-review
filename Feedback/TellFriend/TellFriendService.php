<?php
/**
 * SAM-4712 : Refactor Tell Friend service
 * https://bidpath.atlassian.net/browse/SAM-4712
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/9/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Feedback\TellFriend;

use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveLotDetailsUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Core\Email\Validate\EmailAddressChecker;
use Sam\Core\Service\CustomizableClass;
use Email_Template;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Security\Captcha\Alternative\Validate\AlternativeCaptchaValidator;
use Sam\Security\Captcha\Simple\Validate\SimpleCaptchaValidator;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionLotAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotItemAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use User;

/**
 * Class TellFriendService
 * @package Sam\Feedback\TellFriend
 */
class TellFriendService extends CustomizableClass
{
    use AuctionAwareTrait;
    use AuctionLotAwareTrait;
    use LotItemAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use SettingsManagerAwareTrait;
    use TranslatorAwareTrait;
    use UrlBuilderAwareTrait;
    use UserLoaderAwareTrait;

    public const ERR_RECEIVER_NAME_REQUIRED = 1;
    public const ERR_RECEIVER_EMAIL_REQUIRED = 2;
    public const ERR_RECEIVER_EMAIL_INVALID = 3;
    public const ERR_SENDER_NAME_REQUIRED = 4;
    public const ERR_SENDER_EMAIL_REQUIRED = 5;
    public const ERR_SENDER_EMAIL_INVALID = 6;
    public const OK_SENT = 1;
    /**
     * @var string
     */
    protected string $receiverName = '';
    /**
     * @var string
     */
    protected string $receiverEmail = '';
    /**
     * @var string
     */
    protected string $message = '';
    /**
     * @var string
     */
    protected string $senderName = '';
    /**
     * @var string
     */
    protected string $senderEmail = '';
    /**
     * @var string
     */
    protected string $backUrl = '';
    /**
     * @var AlternativeCaptchaValidator|null
     */
    protected ?AlternativeCaptchaValidator $alternativeCaptchaValidator = null;
    /**
     * @var SimpleCaptchaValidator|null
     */
    protected ?SimpleCaptchaValidator $simpleCaptchaValidator = null;
    /**
     * @var User|null
     */
    protected ?User $senderUser = null;
    /**
     * @var int[]
     */
    protected array $senderNameErrors = [self::ERR_SENDER_NAME_REQUIRED];
    /**
     * @var int[]
     */
    protected array $senderEmailErrors = [self::ERR_SENDER_EMAIL_REQUIRED, self::ERR_SENDER_EMAIL_INVALID];
    /**
     * @var int[]
     */
    protected array $receiverNameErrors = [self::ERR_RECEIVER_NAME_REQUIRED];
    /**
     * @var int[]
     */
    protected array $receiverEmailErrors = [self::ERR_RECEIVER_EMAIL_REQUIRED, self::ERR_RECEIVER_EMAIL_INVALID];

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
        $tr = $this->getTranslator();
        $langRequiredCb = static function () use ($tr) {
            return $tr->translate('GENERAL_REQUIRED', 'general');
        };
        $langInvalidFormatCb = static function () use ($tr) {
            return $tr->translate('GENERAL_INVALID_FORMAT', 'general');
        };
        $errorMessages = [
            self::ERR_RECEIVER_NAME_REQUIRED => $langRequiredCb,
            self::ERR_RECEIVER_EMAIL_REQUIRED => $langRequiredCb,
            self::ERR_RECEIVER_EMAIL_INVALID => $langInvalidFormatCb,
            self::ERR_SENDER_NAME_REQUIRED => $langRequiredCb,
            self::ERR_SENDER_EMAIL_REQUIRED => $langRequiredCb,
            self::ERR_SENDER_EMAIL_INVALID => $langInvalidFormatCb,
        ];
        $successMessages = [
            self::OK_SENT => 'Email has been sent successfully',
        ];
        $this->getResultStatusCollector()->construct($errorMessages, $successMessages);
        return $this;
    }

    /**
     * Get receiver name
     * @return string
     */
    public function getReceiverName(): string
    {
        return $this->receiverName;
    }

    /**
     * Set receiver name
     * @param string $receiverName
     * @return static
     */
    public function setReceiverName(string $receiverName): static
    {
        $this->receiverName = trim($receiverName);
        return $this;
    }

    /**
     * Get receiver email
     * @return string
     */
    public function getReceiverEmail(): string
    {
        return $this->receiverEmail;
    }

    /**
     * Set receiver email
     * @param string $receiverEmail
     * @return static
     */
    public function setReceiverEmail(string $receiverEmail): static
    {
        $this->receiverEmail = trim($receiverEmail);
        return $this;
    }

    /**
     * Get message
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Set message
     * @param string $message
     * @return static
     */
    public function setMessage(string $message): static
    {
        $this->message = trim($message);
        return $this;
    }

    /**
     * Get Sender name
     * @return string
     */
    public function getSenderName(): string
    {
        return $this->senderName;
    }

    /**
     * Set Sender name
     * @param string $senderName
     * @return static
     */
    public function setSenderName(string $senderName): static
    {
        $this->senderName = trim($senderName);
        return $this;
    }

    /**
     * Get sender email
     * @return string
     */
    public function getSenderEmail(): string
    {
        return $this->senderEmail;
    }

    /**
     * Set sender email
     * @param string $senderEmail
     * @return static
     */
    public function setSenderEmail(string $senderEmail): static
    {
        $this->senderEmail = trim($senderEmail);
        return $this;
    }

    /**
     * Get sender user
     * @return User|null
     */
    public function getSenderUser(): ?User
    {
        return $this->senderUser;
    }

    /**
     * Set sender user
     * @param User|null $senderUser
     * @return static
     */
    public function setSenderUser(?User $senderUser): static
    {
        $this->senderUser = $senderUser;
        return $this;
    }

    /**
     * Get alternative captcha validator
     * @return AlternativeCaptchaValidator|null
     */
    public function getAlternativeCaptchaValidator(): ?AlternativeCaptchaValidator
    {
        return $this->alternativeCaptchaValidator;
    }

    /**
     * Set alternative captcha validator
     * @param AlternativeCaptchaValidator $alternativeCaptchaValidator
     * @return static
     */
    public function setAlternativeCaptchaValidator(AlternativeCaptchaValidator $alternativeCaptchaValidator): static
    {
        $this->alternativeCaptchaValidator = $alternativeCaptchaValidator;
        return $this;
    }

    /**
     * Get simple captcha validator
     * @return SimpleCaptchaValidator|null
     */
    public function getSimpleCaptchaValidator(): ?SimpleCaptchaValidator
    {
        return $this->simpleCaptchaValidator;
    }

    /**
     * Set simple captcha validator
     * @param SimpleCaptchaValidator $simpleCaptchaValidator
     * @return static
     */
    public function setSimpleCaptchaValidator(SimpleCaptchaValidator $simpleCaptchaValidator): static
    {
        $this->simpleCaptchaValidator = $simpleCaptchaValidator;
        return $this;
    }

    /**
     * Returns email from sender account or sender input
     * @return string|null
     */
    public function getMyEmail(): ?string
    {
        $myEmail = $this->getSenderUser()->Email ?? $this->getSenderEmail();
        return $myEmail;
    }

    /**
     * Set back url
     * @param string $backUrl
     * @return static
     */
    public function setBackUrl(string $backUrl): static
    {
        $this->backUrl = trim($backUrl);
        return $this;
    }

    /**
     * Returns back url
     * @return string
     */
    public function buildBackRedirectUrl(): string
    {
        if ($this->backUrl) {
            $url = $this->backUrl;
        } else {
            $url = $this->getUrlBuilder()->build(
                ResponsiveLotDetailsUrlConfig::new()->forRedirect(
                    $this->getAuctionLot()->LotItemId,
                    $this->getAuctionLot()->AuctionId,
                    null,
                    [UrlConfigConstants::OP_ACCOUNT_ID => $this->getAuction()->AccountId]
                )
            );
        }
        return $url;
    }

    /**
     * Check sender name error
     * @return bool
     */
    public function hasSenderNameError(): bool
    {
        $has = $this->getResultStatusCollector()->hasConcreteError($this->senderNameErrors);
        return $has;
    }

    /**
     * Check sender email error
     * @return bool
     */
    public function hasSenderEmailError(): bool
    {
        $has = $this->getResultStatusCollector()->hasConcreteError($this->senderEmailErrors);
        return $has;
    }

    /**
     * Check receiver name error
     * @return bool
     */
    public function hasReceiverNameError(): bool
    {
        $has = $this->getResultStatusCollector()->hasConcreteError($this->receiverNameErrors);
        return $has;
    }

    /**
     * Check receiver email error
     * @return bool
     */
    public function hasReceiverEmailError(): bool
    {
        $has = $this->getResultStatusCollector()->hasConcreteError($this->receiverEmailErrors);
        return $has;
    }

    /**
     * Check simple captcha error
     * @return bool
     */
    public function hasSimpleCaptchaError(): bool
    {
        $has = true;
        if ($this->getSimpleCaptchaValidator()) {
            $has = $this->getSimpleCaptchaValidator()->validate();
        }
        return !$has;
    }

    /**
     * Check alternative captcha error
     * @return bool
     */
    public function hasAlternativeCaptchaError(): bool
    {
        $has = true;
        if ($this->getAlternativeCaptchaValidator()) {
            $has = $this->getAlternativeCaptchaValidator()->validate();
        }
        return !$has;
    }

    /**
     * Returns sender name error message
     * @return string
     */
    public function senderNameErrorMessage(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes($this->senderNameErrors);
        return $errorMessage;
    }

    /**
     * Returns sender email error message
     * @return string
     */
    public function senderEmailErrorMessage(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes($this->senderEmailErrors);
        return $errorMessage;
    }

    /**
     * Returns receiver name error message
     * @return string
     */
    public function receiverNameErrorMessage(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes($this->receiverNameErrors);
        return $errorMessage;
    }

    /**
     * Returns receiver email error message
     * @return string
     */
    public function receiverEmailErrorMessage(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes($this->receiverEmailErrors);
        return $errorMessage;
    }

    /**
     * Returns simple captcha error message
     * @return string
     */
    public function simpleCaptchaErrorMessage(): string
    {
        $errorMessage = $this->getSimpleCaptchaValidator() ? $this->getSimpleCaptchaValidator()->errorMessage() : '';
        return $errorMessage;
    }

    /**
     * Returns alternative captcha error message
     * @return string
     */
    public function alternativeCaptchaErrorMessage(): string
    {
        $errorMessage = $this->getAlternativeCaptchaValidator() ? $this->getAlternativeCaptchaValidator()->getErrorMessage() : '';
        return $errorMessage;
    }

    /**
     * Returns success message
     * @return string
     */
    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
    }

    /**
     * Name validation
     * @param string|null $name
     * @param int $code
     * @return void
     */
    protected function validateName(?string $name, int $code): void
    {
        if ($name === '') {
            $this->getResultStatusCollector()->addError($code);
        }
    }

    /**
     * Email validation
     * @param string|null $email
     * @param int $requiredCode
     * @param int $invalidCode
     * @return void
     */
    protected function validateEmail(?string $email, int $requiredCode, int $invalidCode): void
    {
        if ($email === '') {
            $this->getResultStatusCollector()->addError($requiredCode);
        } elseif (!EmailAddressChecker::new()->isEmail($email)) {
            $this->getResultStatusCollector()->addError($invalidCode);
        }
    }

    /**
     * Validate all input
     * @return bool
     */
    public function validate(): bool
    {
        $isValid = true;
        $collector = $this->getResultStatusCollector();
        $collector->clear();
        $this->validateName($this->receiverName, self::ERR_RECEIVER_NAME_REQUIRED);
        $this->validateName($this->senderName, self::ERR_SENDER_NAME_REQUIRED);
        $this->validateEmail($this->receiverEmail, self::ERR_RECEIVER_EMAIL_REQUIRED, self::ERR_RECEIVER_EMAIL_INVALID);
        $this->validateEmail($this->senderEmail, self::ERR_SENDER_EMAIL_REQUIRED, self::ERR_SENDER_EMAIL_INVALID);

        if (
            $this->getSimpleCaptchaValidator()
            && !$this->getSimpleCaptchaValidator()->validate()
        ) {
            $isValid = false;
        }
        if (
            $this->getAlternativeCaptchaValidator()
            && !$this->getAlternativeCaptchaValidator()->validate()
        ) {
            $isValid = false;
        }
        if ($collector->hasError()) {
            $isValid = false;
        }
        return $isValid;
    }

    /**
     * Add email template data to action queue.
     * @param int|null $editorUserId null means anonymous user
     * @return void
     */
    public function sendEmail(?int $editorUserId): void
    {
        $editorUserId = $editorUserId ?? $this->getUserLoader()->loadSystemUserId();
        $senderData = [$this->getSenderName(), $this->getMyEmail()];
        $emailManager = Email_Template::new()->construct(
            $this->getAuction()->AccountId,
            Constants\EmailKey::SEND_A_FRIEND,
            $editorUserId,
            [
                $senderData,
                $this->getAuction(),
                $this->getAuctionLot(),
                $this->getReceiverName(),
                $this->getReceiverEmail(),
                $this->getMessage(),
            ]
        );
        $emailManager->addToActionQueue(Constants\ActionQueue::LOW);
        $this->getResultStatusCollector()->addSuccess(self::OK_SENT);
    }

    /**
     * Check tell a friend is available or not.
     * @return bool
     */
    public function isAvailable(): bool
    {
        $isAvailable = true;
        if (!$this->getAuction()) {
            $isAvailable = false;
        } elseif (!$this->getAuctionLot()) {
            $isAvailable = false;
        } elseif (!$this->isAllowedTellFriend()) {
            $isAvailable = false;
        }
        return $isAvailable;
    }

    /**
     * Check tell a friend allow or not.
     * @return bool
     */
    protected function isAllowedTellFriend(): bool
    {
        $isTellAFriend = $this->getSettingsManager()
            ->get(Constants\Setting::TELL_A_FRIEND, $this->getAuction()->AccountId);
        $isAllowAnyoneToTellAFriend = $this->getSettingsManager()
            ->get(Constants\Setting::ALLOW_ANYONE_TO_TELL_A_FRIEND, $this->getAuction()->AccountId);
        $isAllowed = $isTellAFriend
            && ($isAllowAnyoneToTellAFriend
                || $this->getSenderUser());
        return $isAllowed;
    }
}
