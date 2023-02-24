<?php
/**
 * Class for producing of User entity
 *
 * SAM-8841: User entity-maker module structural adjustments for v3-5
 * SAM-4989: User Entity Maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 22, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Save;

use Exception;
use Sam\Billing\CreditCard\Build\CcExpiryDateBuilderCreateTrait;
use Sam\Billing\CreditCard\Build\CcNumberEncrypterAwareTrait;
use Sam\Billing\CreditCard\Load\CreditCardLoaderAwareTrait;
use Sam\Billing\CreditCard\Validate\CreditCardValidatorAwareTrait;
use Sam\Billing\Gate\Availability\BillingGateAvailabilityCheckerCreateTrait;
use Sam\BuyersPremium\Load\BuyersPremiumLoaderCreateTrait;
use Sam\Commission\CommissionManager;
use Sam\Commission\CommissionManagerAwareTrait;
use Sam\Consignor\Commission\Edit\Dto\ConsignorCommissionFeeRelatedEntityDto;
use Sam\Consignor\Commission\Edit\Save\ConsignorCommissionFeeRelatedEntityProducerCreateTrait;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Core\Transform\Html\HtmlRenderer;
use Sam\Currency\Load\CurrencyLoader;
use Sam\Date\CurrentDateTrait;
use Sam\EntityMaker\Base\Common\CustomFieldManagerAwareTrait;
use Sam\EntityMaker\Base\Common\ValueResolver;
use Sam\EntityMaker\Base\Save\BaseMakerProducer;
use Sam\EntityMaker\Base\Save\Internal\EntitySync\EntitySyncSavingIntegratorCreateTrait;
use Sam\EntityMaker\User\Common\Access\UserMakerAccessCheckerAwareTrait;
use Sam\EntityMaker\User\Common\DirectAccount\UserDirectAccountDetectionInput;
use Sam\EntityMaker\User\Common\DirectAccount\UserDirectAccountDetector;
use Sam\EntityMaker\User\Common\DirectAccount\UserDirectAccountDetectorAwareTrait;
use Sam\EntityMaker\User\Common\UserMakerCustomFieldManager;
use Sam\EntityMaker\User\Dto\UserMakerConfigDto;
use Sam\EntityMaker\User\Dto\UserMakerDtoHelperAwareTrait;
use Sam\EntityMaker\User\Dto\UserMakerInputDto;
use Sam\EntityMaker\User\Save\Internal\AutoPreferredCreditCard\AutoPreferredCreditCardEffectCheckingIntegratorCreateTrait;
use Sam\EntityMaker\User\Save\Internal\BuyersPremium\BuyersPremiumSavingIntegratorCreateTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lang\ViewLanguage\Load\ViewLanguageLoaderAwareTrait;
use Sam\Location\Load\LocationLoaderAwareTrait;
use Sam\PaymentGateway\PaymentGatewayFactory;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\Storage\WriteRepository\Entity\Admin\AdminWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\Bidder\BidderWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\Consignor\ConsignorWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\User\UserWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserAuthentication\UserAuthenticationWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserBilling\UserBillingWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserConsignorCommissionFee\UserConsignorCommissionFeeWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserInfo\UserInfoWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserLog\UserLogWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserShipping\UserShippingWriteRepositoryAwareTrait;
use Sam\User\Account\Save\UserAccountProducerAwareTrait;
use Sam\User\Account\Statistic\Save\UserAccountStatisticProducerAwareTrait;
use Sam\User\Flag\UserFlaggingAwareTrait;
use Sam\User\Identification\UserIdentificationTransformerAwareTrait;
use Sam\User\Load\UserConsignorCommissionFeeLoaderCreateTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Password\HashHelper as PasswordHashHelper;
use Sam\User\Privilege\Calculate\AdminPrivilegeCalculator;
use Sam\User\Privilege\Validate\BidderPrivilegeCheckerAwareTrait;
use User;
use UserAuthentication;
use UserBilling;
use UserInfo;
use UserLog;
use UserShipping;

/**
 * @method UserMakerInputDto getInputDto()
 * @method UserMakerConfigDto getConfigDto()
 * @method User getUser(bool $isReadOnlyDb = false)  availability is checked in produce method
 * @property UserMakerCustomFieldManager $customFieldManager
 */
class UserMakerProducer extends BaseMakerProducer
{
    use AdminWriteRepositoryAwareTrait;
    use AutoPreferredCreditCardEffectCheckingIntegratorCreateTrait;
    use BidderPrivilegeCheckerAwareTrait;
    use BidderWriteRepositoryAwareTrait;
    use BillingGateAvailabilityCheckerCreateTrait;
    use BlockCipherProviderCreateTrait;
    use BuyersPremiumLoaderCreateTrait;
    use BuyersPremiumSavingIntegratorCreateTrait;
    use CcExpiryDateBuilderCreateTrait;
    use CcNumberEncrypterAwareTrait;
    use CommissionManagerAwareTrait;
    use ConsignorCommissionFeeRelatedEntityProducerCreateTrait;
    use ConsignorWriteRepositoryAwareTrait;
    use CreditCardLoaderAwareTrait;
    use CreditCardValidatorAwareTrait;
    use CurrentDateTrait;
    use CustomFieldManagerAwareTrait;
    use DbConnectionTrait;
    use EntityFactoryCreateTrait;
    use EntitySyncSavingIntegratorCreateTrait;
    use LocationLoaderAwareTrait;
    use OptionalsTrait;
    use SettingsManagerAwareTrait;
    use TranslatorAwareTrait;
    use UserAccountProducerAwareTrait;
    use UserAccountStatisticProducerAwareTrait;
    use UserAuthenticationWriteRepositoryAwareTrait;
    use UserAwareTrait;
    use UserBillingWriteRepositoryAwareTrait;
    use UserConsignorCommissionFeeLoaderCreateTrait;
    use UserConsignorCommissionFeeWriteRepositoryAwareTrait;
    use UserDirectAccountDetectorAwareTrait;
    use UserFlaggingAwareTrait;
    use UserIdentificationTransformerAwareTrait;
    use UserInfoWriteRepositoryAwareTrait;
    use UserLoaderAwareTrait;
    use UserLogWriteRepositoryAwareTrait;
    use UserMakerAccessCheckerAwareTrait;
    use UserMakerDtoHelperAwareTrait;
    use UserShippingWriteRepositoryAwareTrait;
    use UserWriteRepositoryAwareTrait;
    use ViewLanguageLoaderAwareTrait;

    public const OP_TARGET_USER_DIRECT_ACCOUNT_ID = 'targetUserDirectAccountId'; // int

