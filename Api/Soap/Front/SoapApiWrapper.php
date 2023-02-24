<?php

namespace Sam\Api\Soap\Front;

use Sam\Api\Soap\Front\Auth\SoapAccessChecker;
use Sam\Api\Soap\Front\Auth\SoapAuthenticator;
use Sam\Api\Soap\Front\Entity\Account\Controller\AccountSoapController;
use Sam\Api\Soap\Front\Entity\Auction\Controller\AuctionSoapController;
use Sam\Api\Soap\Front\Entity\AuctionLot\Controller\AuctionLotSoapController;
use Sam\Api\Soap\Front\Entity\Location\Controller\LocationSoapController;
use Sam\Api\Soap\Front\Entity\LotCategory\Controller\LotCategorySoapController;
use Sam\Api\Soap\Front\Entity\LotImage\Controller\LotImageSoapController;
use Sam\Api\Soap\Front\Entity\LotItem\Controller\LotItemSoapController;
use Sam\Api\Soap\Front\Entity\User\Controller\UserSoapController;
use Sam\EntityMaker\Base\Data\PlaceBidOutput;
use Sam\Core\Service\CustomizableClass;
use Error;
use RuntimeException;
use Sam\Installation\Config\Repository\ConfigRepository;

/**
 * SOAP API Base Class
 * Used as wrapper for the different SOAP API sub requests for users, auctions, etc
 * Wsdl sees only full namespace path for $data type, ex. \Sam\EntityMaker\Account\Dto\AccountMakerInputMeta $data
 * TODO: refactor create/update/delete User, cacheImages, registerBidder methods
 */
class SoapApiWrapper extends CustomizableClass
{
    // (!) DO NOT IMPORT TRAITS it affects this class parsing

    /**
     * @var SoapAuthenticator
     */
    protected $authenticator;
    /**
     * @var string
     */
    protected $namespace;

    /**
     * Returns an instance of Service
     *
     * @notAutoDiscoverable
     * @return \Sam\Api\Soap\Front\SoapApiWrapper (Please, don't change this value to self (or this). It would throw an exception
     * Cannot add a complex type self that is not an object or where class could not be found
     * in 'DefaultComplexType' strategy.
     */
    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * To initialize instance properties
     *
     * @notAutoDiscoverable
     * @return static
     */
    public function initInstance(): static
    {
        return $this;
    }

    /**
     * Authenticate a user in Soap12:Header
     *
     * @param string $Login Login
     * @param string $Password Password
     * @return string Session ID
     * @throws RuntimeException
     */
    public function authenticate(string $Login, string $Password): string
    {
        $this->authenticator = new SoapAuthenticator();
        $authenticated = $this->authenticator->authenticate($Login, $Password);
        if ($authenticated === false) {
            throw new RuntimeException($this->authenticator->concatenatedErrorMessage());
        }
        return $authenticated;
    }

    /**
     * Default function to set SyncNamespace
     *
     * @param string $Namespace entry in SyncNamespace
     */
    public function setNamespace(string $Namespace): void
    {
        $this->namespace = trim($Namespace);
    }

    /**
     * Test Server response
     *
     * @param string $Message
     * @return string
     */
    public function echoTest($Message): string
    {
        log_debug(var_export($Message, true));
        return $Message;
    }

    /**
     * Create an account
     *
     * @param string $Name The account name
     * @param \Sam\EntityMaker\Account\Dto\AccountMakerInputMeta $Data List of elements for the account, account parameters
     * @return int AccountId
     * @throws RuntimeException
     */
    public function createAccount(string $Name, $Data): int
    {
        $this->checkAuthorizedAndLog(__FUNCTION__, ['name' => $Name, 'data' => $Data]);
        $Data->Name = $Name;
        try {
            return AccountSoapController::new()
                ->init($this->authenticator, $this->namespace)
                ->save($Data);
        } catch (Error $e) {
            $this->handleUnexpectedError($e);
        }
    }

    /**
     * Update an account
     *
     * @param string $Id account.id or account_sync_key depending on the namespace
     * @param \Sam\EntityMaker\Account\Dto\AccountMakerInputMeta $Data List of elements for the account, account parameters
     * @throws RuntimeException
     */
    public function updateAccount(string $Id, $Data): void
    {
        $this->checkAuthorizedAndLog(__FUNCTION__, ['id' => $Id, 'data' => $Data]);
        $Data->Key = $Id;
        try {
            AccountSoapController::new()
                ->init($this->authenticator, $this->namespace)
                ->save($Data);
        } catch (Error $e) {
            $this->handleUnexpectedError($e);
        }
    }

