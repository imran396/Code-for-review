<?php
/**
 * SAM-4720: Refactor logic of System Parameters management pages to editor modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Edit\Dto;

use Sam\Core\Constants;
use Sam\Core\Dto\StringDto;

/**
 * Class AuctionParametersDto
 * @package Sam\Settings\Edit
 *
 * @property array $VisibleAuctionStatuses
 * @property int $AuctionLinksTo
 * @property string $AboveReserve
 * @property string $AboveStartingBid
 * @property string $AbsenteeBidLotNotification
 * @property string $AbsenteeBidsDisplay
 * @property string $AchPayment
 * @property string $AchPaymentNmi
 * @property string $AchPaymentOpayo
 * @property string $AchPaymentPayTrace
 * @property string $AchPaymentSage
 * @property string $AddBidsToWatchlist
 * @property string $AddDescriptionInLotNameColumn
 * @property string $AdminCssFile
 * @property string $AdminDateFormat
 * @property string $AdminLanguage
 * @property string $AgentOption
 * @property string $AllUserRequireCcAuth
 * @property string $AllowAccountAdminAddFloorBidder
 * @property string $AllowAccountAdminMakeBidderPreferred
 * @property string $AllowAnyoneToTellAFriend
 * @property string $AllowBiddingDuringStartGapHybrid
 * @property string $AllowConsignorDeleteItem
 * @property string $AllowForceBid
 * @property string $AllowInvoiceItemChanges
 * @property string $AllowManualBidderForFlaggedBidders
 * @property string $AllowMultibids
 * @property string $AssignedLotsRestriction
 * @property string $AucIncAccountId
 * @property string $AucIncBusinessId
 * @property string $AucIncDhl
 * @property string $AucIncDhlAccessKey
 * @property string $AucIncDhlPostalCode
 * @property string $AucIncDimensionType
 * @property string $AucIncFedex
 * @property string $AucIncHeightCustFieldId
 * @property string $AucIncLengthCustFieldId
 * @property string $AucIncMethod
 * @property string $AucIncPickup
 * @property string $AucIncUps
 * @property string $AucIncUsps
 * @property string $AucIncWeightCustFieldId
 * @property string $AucIncWeightType
 * @property string $AucIncWidthCustFieldId
 * @property string $AuctionCatalogAccess
 * @property string $AuctionDateInSearch
 * @property string $AuctionDetailTemplate
 * @property string $AuctionDomainMode
 * @property string $AuctionInfoAccess
 * @property string $AuctionListingPageDesc
 * @property string $AuctionListingPageKeyword
 * @property string $AuctionListingPageTitle
 * @property string $AuctionPageDesc
 * @property string $AuctionPageKeyword
 * @property string $AuctionPageTitle
 * @property string $AuctionSeoUrlTemplate
 * @property string $AuctionVisibilityAccess
 * @property string $AuctioneerFilter
 * @property string $AuthNetAccountType
 * @property string $AuthNetCim
 * @property string $AuthNetLogin
 * @property string $AuthNetMode
 * @property string $AuthNetTrankey
 * @property string $AuthorizationUse
 * @property string $AutoAssignAccountAdminPrivileges
 * @property string $AutoConsignorPrivileges
 * @property string $AutoCreateFloorBidderRecord
 * @property string $AutoIncrementCustomerNum
 * @property string $AutoInvoice
 * @property string $AutoPreferred
 * @property string $AutoPreferredCreditCard
 * @property string $BidAcceptedSound
 * @property string $BidSound
 * @property string $BidTrackingCode
 * @property string $BlacklistPhrase
 * @property string $BlockSoldLots
 * @property string $BuyNowRestriction
 * @property string $BuyNowUnsold
 * @property string $CashDiscount
 * @property string $CategoryInInvoice
 * @property string $CcPayment
 * @property string $CcPaymentEway
 * @property string $CcPaymentNmi
 * @property string $CcPaymentOpayo
 * @property string $CcPaymentPayTrace
 * @property string $CcPaymentSage
 * @property string $ChargeConsignorCommission
 * @property string $ClearMessageCenter
 * @property string $ClearMessageCenterLog
 * @property string $ConditionalSales
 * @property string $ConfirmAddressSale
 * @property string $ConfirmMultibids
 * @property string $ConfirmMultibidsText
 * @property string $ConfirmTermsAndConditionsSale
 * @property string $ConfirmTimedBid
 * @property string $ConfirmTimedBidText
 * @property string $ConsignorCommissionId
 * @property string $ConsignorScheduleHeader
 * @property string $ConsignorSoldFeeId
 * @property string $ConsignorUnsoldFeeId
 * @property string $CustomTemplateHideEmptyFieldsForAllCategories
 * @property string $CustomTemplateHideEmptyFieldsForNoCategoryLot
 * @property string $DateFormat
 * @property string $DefaultAuctionConsigned
 * @property string $DefaultCountry
 * @property string $DefaultCountryCode
 * @property string $DefaultExportEncoding
 * @property string $DefaultImagePreview
 * @property string $DefaultImportEncoding
 * @property string $DefaultInvoiceNotes
 * @property string $DefaultLotItemNoTaxOos
 * @property string $DefaultPostAucImportPremium
 * @property string $DelayAfterBidAccepted
 * @property string $DelayBlockSell
 * @property string $DelaySoldItem
 * @property string $DisplayBidderInfo
 * @property string $DisplayItemNum
 * @property string $DisplayQuantity
 * @property string $DontMakeUserBidder
 * @property string $EmailFormat
 * @property string $EnableConsignorCompanyClerking
 * @property string $EnableConsignorPaymentInfo
 * @property string $EnablePaypalPayments
 * @property string $EnableResellerReg
 * @property string $EnableSecondChance
 * @property string $EnableSmartPayments
 * @property string $EnableUserCompany
 * @property string $EnableUserResume
 * @property string $EwayAccountType
 * @property string $EwayApiKey
 * @property string $EwayEncryptionKey
 * @property string $EwayPassword
 * @property string $ExtendTimeHybrid
 * @property string $ExtendTimeTimed
 * @property string $ExternalJavascript
 * @property string $FailedLoginAttemptLockoutTimeout
 * @property string $FailedLoginAttemptTimeIncrement
 * @property string $FairWarningSound
 * @property string $FairWarningsHybrid
 * @property string $FloorBiddersFromDropdown
 * @property string $ForceMainAccountDomainMode
 * @property string $GaBidTracking
 * @property string $GraphqlCorsAllowedOrigins
 * @property string $HammerPriceBp
 * @property string $HideBidderNumber
 * @property string $HideCountryCodeSelection
 * @property string $HideMovetosale
 * @property string $HideUnsoldLots
 * @property string $ImageAutoOrient
 * @property string $ImageOptimize
 * @property string $IncludeAchInfo
 * @property string $IncludeBasicInfo
 * @property string $IncludeBillingInfo
 * @property string $IncludeCcInfo
 * @property string $IncludeUserPreferences
 * @property string $InlineBidConfirm
 * @property string $InvoiceBpTaxSchemaId
 * @property string $InvoiceHpTaxSchemaId
 * @property string $InvoiceServicesTaxSchemaId
 * @property string $InvoiceIdentification
 * @property string $InvoiceItemDescription
 * @property string $InvoiceItemSalesTax
 * @property string $InvoiceItemSalesTaxApplication
 * @property string $InvoiceItemSeparateTax
 * @property string $InvoiceLogo
 * @property string $InvoiceTaxDesignationStrategy
 * @property string $ItemNumLock
 * @property string $ItemsPerPage
 * @property string $KeepDecimalInvoice
 * @property string $LandingPage
 * @property string $LandingPageUrl
 * @property string $LiveBiddingCountdown
 * @property string $LiveChat
 * @property string $LiveChatViewAll
 * @property string $LiveViewAccess
 * @property string $Locale
 * @property string $LoginDesc
 * @property string $LoginKeyword
 * @property string $LoginTitle
 * @property string $LogoLink
 * @property string $LotBiddingHistoryAccess
 * @property string $LotBiddingInfoAccess
 * @property string $LotCategoryGlobalOrderAvailable
 * @property string $LotDetailsAccess
 * @property string $LotItemDetailTemplate
 * @property string $LotItemDetailTemplateForNoCategory
 * @property string $LotPageDesc
 * @property string $LotPageKeyword
 * @property string $LotPageTitle
 * @property string $LotSeoUrlTemplate
 * @property string $LotStartGapTimeHybrid
 * @property string $LotStartingBidAccess
 * @property string $LotStatus
 * @property string $LotWinningBidAccess
 * @property string $MainMenuAuctionTarget
 * @property string $MakePermanentBidderNum
 * @property string $MandatoryAchInfo
 * @property string $MandatoryBasicInfo
 * @property string $MandatoryBillingInfo
 * @property string $MandatoryCcInfo
 * @property string $MandatoryUserPreferences
 * @property string $MaxStoredSearches
 * @property string $MultiCurrency
 * @property string $MultipleSaleInvoice
 * @property string $MultipleSaleSettlement
 * @property string $NewsletterOption
 * @property string $NextBidButton
 * @property string $NmiMode
 * @property string $NmiPassword
 * @property string $NmiUsername
 * @property string $NmiVault
 * @property string $NmiVaultOption
 * @property string $NoAutoAuthorization
 * @property string $NoLowerMaxbid
 * @property string $NotifyAbsenteeBidders
 * @property string $NumberOfFeaturedOnSearch
 * @property string $OnAuctionRegistration
 * @property string $OnAuctionRegistrationAmount
 * @property string $OnAuctionRegistrationAuto
 * @property string $OnAuctionRegistrationExpires
 * @property string $OnRegistration
 * @property string $OnRegistrationAmount
 * @property string $OnRegistrationExpires
 * @property string $OneSaleGroupedInvoice
 * @property string $OnlineBidIncomingOnAdminSound
 * @property string $OnlinebidButtonInfo
 * @property string $OnlyOneRegEmail
 * @property string $Opayo3dsecure
 * @property string $OpayoAuthTransactionType
 * @property string $OpayoAvscv2
 * @property string $OpayoCurrency
 * @property string $OpayoMode
 * @property string $OpayoSendEmail
 * @property string $OpayoToken
 * @property string $OpayoVendorEmail
 * @property string $OpayoVendorName
 * @property string $OutBidSound
 * @property string $PageHeader
 * @property string $PageHeaderType
 * @property string $PageRedirection
 * @property string $PassedSound
 * @property string $PayTraceCim
 * @property string $PayTraceMode
 * @property string $PayTracePassword
 * @property string $PayTraceUsername
 * @property string $PaymentReminderEmailFrequency
 * @property string $PaymentTrackingCode
 * @property string $PaypalAccountType
 * @property string $PaypalBnCode
 * @property string $PaypalEmail
 * @property string $PaypalIdentityToken
 * @property string $PendingActionTimeoutHybrid
 * @property string $PickupReminderEmailFrequency
 * @property string $PlaceBidRequireCc
 * @property string $PlaceBidSound
 * @property string|int $PrimaryCurrencyId
 * @property string $ProcessingCharge
 * @property string $ProfileBillingOptional
 * @property string $ProfileShippingOptional
 * @property string $PwHistoryRepeat
 * @property string $PwMaxConseqLetter
 * @property string $PwMaxConseqNum
 * @property string $PwMaxSeqLetter
 * @property string $PwMaxSeqNum
 * @property string $PwMinLen
 * @property string $PwMinLetter
 * @property string $PwMinNum
 * @property string $PwMinSpecial
 * @property string $PwRenew
 * @property string $PwReqMixedCase
 * @property string $PwTmpTimeout
 * @property string $QuantityDigits
 * @property string $QuantityInInvoice
 * @property string $QuantityInSettlement
 * @property string $RegConfirmAutoApprove
 * @property string $RegConfirmPage
 * @property string $RegConfirmPageContent
 * @property string $RegReminderEmail
 * @property string $RegUseHighBidder
 * @property string $RegistrationRequireCc
 * @property string $RequireIdentification
 * @property string $RequireOnIncBids
 * @property string $RequireReenterCc
 * @property string $ReserveMetNotice
 * @property string $ReserveNotMetNotice
 * @property string $ResetTimerOnUndoHybrid
 * @property string $ResponsiveCssFile
 * @property string $ResponsiveHeaderAddress
 * @property string $RevokePreferredBidder
 * @property string $RowVersion
 * @property string $RtbDetailTemplate
 * @property string $RtbDetailTemplateForNoCategory
 * @property string $SageAccountType
 * @property string $SageLogin
 * @property string $SageMode
 * @property string $SageTrankey
 * @property string $SalesTax
 * @property string $SalesTaxServices
 * @property string $SamTax
 * @property string $SamTaxDefaultCountry
 * @property string $SaveResellerCertInProfile
 * @property string $SearchResultsFormat
 * @property string $SearchResultsPageDesc
 * @property string $SearchResultsPageKeyword
 * @property string $SearchResultsPageTitle
 * @property string $SendConfirmationLink
 * @property string $SendResultsOnce
 * @property string $SettlementLogo
 * @property string $SettlementUnpaidLots
 * @property string $ShareUserInfo
 * @property string $ShareUserStats
 * @property string $ShippingCharge
 * @property string $ShippingInfoBox
 * @property string $ShowAuctionStartsEnding
 * @property string $ShowCountdownSeconds
 * @property string $ShowHighEst
 * @property string $ShowLowEst
 * @property string $ShowMemberMenuItems
 * @property string $ShowPortNotice
 * @property string $ShowUserResume
 * @property string $ShowWinnerInCatalog
 * @property string $SignupDesc
 * @property string $SignupKeyword
 * @property string $SignupTitle
 * @property string $SignupTrackingCode
 * @property string $SimplifiedSignup
 * @property string $SlideshowProjectorOnly
 * @property string $SmartPayAccountType
 * @property string $SmartPayMerchantAccount
 * @property string $SmartPayMerchantMode
 * @property string $SmartPayMode
 * @property string $SmartPaySharedSecret
 * @property string $SmartPaySkinCode
 * @property string $SmtpAuth
 * @property string $SmtpPassword
 * @property string $SmtpPort
 * @property string $SmtpServer
 * @property string $SmtpSslType
 * @property string $SmtpUsername
 * @property string $SoldNotWonSound
 * @property string $SoldWonSound
 * @property string $StayOnAccountDomain
 * @property string $StlmCheckAddressCoordX
 * @property string $StlmCheckAddressCoordY
 * @property string $StlmCheckAddressTemplate
 * @property string $StlmCheckAmountCoordX
 * @property string $StlmCheckAmountCoordY
 * @property string $StlmCheckAmountSpellingCoordX
 * @property string $StlmCheckAmountSpellingCoordY
 * @property string $StlmCheckDateCoordX
 * @property string $StlmCheckDateCoordY
 * @property string $StlmCheckFile
 * @property string $StlmCheckHeight
 * @property string $StlmCheckMemoCoordX
 * @property string $StlmCheckMemoCoordY
 * @property string $StlmCheckMemoTemplate
 * @property string $StlmCheckNameCoordX
 * @property string $StlmCheckNameCoordY
 * @property string $StlmCheckPayeeTemplate
 * @property string $StlmCheckPerPage
 * @property string $StlmCheckRepeatCount
 * @property string $SuggestedStartingBid
 * @property string $SupportEmail
 * @property string $SwitchFrameSeconds
 * @property string $TakeMaxBidsUnderReserve
 * @property string $TellAFriend
 * @property string $TextMsgApiNotification
 * @property string $TextMsgApiOutbidNotification
 * @property string $TextMsgApiPostVar
 * @property string $TextMsgApiUrl
 * @property string $TextMsgEnabled
 * @property string $TimedAboveReserve
 * @property string $TimedAboveStartingBid
 * @property string $TimezoneId
 * @property string $TwentyMessagesMax
 * @property string $UsNumberFormatting
 * @property string $UseAlternatePdfCatalog
 * @property string $VerifyEmail
 * @property string $ViewLanguage
 * @property string $WavebidEndpoint
 * @property string $WavebidUat
 **/
class AuctionParametersDto extends StringDto
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @inheritDoc
     */
    public function initInstance()
    {
        $properties = array_keys(Constants\Setting::$typeMap);
        $this->setAvailableProperties($properties);
        return parent::initInstance();
    }
}
