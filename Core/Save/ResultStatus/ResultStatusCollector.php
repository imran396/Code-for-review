<?php
/**
 * SAM-5845: Adjust ResultStatusCollector
 * SAM-4729: General logic for editor services
 *
 * Class collects result statuses (error,warning,success) in some processing
 * and supplies helper methods for respective messages rendering.
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/19/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Save\ResultStatus;

use Sam\Core\Save\ResultStatus\Exception\InvalidCodeForType;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ResultStatusCollector
 * @package Sam\Core\Save\ResultStatus
 */
class ResultStatusCollector extends CustomizableClass
{
    private const MESSAGE_GLUE_DEF = '</br>';

    /**
     * General separator for success, error, warning messages concatenation
     */
    protected string $resultMessageGlue = self::MESSAGE_GLUE_DEF;

    /**
     * Registered result statuses
     */
    protected array $resultStatuses = [];

    /**
     * Available result codes and messages
     */
    protected array $all = [
        ResultStatusConstants::TYPE_ERROR => [],
        ResultStatusConstants::TYPE_SUCCESS => [],
        ResultStatusConstants::TYPE_WARNING => [],
        ResultStatusConstants::TYPE_INFO => [],
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- --- --- --- --- --- --- --- --- --- --- --- --- Mutation logic --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---

    /**
     * @param string[]|\Closure[] $errorMessages
     * @param string[]|\Closure[] $successMessages
     * @param string[]|\Closure[] $warningMessages
     * @param string[]|\Closure[] $infoMessages
     * @param string|null $messageGlue
     * @return static
     */
    public function construct(
        array $errorMessages = [],
        array $successMessages = [],
        array $warningMessages = [],
        array $infoMessages = [],
        ?string $messageGlue = null
    ): static {
        $this->clear();
        $this->initAllErrors($errorMessages);
        $this->initAllSuccesses($successMessages);
        $this->initAllWarnings($warningMessages);
        $this->initAllInfos($infoMessages);
        $this->setResultMessageGlue($messageGlue);
        return $this;
    }

    /**
     * @param string|null $resultMessageGlue null means set default glue.
     * @return static
     */
    public function setResultMessageGlue(?string $resultMessageGlue): static
    {
        // Don't normalize string by trim
        $this->resultMessageGlue = $resultMessageGlue ?? self::MESSAGE_GLUE_DEF;
        return $this;
    }

    /**
     * @return static
     */
    public function clear(): static
    {
        $this->resultStatuses = array_fill_keys(ResultStatusConstants::TYPES, []);
        return $this;
    }

    // -------------------------------------------------------------------------------

    /**
     * @param string[] $messages
     * @return static
     */
    public function initAllErrors(array $messages): static
    {
        return $this->initAllMessagesByType(ResultStatusConstants::TYPE_ERROR, $messages);
    }

    /**
     * @param string[] $messages
     * @return static
     */
    public function initAllWarnings(array $messages): static
    {
        return $this->initAllMessagesByType(ResultStatusConstants::TYPE_WARNING, $messages);
    }

    /**
     * @param string[] $messages
     * @return static
     */
    public function initAllSuccesses(array $messages): static
    {
        return $this->initAllMessagesByType(ResultStatusConstants::TYPE_SUCCESS, $messages);
    }

    /**
     * @param string[] $messages
     * @return static
     */
    public function initAllInfos(array $messages): static
    {
        return $this->initAllMessagesByType(ResultStatusConstants::TYPE_INFO, $messages);
    }

    /**
     * @param int $type
     * @param string[] $messages
     * @return static
     */
    protected function initAllMessagesByType(int $type, array $messages): static
    {
        $this->all[$type] = $messages;
        return $this;
    }

    // -------------------------------------------------------------------------------

    /**
     * @param int $code
     * @param string|null $message
     * @param array $payload
     * @return static
     */
    public function addError(int $code, ?string $message = null, array $payload = []): static
    {
        return $this->add(ResultStatusConstants::TYPE_ERROR, $code, $message, $payload);
    }

    /**
     * @param int $code
     * @param string|null $message
     * @param array $payload
     * @return static
     */
    public function addSuccess(int $code, ?string $message = null, array $payload = []): static
    {
        return $this->add(ResultStatusConstants::TYPE_SUCCESS, $code, $message, $payload);
    }

    /**
     * @param int $code
     * @param string|null $message
     * @param array $payload
     * @return static
     */
    public function addWarning(int $code, ?string $message = null, array $payload = []): static
    {
        return $this->add(ResultStatusConstants::TYPE_WARNING, $code, $message, $payload);
    }

    /**
     * @param int $code
     * @param string|null $message
     * @param array $payload
     * @return static
     */
    public function addInfo(int $code, ?string $message = null, array $payload = []): static
    {
        return $this->add(ResultStatusConstants::TYPE_INFO, $code, $message, $payload);
    }

    /**
     * Add result status data to collection
     * @param int $type
     * @param int $code
     * @param string|null $message - redefine message for error status code, null - for initial message
     * @param array $payload - any additional data
     * @return static
     */
    protected function add(int $type, int $code, string $message = null, array $payload = []): static
    {
        if (!$message) {
            if (!isset($this->all[$type][$code])) {
                throw InvalidCodeForType::withDefaultMessage($type, $code);
            }
            $message = $this->all[$type][$code];
        }
        $this->resultStatuses[$type][] = ResultStatus::new()->construct($type, $code, $message, $payload);
        return $this;
    }

    // -------------------------------------------------------------------------------

    public function addSuccessWithInjectedInMessageArguments(int $code, array $arguments = [], array $payload = []): static
    {
        $message = null;
        if ($arguments) {
            $messageTpl = $this->getSuccessMessageByCodeAmongAll($code);
            $message = sprintf($messageTpl, ...$arguments);
        }
        return $this->addSuccess($code, $message, $payload);
    }

    public function addErrorWithInjectedInMessageArguments(int $code, array $arguments = [], array $payload = []): static
    {
        $message = null;
        if ($arguments) {
            $messageTpl = $this->getErrorMessageByCodeAmongAll($code);
            $message = sprintf($messageTpl, ...$arguments);
        }
        return $this->addError($code, $message, $payload);
    }

    public function addWarningWithInjectedInMessageArguments(int $code, array $arguments = [], array $payload = []): static
    {
        $message = null;
        if ($arguments) {
            $messageTpl = $this->getWarningMessageByCodeAmongAll($code);
            $message = sprintf($messageTpl, ...$arguments);
        }
        return $this->addWarning($code, $message, $payload);
    }

    public function addInfoWithInjectedInMessageArguments(int $code, array $arguments = [], array $payload = []): static
    {
        $message = null;
        if ($arguments) {
            $messageTpl = $this->getInfoMessageByCodeAmongAll($code);
            $message = sprintf($messageTpl, ...$arguments);
        }
        return $this->addInfo($code, $message, $payload);
    }

    // -------------------------------------------------------------------------------

    public function addErrorWithAppendedMessage(int $code, string $append, array $payload = []): static
    {
        return $this->addError($code, $this->getErrorMessageByCodeAmongAll($code) . $append, $payload);
    }

    public function addSuccessWithAppendedMessage(int $code, string $append, array $payload = []): static
    {
        return $this->addSuccess($code, $this->getSuccessMessageByCodeAmongAll($code) . $append, $payload);
    }

    public function addWarningWithAppendedMessage(int $code, string $append, array $payload = []): static
    {
        return $this->addWarning($code, $this->getWarningMessageByCodeAmongAll($code) . $append, $payload);
    }

    public function addInfoWithAppendedMessage(int $code, string $append, array $payload = []): static
    {
        return $this->addInfo($code, $this->getInfoMessageByCodeAmongAll($code) . $append, $payload);
    }

    // --- --- --- --- --- --- --- --- --- --- --- --- Query logic --- --- --- --- --- --- --- --- --- --- --- --- ---

    /**
     * @return ResultStatus[]
     */
    public function getErrorStatuses(): array
    {
        return $this->getResultStatuses(ResultStatusConstants::TYPE_ERROR);
    }

    /**
     * @return ResultStatus[]
     */
    public function getSuccessStatuses(): array
    {
        return $this->getResultStatuses(ResultStatusConstants::TYPE_SUCCESS);
    }

    /**
     * @return ResultStatus[]
     */
    public function getWarningStatuses(): array
    {
        return $this->getResultStatuses(ResultStatusConstants::TYPE_WARNING);
    }

    /**
     * @return ResultStatus[]
     */
    public function getInfoStatuses(): array
    {
        return $this->getResultStatuses(ResultStatusConstants::TYPE_INFO);
    }

    /**
     * @param int $type
     * @return ResultStatus[]
     */
    protected function getResultStatuses(int $type): array
    {
        $resultStatuses = $this->resultStatuses[$type] ?? [];
        return $resultStatuses;
    }

    // -------------------------------------------------------------------------------

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return $this->has(ResultStatusConstants::TYPE_ERROR);
    }

