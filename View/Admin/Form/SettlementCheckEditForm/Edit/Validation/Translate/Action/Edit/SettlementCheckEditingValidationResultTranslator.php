<?php
/**
 * SAM-9899: Implement Internationalization (translation) for all on all settlement check related pages: SAM-9899
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           11-28, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SettlementCheckEditForm\Edit\Validation\Translate\Action\Edit;

use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Action\Edit\Single\Validate\Result\SettlementCheckEditingValidationResult as Result;
use Sam\Translation\AdminTranslatorAwareTrait;
use SettlementCheck;

/**
 * Class SettlementCheckEditingValidationResultTranslator
 * @package Sam\Settlement\Check\Action\Edit
 */
class SettlementCheckEditingValidationResultTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    protected Result $validationResult;
    /** @var string[] */
    protected array $errorTranslations = [];
    protected string $language;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(Result $validationResult, string $language): static
    {
        $this->language = $language;
        $this->validationResult = $validationResult;
        $this->buildErrorTranslationCollection();
        return $this;
    }

    public function errorMessageForPayee(): string
    {
        $message = '';
        if ($this->validationResult->hasConcreteError(Result::ERR_PAYEE_REQUIRED)) {
            $message = $this->transError(Result::ERR_PAYEE_REQUIRED);
        } elseif ($this->validationResult->hasConcreteError(Result::ERR_PAYEE_MAX_LENGTH)) {
            $message = $this->transError(Result::ERR_PAYEE_MAX_LENGTH);
        }
        return trim($message);
    }

    public function errorMessageForAmount(): string
    {
        $message = '';
        if ($this->validationResult->hasConcreteError(Result::ERR_AMOUNT_REQUIRED)) {
            $message = $this->transError(Result::ERR_AMOUNT_REQUIRED);
        } elseif ($this->validationResult->hasConcreteError(Result::ERR_AMOUNT_MUST_BE_POSITIVE_DECIMAL)) {
            $message = $this->transError(Result::ERR_AMOUNT_MUST_BE_POSITIVE_DECIMAL);
        }
        return trim($message);
    }

    public function errorMessageForAmountSpelling(): string
    {
        $message = $this->transError(Result::ERR_AMOUNT_SPELLING_MAX_LENGTH);
        return trim($message);
    }

    public function errorMessageForCheckNo(): string
    {
        $message = $this->transError(Result::ERR_CHECK_NO);
        return trim($message);
    }

    public function errorMessageForMemo(): string
    {
        $message = $this->transError(Result::ERR_MEMO_MAX_LENGTH);
        return trim($message);
    }

    public function errorMessageForNote(): string
    {
        $message = $this->transError(Result::ERR_NOTE_MAX_LENGTH);
        return trim($message);
    }

    public function errorMessageForDatePostedOn(): string
    {
        $message = $this->transError(Result::ERR_POSTED_ON_DATE);
        return $message;
    }

    public function errorMessageForDateClearedOn(): string
    {
        $message = $this->transError(Result::ERR_CLEARED_ON_DATE);
        return $message;
    }

    protected function transError(int $code): string
    {
        if (!$this->hasErrorTranslation($code)) {
            return "Unable to find translation for error code: [{$code}]";
        }
        return $this->errorTranslations[$code];
    }

    protected function hasErrorTranslation(int $code): bool
    {
        return isset($this->errorTranslations[$code]);
    }

    protected function buildErrorTranslationCollection(): void
    {
        $tr = $this->getAdminTranslator();
        $domain = 'admin_settlement_check_editing_validator';
        $this->errorTranslations = [
            Result::ERR_CHECK_NO => $tr->trans('error.check_no', [], $domain, $this->language),
            Result::ERR_PAYEE_REQUIRED => $tr->trans('error.payee_required', [], $domain, $this->language),
            Result::ERR_PAYEE_MAX_LENGTH => $tr->trans('error.payee_max_length', ['length' => SettlementCheck::PAYEE_MAX_LENGTH], $domain, $this->language),
            Result::ERR_AMOUNT_REQUIRED => $tr->trans('error.amount_required', [], $domain, $this->language),
            Result::ERR_AMOUNT_MUST_BE_POSITIVE_DECIMAL => $tr->trans('error.amount_must_be_positive_decimal', [], $domain, $this->language),
            Result::ERR_AMOUNT_SPELLING_MAX_LENGTH => $tr->trans('error.amount_spelling_max_length', ['length' => SettlementCheck::AMOUNT_SPELLING_MAX_LENGTH], $domain, $this->language),
            Result::ERR_MEMO_MAX_LENGTH => $tr->trans('error.memo_max_length', ['length' => SettlementCheck::MEMO_MAX_LENGTH], $domain, $this->language),
            Result::ERR_NOTE_MAX_LENGTH => $tr->trans('error.note_max_length', ['length' => SettlementCheck::NOTE_MAX_LENGTH], $domain, $this->language),
            Result::ERR_POSTED_ON_DATE => $tr->trans('error.posted_on_date', [], $domain, $this->language),
            Result::ERR_CLEARED_ON_DATE => $tr->trans('error.cleared_on_date', [], $domain, $this->language),
        ];
    }
}
