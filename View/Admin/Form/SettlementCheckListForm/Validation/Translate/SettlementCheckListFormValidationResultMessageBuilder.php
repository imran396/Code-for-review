<?php
/**
 * SAM-9899: Implement Internationalization (translation) for all on all settlement check related pages
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           12-11, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SettlementCheckListForm\Validation\Translate;

use Sam\Application\Url\BackPage\BackUrlParserAwareTrait;
use Sam\Application\Url\Build\Config\Settlement\SettlementCheckEditUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Action\ApplyPayment\Multiple\Validate\MultipleSettlementCheckPaymentApplyingValidationResult;
use Sam\Settlement\Check\Action\MarkCleared\Multiple\Validate\MultipleSettlementCheckClearedMarkingValidationResult;
use Sam\Settlement\Check\Action\MarkPosted\Multiple\Validate\MultipleSettlementCheckPostedMarkingValidationResult;
use Sam\Settlement\Check\Action\MarkVoided\Multiple\Validate\MultipleSettlementCheckVoidingValidationResult;
use Sam\Settlement\Check\Action\Printing\Multiple\Validate\Result\MultipleSettlementCheckPrintingValidationResult;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\View\Admin\Form\SettlementCheckListForm\Validation\Translate\Action\Printing\MultipleSettlementCheckPrintingValidationResultTranslator;

/**
 * Class SettlementCheckListFormValidationResultMessageBuilder
 * @package Sam\View\Admin\Form\SettlementCheckListForm\Validation\Translate
 */
class SettlementCheckListFormValidationResultMessageBuilder extends CustomizableClass
{
    use AdminTranslatorAwareTrait;
    use BackUrlParserAwareTrait;
    use UrlBuilderAwareTrait;

    protected const SETTLEMENT_CHECK_EDIT_LINK_TPL = <<<HTML
<a href="%url%" target="_blank">%settlementCheckId%</a>
HTML;

    protected const TRANSLATION_DOMAIN = 'admin_settlement_check_list';

