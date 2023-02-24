<?php
/**
 * SAM-7984: Adjustments for Settlement printable with responsive layout [dev only]
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           06-19, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Access;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class SettlementCheckAccessCheckingResult
 * @package Sam\Settlement\Check
 */
class SettlementCheckAccessCheckingResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    // --------- Error codes and related messages -----

    /** @var int Check User entity existence */
    public const ERR_EDITOR_USER_NOT_FOUND = 1;
    /** @var int Check Settlement account id provided */
    public const ERR_SETTLEMENT_ACCOUNT_INVALID = 2;
    /** @var int Check if editor user is authorized */
    public const ERR_ANONYMOUS_USER_ACCESS_DENIED = 3;
    /** @var int Check if user have enough privileges for manage settlements */
    public const ERR_MANAGE_SETTLEMENTS_PRIVILEGE_ABSENT = 4;
    /** @var int Check if the file for the "settlement check" is configured. */
    public const ERR_CHECK_FILE_NOT_CONFIGURED = 5;
    /** @var int Check existence of settlement-check file */
    public const ERR_CHECK_FILE_NOT_FOUND = 6;
    /** @var int Access constraints failed */
    public const ERR_ACCESS_DENIED = 7;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_EDITOR_USER_NOT_FOUND => 'Editor user not found',
        self::ERR_SETTLEMENT_ACCOUNT_INVALID => 'Settlement account invalid',
        self::ERR_ANONYMOUS_USER_ACCESS_DENIED => 'Anonymous user access denied',
        self::ERR_MANAGE_SETTLEMENTS_PRIVILEGE_ABSENT => '"Manage settlements" privilege absent',
        self::ERR_CHECK_FILE_NOT_CONFIGURED => 'Settlement-Check file is not configured',
        self::ERR_CHECK_FILE_NOT_FOUND => 'Settlement-Check file not found',
        self::ERR_ACCESS_DENIED => 'Access denied',
    ];

    // --------- Success codes and related messages -----

    /** @var int Check is installation a single tenant installation (cfg()->core->portal->enabled === false) */
    public const OK_ALLOW_ACCESS_FOR_SINGLE_TENANT_INSTALLATION = 100;
    /** @var int Check access for cross domain admin */
    public const OK_ALLOW_ACCESS_FOR_CROSS_DOMAIN_ADMIN = 101;
    /** @var int Check accounts match for editor user and settlement */
    public const OK_ALLOW_ACCESS_FOR_ACCOUNT_MATCH = 102;


    /** @var string[] */
    protected const SUCCESS_MESSAGES = [
        self::OK_ALLOW_ACCESS_FOR_SINGLE_TENANT_INSTALLATION => 'Access allowed in single-tenant installation',
        self::OK_ALLOW_ACCESS_FOR_CROSS_DOMAIN_ADMIN => 'Access allowed for cross domain admin',
        self::OK_ALLOW_ACCESS_FOR_ACCOUNT_MATCH => 'Access allowed when editor user\'s account is the same as settlement\'s account'
    ];

    protected ?int $settlementAccountId;
    protected ?int $editorUserId;

    public string $fileName = '';
    public string $filePath = '';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $settlementAccountId
     * @param int|null $editorUserId
     * @return $this
     */
    public function construct(?int $settlementAccountId, ?int $editorUserId): static
    {
        $this->settlementAccountId = $settlementAccountId;
        $this->editorUserId = $editorUserId;

        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES, self::SUCCESS_MESSAGES);
        return $this;
    }

    /**
     * @return array
     */
    public function logData(): array
    {
        $logData = [
            'editor id' => $this->editorUserId,
            'settlement acc id' => $this->settlementAccountId,
            'settlement check file name' => $this->fileName,
            'settlement check file path' => $this->filePath
        ];
        $payload = $this->hasError() ? $this->errorPayload() : [];

        $logData = array_merge($logData, $payload);
        return $logData;
    }

    // --- Mutate state ---

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    public function addSuccess(int $code): static
    {
        $this->getResultStatusCollector()->addSuccess($code);
        return $this;
    }

    // --- Query state ---

    public function hasSuccess(): bool
    {
        return $this->getResultStatusCollector()->hasSuccess();
    }

    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    public function errorPayload(): array
    {
        $payloads = $this->getResultStatusCollector()->getErrorPayloads();
        return $payloads[0] ?? [];
    }

    public function hasAccessDeniedError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_ACCESS_DENIED);
    }

    public function statusCode(): ?int
    {
        if ($this->hasError()) {
            return $this->getResultStatusCollector()->getFirstErrorCode();
        }
        if ($this->hasSuccess()) {
            return $this->getResultStatusCollector()->getFirstSuccessCode();
        }
        return null;
    }
}