    /**
     * Delete an account
     *
     * @param string $Id account.id or account_sync_key depending on the namespace
     */
    public function deleteAccount($Id): void
    {
        $this->checkAuthorizedAndLog(__FUNCTION__, ['id' => $Id]);
        try {
            AccountSoapController::new()
                ->init($this->authenticator, $this->namespace)
                ->delete($Id);
        } catch (Error $e) {
            $this->handleUnexpectedError($e);
        }
    }

    /**
     * Create an auction
     *
     * @param string $AuctionType (T)Timed or (L)Live
     * @param \Sam\EntityMaker\Auction\Dto\AuctionMakerInputMeta $Data
     * @return int AuctionId
     */
    public function createAuction($AuctionType, $Data = null): ?int
    {
        $this->checkAuthorizedAndLog(__FUNCTION__, ['auctionType' => $AuctionType, 'data' => $Data]);
        $Data->AuctionType = $AuctionType;
        try {
            return AuctionSoapController::new()
                ->init($this->authenticator, $this->namespace)
                ->save($Data);
        } catch (Error $e) {
            $this->handleUnexpectedError($e);
        }
    }

    /**
     * Delete an auction
     *
     * @param string $Id auction.id, auction.sale_num or auction_sync_key depending on the namespace
     */
    public function deleteAuction($Id): void
    {
        $this->checkAuthorizedAndLog(__FUNCTION__, ['id' => $Id]);
        try {
            AuctionSoapController::new()
                ->init($this->authenticator, $this->namespace)
                ->delete((string)$Id);
        } catch (Error $e) {
            $this->handleUnexpectedError($e);
        }
    }

    /**
     * Update an auction
     *
     * @param string $Id auction.id, auction.sale_num or auction_sync_key depending on the namespace
     * @param \Sam\EntityMaker\Auction\Dto\AuctionMakerInputMeta $Data
     */
    public function updateAuction($Id, $Data = null): void
    {
        $this->checkAuthorizedAndLog(__FUNCTION__, ['id' => $Id, 'data' => $Data]);
        $Data->Key = $Id;
        try {
            AuctionSoapController::new()
                ->init($this->authenticator, $this->namespace)
                ->save($Data);
        } catch (Error $e) {
            $this->handleUnexpectedError($e);
        }
    }

    /**
     * Reorder auction lots
     *
     * @param string $Id Id is the synchronization key
     * @return void
     */
    public function reorderAuctionLots(string $Id): void
    {
        $this->checkAuthorizedAndLog(__FUNCTION__, ['id' => $Id]);
        try {
            AuctionSoapController::new()
                ->init($this->authenticator, $this->namespace)
                ->reorderAuctionLots($Id);
        } catch (Error $e) {
            $this->handleUnexpectedError($e);
        }
    }

    /**
     * Refresh auction lots dates
     *
     * @param string $Id Id is the synchronization key
     * @return void
     */
    public function refreshAuctionLotDates(string $Id): void
    {
        $this->checkAuthorizedAndLog(__FUNCTION__, ['id' => $Id]);
        try {
            AuctionSoapController::new()
                ->init($this->authenticator, $this->namespace)
                ->refreshAuctionLotDates($Id);
        } catch (Error $e) {
            $this->handleUnexpectedError($e);
        }
    }

    /**
     * Create an Auction Lot
     *
     * @param string $AuctionId
     * @param string $LotItemId
     * @param \Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerInputMeta $Data List of elements for the auction_lot_item, auction_lot_item parameters
     * @return int AuctionLotItemId
     */
    public function createAuctionLot($AuctionId, $LotItemId, $Data = null): ?int
    {
        $this->checkAuthorizedAndLog(__FUNCTION__, ['AuctionId' => $AuctionId, 'LotItemId' => $LotItemId, 'DATA Array' => $Data]);
        $Data->AuctionId = $AuctionId;
        $Data->LotItemId = $LotItemId;
        try {
            return AuctionLotSoapController::new()
                ->init($this->authenticator, $this->namespace)
                ->save($Data);
        } catch (Error $e) {
            $this->handleUnexpectedError($e);
        }
    }

