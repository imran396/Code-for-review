<?php
/**
 * SAM-7661: Settlement Printable & viewable version to the new layout Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           02-21, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Settlement\Printable\Internal\Translation;


use Sam\Core\Service\CustomizableClass;

/**
 * Class SettlementTranslatorDto
 * @package Sam\Settlement\Printable
 */
class SettlementTranslatorDto extends CustomizableClass
{
    public string $itemNumHeader = '';
    public string $itemNameHeader = '';
    public string $lotHeader = '';
    public string $saleHeader = '';
    public string $estimateHeader = '';
    public string $saleNameHeader = '';
    public string $hammerHeader = '';
    public string $feeHeader = '';
    public string $taxOnHPHeader = '';
    public string $taxOnCommHeader = '';
    public string $commissionHeader = '';
    public string $subtotalHeader = '';
    public string $quantityHeader = '';
    public string $subtotalLbl = '';
    public string $unpaidLotsLbl = '';
    public string $paidLotsLbl = '';
    public string $commissionLbl = '';
    public string $totalLbl = '';
    public string $extraChargeLbl = '';
    public string $paymentsMadeLbl = '';
    public string $balanceDueLbl = '';
    public string $commissionSubtotalLbl = '';
    public string $taxExcLbl = '';
    public string $taxIncLbl = '';
    public string $notesLbl = '';
    public string $summaryLbl = '';
    public string $subSummaryLbl = '';
    public string $consignorSettlementLbl = '';
    public string $dateCreatedLbl = '';
    public string $statusLbl = '';
    public string $userCustomFieldsLbl = '';
    public string $userCustomerNumberLbl = '';
    public string $consignorInformationLbl = '';
    public string $saleLbl = '';
    public string $saleDateLbl = '';
    public string $saleDateFormat = '';

    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
