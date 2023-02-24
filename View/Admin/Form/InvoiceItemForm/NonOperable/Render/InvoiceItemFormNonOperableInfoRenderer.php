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

namespace Sam\View\Admin\Form\InvoiceItemForm\NonOperable\Render;

use InvoiceItem;
use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Validate\InvoiceRelatedEntityValidator;

/**
 * Class InvoiceItemFormNonOperableInfoRenderer
 * @package Sam\View\Admin\Form\InvoiceItemForm\NonOperable\Render
 */
class InvoiceItemFormNonOperableInfoRenderer extends CustomizableClass
{
    use AccountLoaderAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuctionRendererAwareTrait;

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
     * @param array $invoiceItems
     * @return string
     */
    public function render(InvoiceRelatedEntityValidator $invoiceRelatedEntityValidator, array $invoiceItems): string
    {
        $invoiceItems = ArrayHelper::indexEntities($invoiceItems, 'Id');
        $outputs[] = 'Invoice is locked, because of related entity problems:';
        if ($invoiceRelatedEntityValidator->hasBidderError()) {
            $outputs[] = implode(', ', $invoiceRelatedEntityValidator->getBidderErrorMessages());
        }
        $invoiceItemErrors = $invoiceRelatedEntityValidator->getInvoiceItemErrors();
        foreach ($invoiceItemErrors as $resultStatus) {
            [$invoiceItemId, $lotItemId, $auctionId, $accountId] = $resultStatus->getPayload();
            $code = $resultStatus->getCode();
            $message = $resultStatus->getMessage();
            $invoiceItem = $invoiceItems[(int)$invoiceItemId] ?? null;
            if ($invoiceItem) {
                $lotName = $invoiceItem->LotName;
                if (in_array(
                    $code,
                    [
                        InvoiceRelatedEntityValidator::ERR_LOT_ITEM_DELETED,
                        InvoiceRelatedEntityValidator::ERR_AUCTION_LOT_DELETED,
                    ],
                    true
                )
                ) {
                    $message .= composeSuffix(['li' => $invoiceItem->LotItemId, 'lot' => $lotName]);
                } elseif ($code === InvoiceRelatedEntityValidator::ERR_LOT_ACCOUNT_DELETED) {
                    $account = $this->getAccountLoader()->clear()->load((int)$accountId, true);
                    $message .= composeSuffix(
                        [
                            'acc' => $accountId,
                            'account' => ($account->Name ?? ''),
                            'li' => $lotItemId,
                            'lot' => $lotName,
                        ]
                    );
                } elseif ($code === InvoiceRelatedEntityValidator::ERR_AUCTION_DELETED) {
                    $auction = $this->getAuctionLoader()
                        ->clear()
                        ->load((int)$auctionId, true);
                    $auctionName = $this->getAuctionRenderer()->renderName($auction);
                    $message .= composeSuffix(
                        [
                            'a' => $auctionId,
                            'auction' => $auctionName,
                            'li' => $lotItemId,
                            'lot' => $lotName
                        ]
                    );
                }
            }
            $outputs[] = $message;
        }
        $output = implode("</br>", $outputs);
        $output = "<p class=\"error\">{$output}</p>";
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

}
