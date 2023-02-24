<?php
/**
 *
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 01, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceEditForm\NonOperable\Render;

use InvoiceItem;
use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Validate\InvoiceRelatedEntityValidator;
use Sam\Translation\AdminTranslatorAwareTrait;

class InvoiceEditFormNonOperableInfoRenderer extends CustomizableClass
{
    use AccountLoaderAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuctionRendererAwareTrait;
    use AdminTranslatorAwareTrait;

    protected const TRANSLATION_KEYS = [
        InvoiceRelatedEntityValidator::ERR_INVOICE_DELETED => 'non_operable.err_invoice_deleted',
        InvoiceRelatedEntityValidator::ERR_BIDDER_USER_DELETED => 'non_operable.err_bidder_user_deleted',
        InvoiceRelatedEntityValidator::ERR_BIDDER_USER_ACCOUNT_DELETED => 'non_operable.err_bidder_user_account_deleted',
        InvoiceRelatedEntityValidator::ERR_LOT_ITEM_DELETED => 'non_operable.err_lot_item_deleted',
        InvoiceRelatedEntityValidator::ERR_AUCTION_LOT_DELETED => 'non_operable.err_auction_lot_deleted',
        InvoiceRelatedEntityValidator::ERR_AUCTION_DELETED => 'non_operable.err_auction_deleted',
        InvoiceRelatedEntityValidator::ERR_LOT_ACCOUNT_DELETED => 'non_operable.err_lot_account_deleted',
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param InvoiceRelatedEntityValidator $invoiceRelatedEntityValidator
     * @param InvoiceItem[] $invoiceItems
     * @param string $language
     * @return string
     */
    public function render(InvoiceRelatedEntityValidator $invoiceRelatedEntityValidator, array $invoiceItems, string $language): string
    {
        $invoiceItems = ArrayHelper::indexEntities($invoiceItems, 'Id');
        $outputs[] = $this->translate('non_operable.invoice_locked_because_of_problems', [], $language);
        if ($invoiceRelatedEntityValidator->hasInvoiceDeletedError()) {
            $outputs[] = $this->translate(self::TRANSLATION_KEYS[InvoiceRelatedEntityValidator::ERR_INVOICE_DELETED], [], $language);
        }
        if ($invoiceRelatedEntityValidator->hasBidderError()) {
            $bidderOutputs = [];
            foreach ($invoiceRelatedEntityValidator->getBidderErrorCodes() as $code) {
                $translationKey = self::TRANSLATION_KEYS[$code] ?? '';
                $bidderOutputs[] = $this->translate($translationKey, [], $language);
            }
            $outputs[] = implode(', ', $bidderOutputs);
        }
        $invoiceItemErrors = $invoiceRelatedEntityValidator->getInvoiceItemErrors();
        foreach ($invoiceItemErrors as $resultStatus) {
            [$invoiceItemId, $lotItemId, $auctionId, $accountId] = $resultStatus->getPayload();
            $code = $resultStatus->getCode();
            $translationKey = self::TRANSLATION_KEYS[$code] ?? '';
            $invoiceItem = $invoiceItems[(int)$invoiceItemId] ?? null;
            if ($invoiceItem) {
                if (in_array(
                    $code,
                    [
                        InvoiceRelatedEntityValidator::ERR_LOT_ITEM_DELETED,
                        InvoiceRelatedEntityValidator::ERR_AUCTION_LOT_DELETED,
                    ],
                    true
                )
                ) {
                    $message = $this->translate($translationKey, ['lot' => $invoiceItem->LotName], $language);
                    $message .= composeSuffix(['li' => $invoiceItem->LotItemId]);
                } elseif ($code === InvoiceRelatedEntityValidator::ERR_LOT_ACCOUNT_DELETED) {
                    $account = $this->getAccountLoader()->clear()->load((int)$accountId, true);
                    $message = $this->translate($translationKey, ['account' => $account->Name ?? ''], $language);
                    $message .= composeSuffix(
                        [
                            'acc' => $accountId,
                            'li' => $lotItemId,
                        ]
                    );
                } elseif ($code === InvoiceRelatedEntityValidator::ERR_AUCTION_DELETED) {
                    $auction = $this->getAuctionLoader()
                        ->clear()
                        ->load((int)$auctionId, true);
                    $auctionName = $this->getAuctionRenderer()->renderName($auction);
                    $message = $this->translate($translationKey, ['auction' => $auctionName], $language);
                    $message .= composeSuffix(
                        [
                            'a' => $auctionId,
                            'li' => $lotItemId,
                        ]
                    );
                }
            } else {
                $message = $this->translate($translationKey, [], $language);
            }
            $outputs[] = $message;
        }
        $output = implode("</br>", $outputs);
        // $output = "<p class=\"error\">{$output}</p>";
        return $output;
    }

    /**
     * Find InvoiceItem in array by id
     * @param int $invoiceItemId
     * @param array $invoiceItems
     * @return InvoiceItem|null
     */
    public function getInvoiceItemById(int $invoiceItemId, array $invoiceItems): ?InvoiceItem
    {
        foreach ($invoiceItems as $invoiceItem) {
            if ($invoiceItem->Id === $invoiceItemId) {
                return $invoiceItem;
            }
        }
        return null;
    }

    public function translate(string $key, array $params, string $language): string
    {
        return $this->getAdminTranslator()->trans($key, $params, 'admin_invoice_edit', $language);
    }
}
