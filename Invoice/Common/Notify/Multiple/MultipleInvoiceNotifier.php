<?php
/**
 * SAM-11778: Refactor Invoice Notifier for v4.0
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 24, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Notify\Multiple;

use Sam\Application\Url\Build\Config\Invoice\AnySingleInvoiceUrlConfig;
use Sam\Application\Url\Build\Config\User\AnySingleUserUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Invoice\Common\Notify\Multiple\Internal\Load\DataProviderCreateTrait;
use Sam\Invoice\Common\Notify\Multiple\Translate\MultipleInvoiceNotificationTranslatorCreateTrait;
use Sam\Invoice\Common\Notify\Multiple\MultipleInvoiceNotificationResult as Result;
use Sam\Invoice\Common\Notify\Single\SingleInvoiceNotifierCreateTrait;
use Sam\Invoice\Common\Validate\InvoiceRelatedEntityValidatorAwareTrait;
use Sam\Translation\TranslationLanguageProviderCreateTrait;

/**
 * Class MultipleInvoiceNotifier
 * @package Sam\Invoice\Common\Notify
 */
class MultipleInvoiceNotifier extends CustomizableClass
{
    use DataProviderCreateTrait;
    use InvoiceRelatedEntityValidatorAwareTrait;
    use MultipleInvoiceNotificationTranslatorCreateTrait;
    use SingleInvoiceNotifierCreateTrait;
    use TranslationLanguageProviderCreateTrait;
    use UrlBuilderAwareTrait;