    /** @var string CIM customer profile id */
    public string $cimCpi = '';

    public bool $isUserAccountIdChanged = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param UserMakerInputDto $inputDto
     * @param UserMakerConfigDto $configDto
     * @param array $optionals
     * @return $this
     */
    public function construct(
        UserMakerInputDto $inputDto,
        UserMakerConfigDto $configDto,
        array $optionals = []
    ): static {
        $this->setInputDto($inputDto);
        $this->setConfigDto($configDto);
        $this->initOptionals($optionals);
        $this->getUserMakerAccessChecker()->construct($inputDto, $configDto);
        $this->customFieldManager = UserMakerCustomFieldManager::new()->construct($inputDto, $configDto);
        $this->getUserMakerDtoHelper()->constructUserMakerDtoHelper($configDto->mode, $this->customFieldManager);
        return $this;
    }

    /**
     * Produce the User entity
     * $this->getEditorAdminPrivilegeChecker() - editing user: admin
     * $this->getUserAdminPrivilegeChecker() - editable user: user
     *
     * For work with next entities an admin must have 'UserPrivileges':
     * Admin
     * Bidder
     * Consignor
     * BuyersPremiumRange       -> and user->bidder privilege (bidder->id || dto->bidder)
     * UserConsignorCommission  -> and user->consignor (consignor->id || dto->consignor)
     * UserSalesCommission      -> and user->salesStaff (admin->id || dto->salesStaff)
     *
     * @return static
     */
    public function produce(): static
    {
        $this->assertInputDto();
        $inputDto = $this->getUserMakerDtoHelper()->prepareValues($this->getInputDto(), $this->getConfigDto());
        $this->setInputDto($inputDto);
        $inputDto = $this->getInputDto();
        $this->setUserId(Cast::toInt($inputDto->id));

        $this->assignValues();
        $this->atomicSave();

        return $this;
    }

    /**
     * Atomic persist data.
     * @throws Exception
     */
    protected function atomicSave(): void
    {
        $this->transactionBegin();
        try {
            $this->save();
        } catch (Exception $e) {
            log_errorBackTrace("Rollback transaction, because user save failed.");
            $this->transactionRollback();
            throw $e;
        }
        $this->transactionCommit();
    }

    /**
     * Persist data.
     */
    protected function save(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $isNew = !$inputDto->id;

        $this->getUserWriteRepository()->saveWithModifier($this->getUser(), $configDto->editorUserId);
        $this->refreshUserId(); // Update internal userId value, when user is created with new id
        $userId = $this->getUserId();

        $hasEditorUserSubPrivilegeForUserPrivileges = $this->getUserMakerAccessChecker()->hasEditorUserSubPrivilegeForUserPrivileges();

        if (
            $configDto->saveAdminPrivileges
            || $hasEditorUserSubPrivilegeForUserPrivileges
        ) {
            $admin = $this->getAdminOrCreate();
            if ($this->isRoleSet('admin')) {
                $admin->UserId = $userId;
                $this->getAdminWriteRepository()->saveWithModifier($admin, $configDto->editorUserId);
            } elseif (
                $this->shouldDeleteRole('admin')
                && $admin->Id
            ) {
                $this->getAdminWriteRepository()->deleteWithModifier($admin, $configDto->editorUserId);
            }
        }

        $shouldUpdateBidder = false;
        $bidder = $this->getBidderOrCreate();
        if (
            $configDto->saveBidderPrivileges
            || $hasEditorUserSubPrivilegeForUserPrivileges
        ) {
            if ($this->isRoleSet('bidder')) {
                $bidder->UserId = $userId;
                $shouldUpdateBidder = true;
            } elseif (
                $this->shouldDeleteRole('bidder')
                && $bidder->Id
            ) {
                $this->getBidderWriteRepository()->deleteWithModifier($bidder, $configDto->editorUserId);
            }
        }

        if (
            $configDto->autoAssignPreferredBidder
            && $this->isRoleSet('bidderPreferred')
        ) {
            $bidder->toPreferred();
            $shouldUpdateBidder = true;
        }

        if ($shouldUpdateBidder) {
            $this->getBidderWriteRepository()->saveWithModifier($bidder, $configDto->editorUserId);
        }

        if (
            $configDto->saveConsignorPrivileges
            || $hasEditorUserSubPrivilegeForUserPrivileges
        ) {
            $consignor = $this->getConsignorOrCreate();
            if ($this->isRoleSet('consignor')) {
                $consignor->UserId = $userId;
                $this->getConsignorWriteRepository()->saveWithModifier($consignor, $configDto->editorUserId);
            } elseif (
                $this->shouldDeleteRole('consignor')
                && $consignor->Id
            ) {
                $this->getConsignorWriteRepository()->deleteWithModifier($consignor, $configDto->editorUserId);
            }
        }

        $userAuthentication = $this->getUserAuthenticationOrCreate();
        if ($this->isModified($userAuthentication)) {
            $userAuthentication->UserId = $userId;
            $this->getUserAuthenticationWriteRepository()->saveWithModifier($userAuthentication, $configDto->editorUserId);
        }

        $userBilling = $this->getUserBillingOrCreate();
        if ($this->isModified($userBilling)) {
            $userBilling->UserId = $userId;
            $this->getUserBillingWriteRepository()->saveWithModifier($userBilling, $configDto->editorUserId);
        }

        $userInfo = $this->getUserInfoOrCreate();
        if ($this->isModified($userInfo)) {
            $userInfo->UserId = $userId;
            $this->getUserInfoWriteRepository()->saveWithModifier($userInfo, $configDto->editorUserId);
        }

        $userLog = $this->getUserLog() ?? $this->createUserLog();
        if ($this->isModified($userLog)) {
            $userLog->UserId = $userId;
            $this->getUserLogWriteRepository()->saveWithModifier($userLog, $configDto->editorUserId);
        }

        $userShipping = $this->getUserShippingOrCreate();
        if ($this->isModified($userShipping)) {
            $userShipping->UserId = $userId;
            $this->getUserShippingWriteRepository()->saveWithModifier($userShipping, $configDto->editorUserId);
        }
        $this->saveCimInfo();

        if (
            !$inputDto->id
            && $configDto->systemAccountId !== $this->getUser()->AccountId
            && (
                $inputDto->bidder
                || $inputDto->consignor
            )
        ) {
            $this->getUserAccountProducer()->add($this->getUserId(), $configDto->systemAccountId, $configDto->editorUserId);
        }

        if (isset($inputDto->collateralAccountId)) {
            $this->getUserAccountProducer()->add($this->getUserId(), $inputDto->collateralAccountId, $configDto->editorUserId);
        }

        if ($isNew) {
            $this->doPostCreate();
        } else {
            $this->doPostUpdate();
        }
    }

