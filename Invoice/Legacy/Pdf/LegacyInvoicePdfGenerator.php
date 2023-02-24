<?php
/**
 * SAM-6648:  Refactor \PdfPrintInvoice and move it away from \Invoice_PdfExportInvoice to \Sam namespace
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Pdf;

use DateTime;
use Fpdf\Fpdf;
use Invoice;
use InvoiceAdditional;
use LotItemCustData;
use LotItemCustField;
use Payment;
use Sam\Address\AddressFormatterCreateTrait;
use Sam\Application\Url\Build\Config\Barcode\BarcodeUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Billing\CreditCard\Load\CreditCardLoaderAwareTrait;
use Sam\Billing\Payment\Render\PaymentRendererAwareTrait;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Entity\Model\Invoice\Tax\InvoiceTaxPureCalculator;
use Sam\Core\Math\Floating;
use Sam\Core\Transform\Html\HtmlRenderer;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\CustomField\Lot\Load\LotCustomDataLoaderCreateTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\CustomField\User\Help\UserCustomFieldHelperAwareTrait;
use Sam\CustomField\User\Load\UserCustomDataLoaderAwareTrait;
use Sam\CustomField\User\Load\UserCustomFieldLoaderAwareTrait;
use Sam\CustomField\User\Qform\ViewControls;
use Sam\Date\DateHelperAwareTrait;
use Sam\Date\Render\DateRendererCreateTrait;
use Sam\Invoice\Common\AdditionalCharge\InvoiceAdditionalChargeManagerAwareTrait;
use Sam\Invoice\Common\Bidder\Load\InvoiceUserLoaderAwareTrait;
use Sam\Invoice\Legacy\Calculate\Basic\LegacyInvoiceCalculatorAwareTrait;
use Sam\Invoice\Legacy\Calculate\Tax\LegacyInvoiceTaxCalculatorCreateTrait;
use Sam\Invoice\Common\Load\InvoiceHeaderDataLoaderAwareTrait;
use Sam\Invoice\Common\Load\InvoiceItem\Dto\InvoicedAuctionDto;
use Sam\Invoice\Common\Load\InvoiceItem\Dto\InvoiceItemDto;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Invoice\Common\Payment\InvoicePaymentManagerAwareTrait;
use Sam\Invoice\Common\Render\Logo\InvoiceLogoPathResolverCreateTrait;
use Sam\Invoice\Legacy\Pdf\Transalation\TranslationDto;
use Sam\Lot\Category\Renderer\LotCategoryRendererAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\Amount\LotAmountRendererFactoryCreateTrait;
use Sam\Lot\Render\Amount\LotAmountRendererInterface;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Settings\Layout\Image\Path\LayoutImagePathResolverCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Settings\TermsAndConditionsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\InvoiceAwareTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Identification\UserIdentificationTransformerAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Render\UserRendererAwareTrait;
use UserCustField;

class LegacyInvoicePdfGenerator extends Fpdf
{
    use AddressFormatterCreateTrait;
    use AuctionAwareTrait;
    use AuctionBidderLoaderAwareTrait;
    use AuctionLoaderAwareTrait;
    use BidderNumPaddingAwareTrait;
    use CreditCardLoaderAwareTrait;
    use CurrencyLoaderAwareTrait;
    use DateHelperAwareTrait;
    use DateRendererCreateTrait;
    use InvoiceAdditionalChargeManagerAwareTrait;
    use InvoiceAwareTrait;
    use LegacyInvoiceCalculatorAwareTrait;
    use InvoiceHeaderDataLoaderAwareTrait;
    use InvoiceItemLoaderAwareTrait;
    use InvoiceLoaderAwareTrait;
    use InvoiceLogoPathResolverCreateTrait;
    use InvoicePaymentManagerAwareTrait;
    use LegacyInvoiceTaxCalculatorCreateTrait;
    use InvoiceUserLoaderAwareTrait;
    use LayoutImagePathResolverCreateTrait;
    use LotAmountRendererFactoryCreateTrait;
    use LotCategoryRendererAwareTrait;
    use LotCustomDataLoaderCreateTrait;
    use LotCustomFieldLoaderCreateTrait;
    use LotItemLoaderAwareTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use PaymentRendererAwareTrait;
    use SettingsManagerAwareTrait;
    use TermsAndConditionsManagerAwareTrait;
    use TimezoneLoaderAwareTrait;
    use UrlBuilderAwareTrait;
    use UrlParserAwareTrait;
    use UserCustomDataLoaderAwareTrait;
    use UserCustomFieldHelperAwareTrait;
    use UserCustomFieldLoaderAwareTrait;
    use UserIdentificationTransformerAwareTrait;
    use UserLoaderAwareTrait;
    use UserRendererAwareTrait;

    public const MAX_TERM_CONDITION_TEXT_LENGTH = 1000;

    public int $col = 0; // Current column
    public ?float $y0; //  Ordinate of column start
    /**
     * @var InvoiceAdditional[]
     */
    public array $invoiceAdditionals = [];
    /**
     * @var Payment[]
     */
    public array $payments = [];
    public bool $isInvoiceIdentification = false;
    public bool $isInvoiceItemSeparateTax = false;
    public bool $isShowSalesTax = false;
    public bool $isMultipleSale = false;
    public bool $isQuantityInInvoice = false;
    public bool $isCategoryInInvoice = false;
    public ?int $timezoneId;
    public ?float $cashDiscount;
    /**
     * @var LotItemCustField[]
     */
    public array $lotCustomFields = [];
    /**
     * @var UserCustField[]
     */
    public array $userCustomFields = [];
    protected ?string $currencySign;
    protected ?int $invoiceAccountId;
    protected TranslationDto $translationDto;
    protected ?LotAmountRendererInterface $lotAmountRenderer = null;
    public bool $isCustomFieldInSeparateRow;

    //variables of html parser
    public string $href = '';
    public bool $isSetFont;
    public bool $isSetColor;
    public array $widths;
    public array $aligns;
    public array $fontList;
    public int $B = 0;
    public int $I = 0;
    public int $U = 0;

    /**
     * PdfPrintInvoice constructor.
     * @param Invoice $invoice
     * @param int $viewLanguageId
     */
    public function __construct(Invoice $invoice, int $viewLanguageId)
    {
        parent::__construct();
        // We allow to view invoices with deleted user (invoice.bidder)
        $this->getUserLoader()->clear();
        $this->setInvoice($invoice);
        $auctionId = $this->getInvoiceItemLoader()->findFirstInvoicedAuctionId($invoice->Id, true);
        $auction = $this->getAuctionLoader()->load($auctionId, true);
        $this->setAuction($auction);
        $this->invoiceAccountId = $this->getInvoice()->AccountId;

        $this->getNumberFormatter()->constructForInvoice($this->invoiceAccountId);
        $this->lotAmountRenderer = $this->createLotAmountRendererFactory()->createForInvoice($this->invoiceAccountId);

        $this->invoiceAdditionals = $this->getInvoiceAdditionalChargeManager()->loadForInvoice($this->getInvoiceId());
        $this->payments = $this->getInvoicePaymentManager()->loadForInvoice($this->getInvoiceId());
        $sm = $this->getSettingsManager();
        $this->isCategoryInInvoice = (bool)$sm->get(Constants\Setting::CATEGORY_IN_INVOICE, $this->invoiceAccountId);
        $this->isQuantityInInvoice = (bool)$sm->get(Constants\Setting::QUANTITY_IN_INVOICE, $this->invoiceAccountId);
        $auctionId = $this->getAuctionId();
        $this->currencySign = mb_convert_encoding($this->getCurrencyLoader()->detectDefaultSign($auctionId, true), "CP1252", "UTF-8");

        $this->isMultipleSale = (bool)$sm->get(Constants\Setting::MULTIPLE_SALE_INVOICE, $this->invoiceAccountId);
        $this->isShowSalesTax = (bool)$sm->get(Constants\Setting::INVOICE_ITEM_SALES_TAX, $this->invoiceAccountId);
        $this->isInvoiceItemSeparateTax = (bool)$sm->get(Constants\Setting::INVOICE_ITEM_SEPARATE_TAX, $this->invoiceAccountId);
        $this->isInvoiceIdentification = (bool)$sm->get(Constants\Setting::INVOICE_IDENTIFICATION, $this->invoiceAccountId);
        $this->timezoneId = (int)$sm->get(Constants\Setting::TIMEZONE_ID, $this->invoiceAccountId);
        $this->cashDiscount = $sm->get(Constants\Setting::CASH_DISCOUNT, $this->invoiceAccountId);
        $this->translationDto = TranslationDto::new()->construct($this->invoiceAccountId, $viewLanguageId);

        $this->lotCustomFields = $this->createLotCustomFieldLoader()->loadInInvoices(true);
        $this->userCustomFields = $this->getUserCustomFieldLoader()->loadInInvoices(true);
        $this->isCustomFieldInSeparateRow = $sm->get(Constants\Setting::RENDER_LOT_CUSTOM_FIELDS_IN_SEPARATE_ROW_FOR_INVOICE, $this->invoiceAccountId);
        $this->fontList = Constants\Pdf::$fonts;
        // Headers conversion from ISO-8859-1 to UTF8 that handle special characters mostly ( �,�,�,�,�,�,�,�,�,�,�,�,�,� )
        /* $this->_strDateCreated   = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $this->_strDateCreated);
        $this->_strSale          = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $this->_strSale);
        $this->_strBillingInfo   = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $this->_strBillingInfo);
        $this->_strShippingInfo  = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $this->_strShippingInfo);
        $this->_strItemNum         = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $this->_strItemNum);
        $this->_strItemName         = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $this->_strItemName);
        $this->_strCategory         = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $this->_strCategory);
        $this->_strSalesTax         = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $this->_strSalesTax);
        $this->_strHammerPrice   = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $this->_strHammerPrice);
        $this->_strBuyersPremium = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $this->_strBuyersPremium);
        $this->_strSubTotal         = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $this->_strSubTotal);
        $this->_strBalance       = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $this->_strBalance);

        $this->_strDateCreated   = str_replace('"Y', 'Y', $this->_strDateCreated);
        $this->_strSale          = str_replace('"Y', 'Y', $this->_strSale);
        $this->_strBillingInfo   = str_replace('"Y', 'Y', $this->_strBillingInfo);
        $this->_strShippingInfo  = str_replace('"Y', 'Y', $this->_strShippingInfo);
        $this->_strItemNum       = str_replace('"Y', 'Y', $this->_strItemNum);
        $this->_strItemName      = str_replace('"Y', 'Y', $this->_strItemName);
        $this->_strCategory      = str_replace('"Y', 'Y', $this->_strCategory);
        $this->_strSalesTax      = str_replace('"Y', 'Y', $this->_strSalesTax);
        $this->_strHammerPrice   = str_replace('"Y', 'Y', $this->_strHammerPrice);
        $this->_strBuyersPremium = str_replace('"Y', 'Y', $this->_strBuyersPremium);
        $this->_strSubTotal      = str_replace('"Y', 'Y', $this->_strSubTotal);
        $this->_strBalance       = str_replace('"Y', 'Y', $this->_strBalance);*/
    }

    /**
     * @override
     */
    public function Header(): void
    {
        $this->SetFont('Arial', '', 6);

        if ($this->isRenderLogoImage()) {
            $logoImg = $this->renderLogoImage();
            $this->writeHTML($logoImg);
            $this->Ln(1);
            $this->SetY($this->GetY() + 25);
        }

        //$this->headRow2($this->_invoice);
        $this->headerRow();

        //Save ordinate
        $this->y0 = $this->GetY();
    }

    protected function isRenderLogoImage(): bool
    {
        if ($this->createLayoutImagePathResolver()->hasInvoiceLogo($this->invoiceAccountId)) {
            return true;
        }

        $row = $this->getInvoiceHeaderDataLoader()->load($this->getInvoiceId(), true);
        if (isset($row['logo'])) {
            return true;
        }

        return false;
    }

    /**
     * @override
     */
    public function Footer(): void
    {
        //Page footer
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 6);
        $this->SetTextColor(128);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    public function setCol(int $col): void
    {
        //Set position at a given column
        $this->col = Cast::toInt($col);
        $x = 10 + $col * 95;
        $this->SetLeftMargin($x);
        $this->SetX($x);
    }

    /**
     * @override
     */
    public function AcceptPageBreak(): bool
    {
        //Method accepting or not automatic page break
        if ($this->col < 1) { // Column number
            //Go to next column
            $this->setCol($this->col + 1);
            //Set ordinate to top
            $this->SetY($this->y0);

            //Keep on page
            return false;
        }

        //Go back to first column
        $this->setCol(0);

        //Page break
        return true;
    }

    //======================== write html ==================

    protected function writeHTML(string $html): void
    {
        //HTML parser
        $html = strip_tags($html, "<b><u><i><a><img><p><br><strong><em><font><tr><blockquote>"); //supprime tous les tags sauf ceux reconnus
        $html = str_replace("\n", ' ', $html); //remplace retour � la ligne par un espace
        $a = preg_split('/<(.*)>/U', $html, -1, PREG_SPLIT_DELIM_CAPTURE); //�clate la cha�ne avec les balises
        foreach ($a as $i => $e) {
            if ($i % 2 === 0) {
                //Text
                if ($this->href) {
                    $this->putLink($this->href, $e);
                } else {
                    $this->Write(5, stripslashes($this->txtEntities($e)));
                }
            } else {
                //Tag
                if ($e[0] === '/') {
                    $this->closeTag(strtoupper(substr($e, 1)));
                } else {
                    //Extract attributes
                    $a2 = explode(' ', $e);
                    $tag = strtoupper(array_shift($a2));
                    $attr = [];
                    foreach ($a2 as $v) {
                        if (preg_match('/([^=]*)=["\']?([^"\']*)/', $v, $a3)) {
                            $attr[strtoupper($a3[1])] = $a3[2];
                        }
                    }
                    $this->openTag($tag, $attr);
                }
            }
        }
    }

    function openTag(string $tag, array $attr): void
    {
        //Opening tag
        switch ($tag) {
            case 'STRONG':
                $this->setStyle('B', true);
                break;
            case 'EM':
                $this->setStyle('I', true);
                break;
            case 'B':
            case 'I':
            case 'U':
                $this->setStyle($tag, true);
                break;
            case 'A':
                $this->href = $attr['HREF'];
                break;
            case 'IMG':
                if (
                    isset($attr['SRC'])
                    && (isset($attr['WIDTH'])
                        || isset($attr['HEIGHT']))
                ) {
                    if (!isset($attr['WIDTH'])) {
                        $attr['WIDTH'] = 0;
                    }
                    if (!isset($attr['HEIGHT'])) {
                        $attr['HEIGHT'] = 0;
                    }
                    $this->Image($attr['SRC'], $this->GetX(), $this->GetY(), $this->px2mm($attr['WIDTH']), $this->px2mm($attr['HEIGHT']));
                }
                break;
            case 'TR':
            case 'BLOCKQUOTE':
            case 'P':
            case 'BR':
                $this->Ln(5);
                break;
            case 'FONT':
                if (!empty($attr['COLOR'])) {
                    $color = $this->hex2dec($attr['COLOR']);
                    $this->SetTextColor($color['R'], $color['G'], $color['B']);
                    $this->isSetColor = true;
                }
                if (
                    isset($attr['FACE'])
                    && in_array(strtolower($attr['FACE']), $this->fontList, true)
                ) {
                    $this->SetFont(strtolower($attr['FACE']));
                    $this->isSetFont = true;
                }
                break;
        }
    }

    protected function closeTag(string $tag): void
    {
        //Closing tag
        if ($tag === 'STRONG') {
            $tag = 'B';
        }
        if ($tag === 'EM') {
            $tag = 'I';
        }
        if ($tag === 'B' || $tag === 'I' || $tag === 'U') {
            $this->setStyle($tag, false);
        }
        if ($tag === 'A') {
            $this->href = '';
        }
        if ($tag === 'FONT') {
            if ($this->isSetColor) {
                $this->SetTextColor(0);
            }
            if ($this->isSetFont) {
                $this->SetFont('arial');
                $this->isSetFont = false;
            }
        }
    }

    protected function setStyle(string $tag, bool $enable): void
    {
        //Modify style and select corresponding font
        $this->$tag += ($enable ? 1 : -1);
        $style = '';
        foreach (['B', 'I', 'U'] as $s) {
            if ($this->$s > 0) {
                $style .= $s;
            }
        }
        $this->SetFont('', $style);
    }

    protected function putLink(string $URL, string $txt): void
    {
        //Put a hyperlink
        $this->SetTextColor(0, 0, 255);
        $this->setStyle('U', true);
        $this->Write(5, $txt, $URL);
        $this->setStyle('U', false);
        $this->SetTextColor(0);
    }

    //============= Multi-cell ROW ========================
    protected function setWidths(array $w)
    {
        //Set the array of column widths
        $this->widths = ArrayCast::makeFloatArray($w);
    }

    protected function setAligns(array $a): void
    {
        //Set the array of column alignments
        $this->aligns = ArrayCast::makeStringArray($a);
    }

    protected function row(array $data, bool $border = true, bool $fill = true, int $customh = 5): void
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0, $iMax = count($data); $i < $iMax; $i++) {
            $nb = max($nb, $this->nbLines(@$this->widths[$i], $data[$i]));
        }
        $h = $customh * $nb;
        //Issue a page break first if needed
        $this->checkPageBreak($h);
        //Draw the cells of the row
        for ($i = 0, $iMax = count($data); $i < $iMax; $i++) {
            $w = @$this->widths[$i];
            $a = $this->aligns[$i] ?? 'L';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            if ($border) {
                $this->Rect($x, $y, $w, $h);
                /*
                if($fill)
                    $this->Rect($x,$y,$w,$h,'F');
                else
                    $this->Rect($x,$y,$w,$h);*/
            }
            if (str_contains($data[$i], "/barcode/")) {
                $this->Image($data[$i], $x + 1, $y + 1, 23, 10, 'jpeg');
                $data[$i] = '';
            }

            //Print the text
            $this->MultiCell($w, $customh, $data[$i], 0, $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    protected function checkPageBreak($h): void
    {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
        }
    }

    protected function nbLines($w, string $txt): int
    {
        //Computes the number of lines a MultiCell of width w will take
        $cw =& $this->CurrentFont['cw'];
        if ((int)$w === 0) {
            $w = $this->w - $this->rMargin - $this->x;
        }
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] === "\n") {
            $nb--;
        }
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c === "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c === ' ') {
                $sep = $i;
            }
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep === -1) {
                    if ($i === $j) {
                        $i++;
                    }
                } else {
                    $i = $sep + 1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else {
                $i++;
            }
        }
        return $nl;
    }

    //====================================================

    public function headerRow(): void
    {
        if ($this->PageNo() > 1) {
            return;
        }

        $this->SetFont('Arial', '', 6);
        $title = $this->translationDto->invoiceNum . $this->getInvoice()->InvoiceNo;
        $invoiceDate = $this->getInvoice()->InvoiceDate ?: new DateTime($this->getInvoice()->CreatedOn);
        $invoiceDate = $this->getDateHelper()->convertUtcToSys($invoiceDate);
        $langDateCreated = $this->translationDto->dateCreated . ': ' . $invoiceDate->format($this->translationDto->invoiceData);

        //terms
        $termsAndConditionsText = '';
        $row = $this->getInvoiceHeaderDataLoader()->load($this->getInvoiceId(), true);
        if ($row) {
            $termsAndConditionsText = $this->createAddressFormatter()->format($row['country'], $row['state'], $row['city'], $row['zip'], $row['address'], false);
        }
        if (!$termsAndConditionsText) {
            $termsAndConditions = $this->getTermsAndConditionsManager()->load(
                $this->getInvoice()->AccountId,
                Constants\TermsAndConditions::INVOICE,
                true
            );
            if ($termsAndConditions) {
                $termsAndConditionsContentLines = explode('</p>', $termsAndConditions->Content);
                foreach ($termsAndConditionsContentLines as $termsAndConditionsLine) {
                    $termsAndConditionsLine = trim(str_replace('<p>', '', strip_tags($termsAndConditionsLine)));
                    $length = strlen($termsAndConditionsLine);
                    if ($length > self::MAX_TERM_CONDITION_TEXT_LENGTH) {
                        $longTermAndConditionLineParts = explode("\n", wordwrap($termsAndConditionsLine, self::MAX_TERM_CONDITION_TEXT_LENGTH));
                        $termsAndConditionsLine = implode("", $longTermAndConditionLineParts);
                    }
                    $termsAndConditionsText .= $termsAndConditionsLine;
                }
            } else {
                log_error(
                    "Available Terms and Conditions record not found for rendering invoice pdf"
                    . composeSuffix(['acc' => $this->getInvoice()->AccountId, 'key' => Constants\TermsAndConditions::INVOICE])
                );
            }
        }

        $invDetails = $title . "   " . $langDateCreated;

        if (!$this->isMultipleSale) {
            $invoicedAuctionDtos = $this->getInvoiceItemLoader()->loadInvoicedAuctionDtos($this->getInvoiceId(), true);
            if (count($invoicedAuctionDtos) === 1) {
                $invoicedAuctionDto = current($invoicedAuctionDtos);

                $saleName = strip_tags($this->renderSaleName($invoicedAuctionDto));
                $saleDate = $invoicedAuctionDto->detectSaleDate();
                $saleDate = $this->getDateHelper()->convertUtcToTzById($saleDate, $invoicedAuctionDto->auctionTimezoneId);
                $saleDateFormatted = $this->makeSaleDate(
                    $saleDate,
                    $invoicedAuctionDto->auctionType,
                    $invoicedAuctionDto->auctionEventType,
                    $invoicedAuctionDto->accountId
                );

                $invDetails .= "\n" . $this->translationDto->sale . ' :' . $saleName . "\n" . $this->translationDto->detailSaleDate . ': ' . $saleDateFormatted;
            } else {
                $invSale = '';
                foreach ($invoicedAuctionDtos as $invoicedAuctionDto) {
                    $saleName = $this->renderSaleName($invoicedAuctionDto);
                    $saleDate = $invoicedAuctionDto->detectSaleDate();
                    $saleDate = $this->getDateHelper()->convertUtcToTzById($saleDate, $invoicedAuctionDto->auctionTimezoneId);
                    $saleDateFormatted = $this->makeSaleDate(
                        $saleDate,
                        $invoicedAuctionDto->auctionType,
                        $invoicedAuctionDto->auctionEventType,
                        $invoicedAuctionDto->accountId
                    );

                    $invSale .= $saleName . ' ' . $saleDateFormatted . "\n";
                }
                $invDetails .= "\n" . $invSale;
            }
        }

        $this->setWidths([91, 91]);
        $this->setAligns(['L', 'R']);
        $this->row(
            [
                $invDetails,
                html_entity_decode($termsAndConditionsText, ENT_NOQUOTES, 'ISO-8859-1'),
            ],
            false,
            false,
            3
        );

        $this->Ln();

        //draw line
        $this->Line(10, $this->GetY(), 192, $this->GetY());

        $shipping = $this->renderShippingAddress();
        $shipping = $this->br2nl($shipping);
        $shipping = strip_tags($shipping);

        $this->SetFont('Arial', 'B', 6);
        $this->setAligns(['L', 'L']);
        $this->row(
            [
                $this->translationDto->billingInfo,
                $shipping ? $this->translationDto->shippingInfo : '',
            ],
            false
        );

        $billing = $this->renderBillingAddress();
        $billing = $this->br2nl($billing);
        $billing = strip_tags($billing);

        $this->SetFont('Arial', '', 6);
        $this->row(
            [
                $billing,
                $shipping ?: '',
            ],
            false,
            false,
            3
        );
        $this->Ln(2);

        //draw line
        $this->Line(10, $this->GetY(), 192, $this->GetY());

        $this->SetFont('Arial', 'B', 6);
        $this->row([$this->translationDto->userCustomFieldInfo], false);

        $userCustomFields = $this->renderUserCustomFields();
        $userCustomFields = $this->br2nl($userCustomFields);
        $userCustomFields = strip_tags($userCustomFields);

        $this->SetFont('Arial', '', 6);

        $this->row([$userCustomFields], false, false, 3);
        $this->Ln(2);

        //draw line
        $this->Line(10, $this->GetY(), 192, $this->GetY());

        //after header, make sure to switch back the widths
        $this->Ln(2);
        $this->SetFont('Arial', '', 6);
        $headers = $this->getHeaderColArray();
        // $multiCols = [4, 5, 6];
        $this->SetFont('Arial', 'B', 6);

        $this->SetFillColor(1, 61, 133);
        $this->SetTextColor(255);
        $this->SetDrawColor(1, 61, 133);
        foreach ($headers as $colNo) {
            $this->Cell($colNo[1], 4, $colNo[0], 0, 0, 'L', true);
        }
        //MultiCell(25,6,$f1,'LRT','L',0);
        $this->Ln();

        $headerArray = [
            $headers[0][1],
            $headers[1][1],
            $headers[2][1],
            $headers[3][1],
            $headers[4][1]
            //$headers[5][1]
        ];

        $headerArrayAlign = ['L', 'L', 'L', 'R', 'R'];
        $col = 4;
        if ($this->isMultipleSale) {
            $col++;
            $headerArray[] = $headers[$col][1];
            $col++;
            $headerArray[] = $headers[$col][1];
            array_unshift($headerArrayAlign, 'L', 'L');
        }
        if ($this->isQuantityInInvoice) {
            $col++;
            $headerArray[] = $headers[$col][1];
            array_unshift($headerArrayAlign, 'L');
        }
        if ($this->isCategoryInInvoice) {
            $col++;
            $headerArray[] = $headers[$col][1];
            array_unshift($headerArrayAlign, 'L');
        }
        if (!$this->isCustomFieldInSeparateRow) {
            for ($i = 0, $count = count($this->lotCustomFields); $i < $count; $i++) {
                $col++;
                $headerArray[] = $headers[$col][1];
                array_unshift($headerArrayAlign, 'L');
            }
        }

        if ($this->isShowSalesTax) {
            $col++;
            $headerArray[] = $headers[$col][1];
            $headerArrayAlign[] = 'R';
        }

        $headerArray[] = $headers[$col][1];
        $headerArrayAlign[] = 'R';

        $this->setWidths(
            $headerArray
        );

        $this->setAligns($headerArrayAlign);

        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(0);

        //grayish = 238,238,255
        $this->SetFillColor(238, 238, 255);
        $this->SetDrawColor(1, 61, 133);
    }

    public function renderSaleName(InvoicedAuctionDto $invoicedAuctionDto): string
    {
        $output = '';
        if ($invoicedAuctionDto->auctionId) {
            $auctionName = $invoicedAuctionDto->makeAuctionName();
            $auctionName = mb_convert_encoding($auctionName, "CP1252", "UTF-8");
            $saleNo = $invoicedAuctionDto->makeSaleNo();
            $output = $auctionName . ' (' . $saleNo . ')';
        }
        return $output;
    }

    public function makeSaleDate(
        ?DateTime $date,
        string $auctionType,
        ?int $eventType,
        int $accountId
    ): string {
        if (!$date) {
            return '';
        }

        $auctionStatusPureChecker = AuctionStatusPureChecker::new();

        if (
            $auctionStatusPureChecker->isLiveOrHybrid($auctionType)
            || $auctionStatusPureChecker->isTimedScheduled($auctionType, $eventType)
        ) {
            $dateFormat = $this->translationDto->saleDate;
            if (strrpos($dateFormat, "F") !== false) {
                $month = $date->format('F');
                $langMonth = $this->createDateRenderer()->monthTranslated((int)$month);
                $dateFormatted = $this->getDateHelper()->formattedDate($date, $accountId, null, null, $dateFormat);
                $output = str_replace($month, $langMonth, $dateFormatted);
            } else {
                $output = $this->getDateHelper()->formattedDate($date, $accountId, null, null, $dateFormat);
            }
        } else {
            $output = $this->translationDto->auctionEventType;
        }
        return $output;
    }

    public function renderBillingAddress(): string
    {
        $invoiceBidderUser = $this->getUserLoader()
            ->clear()
            ->load($this->getInvoice()->BidderId, true);
        if (!$invoiceBidderUser) {
            log_error("Available invoice winning user not found" . composeSuffix(['u' => $this->getInvoice()->BidderId]));
            return '';
        }
        $email = $invoiceBidderUser->Email;
        $bidder = '';
        $billing = '';
        $vat = '';

        $auctionBidder = $this->getAuctionBidderLoader()->load($this->getInvoice()->BidderId, $this->getAuctionId(), true);
        if (!$this->isMultipleSale && $auctionBidder) {
            $bidder = '<span class="bidder-num">' . $this->translationDto->invoiceBidderNumber
                . $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum) . '</span><br />';
        }

        $invoiceBilling = $this->getInvoiceUserLoader()
            ->loadInvoiceUserBillingOrCreate($this->getInvoiceId(), true);

        if ($invoiceBilling->CompanyName !== '') {
            $billing .= '<span class="company">' . mb_convert_encoding($invoiceBilling->CompanyName, "CP1252", "UTF-8") . '</span><br />';
        }

        $fullName = UserPureRenderer::new()->renderFullName($invoiceBilling);
        if ($fullName !== '') {
            $billing .= '<span class="name">'
                . mb_convert_encoding($invoiceBilling->FirstName, "CP1252", "UTF-8")
                . ' '
                . mb_convert_encoding($invoiceBilling->LastName, "CP1252", "UTF-8")
                . '</span><br />';
        }

        if ($invoiceBilling->Address !== '' || $invoiceBilling->Address2 !== '') {
            $billing .= '<span class="address">';

            if ($invoiceBilling->Address !== '') {
                $billing .= mb_convert_encoding($invoiceBilling->Address, "CP1252", "UTF-8");
            }

            if ($invoiceBilling->Address !== '' && $invoiceBilling->Address2 !== '') {
                $billing .= '<br />';
            }

            if ($invoiceBilling->Address2 !== '') {
                $billing .= mb_convert_encoding($invoiceBilling->Address2, "CP1252", "UTF-8");
            }

            if (($invoiceBilling->Address !== '' || $invoiceBilling->Address2 !== '') && $invoiceBilling->Address3 !== '') {
                $billing .= '<br />';
            }

            if ($invoiceBilling->Address3 !== '') {
                $billing .= mb_convert_encoding($invoiceBilling->Address3, "CP1252", "UTF-8");
            }

            $billing .= '</span><br />';
        }

        if ($invoiceBilling->City !== '') {
            $billing .= '<span class="city">' . mb_convert_encoding($invoiceBilling->City, "CP1252", "UTF-8") . '</span>, ';
        }

        $state = AddressRenderer::new()->stateName($invoiceBilling->State, $invoiceBilling->Country);
        if ($state !== '') {
            $billing .= '<span class="state">' . mb_convert_encoding($state, "CP1252", "UTF-8") . '</span> ';
        }

        if ($invoiceBilling->Zip !== '') {
            $billing .= '<span class="zip">' . $invoiceBilling->Zip . '</span>';
        }

        $country = AddressRenderer::new()->countryName($invoiceBilling->Country);
        if ($country !== '') {
            $billing .= '<br /><span class="country">' . $country . '</span> ';
        }

        if ($invoiceBilling->Phone !== '') {
            $billing .= '<br /><span class="phone">' . $invoiceBilling->Phone . '</span>';
        }

        if ($invoiceBilling->Fax !== '') {
            $billing .= '<br /><span class="fax">' . $invoiceBilling->Fax . '</span>';
        }

        if ($billing === '') {
            $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($this->getInvoice()->BidderId, true);
            $fullName = UserPureRenderer::new()->renderFullName($userInfo);
            if ($fullName !== '') {
                $billing .= '<span class="cust-name">' . $fullName . '</span>';
            }
        }

        if ($email !== '') {
            $email = '<br /><span class="email">' . $email . '</span>';
        }

        if ($this->isInvoiceIdentification) {
            $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($this->getInvoice()->BidderId, true);
            if (!$userInfo->isNoneIdentificationType()) {
                $identification = $this->getUserIdentificationTransformer()
                    ->render($userInfo->Identification, $userInfo->IdentificationType);
                $identificationType = $this->getUserRenderer()
                    ->makeIdentificationTypeTranslated($userInfo->IdentificationType);
                $vat .= '<br /><span class="vat">' . $identificationType . ': ' . $identification . '</span>';
            }
        }

        $output = $bidder . $billing . $email . $vat;

        return $output;
    }

    public function renderShippingAddress(): string
    {
        $shipping = '';
        $invoiceShipping = $this->getInvoiceUserLoader()
            ->loadInvoiceUserShippingOrCreate($this->getInvoiceId(), true);
        if ($invoiceShipping->CompanyName !== '') {
            $shipping .= '<span class="company">'
                . mb_convert_encoding($invoiceShipping->CompanyName, "CP1252", "UTF-8")
                . '</span><br />';
        }

        $fullName = UserPureRenderer::new()->renderFullName($invoiceShipping);
        if ($fullName !== '') {
            $shipping .= '<span class="name">'
                . mb_convert_encoding($fullName, "CP1252", "UTF-8")
                . '</span><br />';
        }

        if (
            $invoiceShipping->Address !== ''
            || $invoiceShipping->Address2 !== ''
        ) {
            $shipping .= '<span class="address">';

            if ($invoiceShipping->Address !== '') {
                $shipping .= mb_convert_encoding($invoiceShipping->Address, "CP1252", "UTF-8");
            }

            if (
                $invoiceShipping->Address !== ''
                && $invoiceShipping->Address2 !== ''
            ) {
                $shipping .= '<br />';
            }

            if ($invoiceShipping->Address2 !== '') {
                $shipping .= mb_convert_encoding($invoiceShipping->Address2, "CP1252", "UTF-8");
            }

            if (
                (
                    $invoiceShipping->Address !== ''
                    || $invoiceShipping->Address2 !== ''
                )
                && $invoiceShipping->Address3 !== ''
            ) {
                $shipping .= '<br />';
            }

            if ($invoiceShipping->Address3 !== '') {
                $shipping .= mb_convert_encoding($invoiceShipping->Address3, "CP1252", "UTF-8");
            }

            $shipping .= '</span><br />';
        }

        if ($invoiceShipping->City !== '') {
            $shipping .= '<span class="city">'
                . mb_convert_encoding($invoiceShipping->City, "CP1252", "UTF-8")
                . '</span>, ';
        }

        $state = AddressRenderer::new()->stateName($invoiceShipping->State, $invoiceShipping->Country);
        if ($state !== '') {
            $shipping .= '<span class="state">'
                . mb_convert_encoding($state, "CP1252", "UTF-8")
                . '</span> ';
        }

        if ($invoiceShipping->Zip !== '') {
            $shipping .= '<span class="zip">' . $invoiceShipping->Zip . '</span>';
        }

        $country = AddressRenderer::new()->countryName($invoiceShipping->Country);
        if ($country !== '') {
            $shipping .= '<br /><span class="country">' . $country . '</span> ';
        }

        if ($invoiceShipping->Phone !== '') {
            $shipping .= '<br /><span class="phone">' . $invoiceShipping->Phone . '</span>';
        }

        if ($invoiceShipping->Fax !== '') {
            $shipping .= '<br /><span class="fax">' . $invoiceShipping->Fax . '</span>';
        }


        return $shipping;
    }

    public function renderUserCustomFields(): string
    {
        $customFieldColumn = '';
        $userId = $this->getInvoice()->BidderId;
        foreach ($this->userCustomFields as $userCustomField) {
            $userCustomData = $this->getUserCustomDataLoader()->loadOrCreate(
                $userCustomField,
                $userId,
                true
            );
            $renderMethod = $this->getUserCustomFieldHelper()->makeCustomMethodName($userCustomField->Name, 'Render');
            if (method_exists($this, $renderMethod)) {
                $custData = $this->$renderMethod($userCustomField, $userCustomData);
            } else {
                $custData = ViewControls::new()
                    ->enableTranslating(true)
                    ->renderByCustData($userCustomField, $userCustomData);
            }
            $customFieldColumn .= '<span class="user-cust-name">'
                . mb_convert_encoding($userCustomField->Name, "CP1252", "UTF-8") . ': '
                . '</span>'
                . '<span> ' . $custData . '</span><br>';
        }
        return $customFieldColumn;
    }

    public function itemGrid()
    {
        //$this->Ln(1);
        $this->SetFont('Arial', '', 6);
        $headers = $this->getHeaderColArray();
        // $arrMultiCols = [4, 5, 6];
        $this->SetFont('Arial', 'B', 6);

        $this->SetFillColor(1, 61, 133);
        $this->SetTextColor(255);
        $this->SetDrawColor(1, 61, 133);
        /*foreach($headers as $col)
        {
            $this->Cell($col[1],4,$col[0],1,0,'L',true);
        }*/

        //$this->Ln();

        $columnArray = [
            $headers[0][1],
            $headers[1][1],
            $headers[2][1],
            $headers[3][1],
            $headers[4][1]
            //$headers[5][1]
        ];
        $columnValueArray = ['L', 'L', 'L', 'R', 'R'];

        $col = 5;
        if ($this->isMultipleSale) {
            $columnArray[] = $headers[5][1];
            array_unshift($columnValueArray, 'L');
            $columnArray[] = $headers[6][1];
            array_unshift($columnValueArray, 'L');
            $col = 7;
        }

        if ($this->isQuantityInInvoice) {
            $columnArray[] = $headers[$col][1];
            array_unshift($columnValueArray, 'L');
            $col++;
        }
        if ($this->isCategoryInInvoice) {
            $columnArray[] = $headers[$col][1];
            array_unshift($columnValueArray, 'L');
            $col++;
        }

        if (!$this->isCustomFieldInSeparateRow) {
            for ($i = 0, $count = count($this->lotCustomFields); $i < $count; $i++) {
                $columnArray[] = $headers[$col][1];
                array_unshift($columnValueArray, 'L');
                $col++;
            }
        }

        $subTotalWidth = 42;

        if ($this->isInvoiceItemSeparateTax) {
            $columnArray[] = $headers[$col][1];
            $columnValueArray[] = 'R';
            $col++;
            $subTotalWidth -= 22;
            $columnArray[] = $headers[$col][1];
            $columnValueArray[] = 'R';
        }

        if ($this->isShowSalesTax && !$this->isInvoiceItemSeparateTax) {
            $columnArray[] = 20;
            $columnValueArray[] = 'R';
            $subTotalWidth -= 20;
        }

        $columnArray[] = $subTotalWidth;
        $columnValueArray[] = 'R';

        $this->setWidths($columnArray);
        $this->setAligns($columnValueArray);

        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(0);
        $invoiceItemDtos = $this->getInvoiceItemLoader()->loadDtos($this->getInvoiceId(), true);

        $rowCount = 0;
        $this->SetFillColor(238, 238, 255);
        $this->SetDrawColor(1, 61, 133);
        $totalHeaderLength = 0;
        foreach ($headers as $header) {
            $totalHeaderLength += $header[1];
        }

        foreach ($invoiceItemDtos as $invoiceItemDto) {
            $rowCount++;
            $isFill = $rowCount % 2 === 1;

            if ($this->y >= 210) {
                $this->AddPage();
                $this->setWidths($columnArray);
                $this->setAligns($columnValueArray);
            }

            $columnValue = [
                $this->renderLotNo($invoiceItemDto),
                $this->renderItemNo($invoiceItemDto),
                $this->renderLotName($invoiceItemDto),
            ];

            if ($this->isMultipleSale) {
                $saleDate = $invoiceItemDto->detectAuctionDate();
                $saleDate = $this->getDateHelper()->convertUtcToTzById($saleDate, $invoiceItemDto->auctionTimezoneId);
                $saleDateFormatted = $this->makeSaleDate(
                    $saleDate,
                    $invoiceItemDto->auctionType,
                    $invoiceItemDto->eventType,
                    $invoiceItemDto->accountId
                );
                array_unshift(
                    $columnValue,
                    $this->renderSaleColumn($invoiceItemDto),
                    $saleDateFormatted
                );
            }
            if ($this->isQuantityInInvoice) {
                $columnValue[] = $this->renderQuantity($invoiceItemDto);
            }
            if ($this->isCategoryInInvoice) {
                $columnValue[] = $this->renderCategory($invoiceItemDto);
            }
            $lotCustomFieldsData = [];
            foreach ($this->lotCustomFields as $lotCustomField) {
                $lotCustomData = $this->createLotCustomDataLoader()->load($lotCustomField->Id, $invoiceItemDto->lotItemId, true);

                $lotCustomDataValue = '';
                if (
                    $lotCustomData
                    && $lotCustomField->isNumeric()
                ) {
                    if ($lotCustomData->Numeric === null) {
                        $lotCustomDataValue = '';
                    } else {
                        if ($lotCustomField->Type === Constants\CustomField::TYPE_DATE) {
                            $date = (new DateTime())->setTimestamp((int)$lotCustomData->Numeric);
                            $date = $this->getDateHelper()->convertUtcToSys($date);
                            $lotCustomDataValue = $this->getDateHelper()->formattedDate($date, $this->getInvoice()->AccountId);
                            unset($date);
                        } elseif ($lotCustomField->Type === Constants\CustomField::TYPE_DECIMAL) {
                            $precision = (int)$lotCustomField->Parameters;
                            $value = $lotCustomData->calcDecimalValue($precision);
                            $lotCustomDataValue = $this->getNumberFormatter()->format($value, $precision);
                        } else {
                            $lotCustomDataValue = $lotCustomData->Numeric;
                        }
                    }
                } elseif ($lotCustomData) { //text
                    if (
                        $lotCustomField->Type === Constants\CustomField::TYPE_TEXT
                        && $lotCustomField->Barcode
                        && $lotCustomData->Text !== ''
                    ) { // Barcode
                        $lotCustomDataValue = $this->normalizeBarcodeColumnValue($lotCustomData, $lotCustomField->BarcodeType);
                    } else {
                        $lotCustomData->Text = strip_tags($lotCustomData->Text);
                        $lotCustomDataValue = $lotCustomData->Text;
                    }
                }
                if (!$this->isCustomFieldInSeparateRow) {
                    $columnValue[] = $lotCustomDataValue;
                }
                if ($lotCustomDataValue) {
                    $lotCustomFieldsData[] = $lotCustomField->Name . ' : ' . $lotCustomDataValue;
                }
            }

            array_push(
                $columnValue,
                $this->renderHammerPrice($invoiceItemDto),
                $this->renderBuyersPremium($invoiceItemDto)
            );

            if ($this->isInvoiceItemSeparateTax) {
                $columnValue[] = $this->renderTaxOnGoodsCol($invoiceItemDto);
                $columnValue[] = $this->renderTaxOnServicesCol($invoiceItemDto);
            }

            if ($this->isShowSalesTax && !$this->isInvoiceItemSeparateTax) {
                $columnValue[] = $this->renderSalesTaxCol($invoiceItemDto);
            }

            $columnValue[] = $this->renderSubTotal($invoiceItemDto);

            $this->row(
                $columnValue,
                true,
                $isFill,
                4
            );
            if ($this->isCustomFieldInSeparateRow) {
                $lotItemCustomFieldsRowData = implode("\n", $lotCustomFieldsData);
                $rowHeight = count($lotCustomFieldsData) + 1;
                if ($lotItemCustomFieldsRowData) {
                    $this->MultiCell($totalHeaderLength, $rowHeight, $lotItemCustomFieldsRowData, 1, 'L');
                }
            }
        }

        //grid footer
        $this->SetFont('Arial', 'B', 6);

        $this->Cell($headers[0][1], 4, '', 0, 0, 'L'); // Lot num
        $this->Cell($headers[1][1], 4, '', 0, 0, 'L'); // Item num
        $this->Cell($headers[2][1], 4, '', 0, 0, 'L'); // Name
        $col = 3;

        if ($this->isMultipleSale) {
            $this->Cell($headers[3][1], 4, '', 0, 0, 'L'); // Sale
            $this->Cell($headers[4][1], 4, '', 0, 0, 'L'); // Sale date
            $col = 5;
        }
        if ($this->isQuantityInInvoice) {
            $this->Cell($headers[$col][1], 4, '', 0, 0, 'L'); // Quantity
            $col++;
        }
        if ($this->isCategoryInInvoice) {
            $this->Cell($headers[$col][1], 4, '', 0, 0, 'L'); // Category
            $col++;
        }
        if (!$this->isCustomFieldInSeparateRow) {
            for ($i = 0, $count = count($this->lotCustomFields); $i < $count; $i++) {
                $this->Cell($headers[$col][1], 4, '', 0, 0, 'L'); // Category
                $col++;
            }
        }

        $this->Ln(0);
    }

    /**
     * Build barcode image url, and check headers for this url.
     * If response code is 200, and it's a jpg image - will return image url, if not - will return string 'invalid barcode'
     * We need to do this normalization for avaoid of PDF library errors, related with getting
     * image size for unexisted(incorrect url) image (incorrect urls).
     * Now we make sure, that only valid image urls will be processed by PDF library.
     *
     * @param LotItemCustData $lotCustomData
     * @param int $barcodeType
     * @return string
     */
    protected function normalizeBarcodeColumnValue(LotItemCustData $lotCustomData, int $barcodeType): string
    {
        $urlConfig = BarcodeUrlConfig::new()->forWeb($lotCustomData->Text, $barcodeType);
        $barcodeImageUrl = $this->getUrlBuilder()->build($urlConfig);
        $barcodeImageUrl = $this->getUrlParser()->replaceScheme($barcodeImageUrl, 'http');
        $barcodeImgHeaders = get_headers($barcodeImageUrl, true);
        if (
            stripos($barcodeImgHeaders[0], '200 ok') !== false
            && $barcodeImgHeaders['Content-Type'] === 'image/jpg'
        ) {
            return $barcodeImageUrl;
        }
        return 'invalid barcode';
    }

    public function summaryRows(): void
    {
        if ($this->y >= 250) {
            $this->AddPage();
        }

        $invoiceTaxCalculator = $this->createLegacyInvoiceTaxCalculator();
        $currency = $this->currencySign;
        $headers = $this->getHeaderColArray();

        //grid footer
        $this->SetFont('Arial', '', 6);

        $columnValueArray = ['L', 'L', 'L', 'R', 'R'];

        $notesWidth = $headers[0][1] + $headers[1][1] + $headers[2][1] + $headers[3][1] + $headers[4][1];
        $col = 5;
        if ($this->isMultipleSale) {
            $notesWidth += $headers[5][1];
            array_unshift($columnValueArray, 'L');
            $notesWidth += $headers[6][1];
            array_unshift($columnValueArray, 'L');
            $col = 7;
            // $intMinusWidth = 1;
        }

        if ($this->isQuantityInInvoice) {
            $notesWidth += $headers[$col][1];
            array_unshift($columnValueArray, 'L');
            $col++;
        }
        if ($this->isCategoryInInvoice) {
            $notesWidth += $headers[$col][1];
            array_unshift($columnValueArray, 'L');
            $col++;
        }

        /** @noinspection PhpUnusedLocalVariableInspection */
        if (!$this->isCustomFieldInSeparateRow) {
            foreach ($this->lotCustomFields as $lotCustomField) {
                $notesWidth += $headers[$col][1];
                array_unshift($columnValueArray, 'L');
                $col++;
            }
        }

        // $subTotalWidth = 42;

        if ($this->isInvoiceItemSeparateTax) {
            $columnValueArray[] = 'R';
            $col++;
            $columnValueArray[] = 'R';
            $col++;
            // $subTotalWidth = $subTotalWidth - 22;
            $notesWidth += 40;
        }

        if ($this->isShowSalesTax && !$this->isInvoiceItemSeparateTax) {
            $columnValueArray[] = 'R';
            $col++;
            // $subTotalWidth = $subTotalWidth - 20;
            $notesWidth += 20;
        }

        $columnValueArray[] = 'R';

        $labelsWidth = $headers[$col - 1][1] + $headers[$col - 2][1] + $headers[$col - 3][1];
        // $headers[$intCol][1] = 20;

        //display cash discount if greater than 0
        if (
            Floating::gt($this->cashDiscount, 0)
            && $this->getInvoice()->CashDiscount
        ) {
            $this->Cell($notesWidth - $labelsWidth, 4, '', 'L', '0', 'L');
            $this->Cell($labelsWidth, 4, $this->translationDto->cashDiscount . ' (' . $this->cashDiscount . '%):', 1, 0, 'R');
            $this->Cell($headers[$col][1], 4, $currency . $this->renderCashDiscountAmount(), 1, 0, 'R');
            $this->Ln();
        }

        $this->Cell($notesWidth - $labelsWidth, 4, '', 'L', 0, 'L');
        //$this->Cell($intLabelsWidth,4,$this->translationDto->subTotal . ' [' . $strCurrency . ']:',0,0,'R');
        $this->Cell($labelsWidth, 4, $this->translationDto->subTotal . ':', 1, 0, 'R');
        $this->Cell($headers[$col][1], 4, $currency . $this->renderTotalSub(), 1, 0, 'R');
        $this->Ln();

        //$this->Cell($headers[$intCol][1],4,'',0,0,'L');
        //$this->Cell($headers[$intCol][1],4,$this->translationDto->subTotal . ' [' . $this->strCurrency . ']',0,0,'R');
        //$this->Cell($headers[$intCol][1],4,$this->renderTotalSub(),0,0,'R');

        //MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false)
        $this->Cell($notesWidth - $labelsWidth, 4, '', 'L', 0, 'L');
        //$this->Cell($intLabelsWidth,4,$this->translationDto->shipping . ' [' . $strCurrency . ']:',0,0,'R');
        $this->Cell($labelsWidth, 4, $this->translationDto->shipping . ':', 1, 0, 'R');
        $this->Cell($headers[$col][1], 4, $currency . $this->renderShippingAmount(), 1, 0, 'R');
        $this->Ln();

        //additional charges
        if (count($this->invoiceAdditionals) > 0) {
            $this->Cell($notesWidth - $labelsWidth, 4, '', 'L', 0, 'L');
            $this->SetFont('Arial', 'B', 6);
            //$this->Cell($intLabelsWidth,4,$this->translationDto->extraCharges.' ['.$strCurrency.']:',0,0,'R');
            $this->Cell($labelsWidth, 4, $this->translationDto->extraCharges . ':', 1, 0, 'R');
            $this->SetFont('Arial', '', 6);
            $this->Cell($headers[$col][1], 4, '', 1, 0, 'R');
            $this->Ln();

            foreach ($this->invoiceAdditionals as $invoiceAdditional) {
                $this->Cell($notesWidth - $labelsWidth, 4, '', 'L', '0', 'L');
                $this->Cell($labelsWidth, 4, $invoiceAdditional->Name, 1, 0, 'R');
                $this->Cell($headers[$col][1], 4, $currency . $this->getNumberFormatter()->formatMoney($invoiceAdditional->Amount), 1, 0, 'R');
                $this->Ln();
            }
        }
        if (!$this->isInvoiceItemSeparateTax) {
            //Sales Tax
            //MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false)
            $salesTax = $invoiceTaxCalculator->calcTotalSalesTaxApplied($this->getInvoiceId());
            $salesTax = $this->getNumberFormatter()->formatMoney($salesTax);

            $this->SetFont('Arial', 'B', 6);
            $this->Cell($notesWidth - $labelsWidth, 4, '', 'L', 0, 'L');
            //$this->Cell($intLabelsWidth,4,$this->translationDto->salesTax.' ['.$strCurrency.']:',0,0,'R');
            $this->Cell($labelsWidth, 4, $this->translationDto->salesTax . ':', 1, 0, 'R');
            $this->Cell($headers[$col][1], 4, $currency . $salesTax, 1, 0, 'R');
            $this->Ln();
        } else {
            //Tax on goods
            $taxOnGoods = $invoiceTaxCalculator->calcTotalTaxOnGoods($this->getInvoiceId());
            $taxOnGoodsFormatted = $this->getNumberFormatter()->formatMoney($taxOnGoods);
            $this->SetFont('Arial', 'B', 6);
            $this->Cell($notesWidth - $labelsWidth, 4, '', 'L', 0, 'L');
            //$this->Cell($intLabelsWidth,4,$this->translationDto->salesTax.' ['.$strCurrency.']:',0,0,'R');
            $this->Cell($labelsWidth, 4, $this->translationDto->taxOnGoods . ':', 1, 0, 'R');
            $this->Cell($headers[$col][1], 4, $currency . $taxOnGoodsFormatted, 1, 0, 'R');
            $this->Ln();
            //Tax on services
            $taxOnServices = $invoiceTaxCalculator->calcTotalTaxOnServices($this->getInvoiceId());
            $taxOnServicesFormatted = $this->getNumberFormatter()->formatMoney($taxOnServices);
            $this->SetFont('Arial', 'B', 6);
            $this->Cell($notesWidth - $labelsWidth, 4, '', 'L', 0, 'L');
            //$this->Cell($intLabelsWidth,4,$this->translationDto->salesTax.' ['.$strCurrency.']:',0,0,'R');
            $this->Cell($labelsWidth, 4, $this->translationDto->taxOnServices . ':', 1, 0, 'R');
            $this->Cell($headers[$col][1], 4, $currency . $taxOnServicesFormatted, 1, 0, 'R');
            $this->Ln();
        }
        $this->Cell($notesWidth - $labelsWidth, 4, '', 'L', 0, 'L');
        $this->SetFont('Arial', 'B', 6);
        $this->Cell($labelsWidth, 4, $this->translationDto->total . ':', 1, 0, 'R');
        $this->Cell($headers[$col][1], 4, $currency . $this->renderTotalAmount(), 1, 0, 'R');
        $this->SetFont('Arial', '', 6);
        $this->Ln();

        $timezone = $this->getTimezoneLoader()->load($this->timezoneId, true);
        //display if there are payments
        if (count($this->payments) > 0) {
            $this->Cell($notesWidth - $labelsWidth, 4, '', 'L', 0, 'L');
            $this->SetFont('Arial', 'B', 6);
            $this->Cell($labelsWidth, 4, $this->translationDto->paymentMade . ':', 1, 0, 'R');
            $this->SetFont('Arial', '', 6);
            $this->Cell($headers[$col][1], 4, '', 1, 0, 'R');
            $this->Ln();

            foreach ($this->payments as $invoiceAdditional) {
                $note = '';
                if ($invoiceAdditional->Note) {
                    $note = '(' . $invoiceAdditional->Note . ')';
                }

                $date = $invoiceAdditional->PaidOn ?: new DateTime($invoiceAdditional->CreatedOn);
                $date = $this->getDateHelper()->convertUtcToTz($date, $timezone);
                $dateFormatted = ' ' . $this->getDateHelper()
                        ->formattedDate($date, null, $timezone->Location ?? null, null, 'm/d/Y h:i a');

                $this->Cell($notesWidth - $labelsWidth, 4, '', 'L', 0, 'L');
                $langPaymentMethodName = $this->getPaymentRenderer()->makePaymentMethodTranslated($invoiceAdditional->PaymentMethodId);
                if (
                    $invoiceAdditional->CreditCardId
                    && $creditCard = $this->getCreditCardLoader()->load($invoiceAdditional->CreditCardId, true)
                ) {
                    $paymentSeparator = " - ";
                    $langPaymentMethodName .= $paymentSeparator . $creditCard->Name;
                }

                $this->Cell($labelsWidth, 4, $langPaymentMethodName . $note . $dateFormatted, 1, 0, 'R');
                $this->Cell($headers[$col][1], 4, $currency . $this->getNumberFormatter()->formatMoney($invoiceAdditional->Amount), 1, 0, 'R');
                $this->Ln();
            }
        }

        $this->Cell($notesWidth - $labelsWidth, 4, '', 'LB', 0, 'L');
        $this->SetFont('Arial', 'B', 8);
        $this->Cell($labelsWidth, 4, $this->translationDto->balance . ':', 1, 0, 'R');
        $this->Cell($headers[$col][1], 4, $currency . $this->renderBalanceAmount(), 1, 0, 'R');
        $this->Ln();
        $this->SetFont('Arial', '', 6);
    }

    public function summaryNote(): void
    {
        if ($this->y >= 250) {
            $this->AddPage();
        }

        //grid footer
        $this->SetFont('Arial', '', 6);

        $notesWidth = 185;

        $this->Ln(5);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell($notesWidth, 4, $this->translationDto->notes, 0, 0, 'L');
        $this->Ln();

        $this->SetFont('Arial', '', 8);
        //MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false)
        $this->multiCellWithPageBreak($notesWidth, 4, mb_convert_encoding($this->getInvoice()->Note, "CP1252", "UTF-8"), 0, 'L');
        $this->Ln();
    }

    public function getHeaderColArray(): array
    {
        $minusWidth = 0;

        if ($this->isMultipleSale) {
            $headers = [
                [$this->translationDto->sale, 9],       //'Sale '
                [$this->translationDto->detailSaleDate, 13],   //'Sale date'
                [$this->translationDto->saleNum, 15]        //'Sale # / Lot #'
            ];

            $minusWidth = 1;
        } else {
            $lotNumWidth = 15;
            /** @noinspection PhpUnusedLocalVariableInspection */
            foreach ($this->lotCustomFields as $lotCustomField) {
                $lotNumWidth -= 2;
            }
            $headers = [
                [$this->translationDto->lotNum, $lotNumWidth] //'Lot #'
            ];
        }

        $itemNumWidth = 19;
        $itemNameWidth = 32;
        /** @noinspection PhpUnusedLocalVariableInspection */
        foreach ($this->lotCustomFields as $lotCustomField) {
            $itemNumWidth -= 2;
            $itemNameWidth -= 3;
        }
        array_push(
            $headers,
            [$this->translationDto->itemNum, $itemNumWidth - ($minusWidth ? 5 : 0)],  //'Item #'
            [$this->translationDto->itemName, $itemNameWidth - ($minusWidth ? 10 : 0)]  //'Item name'
        );

        $hammerWidth = 30;
        $buyersPremiumWidth = 20;
        if ($this->isQuantityInInvoice) {
            $headers[] = [$this->translationDto->quantity, 10 - ($minusWidth ? 3 : 0)];
            $hammerWidth -= 5;
            $buyersPremiumWidth -= 5;
        }

        if ($this->isCategoryInInvoice) {
            $headers[] = [$this->translationDto->category, 12];
            $hammerWidth -= 6;
            $buyersPremiumWidth -= 6;
        }
        if (!$this->isCustomFieldInSeparateRow) {
            foreach ($this->lotCustomFields as $lotCustomField) {
                $widths = [12, 6, 2];
                if ($lotCustomField->Type === Constants\CustomField::TYPE_TEXT) { // Barcode
                    $widths = [14, 6, 2];
                    $hammerWidth -= 1;
                    $buyersPremiumWidth -= 1;
                }

                $headers[] = [mb_convert_encoding($lotCustomField->Name, "CP1252", "UTF-8"), $widths[0]];

                $hammerWidth -= 2.5;
                $buyersPremiumWidth -= 2.5;
            }
        }
        array_push(
            $headers,
            [$this->translationDto->hammerPrice, $hammerWidth - ($minusWidth ? 3 : 0)],
            [$this->translationDto->buyersPremium, $buyersPremiumWidth]

        //array($this->_strSubTotal.' [' . $this->strCurrency . ']',23)
        );

        $subTotalWidth = 42;

        if ($this->isInvoiceItemSeparateTax) {
            array_push(
                $headers,
                [$this->translationDto->taxOnGoods, 20],
                [$this->translationDto->taxOnServices, 20]
            );
            $subTotalWidth -= 22;
        }

        if (
            $this->isShowSalesTax
            && !$this->isInvoiceItemSeparateTax
        ) {
            $headers[] = [$this->translationDto->salesTax, 20];
            $subTotalWidth -= 20;
        }

        $headers[] = [$this->translationDto->subTotal, $subTotalWidth];

        return $headers;
    }

    public function renderSalesTaxCol(InvoiceItemDto $invoiceItem): string
    {
        $salesTax = InvoiceTaxPureCalculator::new()->calcSalesTaxApplied(
            $invoiceItem->hammerPrice,
            $invoiceItem->buyersPremium,
            $invoiceItem->salesTaxPercent,
            $invoiceItem->taxApplication
        );
        $salesTaxPercent = $this->getNumberFormatter()->formatPercent($invoiceItem->salesTaxPercent);
        $salesTaxFormatted = $this->getNumberFormatter()->formatMoney($salesTax);
        return "$salesTaxFormatted ($salesTaxPercent%)";
    }

    public function renderTaxOnGoodsCol(InvoiceItemDto $invoiceItem): string
    {
        $taxOnGoods = InvoiceTaxPureCalculator::new()->calcSalesTaxAppliedOnGoods(
            $invoiceItem->hammerPrice,
            $invoiceItem->salesTaxPercent,
            $invoiceItem->taxApplication
        );
        $taxOnGoodsFormatted = $this->getNumberFormatter()->formatMoney($taxOnGoods);
        return $taxOnGoodsFormatted;
    }

    public function renderTaxOnServicesCol(InvoiceItemDto $invoiceItem): string
    {
        $taxOnServices = InvoiceTaxPureCalculator::new()->calcSalesTaxAppliedOnServices(
            $invoiceItem->buyersPremium,
            $invoiceItem->salesTaxPercent,
            $invoiceItem->taxApplication
        );
        $taxOnServicesFormatted = $this->getNumberFormatter()->formatMoney($taxOnServices);
        return $taxOnServicesFormatted;
    }

    public function renderLogoImage(): string
    {
        $output = '';
        $invoice = $this->getInvoice();
        $invoiceLogoPathResolver = $this->createInvoiceLogoPathResolver();
        $fileRootPath = $invoiceLogoPathResolver->resolveFileRootPath($invoice);
        if (file_exists($fileRootPath)) {
            [, $height] = @getimagesize($fileRootPath);
            if ($height > 70) {
                $height = 70;
            }
            $url = $invoiceLogoPathResolver->buildUrlByInvoice($invoice);
            $urlParser = $this->getUrlParser();
            /**
             * YV, SAM-7705. We need 'http' scheme here for make sure that FPDF can parse image size properly:
             * @see \FPDF::_parsejpg  --- getimagesize($file);
             * For 'https' urls it returns error like:
             *      "getimagesize(): SSL operation failed with code 1. OpenSSL Error messages:
             *      error:1416F086:SSL routines:tls_process_server_certificate:certificate verify failed"
             */
            $url = $urlParser->replaceScheme($url, 'http');

            if (path()->imagePrefix($invoice->AccountId)) {
                // We need url with '?ts=<number>' request param here (timestamp)
                $tsParam = $urlParser->extractParam($url, 'ts');
                $url = $urlParser->replaceParams($url, ['ts' => $tsParam]);
            } else {
                $url = $urlParser->removeQueryString($url);
            }

            $output = HtmlRenderer::new()->makeImgHtmlTag('img', ['src' => $url, 'height' => $height]);
        }
        return $output;
    }

    public function renderSaleColumn(InvoiceItemDto $invoiceItemDto): string
    {
        return ($invoiceItemDto->auctionId && $invoiceItemDto->auctionName)
            ? mb_convert_encoding($invoiceItemDto->auctionName, "CP1252", "UTF-8")
            : '';
    }

    public function renderLotNo(InvoiceItemDto $invoiceItem): string
    {
        if ($this->isMultipleSale) {
            $saleNo = $invoiceItem->makeSaleNo();
            if ($invoiceItem->lotItemId) {
                $lotNo = $invoiceItem->makeLotNo();
                return "$saleNo / $lotNo";
            }
        } elseif ($invoiceItem->lotItemId) {
            return $invoiceItem->makeLotNo();
        }
        return '';
    }

    public function renderItemNo(InvoiceItemDto $invoiceItem): string
    {
        $lotItem = $this->getLotItemLoader()->load($invoiceItem->lotItemId, true);
        if ($lotItem) {
            return $this->getLotRenderer()->renderItemNo($lotItem);
        }
        return '';
    }

    public function renderLotName(InvoiceItemDto $invoiceItem): string
    {
        $lotName = $invoiceItem->makeLotName();
        return mb_convert_encoding($lotName, "CP1252", "UTF-8");
    }

    public function renderCategory(InvoiceItemDto $invoiceItem): string
    {
        $categoriesText = $this->getLotCategoryRenderer()->getCategoriesText($invoiceItem->lotItemId);
        $categoriesText = mb_convert_encoding($categoriesText, "CP1252", "UTF-8");
        return $categoriesText;
    }

    public function renderQuantity(InvoiceItemDto $invoiceItem): string
    {
        if (Floating::lteq($invoiceItem->quantity, 0, $invoiceItem->quantityScale)) {
            return '-';
        }
        $quantity = $this->lotAmountRenderer->makeQuantity($invoiceItem->quantity, $invoiceItem->quantityScale);
        return $quantity;
    }

    public function renderHammerPrice(InvoiceItemDto $invoiceItem): string
    {
        $hammer = $invoiceItem->hammerPrice;
        $hammer = $this->currencySign . $this->getNumberFormatter()->formatMoney($hammer);
        return $hammer;
    }

    public function renderBuyersPremium(InvoiceItemDto $invoiceItem): string
    {
        $premium = $invoiceItem->buyersPremium;
        $premium = $this->currencySign . $this->getNumberFormatter()->formatMoney($premium);
        return $premium;
    }

    public function renderSubTotal(InvoiceItemDto $invoiceItem): string
    {
        $hammer = $invoiceItem->hammerPrice;
        $premium = $invoiceItem->buyersPremium;

        $sub = $hammer + $premium;
        $sub = $this->currencySign . $this->getNumberFormatter()->formatMoney($sub);
        return $sub;
    }

    public function renderTotalSub(): string
    {
        $output = '';
        if ($this->getInvoice()) {
            $totalSub = $this->getLegacyInvoiceCalculator()->calcSubTotal($this->getInvoiceId());
            $totalSub = $this->getNumberFormatter()->formatMoney($totalSub);
            $output = $totalSub;
        }
        return $output;
    }

    public function renderCashDiscountAmount(): string
    {
        $cashDiscountAmount = $this->getLegacyInvoiceCalculator()
            ->calcCashDiscount($this->getInvoiceId(), $this->getInvoice()->CashDiscount);
        $cashDiscountAmountFormatted = $this->getNumberFormatter()->formatMoney($cashDiscountAmount);
        return $cashDiscountAmountFormatted;
    }

    public function renderShippingAmount(): string
    {
        return $this->getNumberFormatter()->formatMoney($this->getInvoice()->Shipping);
    }

    public function renderTotalAmount(): string
    {
        $totalAmount = $this->getLegacyInvoiceCalculator()->calcGrandTotal($this->getInvoiceId());
        $totalAmountFormatted = $this->getNumberFormatter()->formatMoney($totalAmount);
        return $totalAmountFormatted;
    }

    public function renderBalanceAmount(): string
    {
        $balanceDue = $this->getLegacyInvoiceCalculator()->calcRoundedBalanceDue($this->getInvoiceId());
        $balanceDueFormatted = $this->getNumberFormatter()->formatMoney($balanceDue);
        return $balanceDueFormatted;
    }

    public function br2nl(string $string): string
    {
        $return = (string)preg_replace('/<br\s*\/?\s*>/i', chr(13) . chr(10), $string);
        return $return;
    }

    /**
     * Added page break checking
     * @param int $w width
     * @param int $h height
     * @param string $txt
     * @param int $border
     * @param string $align
     * @param bool $fill
     * @return void
     */
    public function multiCellWithPageBreak(int $w, int $h, string $txt, int $border = 0, string $align = 'J', bool $fill = false): void
    {
        //Output text with automatic or explicit line breaks
        $cw =& $this->CurrentFont['cw'];
        if ($w === 0) {
            $w = $this->w - $this->rMargin - $this->x;
        }
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] === "\n") {
            $nb--;
        }
        $b = 0;
        if ($border) {
            if (
                is_numeric($border)
                && (int)$border === 1
            ) {
                $border = 'LTRB';
                $b = 'LRT';
            } else {
                $b2 = '';
                if (str_contains((string)$border, 'L')) {
                    $b2 .= 'L';
                }
                if (str_contains((string)$border, 'R')) {
                    $b2 .= 'R';
                }
                $b = (str_contains((string)$border, 'T')) ? $b2 . 'T' : $b2;
            }
        }
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $ns = 0;
        $nl = 1;
        $ls = 0;
        $b2 = 0;
        while ($i < $nb) {
            //Get next character
            $c = $s[$i];
            if ($c === "\n") {
                //Explicit line break
                if ($this->ws > 0) {
                    $this->ws = 0;
                    $this->_out('0 Tw');
                }
                $this->Cell($w, $h, substr($s, $j, $i - $j), $b, 2, $align, $fill);
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
                $nl++;
                if ($border && $nl === 2) {
                    $b = $b2;
                }
                continue;
            }
            if ($c === ' ') {
                $sep = $i;
                $ls = $l;
                $ns++;
            }
            $l += $cw[$c];
            if ($l > $wmax) {
                //Automatic line break
                if ($sep === -1) {
                    if ($i === $j) {
                        $i++;
                    }
                    if ($this->ws > 0) {
                        $this->ws = 0;
                        $this->_out('0 Tw');
                    }
                    $this->Cell($w, $h, substr($s, $j, $i - $j), $b, 2, $align, $fill);
                } else {
                    if ($align === 'J') {
                        $this->ws = ($ns > 1) ? ($wmax - $ls) / 1000 * $this->FontSize / ($ns - 1) : 0;
                        $this->_out(sprintf('%.3F Tw', $this->ws * $this->k));
                    }
                    $this->Cell($w, $h, substr($s, $j, $sep - $j), $b, 2, $align, $fill);
                    $i = $sep + 1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
                $nl++;
                if ($border && $nl === 2) {
                    $b = $b2;
                }
            } else {
                $i++;
            }

            if ($this->y >= 260) {
                $this->AddPage();
            }
        }
        //Last chunk
        if ($this->ws > 0) {
            $this->ws = 0;
            $this->_out('0 Tw');
        }
        if ($border && str_contains($border, 'B')) {
            $b .= 'B';
        }
        $this->Cell($w, $h, substr($s, $j, $i - $j), $b, 2, $align, $fill);
        $this->x = $this->lMargin;
    }

    public function Image($file, $x = null, $y = null, $w = 0, $h = 0, $type = '', $link = '')
    {
        if (!path()->imagePrefix($this->getInvoice()->AccountId)) {
            parent::Image($file, $x, $y, $w, $h, $type, $link);
            return;
        }

        // Put an image on the page
        if ($file === '') {
            $this->Error('Image file name is empty');
        }

        $info = $this->fetchImageInfo($file, $type);

        // Automatic width and height calculation if needed
        if ($w == 0 && $h == 0) {
            // Put image at 96 dpi
            $w = -96;
            $h = -96;
        }
        if ($w < 0) {
            $w = -$info['w'] * 72 / $w / $this->k;
        }
        if ($h < 0) {
            $h = -$info['h'] * 72 / $h / $this->k;
        }
        if ($w == 0) {
            $w = $h * $info['w'] / $info['h'];
        }
        if ($h == 0) {
            $h = $w * $info['h'] / $info['w'];
        }

        // Flowing mode
        if ($y === null) {
            if ($this->y + $h > $this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak()) {
                // Automatic page break
                $x2 = $this->x;
                $this->AddPage($this->CurOrientation, $this->CurPageSize, $this->CurRotation);
                $this->x = $x2;
            }
            $y = $this->y;
            $this->y += $h;
        }

        if ($x === null) {
            $x = $this->x;
        }
        $this->_out(sprintf('q %.2F 0 0 %.2F %.2F %.2F cm /I%d Do Q', $w * $this->k, $h * $this->k, $x * $this->k, ($this->h - ($y + $h)) * $this->k, $info['i']));
        if ($link) {
            $this->Link($x, $y, $w, $h, $link);
        }
    }

    protected function fetchImageInfo(string $file, string $type): array
    {
        if (isset($this->images[$file])) {
            return $this->images[$file];
        }

        // First use of this image, get info
        $type = $this->normalizeImageType($file, $type);
        $mtd = '_parse' . $type;
        if (!method_exists($this, $mtd)) {
            $this->Error('Unsupported image type: ' . $type);
        }
        $info = $this->$mtd($file);
        $info['i'] = count($this->images) + 1;
        $this->images[$file] = $info;

        return $info;
    }

    protected function normalizeImageType(string $file, string $type): string
    {
        if ($type === '') {
            $pos = strrpos($file, '.');
            if (!$pos) {
                $this->Error('Image file has no extension and no type was specified: ' . $file);
            }
            $type = substr($file, $pos + 1);
            // this adjustments need to successfully parse type from url with GET query params
            $typeExp = explode('?', $type);
            $type = $typeExp[0];
        }

        $type = strtolower($type);
        if ($type === 'jpeg') {
            $type = 'jpg';
        }

        return $type;
    }

    protected function hex2dec(string $color = "#000000"): array
    {
        $r = substr($color, 1, 2);
        $red = hexdec($r);

        $g = substr($color, 3, 2);
        $green = hexdec($g);

        $b = substr($color, 5, 2);
        $blue = hexdec($b);

        $tblColor = [];
        $tblColor['R'] = $red;
        $tblColor['G'] = $green;
        $tblColor['B'] = $blue;

        return $tblColor;
    }

    //conversion pixel -> millimeter at 72 dpi
    protected function px2mm($px): float
    {
        return $px * 25.4 / 72;
    }

    protected function txtEntities(string $html): string
    {
        $trans = get_html_translation_table(HTML_ENTITIES);
        $trans = array_flip($trans);
        return strtr($html, $trans);
    }
}