    /**
     * Update an Auction Lot
     *
     * @param string $Id auction_lot_item.id or auction_lot_item_sync_key depending on the namespace
     * @param \Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerInputMeta $Data
     */
    public function updateAuctionLot($Id, $Data = null): void
    {
        $this->checkAuthorizedAndLog(__FUNCTION__, ['Id' => $Id, 'DATA Array' => $Data]);
        $Data->Key = $Id;
        try {
            AuctionLotSoapController::new()
                ->init($this->authenticator, $this->namespace)
                ->save($Data);
        } catch (Error $e) {
            $this->handleUnexpectedError($e);
        }
    }

    /**
     * Delete an Auction Lot
     *
     * @param string $Id auction_lot_item.id or auction_lot_item_sync_key depending on the namespace
     */
    public function deleteAuctionLot($Id): void
    {
        $this->checkAuthorizedAndLog(__FUNCTION__, ['id' => $Id]);
        try {
            AuctionLotSoapController::new()
                ->init($this->authenticator, $this->namespace)
                ->delete((string)$Id);
        } catch (Error $e) {
            $this->handleUnexpectedError($e);
        }
    }

    /**
     * Place a bid
     *
     * @param string|null $Auction null when field absent in SOAP request
     * @param string|null $Lot null when field absent in SOAP request
     * @param string|null $User null when field absent in SOAP request
     * @param string|null $Amount decimal number formatted according UsNumberFormatting
     * @param string|null $ShouldNotifyUsers null when field absent in SOAP request
     * @return \Sam\EntityMaker\Base\Data\PlaceBidOutput
     */
    public function placeBid(?string $Auction, ?string $Lot, ?string $User, ?string $Amount, ?string $ShouldNotifyUsers): ?PlaceBidOutput
    {
        $this->checkAuthorizedAndLog(__FUNCTION__, ['auction' => $Auction, 'lot' => $Lot, 'user' => $User, 'amount' => $Amount, 'notify' => $ShouldNotifyUsers]);
        try {
            $auctionKey = (string)$Auction;
            $lotKey = (string)$Lot;
            $userKey = (string)$User;
            $amount = (string)$Amount;
            $isNotifyUsers = (bool)$ShouldNotifyUsers;
            return AuctionLotSoapController::new()
                ->init($this->authenticator, $this->namespace)
                ->placeBid($auctionKey, $lotKey, $userKey, $amount, $isNotifyUsers);
        } catch (Error $e) {
            $this->handleUnexpectedError($e);
        }
    }

    /**
     * Cache Images
     *
     * @param \Sam\EntityMaker\Base\Data\Items $Items List of elements for the images
     * @return int count of pending images for caching
     * @throws \Zend_Validate_Exception
     */
    public function cacheImages($Items = null): ?int
    {
        $this->checkAuthorizedAndLog(__FUNCTION__, ['items' => $Items]);
        try {
            return LotImageSoapController::new()
                ->init($this->authenticator, $this->namespace)
                ->cacheImages($Items);
        } catch (Error $e) {
            $this->handleUnexpectedError($e);
        }
    }

    /**
     * Create a Lot Item
     *
     * @param string $Name
     * @param \Sam\EntityMaker\LotItem\Dto\LotItemMakerInputMeta $Data List of elements for the Lot, Lot parameters
     * @return int LotItemId
     */
    public function createItem($Name, $Data = null): ?int
    {
        $this->checkAuthorizedAndLog(__FUNCTION__, ['name' => $Name, 'data' => $Data]);
        $Data->Name = $Name;
        try {
            return LotItemSoapController::new()
                ->init($this->authenticator, $this->namespace)
                ->save($Data);
        } catch (Error $e) {
            $this->handleUnexpectedError($e);
        }
    }

    /**
     * Update a Lot Item
     *
     * @param string $Id Can be lot_item.id, lot_item.itemNumber or lot_item_sync_key depending on the namespace
     * @param \Sam\EntityMaker\LotItem\Dto\LotItemMakerInputMeta $Data List of elements for the Lot, Lot parameters
     */
    public function updateItem($Id, $Data = null): void
    {
        $this->checkAuthorizedAndLog(__FUNCTION__, ['id' => $Id, 'data' => $Data]);
        $Data->Key = $Id;
        try {
            LotItemSoapController::new()
                ->init($this->authenticator, $this->namespace)
                ->save($Data);
        } catch (Error $e) {
            $this->handleUnexpectedError($e);
        }
    }

