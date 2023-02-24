<?php
/**
 * SAM-4465 : Refactor reminder classes
 * https://bidpath.atlassian.net/browse/SAM-4465
 *
 * @author        Imran Rahman
 * @version       SVN: $Id: $
 * @since         Sept 30, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 *
 */

namespace Sam\Reminder\Concrete\Payment\Internal\Process;

use Email_Template;
use Exception;
use Invoice;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Reminder\Common\Expiry\Validate\ExpirationDateChecker;
use Sam\Reminder\Common\Dto\ProcessingInput;
use Sam\Reminder\Common\Render\Renderer;
use Sam\Reminder\Concrete\Payment\Internal\Load\DataProviderCreateTrait;
use Sam\Reminder\Common\Dto\ProcessingResult;

/**
 * Class Processor
 * @package Sam\Reminder\Concrete\Payment
 */
class Processor extends CustomizableClass
{
    use DataProviderCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param ProcessingInput $input
     * @return ProcessingResult
     */
    public function process(ProcessingInput $input): ProcessingResult
    {
        $result = ProcessingResult::new();
        $dataProvider = $this->createDataProvider();
        $templateName = Constants\EmailKey::PAYMENT_REMINDER;
        $name = Renderer::new()->makeNameFromEmailKey($templateName);

        $hasLastRun = ExpirationDateChecker::new()->checkLastRun(
            $input->currentDateUtc,
            $input->lastRunDateUtc,
            $input->scriptInterval,
            $name
        );
        if (!$hasLastRun) {
            return $result->enableProcessed(false);
        }

        $isExpired = ExpirationDateChecker::new()->isExpiredTimeoutAndFrequencySet(
            $input->currentDateUtc,
            $input->lastRunDateUtc,
            $input->emailFrequency,
            $name
        );
        if (!$isExpired) {
            return $result->enableProcessed(false);
        }

        $result->lastRunUtc = $input->currentDateUtc;
        $editorUserId = $dataProvider->loadEditorUserId();
        $data = $dataProvider->loadReminderData();
        try {
            foreach ($data as $invoiceId) {
                $invoice = $dataProvider->loadInvoice((int)$invoiceId);
                $this->createReminder($invoice, $editorUserId, $templateName, $result);
            }
        } catch (Exception $e) {
            log_error("Error while processing payment reminders: " . $e->getMessage());
        }
        return $result->enableProcessed(true);
    }

    /**
     * Determine who needs to be reminded, create emails and drop in action queue
     * @param Invoice|null $invoice
     * @param int $editorUserId
     * @param string $templateName
     * @param ProcessingResult $result
     */
    protected function createReminder(
        ?Invoice $invoice,
        int $editorUserId,
        string $templateName,
        ProcessingResult $result
    ): void {
        if (!$invoice) {
            log_errorBackTrace('Cannot create reminder for absent invoice');
            return;
        }
        $invoiceBidderUser = $this->createDataProvider()->loadUser($invoice->BidderId, true);
        if (!$invoiceBidderUser) {
            log_error(
                "Available invoice winning user not found, when creating payment reminder"
                . composeSuffix(['u' => $invoice->BidderId, 'i' => $invoice->Id])
            );
            return;
        }
        if (!$invoiceBidderUser->Email) {
            $logData = [
                'username' => $invoiceBidderUser->Username,
                'u' => $invoiceBidderUser->Id,
                'invoice#' => $invoice->InvoiceNo,
                'i' => $invoice->Id,
                'acc' => $invoice->AccountId,
            ];
            log_info('Ignoring user because of missing email' . composeSuffix($logData));
            return;
        }
        $emailManager = Email_Template::new()->construct(
            $invoice->AccountId,
            $templateName,
            $editorUserId,
            [$invoiceBidderUser, $invoice]
        );
        if ($emailManager->EmailTpl->Disabled) {
            log_info(
                "Invoice " . Renderer::new()->makeNameFromEmailKey($templateName) . " reminder email is disabled"
                . composeSuffix(['i' => $invoice->Id, 'acc' => $invoice->AccountId])
            );
            return;
        }
        $emailManager->addToActionQueue(Constants\ActionQueue::LOW);
        /**
         * Update process stats
         */
        $result->countRemindedUsers++;
        $logData = [
            'username' => $invoiceBidderUser->Username,
            'u' => $invoiceBidderUser->Id,
            'email' => $invoiceBidderUser->Email,
            'invoice#' => $invoice->InvoiceNo,
            'i' => $invoice->Id,
            'acc' => $invoice->AccountId,
        ];
        log_info(
            "Created " . Renderer::new()->makeNameFromEmailKey($templateName) . " reminder for user"
            . composeSuffix($logData)
        );
    }
}
