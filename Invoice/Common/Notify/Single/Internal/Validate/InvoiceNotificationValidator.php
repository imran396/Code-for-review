<?php
/**
 * SAM-11778: Refactor Invoice Notifier for v4.0
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 21, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Notify\Single\Internal\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Invoice\Common\Notify\Single\Internal\Validate\Internal\Load\DataProviderCreateTrait;
use Sam\Invoice\Common\Notify\Single\SingleInvoiceNotificationResult as Result;
use Sam\Invoice\Common\Notify\Single\Translate\SingleInvoiceNotificationTranslatorCreateTrait;

/**
 * Class InvoiceNotificationValidator
 * @package Sam\Invoice\Common\Notify\Single\Internal\Validate
 */
class InvoiceNotificationValidator extends CustomizableClass
{
    use DataProviderCreateTrait;
    use SingleInvoiceNotificationTranslatorCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(int $invoiceId, string $language, bool $isReadOnlyDb): Result
    {
        $invoiceUser = null;
        $result = Result::new()->construct();
        $invoice = $this->createDataProvider()->loadInvoice($invoiceId, $isReadOnlyDb);
        $translator = $this->createSingleInvoiceNotificationTranslator();
        if ($invoice) {
            $invoiceUser = $this->createDataProvider()->loadUser($invoice->BidderId, $isReadOnlyDb);
            if (!$invoiceUser) {
                $bidderUserNotFoundMessage = $translator->trans(
                    Result::ERR_BIDDER_USER_NOT_FOUND,
                    [
                        'bidderId' => $invoice->BidderId,
                        'invoiceNo' => $invoice->InvoiceNo
                    ],
                    $language
                );
                $result->addError(Result::ERR_BIDDER_USER_NOT_FOUND, $bidderUserNotFoundMessage);
            } elseif ($invoiceUser->Email === '') {
                $bidderEmailEmptyMessage = $translator->trans(
                    Result::ERR_BIDDER_EMAIL_ABSENT,
                    [
                        'username' => $invoiceUser->Username,
                        'invoiceNo' => $invoice->InvoiceNo
                    ],
                    $language
                );
                $result->addError(Result::ERR_BIDDER_EMAIL_ABSENT, $bidderEmailEmptyMessage);
            } elseif ($invoiceUser->UserStatusId !== Constants\User::US_ACTIVE) {
                $inactiveUserMessage = $translator->trans(
                    Result::ERR_NOT_ACTIVE_USER,
                    [],
                    $language
                );
                $result->addError(Result::ERR_NOT_ACTIVE_USER, $inactiveUserMessage);
            } else {
                $successMessage = $translator->trans(
                    Result::OK_NOTIFIED,
                    [],
                    $language
                );
                $result->addSuccess(Result::OK_NOTIFIED, $successMessage);
            }
        } else {
            $lockedMessage = $translator->trans(
                Result::ERR_LOCKED,
                [],
                $language
            );
            $result->addError(Result::ERR_LOCKED, $lockedMessage);
        }
        $result->setInvoice($invoice);
        $result->setInvoiceUser($invoiceUser);
        return $result;
    }
}