    /**
     * @return bool
     */
    public function hasSuccess(): bool
    {
        return $this->has(ResultStatusConstants::TYPE_SUCCESS);
    }

    /**
     * @return bool
     */
    public function hasWarning(): bool
    {
        return $this->has(ResultStatusConstants::TYPE_WARNING);
    }

    /**
     * @return bool
     */
    public function hasInfo(): bool
    {
        return $this->has(ResultStatusConstants::TYPE_INFO);
    }

    /**
     * @param int $type
     * @return bool
     */
    protected function has(int $type): bool
    {
        $has = (bool)count($this->getResultStatuses($type));
        return $has;
    }

    // -------------------------------------------------------------------------------

    /**
     * @param int|int[] $searchCodes
     * @return bool
     */
    public function hasConcreteError(int|array $searchCodes): bool
    {
        $registeredCodes = $this->getResultStatusCodes(ResultStatusConstants::TYPE_ERROR);
        return $this->hasConcreteResult($searchCodes, $registeredCodes);
    }

    /**
     * @param int|int[] $searchCodes
     * @return bool
     */
    public function hasConcreteSuccess(int|array $searchCodes): bool
    {
        $registeredCodes = $this->getResultStatusCodes(ResultStatusConstants::TYPE_SUCCESS);
        return $this->hasConcreteResult($searchCodes, $registeredCodes);
    }

