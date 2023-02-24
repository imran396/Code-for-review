<?php
/**
 * SAM-4714:Refactor Ask Question service
 * https://bidpath.atlassian.net/browse/SAM-4714
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/6/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Feedback\AskQuestion;

use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveLotDetailsUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Core\Email\Validate\EmailAddressChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Email\Email;
use Sam\Email\Queue\ActionQueue;
use Sam\Email\Queue\ActionQueueDto;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Security\Captcha\Alternative\Validate\AlternativeCaptchaValidator;
use Sam\Security\Captcha\Simple\Validate\SimpleCaptchaValidator;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionLotAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotItemAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class AskQuestionService
 * @package Sam\Feedback\AskQuestionService
 */
class AskQuestionService extends CustomizableClass
{
    use AuctionAwareTrait;
    use AuctionLotAwareTrait;
    use AuctionRendererAwareTrait;
    use LotItemAwareTrait;
    use LotRendererAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use SettingsManagerAwareTrait;
    use TranslatorAwareTrait;
    use UrlBuilderAwareTrait;
    use UserLoaderAwareTrait;

    public const ERR_SENDER_NAME_REQUIRED = 1;
    public const ERR_SENDER_EMAIL_REQUIRED = 2;
    public const ERR_SENDER_EMAIL_INVALID = 3;
    public const ERR_MESSAGE_REQUIRED = 4;
    public const ERR_SUBJECT_REQUIRED = 5;

    public const OK_SENT = 11;
    public const OK_VALID = 12;