    protected string $language;
    protected string $backLink;
    protected string $glue = '<br />';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        string $language,
        string $backLink,
        string $glue = ''
    ): static {
        $this->language = $language;
        $this->backLink = $backLink;
        if ($glue) {
            $this->glue = $glue;
        }
        return $this;
    }

    public function buildErrorMessageOnPrintCheckAction(MultipleSettlementCheckPrintingValidationResult $validationResult): string
    {
        $errorMessages = [];
        if ($validationResult->hasInvalidStartingCheckNoError()) {
            $validationResultTranslator = MultipleSettlementCheckPrintingValidationResultTranslator::new()
                ->construct($validationResult, $this->language);
            $errorMessages[] = $validationResultTranslator->errorMessage('; ');
        } else {
            $stlmCheckIdsOnUnavailableError = $validationResult->collectCheckIdsOnUnAvailableError();
            if ($stlmCheckIdsOnUnavailableError) {
                $unavailableLinkList = $this->renderSettlementCheckEditLinkList($stlmCheckIdsOnUnavailableError, true);
                $errorMessages[] = $this->trans('form.action.print_selected.message.fail.unavailable', ['linksList' => $unavailableLinkList]);
            }

            $stlmCheckIdsOnCheckNumExistsError = $validationResult->collectCheckIdsOnCheckNumExistsError();
            if ($stlmCheckIdsOnCheckNumExistsError) {
                $checkNumExistsLinkList = $this->renderSettlementCheckEditLinkList($stlmCheckIdsOnCheckNumExistsError);
                $errorMessages[] = $this->trans('form.action.print_selected.message.fail.have_check_number', ['linksList' => $checkNumExistsLinkList]);
            }

            $stlmCheckIdsOnAlreadyPrintedError = $validationResult->collectCheckIdsOnAlreadyPrintedError();
            if ($stlmCheckIdsOnAlreadyPrintedError) {
                $alreadyPrintedLinkList = $this->renderSettlementCheckEditLinkList($stlmCheckIdsOnAlreadyPrintedError);
                $errorMessages[] = $this->trans('form.action.print_selected.message.fail.already_printed', ['linksList' => $alreadyPrintedLinkList]);
            }

            $stlmCheckIdsOnAlreadyVoidedError = $validationResult->collectCheckIdsOnAlreadyVoidedError();
            if ($stlmCheckIdsOnAlreadyVoidedError) {
                $alreadyVoidedLinkList = $this->renderSettlementCheckEditLinkList($stlmCheckIdsOnAlreadyVoidedError);
                $errorMessages[] = $this->trans('form.action.print_selected.message.fail.already_voided', ['linksList' => $alreadyVoidedLinkList]);
            }
        }

        $errorMessage = implode($this->glue, $errorMessages);
        return $errorMessage;
    }

    public function buildErrorMessageOnMarkPostedAction(MultipleSettlementCheckPostedMarkingValidationResult $validationResult): string
    {
        $errorMessages = [];
        $alreadyPostedCheckIds = $validationResult->collectedAlreadyPostedSettlementCheckIds();
        if ($alreadyPostedCheckIds) {
            $alreadyPostedLinkList = $this->renderSettlementCheckEditLinkList($alreadyPostedCheckIds);
            $errorMessages[] = $this->trans('form.action.mark_posted.message.fail.already_posted', ['linksList' => $alreadyPostedLinkList]);
        }

        $emptyCheckNoCheckIds = $validationResult->collectedEmptyCheckNoSettlementCheckIds();
        $notPrintedCheckIds = $validationResult->collectedNotPrintedSettlementCheckIds();
        if (
            $emptyCheckNoCheckIds
            || $notPrintedCheckIds
        ) {
            $emptyCheckNoLinkList = $this->renderSettlementCheckEditLinkList($emptyCheckNoCheckIds);
            $notPrintedLinkList = $this->renderSettlementCheckEditLinkList($notPrintedCheckIds);
            $errorMessages[] = $this->trans(
                'form.action.mark_posted.message.fail.empty_check_no_or_not_printed',
                ['npLinksList' => $notPrintedLinkList, 'ncLinksList' => $emptyCheckNoLinkList]
            );
        }
        $errorMessages[] = $this->trans('form.action.mark_posted.message.fail.please_deselect');

        $errorMessage = implode($this->glue, $errorMessages);
        return $errorMessage;
    }

    public function buildErrorMessageOnMarkClearedAction(MultipleSettlementCheckClearedMarkingValidationResult $validationResult): string
    {
        $errorMessages = [];
        $alreadyClearedCheckIds = $validationResult->collectedAlreadyClearedSettlementCheckIds();
        if ($alreadyClearedCheckIds) {
            $alreadyClearedLinkList = $this->renderSettlementCheckEditLinkList($alreadyClearedCheckIds);
            $errorMessages[] = $this->trans('form.action.mark_cleared.message.fail.already_cleared', ['%$alreadyClearedLinkList%' => $alreadyClearedLinkList]);
        }

        $notPostedCheckIds = $validationResult->collectedNotPostedSettlementCheckIds();
        $emptyCheckNoCheckIds = $validationResult->collectedEmptyCheckNoSettlementCheckIds();
        $notPrintedCheckIds = $validationResult->collectedNotPrintedSettlementCheckIds();
        if (
            $notPostedCheckIds
            || $emptyCheckNoCheckIds
            || $notPrintedCheckIds
        ) {
            $notPostedLinkList = $this->renderSettlementCheckEditLinkList($notPostedCheckIds);
            $emptyCheckNoLinkList = $this->renderSettlementCheckEditLinkList($emptyCheckNoCheckIds);
            $notPrintedLinkList = $this->renderSettlementCheckEditLinkList($notPrintedCheckIds);
            $errorMessages[] = $this->trans(
                'form.action.mark_cleared.message.fail.no_printed_date_or_no_check_no_or_no_posted_date',
                ['nprdLinksList' => $notPrintedLinkList, 'ncLinksList' => $emptyCheckNoLinkList, 'npsdLinksList' => $notPostedLinkList]
            );
        }
        $errorMessages[] = $this->trans('form.action.mark_cleared.message.fail.please_deselect');

        $errorMessage = implode($this->glue, $errorMessages);
        return $errorMessage;
    }

    public function buildErrorMessageOnApplyAsPaymentAction(MultipleSettlementCheckPaymentApplyingValidationResult $validationResult): string
    {
        $errorMessages = [];
        $alreadyAppliedPaymentCheckIds = $validationResult->collectedAlreadyAppliedPaymentSettlementCheckIds();
        if ($alreadyAppliedPaymentCheckIds) {
            $alreadyAppliedPaymentLinkList = $this->renderSettlementCheckEditLinkList($alreadyAppliedPaymentCheckIds);
            $errorMessages[] = $this->trans('form.action.apply_as_payment.message.fail.already_has_payment_record', ['linksList' => $alreadyAppliedPaymentLinkList]);
        }

        $alreadyVoidedCheckIds = $validationResult->collectedAlreadyVoidedSettlementCheckIds();
        if ($alreadyVoidedCheckIds) {
            $alreadyVoidedLinkList = $this->renderSettlementCheckEditLinkList($alreadyVoidedCheckIds);
            $errorMessages[] = $this->trans('form.action.apply_as_payment.message.fail.already_voided', ['linksList' => $alreadyVoidedLinkList]);
        }
        $errorMessages[] = $this->trans('form.action.apply_as_payment.message.fail.please_deselect');

        $errorMessage = implode($this->glue, $errorMessages);
        return $errorMessage;
    }

    public function buildErrorMessageOnMarkPostedAndApplyAsPaymentAction(
        MultipleSettlementCheckPostedMarkingValidationResult $markPostedValidationResult,
        MultipleSettlementCheckPaymentApplyingValidationResult $applyPaymentValidationResult
    ): string {
        $errorMessages = [];

        /**
         * Process errors for "Mark Posted" sub-action.
         */
        $alreadyPostedCheckIds = $markPostedValidationResult->collectedAlreadyPostedSettlementCheckIds();
        if ($alreadyPostedCheckIds) {
            $alreadyPostedLinkList = $this->renderSettlementCheckEditLinkList($alreadyPostedCheckIds);
            $errorMessages[] = $this->trans('form.action.mark_posted.message.fail.already_posted', ['linksList' => $alreadyPostedLinkList]);
        }

        $emptyCheckNoCheckIds = $markPostedValidationResult->collectedEmptyCheckNoSettlementCheckIds();
        $notPrintedCheckIds = $markPostedValidationResult->collectedNotPrintedSettlementCheckIds();
        if (
            $emptyCheckNoCheckIds
            || $notPrintedCheckIds
        ) {
            $emptyCheckNoLinkList = $this->renderSettlementCheckEditLinkList($emptyCheckNoCheckIds);
            $notPrintedLinkList = $this->renderSettlementCheckEditLinkList($notPrintedCheckIds);
            $errorMessages[] = $this->trans(
                'form.action.mark_posted_and_apply_as_payment.message.fail.empty_check_no_or_not_printed',
                ['npLinksList' => $notPrintedLinkList, 'ncLinksList' => $emptyCheckNoLinkList]
            );
        }

        /**
         * Process errors for "Apply as Payment" sub-action.
         */
        $alreadyAppliedPaymentCheckIds = $applyPaymentValidationResult->collectedAlreadyAppliedPaymentSettlementCheckIds();
        if ($alreadyAppliedPaymentCheckIds) {
            $alreadyAppliedPaymentLinkList = $this->renderSettlementCheckEditLinkList($alreadyAppliedPaymentCheckIds);
            $errorMessages[] = $this->trans('form.action.mark_posted_and_apply_as_payment.message.fail.already_has_payment_record', ['linksList' => $alreadyAppliedPaymentLinkList]);
        }

        $alreadyVoidedCheckIds = $applyPaymentValidationResult->collectedAlreadyVoidedSettlementCheckIds();
        if ($alreadyVoidedCheckIds) {
            $alreadyVoidedLinkList = $this->renderSettlementCheckEditLinkList($alreadyVoidedCheckIds);
            $errorMessages[] = $this->trans('form.action.mark_posted_apply_as_payment.message.fail.already_voided', ['linksList' => $alreadyVoidedLinkList]);
        }

        $errorMessages[] = $this->trans('form.action.mark_posted_and_apply_as_payment.message.fail.please_deselect');

        $errorMessage = implode($this->glue, $errorMessages);
        return $errorMessage;
    }

    public function buildErrorMessageOnMarkVoidedAction(MultipleSettlementCheckVoidingValidationResult $validationResult): string
    {
        $errorMessages = [];
        $alreadyVoidedCheckIds = $validationResult->collectedAlreadyVoidedSettlementCheckIds();
        if ($alreadyVoidedCheckIds) {
            $alreadyVoidedLinkList = $this->renderSettlementCheckEditLinkList($alreadyVoidedCheckIds);
            $errorMessages[] = $this->trans('form.action.mark_voided.message.fail.already_voided', ['linksList' => $alreadyVoidedLinkList]);
        }
        $errorMessages[] = $this->trans('form.action.mark_voided.message.fail.please_deselect');

        $errorMessage = implode($this->glue, $errorMessages);
        return $errorMessage;
    }

    public function buildWarningMessageOnMarkVoidedAction(MultipleSettlementCheckVoidingValidationResult $validationResult): string
    {
        $warningMessages = [];
        $alreadyAppliedPaymentCheckIds = $validationResult->collectedAlreadyAppliedPaymentSettlementCheckIds();
        if ($alreadyAppliedPaymentCheckIds) {
            $alreadyAppliedPaymentLinkList = $this->renderSettlementCheckEditLinkList($alreadyAppliedPaymentCheckIds);
            $warningMessages[] = $this->trans('form.action.mark_voided.message.warning.already_applied_payment', ['linksList' => $alreadyAppliedPaymentLinkList]);
        }

        $warningMessage = implode($this->glue, $warningMessages);
        return $warningMessage;
    }

    protected function renderSettlementCheckEditLinkList(array $settlementCheckIds, bool $forUnavailableEntity = false): string
    {
        $links = $this->renderSettlementCheckEditLinks($settlementCheckIds, $forUnavailableEntity);
        if (!$links) {
            return '';
        }
        return ': ' . implode(', ', $links);
    }

    protected function renderSettlementCheckEditLinks(array $settlementCheckIds, bool $forUnavailableEntity = false): array
    {
        $links = [];
        foreach ($settlementCheckIds as $settlementCheckId) {
            if ($forUnavailableEntity) {
                $links[] = strtr(
                    self::SETTLEMENT_CHECK_EDIT_LINK_TPL,
                    [
                        '%url%' => '#',
                        '%settlementCheckId%' => "<b><i>{$settlementCheckId}</i></b>"
                    ]
                );
            } else {
                $url = $this->buildSettlementCheckEditUrl($settlementCheckId);
                $links[] = strtr(
                    self::SETTLEMENT_CHECK_EDIT_LINK_TPL,
                    [
                        '%url%' => $url,
                        '%settlementCheckId%' => $settlementCheckId
                    ]
                );
            }
        }
        return $links;
    }

    protected function buildSettlementCheckEditUrl(int $settlementCheckId): string
    {
        $editUrl = $this->getUrlBuilder()->build(
            SettlementCheckEditUrlConfig::new()->forWeb($settlementCheckId)
        );
        $editUrl = $this->getBackUrlParser()->replace($editUrl, $this->backLink);

        return $editUrl;
    }

    protected function trans(string $id, $parameters = []): string
    {
        return $this->getAdminTranslator()->trans($id, $parameters, self::TRANSLATION_DOMAIN, $this->language);
    }
}