    /**
     * Run necessary actions after User was created
     */
    public function doPostCreate(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $inputDto->id = $this->getUserId();

        if ($this->hasBidderPrivileges()) {
            $this->createBuyersPremiumSavingIntegrator()->create($this);
        }

        $this->createEntitySyncSavingIntegrator()->create($this);

        if ($this->hasConsignorPrivileges()) {
            $this->updateConsignorCommissionFee();
        }

        if (
            $this->hasSalesStaffPrivileges()
            && isset($inputDto->salesCommissions)
        ) {
            $this->getCommissionManager()
                ->setAccountId($this->userDirectAccountId())
                ->setEditorUserId($configDto->editorUserId)
                ->setType(CommissionManager::TYPE_SALES)
                ->bulkCreate(
                    (array)$inputDto->salesCommissions,
                    (int)$inputDto->id,
                    $configDto->editorUserId
                );
        }

        $this->customFieldManager
            ->setInputDto($inputDto)
            ->save();
        $this->updateLocations();
        $this->updateUserAccountStats();
        $this->createUserAccountStatsCurrency();
    }

    /**
     * Run necessary actions after User was updated
     */
    public function doPostUpdate(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        if ($this->hasBidderPrivileges()) {
            $this->createBuyersPremiumSavingIntegrator()->update($this);
        }

        $this->createEntitySyncSavingIntegrator()->update($this);

        if ($this->hasConsignorPrivileges()) {
            $this->updateConsignorCommissionFee();
        }

        if (
            $this->hasSalesStaffPrivileges()
            && isset($inputDto->salesCommissions)
        ) {
            $this->getCommissionManager()
                ->setAccountId($this->userDirectAccountId())
                ->setEditorUserId($configDto->editorUserId)
                ->setType(CommissionManager::TYPE_SALES)
                ->bulkUpdate(
                    (array)$inputDto->salesCommissions,
                    (int)$inputDto->id,
                    $configDto->editorUserId
                );
        }

        $this->customFieldManager
            ->setInputDto($inputDto)
            ->save();
        $this->updateLocations();
        $this->updateUserAccountStats();
        $this->createUserAccountStatsCurrency();
    }