    /**
     * @param int|int[] $searchCodes
     * @return bool
     */
    public function hasConcreteWarning(int|array $searchCodes): bool
    {
        $registeredCodes = $this->getResultStatusCodes(ResultStatusConstants::TYPE_WARNING);
        return $this->hasConcreteResult($searchCodes, $registeredCodes);
    }

    /**
     * @param int|int[] $searchCodes
     * @return bool
     */
    public function hasConcreteInfo(int|array $searchCodes): bool
    {
        $registeredCodes = $this->getResultStatusCodes(ResultStatusConstants::TYPE_INFO);
        return $this->hasConcreteResult($searchCodes, $registeredCodes);
    }

    /**
     * Return registered in collection status codes for definite result status type
     * @param int $type
     * @return array
     */
    protected function getResultStatusCodes(int $type): array
    {
        $codes = array_map(
            static function (ResultStatus $resultStatus) {
                return $resultStatus->getCode();
            },
            $this->getResultStatuses($type)
        );
        return $codes;
    }

    /**
     * Check current collection has concrete result (error|warning|success) code $needle
     * among registered codes $haystack
     * @param int|int[] $needle
     * @param int[] $haystack
     * @return bool
     */
    protected function hasConcreteResult(int|array $needle, array $haystack): bool
    {
        $intersected = array_intersect($haystack, (array)$needle);
        $has = count($intersected) > 0;
        return $has;
    }

    // -------------------------------------------------------------------------------

    /**
     * @return string[]
     */
    public function getErrorMessages(): array
    {
        return $this->getResultStatusMessages(ResultStatusConstants::TYPE_ERROR);
    }

    /**
     * @return string[]
     */
    public function getSuccessMessages(): array
    {
        return $this->getResultStatusMessages(ResultStatusConstants::TYPE_SUCCESS);
    }

    /**
     * @return string[]
     */
    public function getWarningMessages(): array
    {
        return $this->getResultStatusMessages(ResultStatusConstants::TYPE_WARNING);
    }

