<?php
/**
 * SAM-9899: Implement Internationalization (translation) for all on all settlement check related pages
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

namespace Sam\View\Admin\Form\SettlementCheckEditForm\Edit\Validation\Translate\Action\Printing;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\Settlement\Check\Action\Printing\Single\Validate\Result\SingleSettlementCheckPrintingValidationResult as Result;

/**
 * Class SingleSettlementCheckPrintingValidationResultTranslator
 * @package Sam\Settlement\Check\Action\Printing
 */
class SingleSettlementCheckPrintingValidationResultTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    protected Result $validationResult;
    /** @var string [] */
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

    public function errorMessage(?string $glue = null): string
    {
        $messages = array_map(
            function (ResultStatus $resultStatus) {
                return $this->transError($resultStatus->getCode());
            },
            $this->validationResult->getErrorStatuses()
        );
        $output = implode($glue, $messages);
        return $output;
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
        $domain = 'admin_settlement_check_printing_validator';
        $this->errorTranslations = [
            Result::ERR_UNKNOWN_ID => $tr->trans('error.unknown_id', [], $domain, $this->language),
            Result::ERR_CHECK_NOT_FOUND => $tr->trans('error.check_not_found', [], $domain, $this->language),
            Result::ERR_EMPTY_CHECK_NO => $tr->trans('error.empty_check_no', [], $domain, $this->language),
            Result::ERR_HAS_CHECK_NO_ON_MULTI_PRINT => $tr->trans('error.not_empty_check_no', [], $domain, $this->language),
            Result::ERR_ALREADY_PRINTED_ON => $tr->trans('error.already_printed_on', [], $domain, $this->language),
            Result::ERR_ALREADY_VOIDED_ON => $tr->trans('error.already_voided_on', [], $domain, $this->language),
        ];
    }
}
