<?php
/**
 * SAM-9899: Implement Internationalization (translation) for all on all settlement check related pages
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           11-29, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SettlementCheckEditForm\Edit\Validation\Translate\Action\HardDelete;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Action\HardDelete\Single\Validate\Result\SingleSettlementCheckDeleteValidationResult as Result;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class SingleSettlementCheckDeleteValidationResultTranslator
 * @package Sam\Settlement\Check\Action\HardDelete
 */
class SingleSettlementCheckDeleteValidationResultTranslator extends CustomizableClass
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
        $domain = 'admin_single_settlement_check_delete_validator';
        $this->errorTranslations = [
            Result::ERR_UNKNOWN_ID => $tr->trans('error.unknown_id', [], $domain, $this->language),
            Result::ERR_CHECK_NOT_FOUND => $tr->trans('error.check_not_found', [], $domain, $this->language),
            Result::ERR_CHECK_NO_ASSIGNED => $tr->trans('error.check_no_assigned', [], $domain, $this->language),
            Result::ERR_PRINTED_ON_ASSIGNED => $tr->trans('error.printed_on_assigned', [], $domain, $this->language),
            Result::ERR_PAYMENT_APPLIED => $tr->trans('error.payment_applied', [], $domain, $this->language),
        ];
    }
}