    /**
     * Assign User values from Dto object
     */
    public function assignValues(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $sm = $this->getSettingsManager();
        $valueResolver = ValueResolver::new();

        $availableLotsPrivilege = Constants\AdminPrivilege::SUB_AUCTION_AVAILABLE_LOT;
        $publishPrivilege = Constants\AdminPrivilege::SUB_AUCTION_PUBLISH;
        $remainingUsersPrivilege = Constants\AdminPrivilege::SUB_AUCTION_REMAINING_USER;

        $user = $this->getUserOrCreate();
        $userInfo = $this->getUserInfoOrCreate();
        $userBilling = $this->getUserBillingOrCreate();
        $userShipping = $this->getUserShippingOrCreate();
        $userAuthentication = $this->getUserAuthenticationOrCreate();
        $userLog = $this->createUserLog();
        $admin = $this->getAdminOrCreate();
        $bidder = $this->getBidderOrCreate();
        $consignor = $this->getConsignorOrCreate();

        // Admin
        if (
            isset($inputDto->adminSalesCommissionStepdown)
            && $this->hasSalesStaffPrivileges()
        ) {
            $admin->SalesCommissionStepdown = $valueResolver->isTrue($inputDto->adminSalesCommissionStepdown);
        }
        $this->assignAdminPrivilege('manageAuctions', Constants\AdminPrivilege::MANAGE_AUCTIONS);
        $this->assignAdminPrivilege('manageCcInfo', Constants\AdminPrivilege::MANAGE_CC_INFO);
        $this->assignAdminPrivilege('manageConsignorSettlements', Constants\AdminPrivilege::MANAGE_SETTLEMENTS);
        $this->assignAdminPrivilege('manageInventory', Constants\AdminPrivilege::MANAGE_INVENTORY);
        $this->assignAdminPrivilege('manageInvoices', Constants\AdminPrivilege::MANAGE_INVOICES);
        $this->assignAdminPrivilege('manageSettings', Constants\AdminPrivilege::MANAGE_SETTINGS);
        $this->assignAdminPrivilege('manageUsers', Constants\AdminPrivilege::MANAGE_USERS);
        $this->assignAdminPrivilege('reports', Constants\AdminPrivilege::MANAGE_REPORTS);
        $this->assignAdminPrivilege('salesStaff', Constants\AdminPrivilege::SALES_STAFF);
        $this->assignAdminPrivilege('superadmin', Constants\AdminPrivilege::SUPERADMIN);
        // "Manage Auctions" sub-privileges
        $this->assignSubPrivilege('auctioneerScreen', Constants\AdminPrivilege::SUB_AUCTION_AUCTIONEER);
        $this->assignSubPrivilege('archiveAuction', Constants\AdminPrivilege::SUB_AUCTION_ARCHIVE);
        $this->assignSubPrivilege('bidders', Constants\AdminPrivilege::SUB_AUCTION_BIDDER);
        $this->assignSubPrivilege('bidIncrements', Constants\AdminPrivilege::SUB_AUCTION_BID_INCREMENT);
        $this->assignSubPrivilege('buyersPremium', Constants\AdminPrivilege::SUB_AUCTION_BUYER_PREMIUM);
        $this->assignSubPrivilege('deleteAuction', Constants\AdminPrivilege::SUB_AUCTION_DELETE);
        $this->assignSubPrivilege('information', Constants\AdminPrivilege::SUB_AUCTION_INFORMATION);
        $this->assignSubPrivilege('lots', Constants\AdminPrivilege::SUB_AUCTION_LOT_LIST);
        $this->assignSubPrivilege('manageAllAuctions', Constants\AdminPrivilege::SUB_AUCTION_MANAGE_ALL);
        $this->assignSubPrivilege('permissions', Constants\AdminPrivilege::SUB_AUCTION_PERMISSION);
        $this->assignSubPrivilege('createBidder', Constants\AdminPrivilege::SUB_AUCTION_CREATE_BIDDER);
        $this->assignSubPrivilege('projector', Constants\AdminPrivilege::SUB_AUCTION_PROJECTOR);
        $this->assignSubPrivilege('resetAuction', Constants\AdminPrivilege::SUB_AUCTION_RESET);
        $this->assignSubPrivilege('runLiveAuction', Constants\AdminPrivilege::SUB_AUCTION_RUN_LIVE);
        $this->assignSubPrivilege('availableLots', $availableLotsPrivilege);
        $this->assignSubPrivilege('publish', $publishPrivilege);
        $this->assignSubPrivilege('remainingUsers', $remainingUsersPrivilege);
        // "Manage Users" sub-privileges
        $this->assignSubPrivilege('bulkUserExport', Constants\AdminPrivilege::SUB_USER_BULK_EXPORT);
        $this->assignSubPrivilege('userPasswords', Constants\AdminPrivilege::SUB_USER_PASSWORD);
        $this->assignSubPrivilege('userPrivileges', Constants\AdminPrivilege::SUB_USER_PRIVILEGE);
        $this->assignSubPrivilege('deleteUser', Constants\AdminPrivilege::SUB_USER_DELETE);

        // Bidder
        $this->setIfAssign($bidder, 'additionalBpInternetHybrid', self::STRATEGY_REMOVE_FORMAT);
        $this->setIfAssign($bidder, 'additionalBpInternetLive', self::STRATEGY_REMOVE_FORMAT);
        $this->setIfAssign($bidder, 'bpRangeCalculationHybrid');
        $this->setIfAssign($bidder, 'bpRangeCalculationLive');
        $this->setIfAssign($bidder, 'bpRangeCalculationTimed');
        $this->setIfAssign($bidder, 'agent', self::STRATEGY_SPECIFIC_NAME, 'agentId');
        if (isset($inputDto->bidderAgent)) {
            $valueResolver->isTrue($inputDto->bidderAgent) ? $bidder->toAgent() : $bidder->dropAgent();
        }
        if (isset($inputDto->bidderHouse)) {
            $valueResolver->isTrue($inputDto->bidderHouse) ? $bidder->toHouse() : $bidder->dropHouse();
        }
        if (isset($inputDto->bidderPreferred)) {
            $valueResolver->isTrue($inputDto->bidderPreferred) ? $bidder->toPreferred() : $bidder->dropPreferred();
        }
        if (isset($inputDto->bpRule)) {
            $bpRule = $this->createBuyersPremiumLoader()->loadNotDefault($inputDto->bpRule, $this->userDirectAccountId());
            $bidder->BuyersPremiumId = $bpRule->Id ?? null;
        }

        if ($sm->getForMain(Constants\Setting::ENABLE_CONSIGNOR_PAYMENT_INFO)) {
            $this->setIfAssign($consignor, 'consignorPaymentInfo', self::STRATEGY_SPECIFIC_NAME, 'PaymentInfo');
        }
        if (isset($inputDto->consignorSalesTax)) {
            $consignor->SalesTax = $configDto->mode->isSoap()
                ? Cast::toFloat($inputDto->consignorSalesTax)
                : $this->getNumberFormatter()->parseMoney($inputDto->consignorSalesTax);
        }
        if (isset($inputDto->consignorSalesTaxBP)) {
            $consignor->BuyerTaxBp = $valueResolver->isTrue($inputDto->consignorSalesTaxBP);
        }
        if (isset($inputDto->consignorSalesTaxHP)) {
            $consignor->BuyerTaxHp = $valueResolver->isTrue($inputDto->consignorSalesTaxHP);
        }
        if (isset($inputDto->consignorSalesTaxServices)) {
            $consignor->BuyerTaxServices = $valueResolver->isTrue($inputDto->consignorSalesTaxServices);
        }
        $this->setIfAssign($consignor, 'consignorTax', self::STRATEGY_REMOVE_FORMAT);
        if (isset($inputDto->consignorTaxHP)) {
            $consignor->ConsignorTaxHp = $valueResolver->isTrue($inputDto->consignorTaxHP);
        }
        $this->setIfAssign($consignor, 'consignorTaxServices', self::STRATEGY_BOOL);
        if (isset($inputDto->consignorTaxCommission)) {
            $consignor->ConsignorTaxComm = $valueResolver->isTrue($inputDto->consignorTaxCommission);
        }
        if (isset($inputDto->exclusive)) {
            $consignor->ConsignorTaxHpType = $valueResolver->isTrue($inputDto->exclusive)
                ? Constants\Consignor::TAX_HP_EXCLUSIVE
                : Constants\Consignor::TAX_HP_INCLUSIVE;
        }

        // User
        if (!$inputDto->id) {
            $user->toActive();
        }

        // New user should be created with direct account of service account unless stated otherwise by $inputDto->accountId
        $this->isUserAccountIdChanged = ($user->AccountId !== $this->userDirectAccountId());
        $user->AccountId = $this->userDirectAccountId();

        $this->setIfAssign($user, 'customerNo');
        $this->setIfAssign($user, 'email');
        if (isset($inputDto->flag)) {
            if (
                $user->Id
                && $inputDto->flag !== Constants\User::FLAG_SOAP_VALUES[$user->Flag]
            ) {
                $this->getUserFlagging()->logFlagChangeByAdmin($user, array_search($inputDto->flag, Constants\User::FLAG_SOAP_VALUES));
            }
            $user->Flag = array_search($inputDto->flag, Constants\User::FLAG_SOAP_VALUES, true) ?: 0;
        }
        $this->setIfAssign($user, 'flagId', self::STRATEGY_SPECIFIC_NAME, 'Flag');
        $this->setIfAssign($user, 'username');
        $this->setIfAssign($user, 'usePermanentBidderno', self::STRATEGY_BOOL);
        $this->setIfAssign($user, 'userStatusId');

        if (isset($inputDto->pword)) {
            $user->Pword = '';
            if ($inputDto->pword) {
                // accept valid hash without rehashing
                $user->Pword = password_get_info($inputDto->pword)['algo']
                    ? $inputDto->pword
                    : PasswordHashHelper::new()->normalizeAndEncrypt($inputDto->pword);
            }
        }
        if (isset($inputDto->userStatus)) {
            $user->UserStatusId = array_search($inputDto->userStatus, Constants\User::USER_STATUS_NAMES, true);
        }

        // User Billing
        $this->setIfAssign($userBilling, 'billingAddress', self::STRATEGY_SPECIFIC_NAME, 'Address');
        $this->setIfAssign($userBilling, 'billingAddress2', self::STRATEGY_SPECIFIC_NAME, 'Address2');
        $this->setIfAssign($userBilling, 'billingAddress3', self::STRATEGY_SPECIFIC_NAME, 'Address3');
        $this->setIfAssign($userBilling, 'billingBankAccountName', self::STRATEGY_SPECIFIC_NAME, 'BankAccountName');
        $this->setIfAssign($userBilling, 'billingBankAccountType', self::STRATEGY_SPECIFIC_NAME, 'BankAccountType');
        $this->setIfAssign($userBilling, 'billingBankName', self::STRATEGY_SPECIFIC_NAME, 'BankName');
        $this->setIfAssign($userBilling, 'billingBankRoutingNumber', self::STRATEGY_SPECIFIC_NAME, 'BankRoutingNumber');
        $this->setIfAssign($userBilling, 'billingCity', self::STRATEGY_SPECIFIC_NAME, 'City');
        $this->setIfAssign($userBilling, 'billingCompanyName', self::STRATEGY_SPECIFIC_NAME, 'CompanyName');
        $this->setIfAssign($userBilling, 'billingEmail', self::STRATEGY_SPECIFIC_NAME, 'Email');
        $this->setIfAssign($userBilling, 'billingFax', self::STRATEGY_SPECIFIC_NAME, 'Fax');
        $this->setIfAssign($userBilling, 'billingFirstName', self::STRATEGY_SPECIFIC_NAME, 'FirstName');
        $this->setIfAssign($userBilling, 'billingLastName', self::STRATEGY_SPECIFIC_NAME, 'LastName');
        $this->setIfAssign($userBilling, 'billingPhone', self::STRATEGY_SPECIFIC_NAME, 'Phone');
        $this->setIfAssign($userBilling, 'billingZip', self::STRATEGY_SPECIFIC_NAME, 'Zip');
        $this->setIfAssign($userBilling, 'opayoTokenId');
        $this->setIfAssign($userBilling, 'ccNumberEway');

        $blockCipher = $this->createBlockCipherProvider()->construct();
        if (isset($inputDto->billingBankAccountNumber)) {
            $userBilling->BankAccountNumber = $blockCipher->encrypt($inputDto->billingBankAccountNumber);
        }
        if (isset($inputDto->authNetCpi)) {
            $userBilling->AuthNetCpi = $blockCipher->encrypt($inputDto->authNetCpi);
        }
        if (isset($inputDto->authNetCppi)) {
            $userBilling->AuthNetCppi = $blockCipher->encrypt($inputDto->authNetCppi);
        }
        if (isset($inputDto->authNetCai)) {
            $userBilling->AuthNetCai = $blockCipher->encrypt($inputDto->authNetCai);
        }
        if (isset($inputDto->payTraceCustId)) {
            $userBilling->PayTraceCustId = $blockCipher->encrypt($inputDto->payTraceCustId);
        }
        if (isset($inputDto->nmiVaultId)) {
            $userBilling->NmiVaultId = $blockCipher->encrypt($inputDto->nmiVaultId);
        }
        if (isset($inputDto->billingContactType)) {
            $userBilling->ContactType = $inputDto->billingContactType ?: Constants\User::CT_NONE;
        }
        if (isset($inputDto->billingCountry)) {
            $userBilling->Country = AddressRenderer::new()->normalizeCountry($inputDto->billingCountry);
        }
        if (isset($inputDto->billingState)) {
            $userBilling->State = AddressRenderer::new()->normalizeState($userBilling->Country, $inputDto->billingState);
        }
        if (isset($inputDto->billingUseCard)) {
            $userBilling->UseCard = $valueResolver->isTrue($inputDto->billingUseCard);
        }

        if (
            $configDto->mode->isWebResponsive()
            || $this->getUserMakerAccessChecker()->hasEditorUserPrivilegeForManageCcInfo()
        ) {
            // Check conditions that should lead to force assigning "Preferred Bidder" privilege
            $hasEffect = $this->createAutoPreferredCreditCardEffectCheckingIntegrator()->hasEffect($this);
            if ($hasEffect) {
                $inputDto->bidderPreferred = 'Y';
                $configDto->autoAssignPreferredBidder = true;
            }

            $this->setIfAssign($userBilling, 'billingCcExpDate', self::STRATEGY_SPECIFIC_NAME, 'CcExpDate');
            if (isset($inputDto->billingCcNumber)) {
                $ccPaymentEway = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::CC_PAYMENT_EWAY);
                $ewayEncryptionKey = trim($this->getSettingsManager()->getForMain(Constants\Setting::EWAY_ENCRYPTION_KEY));
                if ((
                        $ccPaymentEway
                        && $ewayEncryptionKey !== ''
                    )
                    || $this->createBillingGateAvailabilityChecker()->isCcTokenizationEnabled($this->mainAccountId())
                ) {
                    $userBilling->CcNumber = $this->getCcNumberEncrypter()->encryptLastFourDigits($inputDto->billingCcNumber);
                    $userBilling->CcNumberEway = trim((string)$inputDto->ccNumberEway);
                } else {
                    $userBilling->CcNumber = $blockCipher->encrypt($inputDto->billingCcNumber);
                }
                if ($this->getCreditCardValidator()->isFullCcNumber((string)$inputDto->billingCcNumber)) {
                    $userBilling->CcNumberHash = $this->getCcNumberEncrypter()->createHash($inputDto->billingCcNumber);
                }
            }
            if (isset($inputDto->billingCcNumberHash)) {
                $userBilling->CcNumberHash = $inputDto->billingCcNumberHash;
            }
            if (isset($inputDto->billingCcType)) {
                $creditCard = $this->getCreditCardLoader()->loadByName((string)$inputDto->billingCcType);
                $userBilling->CcType = $creditCard->Id ?? null;
            }
            if ($inputDto->billingCcNumber === '') {
                $userBilling->CcExpDate = '';
                $userBilling->CcNumberHash = '';
            }
        }