    /**
     * @return string[]
     */
    public function getInfoMessages(): array
    {
        return $this->getResultStatusMessages(ResultStatusConstants::TYPE_INFO);
    }

    /**
     * Return array of messages for definite result status type
     * @param int $type
     * @return array
     */
    protected function getResultStatusMessages(int $type): array
    {
        $messages = array_map(
            static function (ResultStatus $resultStatus) {
                return $resultStatus->getMessage();
            },
            $this->getResultStatuses($type)
        );
        return $messages;
    }

    // -------------------------------------------------------------------------------

    /**
     * @param string|null $glue
     * @return string
     */
    public function getConcatenatedErrorMessage(string $glue = null): string
    {
        return $this->getConcatenatedMessage(ResultStatusConstants::TYPE_ERROR, $glue);
    }

    /**
     * @param string|null $glue
     * @return string
     */
    public function getConcatenatedSuccessMessage(string $glue = null): string
    {
        return $this->getConcatenatedMessage(ResultStatusConstants::TYPE_SUCCESS, $glue);
    }

    /**
     * @param string|null $glue
     * @return string
     */
    public function getConcatenatedWarningMessage(string $glue = null): string
    {
        return $this->getConcatenatedMessage(ResultStatusConstants::TYPE_WARNING, $glue);
    }

    /**
     * @param string|null $glue
     * @return string
     */
    public function getConcatenatedInfoMessage(string $glue = null): string
    {
        return $this->getConcatenatedMessage(ResultStatusConstants::TYPE_INFO, $glue);
    }

    /**
     * Get message as concatenated string
     * @param int $type
     * @param string|null $glue
     * @return string
     */
    protected function getConcatenatedMessage(int $type, string $glue = null): string
    {
        if ($glue === null) {
            $glue = $this->resultMessageGlue;
        }
        $messages = $this->getResultStatusMessages($type);
        $output = implode($glue, $messages);
        return $output;
    }

    // -------------------------------------------------------------------------------

    /**
     * @param string|null $glue
     * @return string
     */
    public function getConcatenatedErrorMessageSuffixedByPayload(string $glue = null): string
    {
        return $this->getConcatenatedMessageSuffixedByPayload(ResultStatusConstants::TYPE_ERROR, $glue);
    }

    /**
     * @param string|null $glue
     * @return string
     */
    public function getConcatenatedSuccessMessageSuffixedByPayload(string $glue = null): string
    {
        return $this->getConcatenatedMessageSuffixedByPayload(ResultStatusConstants::TYPE_SUCCESS, $glue);
    }

    /**
     * @param string|null $glue
     * @return string
     */
    public function getConcatenatedWarningMessageSuffixedByPayload(string $glue = null): string
    {
        return $this->getConcatenatedMessageSuffixedByPayload(ResultStatusConstants::TYPE_WARNING, $glue);
    }

    /**
     * @param string|null $glue
     * @return string
     */
    public function getConcatenatedInfoMessageSuffixedByPayload(string $glue = null): string
    {
        return $this->getConcatenatedMessageSuffixedByPayload(ResultStatusConstants::TYPE_INFO, $glue);
    }

    // -------------------------------------------------------------------------------

    /**
     * @param int $code
     * @return string|callable|null
     */
    public function getErrorMessageByCodeAmongAll(int $code): string|callable|null
    {
        return $this->getMessageByCodeAmongAll(ResultStatusConstants::TYPE_ERROR, $code);
    }

    /**
     * @param int $code
     * @return string|callable|null
     */
    public function getSuccessMessageByCodeAmongAll(int $code): string|callable|null
    {
        return $this->getMessageByCodeAmongAll(ResultStatusConstants::TYPE_SUCCESS, $code);
    }

    /**
     * @param int $code
     * @return string|callable|null
     */
    public function getWarningMessageByCodeAmongAll(int $code): string|callable|null
    {
        return $this->getMessageByCodeAmongAll(ResultStatusConstants::TYPE_WARNING, $code);
    }

    /**
     * @param int $code
     * @return string|callable|null
     */
    public function getInfoMessageByCodeAmongAll(int $code): string|callable|null
    {
        return $this->getMessageByCodeAmongAll(ResultStatusConstants::TYPE_INFO, $code);
    }

