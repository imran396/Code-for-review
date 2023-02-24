<?php
/**
 * Data transfer object for passing input data for User entity creating/updating/validating
 * Dto does not have to accurately describe the fields of the entity, it describes the incoming data from the external interface
 *
 * SAM-10375: Input DTO adjustments and fixes for v3-7
 * SAM-8841: User entity-maker module structural adjustments for v3-5
 * SAM-4989: User Entity Maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           May 22, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &ltinfo@bidpath.com&gt
 */

namespace Sam\EntityMaker\User\Dto;

use Sam\EntityMaker\Base\Dto\InputDto;

/**
 * @package Sam\EntityMaker\User
 * @property string|null $accountId
 * @property string|null $additionalBpInternetHybrid
 * @property string|null $admin
 * @property string|null $adminSalesCommissionStepdown
 * @property string|null $additionalBpInternetLive
 * @property string|null $agent
 * @property string|null $archiveAuction
 * @property string|null $auctioneerScreen
 * @property string|null $authNetCai
 * @property string|null $authNetCpi
 * @property string|null $authNetCppi
 * @property string|null $availableLots
 * @property string|null $bidder
 * @property string|null $bidderAgent
 * @property string|null $bidderHouse
 * @property string|null $bidderPreferred
 * @property string|null $bidders
 * @property string|null $bidIncrements
 * @property string|null $billingAddress
 * @property string|null $billingAddress2
 * @property string|null $billingAddress3
 * @property string|null $billingBankAccountName
 * @property string|null $billingBankAccountNumber
 * @property string|null $billingBankAccountType
 * @property string|null $billingBankName
 * @property string|null $billingBankRoutingNumber
 * @property string|null $billingCcCode
 * @property string|null $billingCcExpDate
 * @property string|null $billingCcNumber
 * @property string|null $billingCcNumberHash
 * @property string|null $billingCcType
 * @property string|null $billingCity
 * @property string|null $billingCompanyName
 * @property string|null $billingContactType
 * @property string|null $billingCountry
 * @property string|null $billingEmail
 * @property string|null $billingFax
 * @property string|null $billingFirstName
 * @property string|null $billingLastName
 * @property string|null $billingPhone
 * @property string|null $billingState
 * @property string|null $billingUseCard
 * @property string|null $billingZip
 * @property string|null $bpRangeCalculationHybrid
 * @property string|null $bpRangeCalculationLive
 * @property string|null $bpRangeCalculationTimed
 * @property string|null $bpRule
 * @property string|null $bulkUserExport
 * @property string|null $buyersPremium "Buyers Premium" sub-privilege for "Manage Auctions" privilege
 * @property int|null $collateralAccountId
 * @property array|null $buyersPremiumHybridDataRows
 * @property array|null $buyersPremiumLiveDataRows
 * @property array|null $buyersPremiumTimedDataRows
 * @property string|null $buyersPremiumHybridString not parsed string
 * @property string|null $buyersPremiumLiveString not parsed string
 * @property string|null $buyersPremiumTimedString not parsed string
 * @property int|null $consignorCommissionFeeAccountId
 * @property int|string|null $consignorCommissionId
 * @property \Sam\EntityMaker\Base\Data\Range[]|null $consignorCommissionRanges
 * @property string|null $consignorCommissionCalculationMethod
 * @property int|string|null $consignorSoldFeeId
 * @property \Sam\EntityMaker\Base\Data\Range[]|null $consignorSoldFeeRanges
 * @property string|null $consignorSoldFeeCalculationMethod
 * @property string|null $consignorSoldFeeReference
 * @property int|string|null $consignorUnsoldFeeId
 * @property \Sam\EntityMaker\Base\Data\Range[]|null $consignorUnsoldFeeRanges
 * @property string|null $consignorUnsoldFeeCalculationMethod
 * @property string|null $consignorUnsoldFeeReference
 * @property string|null $companyName
 * @property string|null $consignor
 * @property string|null $consignorPaymentInfo
 * @property string|null $consignorSalesTax
 * @property string|null $consignorSalesTaxBP
 * @property string|null $consignorSalesTaxHP
 * @property string|null $consignorSalesTaxServices
 * @property string|null $consignorTax
 * @property string|null $consignorTaxHP
 * @property string|null $consignorTaxCommission
 * @property string|null $consignorTaxServices
 * @property string|null $customerNo
 * @property string|null $deleteAuction
 * @property string|null $deleteUser
 * @property string|null $email
 * @property string|null $emailConfirmation // not in meta (moved from config dto)
 * @property string|null $emailVerified
 * @property string|null $exclusive
 * @property string|null $firstName
 * @property string|null $flag
 * @property string|null $flagId // not in meta (moved from config dto)
 * @property string|int|null $id
 * @property string|null $identification
 * @property string|null $identificationType
 * @property string|null $information
 * @property string|null $lastName
 * @property string|null $locale
 * @property string|null $location
 * @property string|null $locationId
 * @property string|null $lots
 * @property string|null $manageAllAuctions
 * @property string|null $manageAuctions
 * @property string|null $manageCcInfo
 * @property string|null $manageConsignorSettlements
 * @property string|null $manageInventory
 * @property string|null $manageInvoices
 * @property string|null $manageSettings
 * @property string|null $manageUsers
 * @property string|null $maxOutstanding
 * @property string|null $newsLetter
 * @property string|null $nmiVaultId
 * @property string|null $noTax
 * @property string|null $noTaxBp
 * @property string|null $note
 * @property string|null $payTraceCustId
 * @property string|null $permissions
 * @property string|null $phone
 * @property string|null $phoneType
 * @property string|null $projector
 * @property string|null $publish
 * @property string|null $pword
 * @property string|null $pwordConfirmation // not in meta (moved from config fields)
 * @property string|null $regAuthDate
 * @property string|null $referrer
 * @property string|null $referrerHost
 * @property string|null $runLiveAuction
 * @property string|null $remainingUsers
 * @property string|null $reports
 * @property string|null $resetAuction
 * @property string|null $resume
 * @property string|null $opayoTokenId
 * @property string|null $ccNumberEway
 * @property string|null $createBidder
 * @property array|null $salesCommissions
 * @property string|null $salesStaff
 * @property string|null $salesTax
 * @property string|null $sendTextAlerts
 * @property string|null $shippingAddress
 * @property string|null $shippingAddress2
 * @property string|null $shippingAddress3
 * @property string|null $shippingCarrierMethod
 * @property string|null $shippingCity
 * @property string|null $shippingCompanyName
 * @property string|null $shippingContactType
 * @property string|null $shippingCountry
 * @property string|null $shippingFax
 * @property string|null $shippingFirstName
 * @property string|null $shippingLastName
 * @property string|null $shippingPhone
 * @property string|null $shippingState
 * @property string|null $shippingZip
 * @property object|null $specificLocation
 * @property string|null $superadmin
 * @property string|null $syncKey
 * @property string|null $syncNamespaceId
 * @property string|int|null $taxApplication
 * @property string|null $taxApplicationName
 * @property string|null $timezone
 * @property \Sam\EntityMaker\Base\Data\Field[]|null $userCustomFields
 * @property string|null $usePermanentBidderno
 * @property string|null $userLog
 * @property string|null $username
 * @property string|null $userPasswords
 * @property string|null $userPrivileges
 * @property string|null $userStatus
 * @property string|int|null $userStatusId
 * @property string|null $verificationCode
 * @property string|null $viewLanguage
 * @property string|int|null $viewLanguageId
 */
class UserMakerInputDto extends InputDto
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