        if (!$user->CustomerNo) {
            $user->UsePermanentBidderno = false;
        }

        // User Authentication
        if (
            isset($inputDto->pword)
        ) {
            $userAuthentication->PwordDate = $this->getCurrentDateUtc();
        }
        $this->setIfAssign($userAuthentication, 'emailVerified', self::STRATEGY_BOOL);
        $this->setIfAssign($userAuthentication, 'verificationCode');
        if (isset($inputDto->verificationCode)) {
            $userAuthentication->VerificationCodeGeneratedDate = $this->getCurrentDateUtc();
        }

        // User Info
        if ($sm->getForMain(Constants\Setting::ENABLE_USER_COMPANY)) {
            $this->setIfAssign($userInfo, 'companyName');
        }
        $this->setIfAssign($userInfo, 'firstName');
        if (isset($inputDto->identification)) {
            $userInfo->Identification = $this->getUserIdentificationTransformer()->produceValue($inputDto->identification, $inputDto->identificationType);
        }
        if (isset($inputDto->identificationType)) {
            $identificationType = $inputDto->identificationType ?: Constants\User::IDT_NONE;
            $userInfo->IdentificationType = $identificationType;
        }
        $this->setIfAssign($userInfo, 'lastName');
        $this->setIfAssign($userInfo, 'locale');
        $this->setIfAssign($userInfo, 'locationId');
        if (isset($inputDto->location)) {
            $location = $this->getLocationLoader()->loadByName($inputDto->location, $this->userDirectAccountId());
            $userInfo->LocationId = $location->Id ?? null;
        }
        $this->setIfAssign($userInfo, 'maxOutstanding', self::STRATEGY_REMOVE_FORMAT);
        $this->setIfAssign($userInfo, 'newsLetter', self::STRATEGY_BOOL);
        $this->setIfAssign($userInfo, 'noTax', self::STRATEGY_BOOL);
        $this->setIfAssign($userInfo, 'noTaxBp', self::STRATEGY_BOOL);
        if (isset($inputDto->note)) {
            $userInfo->Note = HtmlRenderer::new()->replaceTags($inputDto->note);
        }
        $this->setIfAssign($userInfo, 'phone');
        if (isset($inputDto->phoneType)) {
            $userInfo->PhoneType = $inputDto->phoneType ?: Constants\User::PT_NONE;
        }
        $this->setIfAssign($userInfo, 'referrer');
        if (isset($inputDto->referrer)) {
            $userInfo->ReferrerHost = preg_replace('/(?:https?:\/\/)?(?:www\.)?(.*)\/?$/i', '$1', $inputDto->referrer);
        }
        $this->setIfAssign($userInfo, 'referrerHost');
        $this->setIfAssign($userInfo, 'regAuthDate', self::STRATEGY_DATE_TIME);
        if (
            $sm->getForMain(Constants\Setting::ENABLE_USER_RESUME)
            && $sm->getForMain(Constants\Setting::SHOW_USER_RESUME) === Constants\SettingUser::SUR_ALL
        ) {
            $this->setIfAssign($userInfo, 'resume');
        }
        $this->setIfAssign($userInfo, 'salesTax', self::STRATEGY_REMOVE_FORMAT);
        if (
            $sm->getForMain(Constants\Setting::TEXT_MSG_ENABLED)
            && $userInfo->isMobilePhoneType()
        ) {
            $this->setIfAssign($userInfo, 'sendTextAlerts', self::STRATEGY_BOOL);
        }
        $this->setIfAssign($userInfo, 'taxApplication');
        if (isset($inputDto->taxApplicationName)) {
            $userInfo->TaxApplication = array_search($inputDto->taxApplicationName, Constants\User::TAX_APPLICATION_NAMES, true);
        }
        if (isset($inputDto->timezone)) {
            if ($inputDto->timezone) {
                $timezone = $this->getTimezoneLoader()->loadByLocationOrCreatePersisted($inputDto->timezone);
                $userInfo->TimezoneId = $timezone->Id;
            } else {
                $userInfo->TimezoneId = null;
            }
        }
        if (isset($inputDto->viewLanguage)) {
            $viewLanguage = $this->getViewLanguageLoader()->loadByName($inputDto->viewLanguage, $this->userDirectAccountId());
            $userInfo->ViewLanguage = $viewLanguage->Id ?? null;
        }
        if (isset($inputDto->viewLanguageId)) {
            $userInfo->ViewLanguage = Cast::toInt($inputDto->viewLanguageId);
        }