    protected string $senderName = '';
    protected string $senderEmail = '';
    protected string $message = '';
    protected string $subject = '';
    protected string $backUrl = '';
    protected ?string $supportEmail = null;
    protected ?AlternativeCaptchaValidator $alternativeCaptchaValidator = null;
    protected ?SimpleCaptchaValidator $simpleCaptchaValidator = null;
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
    protected array $messageErrors = [self::ERR_MESSAGE_REQUIRED];
    /**
     * @var int[]
     */
    protected array $subjectErrors = [self::ERR_SUBJECT_REQUIRED];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function initResultStatusCollector(): void
    {
        // Init ResultStatusCollector
        $tr = $this->getTranslator();
        $langRequiredCb = static fn() => $tr->translate('GENERAL_REQUIRED', 'general');
        $langInvalidFormatCb = static fn() => $tr->translate('GENERAL_INVALID_FORMAT', 'general');
        $errorMessages = [
            self::ERR_SENDER_NAME_REQUIRED => $langRequiredCb,
            self::ERR_SENDER_EMAIL_REQUIRED => $langRequiredCb,
            self::ERR_MESSAGE_REQUIRED => $langRequiredCb,
            self::ERR_SUBJECT_REQUIRED => $langRequiredCb,
            self::ERR_SENDER_EMAIL_INVALID => $langInvalidFormatCb,
        ];
        $successMessages = [
            self::OK_SENT => 'Email has been sent successfully',
            self::OK_VALID => 'No errors found',
        ];
        $this->getResultStatusCollector()->construct($errorMessages, $successMessages);
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
     * Get Subject
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * Set Subject
     * @param string $subject
     * @return static
     */
    public function setSubject(string $subject): static
    {
        $this->subject = trim($subject);
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
     * @return string
     */
    public function buildBackRedirectUrl(): string
    {
        if ($this->backUrl) {
            $url = $this->backUrl;
        } else {
            $url = $this->getUrlBuilder()->build(
                ResponsiveLotDetailsUrlConfig::new()->forRedirect(
                    $this->getLotItemId(),
                    $this->getAuctionId(),
                    null,
                    [UrlConfigConstants::OP_ACCOUNT_ID => $this->getAuction()->AccountId]
                )
            );
        }
        return $url;
    }

    /**
     * @param string $backUrl
     * @return AskQuestionService
     */
    public function setBackUrl(string $backUrl): AskQuestionService
    {
        $this->backUrl = trim($backUrl);
        return $this;
    }

    /**
     * @return string
     */
    public function getSupportEmail(): string
    {
        if ($this->supportEmail === null) {
            $this->supportEmail = (string)$this->getSettingsManager()
                ->get(Constants\Setting::SUPPORT_EMAIL, $this->getAuction()->AccountId);
        }
        return $this->supportEmail;
    }

    /**
     * @param string $supportEmail
     * @return static
     * @noinspection PhpUnused
     */
    public function setSupportEmail(string $supportEmail): static
    {
        $this->supportEmail = trim($supportEmail);
        return $this;
    }

    /**
     * Name validation
     * @param string $name
     */
    protected function validateName(string $name): void
    {
        if ($name === '') {
            $this->getResultStatusCollector()->addError(self::ERR_SENDER_NAME_REQUIRED);
        }
    }

    /**
     * Message validation
     * @param string $message
     */
    protected function validateMessage(string $message): void
    {
        if ($message === '') {
            $this->getResultStatusCollector()->addError(self::ERR_MESSAGE_REQUIRED);
        }
    }

    /**
     * Subject validation
     * @param string $subject
     */
    protected function validateSubject(string $subject): void
    {
        if ($subject === '') {
            $this->getResultStatusCollector()->addError(self::ERR_SUBJECT_REQUIRED);
        }
    }

    /**
     * Email validation
     * @param string $email
     */
    public function validateEmail(string $email): void
    {
        if ($email === '') {
            $this->getResultStatusCollector()->addError(self::ERR_SENDER_EMAIL_REQUIRED);
        } elseif (!EmailAddressChecker::new()->isEmail($email)) {
            $this->getResultStatusCollector()->addError(self::ERR_SENDER_EMAIL_INVALID);
        }
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
     * Check subject error
     * @return bool
     */
    public function hasSubjectError(): bool
    {
        $has = $this->getResultStatusCollector()->hasConcreteError($this->subjectErrors);
        return $has;
    }

    /**
     * Check message error
     * @return bool
     */
    public function hasMessageError(): bool
    {
        $has = $this->getResultStatusCollector()->hasConcreteError($this->messageErrors);
        return $has;
    }

    /**
     * @return int[]
     * @internal
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    /**
     * @return int[]
     * @internal
     */
    public function successCodes(): array
    {
        return $this->getResultStatusCollector()->getSuccessCodes();
    }

    /**
     * Returns success message
     * @return string
     */
    public function successMessage(): string
    {
        $successMessage = (string)$this->getResultStatusCollector()->findFirstSuccessMessageAmongCodes([self::OK_VALID]);
        return $successMessage;
    }

    /**
     * Returns success message
     * @return string
     */
    public function emailSentSuccessMessage(): string
    {
        $successMessage = (string)$this->getResultStatusCollector()->findFirstSuccessMessageAmongCodes([self::OK_SENT]);
        return $successMessage;
    }

    /**
     * Returns simple captcha error message
     * @return string
     */
    public function simpleCaptchaErrorMessage(): string
    {
        $validator = $this->getSimpleCaptchaValidator();
        $errorMessage = $validator ? $validator->errorMessage() : '';
        return $errorMessage;
    }

    /**
     * Returns alternative captcha error message
     * @return string
     */
    public function alternativeCaptchaErrorMessage(): string
    {
        $validator = $this->getAlternativeCaptchaValidator();
        $errorMessage = $validator ? $validator->getErrorMessage() : '';
        return $errorMessage;
    }

    /**
     * Check simple captcha error
     * @return bool
     */
    public function hasSimpleCaptchaError(): bool
    {
        $has = true;
        $validator = $this->getSimpleCaptchaValidator();
        if ($validator) {
            $has = $validator->validate();
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
        $validator = $this->getAlternativeCaptchaValidator();
        if ($validator) {
            $has = $validator->validate();
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
     * Returns subject error message
     * @return string
     */
    public function subjectErrorMessage(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes($this->subjectErrors);
        return $errorMessage;
    }

    /**
     * Returns subject error message
     * @return string
     */
    public function messageFieldErrorMessage(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes($this->messageErrors);
        return $errorMessage;
    }

    /**
     * Validate all input
     * @return bool
     */
    public function validate(): bool
    {
        $isValid = true;
        $this->initResultStatusCollector();
        $collector = $this->getResultStatusCollector();

        $this->validateName($this->getSenderName());
        $this->validateEmail($this->getSenderEmail());
        $this->validateSubject($this->getSubject());
        $this->validateMessage($this->getMessage());

        $simpleValidator = $this->getSimpleCaptchaValidator();
        if (
            $simpleValidator
            && !$simpleValidator->validate()
        ) {
            $isValid = false;
        }

        $advancedValidator = $this->getAlternativeCaptchaValidator();
        if (
            $advancedValidator
            && !$advancedValidator->validate()
        ) {
            $isValid = false;
        }

        if ($collector->hasError()) {
            $isValid = false;
        }

        if ($isValid) {
            $this->getResultStatusCollector()->addSuccess(self::OK_VALID);
        }

        return $isValid;
    }

    /**
     * Add email template data to action queue.
     * @param int|null $editorUserId null means anonymous user
     */
    public function sendEmail(?int $editorUserId): void
    {
        $editorUserId = $editorUserId ?? $this->getUserLoader()->loadSystemUserId();
        $lotDetailsUrl = $this->getUrlBuilder()->build(
            ResponsiveLotDetailsUrlConfig::new()->forDomainRule(
                $this->getLotItemId(),
                $this->getAuctionId(),
                null,
                [UrlConfigConstants::OP_ACCOUNT_ID => $this->getAuction()->AccountId]
            )
        );

        $lotNo = $this->getLotRenderer()->renderLotNo($this->getAuctionLot());
        $itemNo = $this->getLotRenderer()->renderItemNo($this->getLotItem());
        $lotName = $this->getLotRenderer()->makeName($this->getLotItem()->Name, $this->getAuction()->TestAuction);
        $saleNo = $this->getAuctionRenderer()->renderSaleNo($this->getAuction());
        $auctionName = $this->getAuctionRenderer()->renderName($this->getAuction());
        $body = $this->getSenderName() . '(' . $this->getSenderEmail() . ')' . ' has a question about item: <br />' . "\n" .
            $lotNo . ' - ' . $lotName . ' (' . $itemNo . ')' . '<br />' . "\n" .
            'in Sale ' . $saleNo . ' ' . $auctionName . '<br/>';

        $body .= $lotDetailsUrl;

        $body .= '<br /><br />' .
            '====================================================<br />' .
            nl2br($this->getMessage());

        $email = Email::new()
            ->setFrom($this->getSupportEmail())
            ->setTo($this->getSupportEmail())
            ->setReplyTo($this->getSenderEmail())
            ->setSubject($this->getSubject())
            ->setHtmlBody($body);

        if (
            $this->getAuction()
            && $this->getAuction()->Email !== ''
        ) {
            $email->setBcc($this->getAuction()->Email);
        }

        $dto = ActionQueueDto::new();
        $dto->setEmail($email);
        $dto->setAccountId($this->getAuction()->AccountId);
        ActionQueue::new()->add($dto, Constants\ActionQueue::LOW, $editorUserId);

        $this->getResultStatusCollector()->addSuccess(self::OK_SENT);
    }

    /**
     * Check if "Ask question" function available
     */
    public function isFeatureAvailable(): bool
    {
        if (
            !$this->getLotItem()
            || !$this->getAuctionLot()
            || !$this->getAuction()
        ) {
            log_debug(
                "Auction lot was not found"
                . composeSuffix(['li' => $this->getLotItemId(), 'a' => $this->getAuctionId()])
            );
            return false;
        }
        return true;
    }
}
