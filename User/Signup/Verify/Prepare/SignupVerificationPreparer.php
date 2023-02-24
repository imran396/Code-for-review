<?php
/**
 * This is application dependent wrapper for generating verification code and send email to user.
 * It accesses to web request context and calls classes, that are pure from web context.
 *
 * SAM-6801: Improve email verification for v3.5 - refactor service and add unit tests
 * SAM-5327: Refactor verification email module
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           7/25/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Signup\Verify\Prepare;

use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Email_Template;
use RuntimeException;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Format\Base64\Base64Encoder;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Installation\Config\Repository\ConfigRepository;
use Sam\Settings\SettingsManager;
use Sam\Storage\WriteRepository\Entity\UserAuthentication\UserAuthenticationWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoader;
use User;
use UserAuthentication;

/**
 * Class VerificationEmailSender
 * @package Sam\User\Signup\Verify\Prepare
 */
class SignupVerificationPreparer extends CustomizableClass
{
    use EntityFactoryCreateTrait;
    use OptionalsTrait;
    use ResultStatusCollectorAwareTrait;
    use UserAuthenticationWriteRepositoryAwareTrait;

    // --- Incoming values ---

    public const OP_BACK_PAGE_PARAM_URL = OptionalKeyConstants::KEY_BACK_PAGE_PARAM_URL; // string
    public const OP_CURRENT_DATE_UTC = OptionalKeyConstants::KEY_CURRENT_DATE_UTC; // DateTimeInterface
    public const OP_EMAIL_PRIORITY = OptionalKeyConstants::KEY_EMAIL_PRIORITY; // int
    public const OP_IS_EMAILING_ENABLED = OptionalKeyConstants::KEY_IS_EMAILING_ENABLED; // bool
    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB; // bool
    public const OP_IS_VERIFY_EMAIL = Constants\Setting::VERIFY_EMAIL; // bool
    public const OP_SALE = 'sale'; // ?int
    public const OP_TTL = OptionalKeyConstants::KEY_TTL; // int
    public const OP_USER = OptionalKeyConstants::KEY_USER; // ?User
    public const OP_USER_AUTHENTICATION = OptionalKeyConstants::KEY_USER_AUTHENTICATION; // ?UserAuthentication
    public const OP_VERIFICATION_CODE_LENGTH = 'verificationCodeLength'; // int
    public const OP_VERIFICATION_SEED = 'verificationSeed'; // string

    protected int $systemAccountId;
    protected int $userId;

    // --- Outgoing values ---

    public const ERR_EMAIL_VERIFICATION_DISABLED = 1;
    public const ERR_USER_NOT_FOUND = 2;

    private const ERR_MESSAGES = [
        self::ERR_EMAIL_VERIFICATION_DISABLED => 'Email verification disabled',
        self::ERR_USER_NOT_FOUND => 'User not found for verification via email',
    ];

    // --- Constructors ---

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $userId
     * @param int $systemAccountId
     * @param array $optionals
     * @return $this
     */
    public function construct(int $userId, int $systemAccountId, array $optionals = []): static
    {
        $this->userId = $userId;
        $this->systemAccountId = $systemAccountId;
        $this->getResultStatusCollector()->construct(self::ERR_MESSAGES);
        $this->initOptionals($optionals);
        return $this;
    }

    // --- Published logic ---

    /**
     * Perform actions for asking user via email for verification code
     * @param int $editorUserId
     */
    public function requireVerification(int $editorUserId): void
    {
        if (!$this->canVerify()) {
            log_error($this->getResultStatusCollector()->getConcatenatedErrorMessage());
            return;
        }

        $this->prepare($editorUserId);
        $this->email($editorUserId);
    }

    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    // --- Internal logic ---

    /**
     * @return bool
     */
    protected function isVerifyEmail(): bool
    {
        return (bool)$this->fetchOptional(self::OP_IS_VERIFY_EMAIL);
    }

    /**
     * Build verification email and register it in action queue
     * @param int $editorUserId
     */
    protected function email(int $editorUserId): void
    {
        if (!$this->fetchOptional(self::OP_IS_EMAILING_ENABLED)) {
            return;
        }

        $emailManager = Email_Template::new()->construct(
            $this->systemAccountId,
            Constants\EmailKey::EMAIL_VERIFICATION,
            $editorUserId,
            [
                $this->fetchOptional(self::OP_USER),
                $this->fetchOptional(self::OP_BACK_PAGE_PARAM_URL),
                $this->fetchOptional(self::OP_SALE)
            ]
        );
        $emailPriority = $this->fetchOptional(self::OP_EMAIL_PRIORITY);
        $emailManager->addToActionQueue($emailPriority);
    }