        // User Log
        if (isset($inputDto->userLog)) {
            $userLog->Note = $inputDto->userLog;
            $userLog->TimeLog = $this->getCurrentDateUtc();
        }

        // User Shipping
        $this->setIfAssign($userShipping, 'shippingAddress', self::STRATEGY_SPECIFIC_NAME, 'Address');
        $this->setIfAssign($userShipping, 'shippingAddress2', self::STRATEGY_SPECIFIC_NAME, 'Address2');
        $this->setIfAssign($userShipping, 'shippingAddress3', self::STRATEGY_SPECIFIC_NAME, 'Address3');
        $this->setIfAssign($userShipping, 'shippingCarrierMethod', self::STRATEGY_SPECIFIC_NAME, 'CarrierMethod');
        $this->setIfAssign($userShipping, 'shippingCity', self::STRATEGY_SPECIFIC_NAME, 'City');
        $this->setIfAssign($userShipping, 'shippingCompanyName', self::STRATEGY_SPECIFIC_NAME, 'CompanyName');
        $this->setIfAssign($userShipping, 'shippingFax', self::STRATEGY_SPECIFIC_NAME, 'Fax');
        $this->setIfAssign($userShipping, 'shippingFirstName', self::STRATEGY_SPECIFIC_NAME, 'FirstName');
        $this->setIfAssign($userShipping, 'shippingLastName', self::STRATEGY_SPECIFIC_NAME, 'LastName');
        $this->setIfAssign($userShipping, 'shippingPhone', self::STRATEGY_SPECIFIC_NAME, 'Phone');
        $this->setIfAssign($userShipping, 'shippingZip', self::STRATEGY_SPECIFIC_NAME, 'Zip');

        if (isset($inputDto->shippingContactType)) {
            $userShipping->ContactType = $inputDto->shippingContactType ?: Constants\User::CT_NONE;
        }