    /**
     * @var string
     */
    public string $language = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $invoiceIds
     * @param int $editorUserId
     * @return MultipleInvoiceNotificationResult
     */
    public function notify(
        array $invoiceIds,
        int $editorUserId,
        string $language,
        bool $isReadOnlyDb = false
    ): Result {
        $translator = $this->createMultipleInvoiceNotificationTranslator();
        $result = Result::new()->construct();
        $lockedInvoiceNos = [];
        $invoiceNoWithUsernameForAbsentEmails = [];
        $invoiceNoWithBidderIdForNotFoundBidders = [];
        $invoiceNoForInactiveUsers = [];
        $sentCount = 0;
        $totalInvoice = count($invoiceIds);
        if ($totalInvoice) {
            foreach ($invoiceIds as $invoiceId) {
                $invoiceRow = $this->createDataProvider()->loadInvoiceRow($invoiceId, $isReadOnlyDb);
                if (!$invoiceRow) {
                    log_error(
                        "Available invoice not found for email notification" . composeSuffix(['i' => $invoiceId])
                    );
                    continue;
                }
                $singleNotifier = $this->createSingleInvoiceNotifier();
                $singleNotifierResult = $singleNotifier->notify(
                    $invoiceId,
                    Constants\ActionQueue::LOW,
                    $editorUserId,
                    $language
                );
                if ($singleNotifierResult->hasSuccess()) {
                    $sentCount++;
                } elseif ($singleNotifierResult->hasInvoiceLockedError()) {
                    $lockedInvoiceNos[] = $invoiceRow['invoice_no'];
                } elseif ($singleNotifierResult->hasBidderAbsentEmailError()) {
                    $invoiceNoLink = $this->buildInvoiceLink(
                        $invoiceId,
                        $invoiceRow['invoice_no'],
                        $invoiceRow['tax_designation']
                    );
                    $usernameLink = $this->buildUserLink($invoiceRow['bidder_id'], $invoiceRow['username']);
                    $invoiceNoWithUsernameForAbsentEmails[] = composeSuffix(
                        ['i' => $invoiceNoLink, 'u' => $usernameLink]
                    );
                } elseif ($singleNotifierResult->hasBidderUserNotFoundError()) {
                    $invoiceNoLink = $this->buildInvoiceLink(
                        $invoiceId,
                        $invoiceRow['invoice_no'],
                        $invoiceRow['tax_designation']
                    );
                    $usernameLink = $this->buildUserLink($invoiceRow['bidder_id'], $invoiceRow['bidder_id']);
                    $invoiceNoWithBidderIdForNotFoundBidders[] = composeSuffix(
                        ['i' => $invoiceNoLink, 'b' => $usernameLink]
                    );
                } elseif ($singleNotifierResult->hasInactiveUserError()) {
                    $invoiceNoForInactiveUsers [] = $invoiceRow['invoice_no'];
                }
            }
        } else {
            $noInvoiceSpecifiedMessage = $translator->trans(
                MultipleInvoiceNotificationResult::ERR_NO_INVOICE_SPECIFIED,
                [],
                $language
            );
            $result->addError(MultipleInvoiceNotificationResult::ERR_NO_INVOICE_SPECIFIED, $noInvoiceSpecifiedMessage);
        }

        if (count($lockedInvoiceNos)) {
            $lockedInvoiceNoList = implode(', ', $lockedInvoiceNos);
            $lockedInvoiceMessage = $translator->trans(
                MultipleInvoiceNotificationResult::ERR_WITH_LOCKED_INVOICES,
                ['lockedInvoices' => $lockedInvoiceNoList],
                $language
            );
            $result->addError(MultipleInvoiceNotificationResult::ERR_WITH_LOCKED_INVOICES, $lockedInvoiceMessage);
        }

        if (count($invoiceNoWithUsernameForAbsentEmails)) {
            $unsentCount = $totalInvoice - $sentCount;
            $invoiceNoWithUsernamePairsForAbsentEmail = implode(', ', $invoiceNoWithUsernameForAbsentEmails);
            $bidderEmailAbsentMessage = $translator->trans(
                MultipleInvoiceNotificationResult::ERR_BIDDER_EMAIL_ABSENT,
                [
                    'unsentCount' => $unsentCount,
                    'invoiceNoUsernamePairs' => $invoiceNoWithUsernamePairsForAbsentEmail,
                ],
                $language
            );
            $result->addError(MultipleInvoiceNotificationResult::ERR_BIDDER_EMAIL_ABSENT, $bidderEmailAbsentMessage);
        }

        if (count($invoiceNoWithBidderIdForNotFoundBidders)) {
            $unsentCount = $totalInvoice - $sentCount;
            $invoiceNoWithBidderIdPairsForNotFoundBidder = implode(', ', $invoiceNoWithBidderIdForNotFoundBidders);
            $bidderUserNotFoundMessage = $translator->trans(
                MultipleInvoiceNotificationResult::ERR_BIDDER_USER_NOT_FOUND,
                [
                    'unsentCount' => $unsentCount,
                    'invoiceNoBidderPairs' => $invoiceNoWithBidderIdPairsForNotFoundBidder
                ],
                $language
            );
            $result->addError(MultipleInvoiceNotificationResult::ERR_BIDDER_USER_NOT_FOUND, $bidderUserNotFoundMessage);
        }

        if (count($invoiceNoForInactiveUsers)) {
            $unsentCount = $totalInvoice - $sentCount;
            $noActivateUserInvoiceList = implode(', ', $invoiceNoForInactiveUsers);
            $inactiveUserMessage = $translator->trans(
                MultipleInvoiceNotificationResult::ERR_NO_ACTIVATE_USER,
                [
                    'unsentCount' => $unsentCount,
                    'invoiceNos' => $noActivateUserInvoiceList
                ],
                $language
            );
            $result->addError(MultipleInvoiceNotificationResult::ERR_NO_ACTIVATE_USER, $inactiveUserMessage);
        }

        if ($sentCount > 0) {
            $successMessage = $translator->trans(
                MultipleInvoiceNotificationResult::OK_NOTIFIED,
                [
                    'sentCount' => $sentCount
                ],
                $language
            );
            $result->addSuccess(MultipleInvoiceNotificationResult::OK_NOTIFIED, $successMessage);
        }
        return $result;
    }

    public function buildInvoiceLink(int $invoiceId, string $invoiceLinkText, ?int $taxDesignation): string
    {
        if ($taxDesignation === Constants\Invoice::TDS_STACKED_TAX) {
            $url = $this->getUrlBuilder()->build(
                AnySingleInvoiceUrlConfig::new()->forWeb(Constants\Url::A_STACKED_TAX_INVOICE_EDIT, $invoiceId)
            );
        } else {
            $url = $this->getUrlBuilder()->build(
                AnySingleInvoiceUrlConfig::new()->forWeb(Constants\Url::A_INVOICES_EDIT, $invoiceId)
            );
        }
        return $this->makeLink($url, $invoiceLinkText);
    }

    public function buildUserLink(int $userId, string $userLinkText): string
    {
        $url = $this->getUrlBuilder()->build(
            AnySingleUserUrlConfig::new()->forWeb(Constants\Url::A_USERS_EDIT, $userId)
        );
        return $this->makeLink($url, $userLinkText);
    }

    protected function makeLink(string $url, string $text, ?string $target = null): string
    {
        $targetAttr = $target ? "target=\"{$target}\"" : '';
        return sprintf('<a href="%s" %s>%s</a>', $url, $targetAttr, $text);
    }
}