    /**
     * @param int $type
     * @param int $code
     * @return string|callable|null
     */
    protected function getMessageByCodeAmongAll(int $type, int $code): string|callable|null
    {
        $message = $this->all[$type][$code] ?? null;
        return $message;
    }

    // -------------------------------------------------------------------------------

    /**
     * @return int[]
     */
    public function getErrorCodes(): array
    {
        return $this->getResultStatusCodes(ResultStatusConstants::TYPE_ERROR);
    }

    /**
     * @return int[]
     */
    public function getSuccessCodes(): array
    {
        return $this->getResultStatusCodes(ResultStatusConstants::TYPE_SUCCESS);
    }

    /**
     * @return int[]
     */
    public function getWarningCodes(): array
    {
        return $this->getResultStatusCodes(ResultStatusConstants::TYPE_WARNING);
    }

    /**
     * @return int[]
     */
    public function getInfoCodes(): array
    {
        return $this->getResultStatusCodes(ResultStatusConstants::TYPE_INFO);
    }

    /**
     * @return int[][]
     */
    public function getAllCodes(): array
    {
        return [
            $this->getErrorCodes(),
            $this->getSuccessCodes(),
            $this->getWarningCodes(),
            $this->getInfoCodes()
        ];
    }

    // -------------------------------------------------------------------------------

    /**
     * @return array
     */
    public function getErrorPayloads(): array
    {
        return $this->getResultStatusPayloads(ResultStatusConstants::TYPE_ERROR);
    }

    /**
     * @return array
     */
    public function getSuccessPayloads(): array
    {
        return $this->getResultStatusPayloads(ResultStatusConstants::TYPE_SUCCESS);
    }

    /**
     * @return array
     */
    public function getWarningPayloads(): array
    {
        return $this->getResultStatusPayloads(ResultStatusConstants::TYPE_WARNING);
    }

    /**
     * @return array
     */
    public function getInfoPayloads(): array
    {
        return $this->getResultStatusPayloads(ResultStatusConstants::TYPE_INFO);
    }

    /**
     * Return array of payloads for definite result status type
     * @param int $type
     * @return array
     */
    protected function getResultStatusPayloads(int $type): array
    {
        $payloads = array_map(
            static function (ResultStatus $resultStatus) {
                return $resultStatus->getPayload();
            },
            $this->getResultStatuses($type)
        );
        return $payloads;
    }

    // -------------------------------------------------------------------------------

    /**
     * @param int|array $codes
     * @return array
     */
    public function getConcreteErrorCodes(int|array $codes): array
    {
        return $this->getConcreteResultStatusCodes(ResultStatusConstants::TYPE_ERROR, $codes);
    }

    /**
     * @param int|array $codes
     * @return array
     */
    public function getConcreteSuccessCodes(int|array $codes): array
    {
        return $this->getConcreteResultStatusCodes(ResultStatusConstants::TYPE_SUCCESS, $codes);
    }

    /**
     * @param int|array $codes
     * @return array
     */
    public function getConcreteWarningCodes(int|array $codes): array
    {
        return $this->getConcreteResultStatusCodes(ResultStatusConstants::TYPE_WARNING, $codes);
    }

    /**
     * @param int|array $codes
     * @return array
     */
    public function getConcreteInfoCodes(int|array $codes): array
    {
        return $this->getConcreteResultStatusCodes(ResultStatusConstants::TYPE_INFO, $codes);
    }

    /**
     * Return array of Codes for definite result status type
     */
    protected function getConcreteResultStatusCodes(int $type, int|array $codes): array
    {
        $resultCodes = array_map(
            static function (ResultStatus $resultStatus) use ($codes) {
                if (in_array($resultStatus->getCode(), (array)$codes, true)) {
                    return $resultStatus->getCode();
                }
                return null;
            },
            $this->getResultStatuses($type)
        );
        return array_values(array_filter($resultCodes));
    }

    // -------------------------------------------------------------------------------

    /**
     * @param int|array $codes
     * @return array
     */
    public function getConcreteErrorPayloads(int|array $codes): array
    {
        return $this->getConcreteResultStatusPayloads(ResultStatusConstants::TYPE_ERROR, $codes);
    }