    /**
     * Delete a Lot Item
     *
     * @param string $Id auction_lot_item.id or auction_lot_item_sync_key depending on the namespace
     */
    public function deleteItem($Id): void
    {
        $this->checkAuthorizedAndLog(__FUNCTION__, ['id' => $Id]);
        try {
            LotItemSoapController::new()
                ->init($this->authenticator, $this->namespace)
                ->delete($Id);
        } catch (Error $e) {
            $this->handleUnexpectedError($e);
        }
    }

    /**
     * Check user authorized and log input data
     * @param $action
     * @param array $args The input data
     */
    private function checkAuthorizedAndLog($action, $args): void
    {
        $message = '';
        foreach ($args as $key => $value) {
            $message .= $key . ': ' . var_export($value, true) . '; ';
        }
        log_trace(composeLogData([$message . 'NAMESPACE' => var_export($this->namespace, true)]));

        $checker = SoapAccessChecker::new()->construct(
            $this->authenticator->getUserId(),
            $this->authenticator->getSystemAccountId()
        );
        if (!$checker->check($action)) {
            throw new RuntimeException($checker->concatenatedErrorMessage());
        }
    }

    /**
     * Create an location
     *
     * @param \Sam\EntityMaker\Location\Dto\LocationMakerInputMeta $Data
     * @return int AuctionId
     */
    public function createLocation($Data = null): ?int
    {
        $this->checkAuthorizedAndLog(__FUNCTION__, ['data' => $Data]);
        try {
            return LocationSoapController::new()
                ->init($this->authenticator, $this->namespace)
                ->save($Data);
        } catch (Error $e) {
            $this->handleUnexpectedError($e);
        }
    }

    /**
     * Update a location
     *
     * @param string $Id location.id or location_sync_key depending on the namespace
     * @param \Sam\EntityMaker\Location\Dto\LocationMakerInputMeta $Data
     */
    public function updateLocation($Id, $Data = null): void
    {
        $this->checkAuthorizedAndLog(__FUNCTION__, ['id' => $Id, 'data' => $Data]);
        $Data->Key = $Id;
        try {
            LocationSoapController::new()
                ->init($this->authenticator, $this->namespace)
                ->save($Data);
        } catch (Error $e) {
            $this->handleUnexpectedError($e);
        }
    }

    /**
     * Delete a location
     *
     * @param string $Id location.id or location_sync_key depending on the namespace
     */
    public function deleteLocation($Id): void
    {
        $this->checkAuthorizedAndLog(__FUNCTION__, ['id' => $Id]);
        try {
            LocationSoapController::new()
                ->init($this->authenticator, $this->namespace)
                ->delete((string)$Id);
        } catch (Error $e) {
            $this->handleUnexpectedError($e);
        }
    }

    /**
     * Create LotCategory
     *
     * @param string $Name
     * @param \Sam\EntityMaker\LotCategory\Dto\LotCategoryMakerInputMeta $Data
     * @return int LotCategory Id
     */
    public function createLotCategory($Name, $Data = null): ?int
    {
        $this->checkAuthorizedAndLog(__FUNCTION__, ['name' => $Name, 'data' => $Data]);
        $Data->Name = $Name;
        try {
            return LotCategorySoapController::new()
                ->init($this->authenticator, $this->namespace)
                ->save($Data);
        } catch (Error $e) {
            $this->handleUnexpectedError($e);
        }
    }

    /**
     * Update LotCategory
     *
     * @param string $Id
     * @param \Sam\EntityMaker\LotCategory\Dto\LotCategoryMakerInputMeta $Data
     * @return int|null
     */
    public function updateLotCategory($Id, $Data = null): ?int
    {
        $this->checkAuthorizedAndLog(__FUNCTION__, ['id' => $Id, 'data' => $Data]);
        $Data->Key = $Id;
        try {
            return LotCategorySoapController::new()
                ->init($this->authenticator, $this->namespace)
                ->save($Data);
        } catch (Error $e) {
            $this->handleUnexpectedError($e);
        }
    }

    /**
     * Delete LotCategory
     *
     * @param string $Id
     * @return void
     */
    public function deleteLotCategory($Id): void
    {
        $this->checkAuthorizedAndLog(__FUNCTION__, ['id' => $Id]);
        try {
            LotCategorySoapController::new()
                ->init($this->authenticator, $this->namespace)
                ->delete($Id);
        } catch (Error $e) {
            $this->handleUnexpectedError($e);
        }
    }

