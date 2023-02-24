<?php
/**
 * SAM-11160: Stacked Tax. Admin/Public Single Invoice Printing View page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\View\IsolatedInvoiceView\Internal;

use Invoice;
use InvoiceAdditional;
use InvoiceAuction;
use InvoiceUser;
use InvoiceUserBilling;
use InvoiceUserShipping;
use LotItemCustField;
use Payment;
use Sam\Invoice\StackedTax\View\Common\Goods\InvoiceGoodsSubtotal;
use Sam\Invoice\StackedTax\View\Common\Goods\Load\InvoiceItemData;
use Sam\Lang\TranslatorInterface;
use Sam\Transform\Number\NumberFormatterInterface;
use User;
use UserCustField;

/**
 * Class IsolatedInvoiceViewModel
 * @package Sam\Invoice\StackedTax\View\IsolatedInvoiceView\Internal
 *
 * @property Invoice $invoice
 * @property InvoiceAdditional[]|mixed $invoiceAdditionals
 * @property InvoiceAuction[] $invoiceAuctions
 * @property InvoiceGoodsSubtotal $invoiceItemSubtotal
 * @property InvoiceItemData[] $invoiceItemDtos
 * @property IsolatedInvoiceViewRenderHelper $renderHelper
 * @property InvoiceUserBilling $userBilling
 * @property InvoiceUserShipping $userShipping
 * @property LotItemCustField[] $lotCustomFields
 * @property NumberFormatterInterface $numberFormatter
 * @property Payment[] $payments
 * @property TranslatorInterface $translator
 * @property UserCustField[] $userCustomFields
 * @property InvoiceUser|null $bidderUserInfo
 * @property User|null $bidderUser
 * @property bool $isInvoiceIdentification
 * @property bool $isInvoiceItemSeparateTax
 * @property bool $isMultipleSale
 * @property bool $isShowCategory
 * @property bool $isShowQuantity
 * @property bool $isShowSalesTax
 * @property bool $isTaxSeparatedRendering
 * @property bool $isTaxUnitedRendering
 * @property int $languageId
 * @property int|null $auctionId
 * @property string $currencySign
 * @property string|null $taxCountry null means default country key
 */
class IsolatedInvoiceViewModel extends \Laminas\View\Model\ViewModel
{
}