    /**
     * Prepare verification data in user's entities
     * @param int $editorUserId
     */
    protected function prepare(int $editorUserId): void
    {
        $userAuthentication = $this->fetchOrCreateUserAuthentication();
        $verificationCodeGeneratedDateTimestamp = $userAuthentication->VerificationCodeGeneratedDate
            ? $userAuthentication->VerificationCodeGeneratedDate->getTimestamp()
            : 0;

        // Before generating a new verification code, determine if there is one and whether it is not expired yet
        $currentDateUtc = $this->fetchOptional(self::OP_CURRENT_DATE_UTC);
        $currentTimestamp = $currentDateUtc->getTimestamp();
        $ttl = $this->fetchOptional(self::OP_TTL);
        if (
            $userAuthentication->VerificationCode
            && $verificationCodeGeneratedDateTimestamp + $ttl > $currentTimestamp
        ) {
            return;
        }

        $userAuthentication->EmailVerified = false;
        $userAuthentication->VerificationCode = $this->generateVerificationCode();
        $userAuthentication->VerificationCodeGeneratedDate = $currentDateUtc;
        $this->getUserAuthenticationWriteRepository()->saveWithModifier($userAuthentication, $editorUserId);
    }

    /**
     * @return string
     */
    protected function generateVerificationCode(): string
    {
        $randomBytes = openssl_random_pseudo_bytes($this->fetchOptional(self::OP_VERIFICATION_CODE_LENGTH), $cryptoStrong);
        if (!$cryptoStrong) {
            log_warning('Random byte generator not crypto strong');
        }
        if (!$randomBytes) {
            throw new RuntimeException('Failed to generate random iv string');
        }
        $randomBytes = Base64Encoder::new()->construct()->encode($randomBytes);

        $verificationSeed = $this->fetchOptional(self::OP_VERIFICATION_SEED) . $this->userId;
        $nowIso = $this->fetchOptional(self::OP_CURRENT_DATE_UTC)->format(Constants\Date::ISO_MS);
        $encrypted = md5($nowIso . $verificationSeed . $randomBytes);
        return $encrypted;
    }

    /**
     * @return bool
     */
    protected function canVerify(): bool
    {
        if (!$this->isVerifyEmail()) {
            $this->getResultStatusCollector()->addError(self::ERR_EMAIL_VERIFICATION_DISABLED);
            return false;
        }
        if (!$this->fetchOptional(self::OP_USER)) {
            $this->getResultStatusCollector()->addError(self::ERR_USER_NOT_FOUND);
            return false;
        }
        return true;
    }

    /**
     * @return UserAuthentication
     */
    protected function fetchOrCreateUserAuthentication(): UserAuthentication
    {
        $userAuthentication = $this->fetchOptional(self::OP_USER_AUTHENTICATION);
        if (!$userAuthentication) {
            $userAuthentication = $this->createEntityFactory()->userAuthentication();
            $userAuthentication->UserId = $this->userId;
        }
        return $userAuthentication;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $isReadOnlyDb = (bool)($optionals[self::OP_IS_READ_ONLY_DB] ?? false);
        $userId = $this->userId;

        $optionals[self::OP_BACK_PAGE_PARAM_URL] = (string)($optionals[self::OP_BACK_PAGE_PARAM_URL] ?? '');
        $optionals[self::OP_CURRENT_DATE_UTC] = $optionals[self::OP_CURRENT_DATE_UTC]
            ?? static function (): DateTimeInterface {
                return (new DateTime())->setTimezone(new DateTimeZone('UTC'));
            };
        $optionals[self::OP_EMAIL_PRIORITY] = (int)($optionals[self::OP_EMAIL_PRIORITY]
            ?? Constants\ActionQueue::MEDIUM);
        $optionals[self::OP_IS_EMAILING_ENABLED] = (bool)($optionals[self::OP_IS_EMAILING_ENABLED] ?? true);
        $optionals[self::OP_IS_VERIFY_EMAIL] = $optionals[self::OP_IS_VERIFY_EMAIL]
            ?? static function (): bool {
                return (bool)SettingsManager::new()->getForMain(Constants\Setting::VERIFY_EMAIL);
            };
        $optionals[self::OP_SALE] = Cast::toInt($optionals[self::OP_SALE] ?? null);
        $optionals[self::OP_TTL] = $optionals[self::OP_TTL]
            ?? static function (): int {
                return ConfigRepository::getInstance()->get('core->user->signup->verifyEmail->ttl');
            };
        $optionals[self::OP_USER] = array_key_exists(self::OP_USER, $optionals)
            ? $optionals[self::OP_USER]
            : static function () use ($userId, $isReadOnlyDb): ?User {
                return UserLoader::new()->load($userId, $isReadOnlyDb);
            };
        $optionals[self::OP_USER_AUTHENTICATION] = array_key_exists(self::OP_USER_AUTHENTICATION, $optionals)
            ? $optionals[self::OP_USER_AUTHENTICATION]
            : static function () use ($userId, $isReadOnlyDb): ?UserAuthentication {
                return UserLoader::new()->loadUserAuthentication($userId, $isReadOnlyDb);
            };
        $optionals[self::OP_VERIFICATION_CODE_LENGTH] = $optionals[self::OP_VERIFICATION_CODE_LENGTH]
            ?? static function (): int {
                return ConfigRepository::getInstance()->get('core->user->signup->verifyEmail->verificationCodeLength');
            };
        $optionals[self::OP_VERIFICATION_SEED] = $optionals[self::OP_VERIFICATION_SEED]
            ?? static function (): string {
                return ConfigRepository::getInstance()->get('core->user->signup->verifyEmail->verificationSeed');
            };
        $this->setOptionals($optionals);
    }
}