    /**
     * Default reorder all LotCategories
     *
     * @param string $OrderBy
     * @return bool
     */
    public function reorderLotCategories(string $OrderBy = ''): bool
    {
        $this->checkAuthorizedAndLog(__FUNCTION__, []);
        try {
            return LotCategorySoapController::new()
                ->init($this->authenticator, $this->namespace)
                ->reorderLotCategories($OrderBy);
        } catch (Error $e) {
            $this->handleUnexpectedError($e);
        }
    }

    /**
     * Create user
     *
     * @param string $Username Required, needs to be unique in the system
     * @param \Sam\EntityMaker\User\Dto\UserMakerInputMeta $Data List of elements for the user, user_info, user_billing, user_shipping fields
     * @return \Sam\EntityMaker\Base\Data\UserOutput
     */
    public function createUser($Username, $Data = null): ?object
    {
        $this->checkAuthorizedAndLog(__FUNCTION__, ['username' => $Username, 'data' => $Data]);
        $Data->Username = $Username;
        try {
            return UserSoapController::new()
                ->init($this->authenticator, $this->namespace)
                ->save($Data);
        } catch (Error $e) {
            $this->handleUnexpectedError($e);
        }
    }

    /**
     * Update user
     *
     * @param string $Id user.id, user.customer_no or user_sync_key depending on the namespace
     * @param \Sam\EntityMaker\User\Dto\UserMakerInputMeta $Data
     * @return \Sam\EntityMaker\Base\Data\UserOutput
     */
    public function updateUser($Id, $Data = null): ?object
    {
        $this->checkAuthorizedAndLog(__FUNCTION__, ['id' => $Id, 'data' => $Data]);
        $Data->Key = $Id;
        try {
            return UserSoapController::new()
                ->init($this->authenticator, $this->namespace)
                ->save($Data);
        } catch (Error $e) {
            $this->handleUnexpectedError($e);
        }
    }

    /**
     * Delete user
     *
     * @param string $Id user.id, user.customer_no or user.key depending on the namespace
     */
    public function deleteUser($Id): void
    {
        $this->checkAuthorizedAndLog(__FUNCTION__, ['id' => $Id]);
        try {
            UserSoapController::new()
                ->init($this->authenticator, $this->namespace)
                ->delete($Id);
        } catch (Error $e) {
            $this->handleUnexpectedError($e);
        }
    }

    /**
     * Register Bidder
     *
     * @param string $User
     * @param string $Auction
     * @param string $BidderNumber
     * @param string $ForceUpdateBidderNumber
     * @return string
     */
    public function registerBidder($User, $Auction, $BidderNumber, $ForceUpdateBidderNumber = 'N'): string
    {
        $args = [
            'user' => $User,
            'auction' => $Auction,
            'bidderNumber' => $BidderNumber,
            'forceUpdateBidderNumber' => $ForceUpdateBidderNumber
        ];
        $this->checkAuthorizedAndLog(__FUNCTION__, $args);

        $adminPrivilegeChecker = $this->authenticator->getUserAdminPrivilegeChecker();

        if (!$adminPrivilegeChecker->hasPrivilegeForManageAuctions()) {
            throw new RuntimeException('Manage auction privileges required');
        }

        if (
            !$adminPrivilegeChecker->hasSubPrivilegeForBidders()
            || !$adminPrivilegeChecker->hasSubPrivilegeForRemainingUsers()
        ) {
            throw new RuntimeException('Manage auction (bidders and remaining users) privileges required');
        }

        try {
            return AuctionSoapController::new()
                ->init($this->authenticator, $this->namespace)
                ->registerBidder($User, $Auction, $BidderNumber, $ForceUpdateBidderNumber);
        } catch (Error $e) {
            $this->handleUnexpectedError($e);
        }
    }

    /**
     * Handle unexpected errors depending errorFriendlyPage is set or not
     *
     * @param Error $e
     */
    private function handleUnexpectedError(Error $e): never
    {
        $logData = [
            'faultcode' => $e->getCode(),
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ];
        log_error(composeLogData($logData));
        if (ConfigRepository::getInstance()->get('core->app->errorFriendlyPage->path')) {
            throw new Error('Oops! An error has occurred. It has been logged and we\'ll look into it right away.');
        }
        throw new Error($e->getMessage());
    }
}