        if (isset($inputDto->shippingCountry)) {
            $userShipping->Country = AddressRenderer::new()->normalizeCountry($inputDto->shippingCountry);
        }
        if (isset($inputDto->shippingState)) {
            $userShipping->State = AddressRenderer::new()->normalizeState($userShipping->Country, $inputDto->shippingState);
        }
        // Set email verified if verification is disabled
        if (isset($inputDto->email)) {
            if (
                !$sm->getForMain(Constants\Setting::VERIFY_EMAIL)
                || !$sm->getForMain(Constants\Setting::SEND_CONFIRMATION_LINK)
            ) {
                $userAuthentication->EmailVerified = true;
                $userAuthentication->VerificationCode = Constants\User::VC_NOCODE;
            }
        }
    }

    /**
     * Get added custom field files names
     * @return string[]
     */
    public function getAddedCustomFieldFiles(): array
    {
        return $this->customFieldManager->getAddedFileNames();
    }

    /**
     * @return void
     */
    protected function saveCimInfo(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();

        $ccTypeName = Constants\CreditCard::$ccTypes[$this->getUserBilling()->CcType][0] ?? null;
        if (
            !$inputDto->billingCcExpDate
            || !$inputDto->billingCcNumber
            || !$this->getCreditCardValidator()->validateNumber($inputDto->billingCcNumber, $ccTypeName)
        ) {
            return;
        }

        /** @var UserInfo $userInfo */
        $userInfo = $this->getUserInfo();
        /** @var UserBilling $userBilling */
        $userBilling = $this->getUserBilling();
        /** @var UserShipping $userShipping */
        $userShipping = $this->getUserShipping();
        [$ccExpMonth, $ccExpYear] = $this->createCcExpiryDateBuilder()->explode($userBilling->CcExpDate);

        $blockCipher = $this->createBlockCipherProvider()->construct();
        $params = [
            Constants\BillingAuthNet::P_AUTH_NET_CAI => $blockCipher->decrypt($userBilling->AuthNetCai),
            Constants\BillingAuthNet::P_AUTH_NET_CPI => $blockCipher->decrypt($userBilling->AuthNetCpi),
            Constants\BillingAuthNet::P_AUTH_NET_CPPI => $blockCipher->decrypt($userBilling->AuthNetCppi),
            Constants\BillingParam::BILLING_ADDRESS => $userBilling->Address,
            Constants\BillingParam::BILLING_CITY => $userBilling->City,
            Constants\BillingParam::BILLING_COMPANY_NAME => $userBilling->CompanyName,
            Constants\BillingParam::BILLING_COUNTRY => $userBilling->Country,
            Constants\BillingParam::BILLING_EMAIL => $userBilling->Email,
            Constants\BillingParam::BILLING_FIRST_NAME => $userBilling->FirstName ?: $userInfo->FirstName,
            Constants\BillingParam::BILLING_LAST_NAME => $userBilling->LastName ?: $userInfo->LastName,
            Constants\BillingParam::BILLING_PHONE => $userBilling->Phone ?: $userInfo->Phone,
            Constants\BillingParam::BILLING_STATE => $userBilling->State,
            Constants\BillingParam::BILLING_ZIP => $userBilling->Zip,
            Constants\BillingParam::CC_CODE => $inputDto->billingCcCode,
            Constants\BillingParam::CC_EXP_MONTH => $ccExpMonth,
            Constants\BillingParam::CC_EXP_YEAR => $ccExpYear,
            Constants\BillingParam::CC_NUMBER => $inputDto->billingCcNumber,
            Constants\BillingParam::CC_NUMBER_HASH => $userBilling->CcNumberHash,
            Constants\BillingParam::CC_TYPE => $userBilling->CcType,
            Constants\BillingNmi::P_NMI_VAULT_ID => $blockCipher->decrypt($userBilling->NmiVaultId),
            Constants\BillingPayTrace::P_PAY_TRACE_CUST_ID => $blockCipher->decrypt($userBilling->PayTraceCustId),
            Constants\BillingParam::SHIPPING_ADDRESS => $userShipping->Address,
            Constants\BillingOpayo::P_OPAYO_TOKEN_ID => $blockCipher->decrypt($userBilling->OpayoTokenId),
            Constants\BillingParam::SHIPPING_CITY => $userShipping->City,
            Constants\BillingParam::SHIPPING_COMPANY_NAME => $userShipping->CompanyName,
            Constants\BillingParam::SHIPPING_COUNTRY => $userShipping->Country,
            Constants\BillingParam::SHIPPING_FIRST_NAME => $userShipping->FirstName,
            Constants\BillingParam::SHIPPING_LAST_NAME => $userShipping->LastName,
            Constants\BillingParam::SHIPPING_PHONE => $userShipping->Phone,
            Constants\BillingParam::SHIPPING_STATE => $userShipping->State,
            Constants\BillingParam::SHIPPING_ZIP => $userShipping->Zip,
        ];

        $paymentGateway = PaymentGatewayFactory::new()->getActivePaymentGateway($this->mainAccountId());
        if (!$paymentGateway) {
            return;
        }
        $paymentProcessor = $paymentGateway->save(
            $params,
            $this->getUser(),
            $userBilling,
            $configDto->wasCcModified,
            $configDto->editorUserId
        );

        // TODO: get from $this->getUserBilling()
        if (isset($paymentProcessor['result'][Constants\BillingAuthNet::P_AUTH_NET_CPI])) {
            $this->cimCpi = $paymentProcessor['result'][Constants\BillingAuthNet::P_AUTH_NET_CPI];
        }
        if (isset($paymentProcessor['result'][Constants\BillingPayTrace::P_PAY_TRACE_CUST_ID])) {
            $this->cimCpi = $paymentProcessor['result'][Constants\BillingPayTrace::P_PAY_TRACE_CUST_ID];
        }
    }

    /**
     * @return bool
     */
    protected function hasBidderPrivileges(): bool
    {
        $inputDto = $this->getInputDto();
        return $this->getUserMakerAccessChecker()->hasEditorUserSubPrivilegeForUserPrivileges()
            && (
                $this->getBidder()->Id
                || ValueResolver::new()->isTrue($inputDto->bidder)
            );
    }

    /**
     * @return bool
     */
    protected function hasConsignorPrivileges(): bool
    {
        $inputDto = $this->getInputDto();
        return $this->getUserMakerAccessChecker()->hasEditorUserSubPrivilegeForUserPrivileges()
            && (
                $this->getConsignor()->Id
                || ValueResolver::new()->isTrue($inputDto->consignor)
            );
    }

    /**
     * @return bool
     */
    protected function hasSalesStaffPrivileges(): bool
    {
        $inputDto = $this->getInputDto();
        return $this->getUserMakerAccessChecker()->hasEditorUserSubPrivilegeForUserPrivileges()
            && ((
                    $this->getAdmin()->Id
                    && $this->getUserAdminPrivilegeChecker()->hasPrivilegeForSalesStaff()
                ) || (
                    ValueResolver::new()->isTrue($inputDto->admin)
                    && ValueResolver::new()->isTrue($inputDto->salesStaff)
                )
            );
    }

    protected function updateConsignorCommissionFee(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $accountId = $inputDto->consignorCommissionFeeAccountId ?: $this->entityContextAccountId();
        $userConsignorCommissionFee = $this->createUserConsignorCommissionFeeLoader()
            ->loadOrCreate((int)$inputDto->id, $accountId);

        $commissionDto = ConsignorCommissionFeeRelatedEntityDto::new()->fromEntityMakerInputDto(
            $inputDto,
            'consignorCommissionId',
            'consignorCommissionRanges',
            'consignorCommissionCalculationMethod'
        );
        $userConsignorCommissionFee->CommissionId = $this->createConsignorCommissionFeeRelatedEntityProducer()->update(
            $userConsignorCommissionFee->CommissionId,
            $commissionDto,
            Constants\ConsignorCommissionFee::LEVEL_USER,
            Cast::toInt($inputDto->id),
            $configDto->editorUserId,
            $configDto->mode
        );

        $soldFeeDto = ConsignorCommissionFeeRelatedEntityDto::new()->fromEntityMakerInputDto(
            $inputDto,
            'consignorSoldFeeId',
            'consignorSoldFeeRanges',
            'consignorSoldFeeCalculationMethod',
            'consignorSoldFeeReference'
        );
        $userConsignorCommissionFee->SoldFeeId = $this->createConsignorCommissionFeeRelatedEntityProducer()->update(
            $userConsignorCommissionFee->SoldFeeId,
            $soldFeeDto,
            Constants\ConsignorCommissionFee::LEVEL_USER,
            Cast::toInt($inputDto->id),
            $configDto->editorUserId,
            $configDto->mode
        );

        $unsoldFeeDto = ConsignorCommissionFeeRelatedEntityDto::new()->fromEntityMakerInputDto(
            $inputDto,
            'consignorUnsoldFeeId',
            'consignorUnsoldFeeRanges',
            'consignorUnsoldFeeCalculationMethod',
            'consignorUnsoldFeeReference'
        );
        $userConsignorCommissionFee->UnsoldFeeId = $this->createConsignorCommissionFeeRelatedEntityProducer()->update(
            $userConsignorCommissionFee->UnsoldFeeId,
            $unsoldFeeDto,
            Constants\ConsignorCommissionFee::LEVEL_USER,
            Cast::toInt($inputDto->id),
            $configDto->editorUserId,
            $configDto->mode
        );
        $this->getUserConsignorCommissionFeeWriteRepository()
            ->saveWithModifier($userConsignorCommissionFee, $configDto->editorUserId);
    }

    /**
     * @param UserAuthentication|UserBilling|UserInfo|UserLog|UserShipping $entity
     * @return bool
     */
    protected function isModified(UserAuthentication|UserBilling|UserInfo|UserLog|UserShipping $entity): bool
    {
        return count($entity->__Modified)
            && !(array_key_exists('UserId', $entity->__Modified)
                && count($entity->__Modified) === 1);
    }

    /**
     * @param string $field
     * @param string $privilege
     */
    protected function assignSubPrivilege(string $field, string $privilege): void
    {
        $inputDto = $this->getInputDto();
        if (!isset($inputDto->$field)) {
            return;
        }

        $this->getAdmin()->$privilege = ValueResolver::new()->isTrue($inputDto->$field);
    }

    /**
     * @param string $field
     * @param int $privilege
     */
    protected function assignAdminPrivilege(string $field, int $privilege): void
    {
        $inputDto = $this->getInputDto();
        if (isset($inputDto->$field)) {
            $admin = $this->getAdminOrCreate();
            if (ValueResolver::new()->isTrue($inputDto->$field)) {
                $this->setAdmin(AdminPrivilegeCalculator::new()->add($admin, $privilege));
            } else {
                $this->setAdmin(AdminPrivilegeCalculator::new()->remove($admin, $privilege));
            }
        }

        if (
            $privilege === Constants\AdminPrivilege::SUPERADMIN
            && !$this->isCrossDomainAdminPrivilegeApplicable()
        ) {
            $admin = $this->getAdminOrCreate();
            $this->setAdmin(AdminPrivilegeCalculator::new()->remove($admin, $privilege));
        }
    }

    /**
     * Create records in user_account_stats_currency for new user for each currency
     */
    protected function createUserAccountStatsCurrency(): void
    {
        $configDto = $this->getConfigDto();
        if (!$this->isUserAccountIdChanged) {
            return;
        }

        foreach (CurrencyLoader::new()->loadAll() as $currency) {
            $this->getUserAccountStatisticProducer()->createUserAccountStatsCurrency(
                $this->getUser()->Id,
                $this->getUser()->AccountId,
                $configDto->editorUserId,
                $currency->Sign
            );
        }
    }

    /**
     * Create record in user_account_stats for new user or if user.account_id is changed
     */
    protected function updateUserAccountStats(): void
    {
        $configDto = $this->getConfigDto();
        if (!$this->isUserAccountIdChanged) {
            return;
        }

        $this->getUserAccountStatisticProducer()->createUserAccountStats(
            $this->getUser()->Id,
            $this->getUser()->AccountId,
            $configDto->editorUserId
        );
    }

    protected function isCrossDomainAdminPrivilegeApplicable(): bool
    {
        return $this->userDirectAccountId() === $this->mainAccountId();
    }

    protected function isRoleSet(string $field): bool
    {
        $inputDto = $this->getInputDto();
        return isset($inputDto->$field)
            && ValueResolver::new()->isTrue($inputDto->$field);
    }

    protected function shouldDeleteRole(string $field): bool
    {
        $inputDto = $this->getInputDto();
        return isset($inputDto->$field)
            && !ValueResolver::new()->isTrue($inputDto->$field);
    }

    /**
     * Detect or get cached direct account of target user.
     */
    public function userDirectAccountId(): int
    {
        $userDirectAccountId = $this->fetchOptional(self::OP_TARGET_USER_DIRECT_ACCOUNT_ID);
        if ($userDirectAccountId === null) {
            $userDirectAccountId = $this->detectUserDirectAccountId();
            $this->setOptional(self::OP_TARGET_USER_DIRECT_ACCOUNT_ID, $userDirectAccountId);
        }
        return $userDirectAccountId;
    }

    /**
     * Detects user's direct account id for assigning to user.account_id
     */
    protected function detectUserDirectAccountId(): int
    {
        $input = UserDirectAccountDetectionInput::new()
            ->fromMakerDto($this->getInputDto(), $this->getConfigDto());
        $optionals = $this->getUserId()
            ? [UserDirectAccountDetector::OP_TARGET_USER => $this->getUser()]
            : [];
        $detector = $this->getUserDirectAccountDetector()->construct($optionals);
        return $detector->detect($input);
    }

    public function entityContextAccountId(): int
    {
        return $this->getConfigDto()->serviceAccountId ?? $this->userDirectAccountId();
    }

    protected function mainAccountId(): int
    {
        return $this->cfg()->get('core->portal->mainAccountId');
    }

    protected function initOptionals(array $optionals): void
    {
        $this->setOptionals($optionals);
    }

    protected function updateLocations(): void
    {
        $inputDto = $this->getInputDto();

        if (isset($inputDto->specificLocation)) {
            $location = $this->createLocationSavingIntegrator()->save($this, $inputDto->specificLocation, Constants\Location::TYPE_USER, $this->userDirectAccountId());
            $this->setEntityLocation(Constants\Location::TYPE_USER, $location);
        }

        $this->createLocationSavingIntegrator()->removeExcessCommonOrSpecificLocation(
            $this,
            'specificLocation',
            ['location', 'locationId'],
            Constants\Location::TYPE_USER,
            $this->getUserInfoOrCreate(),
            'LocationId',
        );
    }
}
