<?php
/**
 * SAM-11177: Stacked Tax. Invoice e-mail view - (Stage 2)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 15, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\View\IsolatedInvoiceView\Internal;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\CustomField\User\Load\UserCustomFieldLoaderAwareTrait;
use Sam\Invoice\Common\AdditionalCharge\InvoiceAdditionalChargeManagerAwareTrait;
use Sam\Invoice\Common\Bidder\Load\InvoiceUserLoaderAwareTrait;
use Sam\Invoice\Common\Load\Exception\CouldNotFindInvoice;
use Sam\Invoice\Common\Load\InvoiceAuctionLoaderCreateTrait;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Invoice\Common\Payment\InvoicePaymentManagerAwareTrait;
use Sam\Invoice\Common\Setting\InvoiceSettingCheckerCreateTrait;
use Sam\Invoice\StackedTax\View\Common\Goods\InvoiceGoodsSubtotal;
use Sam\Invoice\StackedTax\View\Common\Goods\Load\InvoiceItemDataLoaderCreateTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class IsolatedInvoiceViewModelFactory
 * @package Sam\Invoice\StackedTax\View\IsolatedInvoiceView\Internal
 */
class IsolatedInvoiceViewModelFactory extends CustomizableClass
{
    use CurrencyLoaderAwareTrait;
    use InvoiceAdditionalChargeManagerAwareTrait;
    use InvoiceAuctionLoaderCreateTrait;
    use InvoiceItemDataLoaderCreateTrait;
    use InvoiceItemLoaderAwareTrait;
    use InvoiceLoaderAwareTrait;
    use InvoicePaymentManagerAwareTrait;
    use InvoiceSettingCheckerCreateTrait;
    use InvoiceUserLoaderAwareTrait;
    use IsolatedInvoiceViewRenderHelperCreateTrait;
    use LotCustomFieldLoaderCreateTrait;
    use NumberFormatterAwareTrait;
    use SettingsManagerAwareTrait;
    use TranslatorAwareTrait;
    use UserCustomFieldLoaderAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function create(int $invoiceId, int $viewLanguageId): IsolatedInvoiceViewModel
    {
        $invoice = $this->getInvoiceLoader()->load($invoiceId);
        if (!$invoice) {
            throw CouldNotFindInvoice::withId($invoiceId);
        }

        $model = new IsolatedInvoiceViewModel();

        $sm = $this->getSettingsManager();
        $model->languageId = $viewLanguageId;
        $model->isShowCategory = $sm->get(Constants\Setting::CATEGORY_IN_INVOICE, $invoice->AccountId);
        $model->isMultipleSale = $sm->get(Constants\Setting::MULTIPLE_SALE_INVOICE, $invoice->AccountId);
        $model->isShowQuantity = $sm->get(Constants\Setting::QUANTITY_IN_INVOICE, $invoice->AccountId);
        $model->isShowSalesTax = $sm->get(Constants\Setting::INVOICE_ITEM_SALES_TAX, $invoice->AccountId);
        $model->isInvoiceItemSeparateTax = $sm->get(Constants\Setting::INVOICE_ITEM_SEPARATE_TAX, $invoice->AccountId);
        $model->isInvoiceIdentification = $sm->get(Constants\Setting::INVOICE_IDENTIFICATION, $invoice->AccountId);
        $model->isTaxSeparatedRendering = $this->createInvoiceSettingChecker()->isTaxSeparatedRendering($invoice->AccountId);
        $model->isTaxUnitedRendering = $this->createInvoiceSettingChecker()->isTaxUnitedRendering($invoice->AccountId);

        $model->invoice = $invoice;
        $model->taxCountry = $invoice->TaxCountry;
        $auctionId = $this->getInvoiceItemLoader()->findFirstInvoicedAuctionId($invoiceId, true);
        $model->currencySign = $this->getCurrencyLoader()->detectDefaultSign($auctionId, true);

        if ($model->isMultipleSale) {
            $model->auctionId = null;
            $model->invoiceAuctions = [];
        } else {
            $model->auctionId = $auctionId;
            $model->invoiceAuctions = $this->createInvoiceAuctionLoader()->loadByInvoiceId($invoiceId, true);
        }

        $model->renderHelper = $this->createIsolatedInvoiceViewRenderHelper();
        $model->translator = $this->getTranslator()->construct($invoice->AccountId, $viewLanguageId);
        $model->numberFormatter = $this->getNumberFormatter()->constructForInvoice($invoice->AccountId);

        $model->lotCustomFields = $this->createLotCustomFieldLoader()->loadInInvoices(true);
        $model->userCustomFields = $this->getUserCustomFieldLoader()->loadInInvoices(true);

        $model->invoiceItemDtos = $this->createInvoiceItemDataLoader()->loadForInvoice($invoiceId, $model->lotCustomFields, true);
        $model->invoiceItemSubtotal = InvoiceGoodsSubtotal::new()->construct($model->invoiceItemDtos);

        $model->invoiceAdditionals = $this->getInvoiceAdditionalChargeManager()->loadForInvoice($invoiceId);
        $model->payments = $this->getInvoicePaymentManager()->loadForInvoice($invoice->Id);

        $model->bidderUser = $this->getUserLoader()->load($invoice->BidderId, true);
        $model->bidderUserInfo = $this->getInvoiceUserLoader()->loadInvoiceUserOrCreate($invoiceId, true);
        $model->userBilling = $this->getInvoiceUserLoader()->loadInvoiceUserBillingOrCreate($invoiceId, true);
        $model->userShipping = $this->getInvoiceUserLoader()->loadInvoiceUserShippingOrCreate($invoiceId, true);

        return $model;
    }
}