    /**
     * @param int|array $codes
     * @return array
     */
    public function getConcreteSuccessPayloads(int|array $codes): array
    {
        return $this->getConcreteResultStatusPayloads(ResultStatusConstants::TYPE_SUCCESS, $codes);
    }

    /**
     * @param int|array $codes
     * @return array
     */
    public function getConcreteWarningPayloads(int|array $codes): array
    {
        return $this->getConcreteResultStatusPayloads(ResultStatusConstants::TYPE_WARNING, $codes);
    }

    /**
     * @param int|array $codes
     * @return array
     */
    public function getConcreteInfoPayloads(int|array $codes): array
    {
        return $this->getConcreteResultStatusPayloads(ResultStatusConstants::TYPE_INFO, $codes);
    }

    /**
     * Return array of payloads for definite result status type
     */
    protected function getConcreteResultStatusPayloads(int $type, int|array $codes): array
    {
        $payloads = array_map(
            static function (ResultStatus $resultStatus) use ($codes) {
                if (in_array($resultStatus->getCode(), (array)$codes, true)) {
                    return $resultStatus->getPayload();
                }
                return null;
            },
            $this->getResultStatuses($type)
        );
        return array_values(array_filter($payloads));
    }

    // -------------------------------------------------------------------------------

    /**
     * @return int|null
     */
    public function getFirstErrorCode(): ?int
    {
        $errorCodes = $this->getErrorCodes();
        return $errorCodes[0] ?? null;
    }

    /**
     * @return int|null
     */
    public function getFirstSuccessCode(): ?int
    {
        $successCodes = $this->getSuccessCodes();
        return $successCodes[0] ?? null;
    }

    /**
     * @return int|null
     */
    public function getFirstWarningCode(): ?int
    {
        $warningCodes = $this->getWarningCodes();
        return $warningCodes[0] ?? null;
    }

    /**
     * @return int|null
     */
    public function getFirstInfoCode(): ?int
    {
        $infoCodes = $this->getInfoCodes();
        return $infoCodes[0] ?? null;
    }

    /**
     * Returns the first status code of any type.
     * @return int|null
     */
    public function getFirstStatusCode(): ?int
    {
        /** @noinspection ArgumentUnpackingCanBeUsedInspection */
        $statuses = call_user_func_array('array_merge', $this->resultStatuses);
        return $statuses ? $statuses[0]->getCode() : null;
    }

    // -------------------------------------------------------------------------------

    /**
     * @param int[] $codes
     * @return string|null
     */
    public function findFirstErrorMessageAmongCodes(array $codes): ?string
    {
        return $this->findFirstMessageAmongCodes(ResultStatusConstants::TYPE_ERROR, $codes);
    }

    /**
     * @param int[] $codes
     * @return string|null
     */
    public function findFirstSuccessMessageAmongCodes(array $codes): ?string
    {
        return $this->findFirstMessageAmongCodes(ResultStatusConstants::TYPE_SUCCESS, $codes);
    }

    /**
     * @param int[] $codes
     * @return string|null
     */
    public function findFirstWarningMessageAmongCodes(array $codes): ?string
    {
        return $this->findFirstMessageAmongCodes(ResultStatusConstants::TYPE_WARNING, $codes);
    }

    /**
     * @param int[] $codes
     * @return string|null
     */
    public function findFirstInfoMessageAmongCodes(array $codes): ?string
    {
        return $this->findFirstMessageAmongCodes(ResultStatusConstants::TYPE_INFO, $codes);
    }

    /**
     * Get first message
     * @param int $type
     * @param int[] $searchCodes
     * @return string|null
     */
    protected function findFirstMessageAmongCodes(int $type, array $searchCodes): ?string
    {
        $resultStatuses = $this->getResultStatuses($type);
        foreach ($resultStatuses as $resultStatus) {
            if (in_array($resultStatus->getCode(), $searchCodes, true)) {
                return $resultStatus->getMessage();
            }
        }
        return null;
    }

    // -------------------------------------------------------------------------------

    /**
     * @param int[] $codes
     * @return array|ResultStatus[]
     */
    public function findErrorResultStatusesByCodes(array $codes): array
    {
        return $this->findResultStatusesByCodes(ResultStatusConstants::TYPE_ERROR, $codes);
    }

    /**
     * @param int[] $codes
     * @return array|ResultStatus[]
     */
    public function findSuccessResultStatusesByCodes(array $codes): array
    {
        return $this->findResultStatusesByCodes(ResultStatusConstants::TYPE_SUCCESS, $codes);
    }

    /**
     * @param int[] $codes
     * @return array|ResultStatus[]
     */
    public function findWarningResultStatusesByCodes(array $codes): array
    {
        return $this->findResultStatusesByCodes(ResultStatusConstants::TYPE_WARNING, $codes);
    }

    /**
     * @param int[] $codes
     * @return array|ResultStatus[]
     */
    public function findInfoResultStatusesByCodes(array $codes): array
    {
        return $this->findResultStatusesByCodes(ResultStatusConstants::TYPE_INFO, $codes);
    }

    /**
     * @param int $type
     * @param int[] $searchCodes
     * @return ResultStatus[]
     */
    protected function findResultStatusesByCodes(int $type, array $searchCodes): array
    {
        $codes = array_intersect($this->getResultStatusCodes($type), $searchCodes);
        $codes = array_unique($codes);
        $registeredCodes = $this->getResultStatusCodes($type);
        $resultStatusesByCodes = [];
        foreach ($codes as $code) {
            $resultStatusIndex = array_keys($registeredCodes, $code);
            $resultStatuses = $this->getResultStatuses($type);
            $resultStatus = array_intersect_key($resultStatuses, array_flip($resultStatusIndex));
            $resultStatusesByCodes[] = $resultStatus;
        }
        return array_merge([], ...$resultStatusesByCodes);
    }

    // -------------------------------------------------------------------------------

    public function lastAddedErrorStatus(): ?ResultStatus
    {
        return $this->findLastAddedStatusByType(ResultStatusConstants::TYPE_ERROR);
    }

    public function lastAddedSuccessStatus(): ?ResultStatus
    {
        return $this->findLastAddedStatusByType(ResultStatusConstants::TYPE_SUCCESS);
    }

    public function lastAddedWarningStatus(): ?ResultStatus
    {
        return $this->findLastAddedStatusByType(ResultStatusConstants::TYPE_WARNING);
    }

    public function lastAddedInfoStatus(): ?ResultStatus
    {
        return $this->findLastAddedStatusByType(ResultStatusConstants::TYPE_INFO);
    }

    protected function findLastAddedStatusByType(int $type): ?ResultStatus
    {
        return $this->resultStatuses[$type][count($this->resultStatuses[$type]) - 1] ?? null;
    }

    // -------------------------------------------------------------------------------

    public function lastAddedErrorMessage(): string
    {
        $resultStatus = $this->lastAddedErrorStatus();
        return $resultStatus ? $resultStatus->getMessage() : '';
    }

    public function lastAddedSuccessMessage(): string
    {
        $resultStatus = $this->lastAddedSuccessStatus();
        return $resultStatus ? $resultStatus->getMessage() : '';
    }

    public function lastAddedWarningMessage(): string
    {
        $resultStatus = $this->lastAddedWarningStatus();
        return $resultStatus ? $resultStatus->getMessage() : '';
    }

    public function lastAddedInfoMessage(): string
    {
        $resultStatus = $this->lastAddedInfoStatus();
        return $resultStatus ? $resultStatus->getMessage() : '';
    }

    // -------------------------------------------------------------------------------

    /**
     * Get message as concatenated string, add payload as suffix of every message
     * @param int $type
     * @param string|null $glue
     * @return string
     */
    public function getConcatenatedMessageSuffixedByPayload(int $type, string $glue = null): string
    {
        if ($glue === null) {
            $glue = $this->resultMessageGlue;
        }
        $resultStatuses = $this->getResultStatuses($type);
        $messages = array_map(
            static function (ResultStatus $resultStatus) {
                $message = $resultStatus->getMessage();
                $payload = $resultStatus->getPayload();
                if (!empty($payload)) {
                    $message .= composeSuffix($payload);
                }
                return $message;
            },
            $resultStatuses
        );
        $output = implode($glue, $messages);
        return $output;
    }
}
