<?php
/**
 * SAM-6504: Move classes from legacy Api namespace to \Sam\Api namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 14, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\Json;


use GuzzleHttp\Psr7\ServerRequest;
use JsonException;
use RuntimeException;
use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Application\Session\PhpSessionKillerCreateTrait;
use Sam\Application\Url\Domain\AccountDomainDetectorCreateTrait;
use Sam\Application\Url\DomainDestination\DomainDestinationDetectorCreateTrait;
use Sam\Auction\Available\AuctionAvailabilityCheckerFactory;
use Sam\Bidder\AuctionBidder\Load\UserInfoLoader;
use Sam\Cache\Memory\MemoryCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Lot\LotList\Catalog;
use Sam\Core\Lot\LotList\Catalog\DataSourceMysql;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Base\Help\BaseCustomFieldHelperAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotItemCustField\LotItemCustFieldReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotItemCustFieldLotCategory\LotItemCustFieldLotCategoryReadRepositoryCreateTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Privilege\Validate\AdminPrivilegeCheckerAwareTrait;
use Sam\User\Privilege\Validate\RoleCheckerAwareTrait;
use User;
use Wavebid\CryptoHelper;
use Wavebid\PostAuction;

/**
 * Class JsonApiWrapper
 * @package Sam\Api\Json
 */
class JsonApiWrapper extends CustomizableClass
{
    use AccountDomainDetectorCreateTrait;
    use AccountLoaderAwareTrait;
    use AdminPrivilegeCheckerAwareTrait;
    use AuctionReadRepositoryCreateTrait;
    use BaseCustomFieldHelperAwareTrait;
    use ConfigRepositoryAwareTrait;
    use DomainDestinationDetectorCreateTrait;
    use EditorUserAwareTrait;
    use LotCategoryLoaderAwareTrait;
    use LotItemCustFieldLotCategoryReadRepositoryCreateTrait;
    use LotItemCustFieldReadRepositoryCreateTrait;
    use MemoryCacheManagerAwareTrait;
    use PhpSessionKillerCreateTrait;
    use RoleCheckerAwareTrait;
    use SettingsManagerAwareTrait;
    use SystemAccountAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return an array with configuration
     *
     * @return array
     */
    public function getConfigurationAction(): array
    {
        $accountId = $this->requireAdminPrivilege()->AccountId;
        $isAvailableHybrid = AuctionAvailabilityCheckerFactory::new()
            ->create(Constants\Auction::HYBRID)
            ->isAvailableForAccountId($accountId);
        $imageSizes = $this->cfg()->get('core->image->thumbnail')->toArray();
        $maxDimensions = array_reduce(
            $imageSizes,
            static function ($carry, $item) {
                return [
                    'maxWidth' => max($carry['maxWidth'], $item['width']),
                    'maxHeight' => max($carry['maxHeight'], $item['height'])
                ];
            },
            ['maxWidth' => 0, 'maxHeight' => 0]
        );
        $data = [
            'InstallationHost' => $this->cfg()->get('core->app->httpHost'),
            'Portal' => $this->cfg()->get('core->portal->enabled'),
            'ConcatenatedLotNumbers' => $this->cfg()->get('core->lot->lotNo->concatenated'),
            'ConcatenatedItemNumbers' => $this->cfg()->get('core->lot->itemNo->concatenated'),
            'ConcatenatedSaleNumbers' => $this->cfg()->get('core->auction->saleNo->concatenated'),
            'Hybrid' => $isAvailableHybrid,
            'Image' => $maxDimensions,
        ];
        if ($this->cfg()->get('core->portal->enabled')) {
            $editorUser = $this->getEditorUser();
            if (!$editorUser) {
                log_error("Available EditorUser not found, when making configuration data" . composeSuffix(['uid' => $this->getEditorUserId()]));
                return $data;
            }
            $editorUserAccount = $this->getAccountLoader()->load($editorUser->AccountId);
            if (!$editorUserAccount) {
                log_error("Available editor user account not found, when making configuration data" . composeSuffix(['acc' => $editorUser->AccountId]));
                return $data;
            }

            $data['AccountId'] = $editorUserAccount->Id;
            $data['AccountHost'] = $this->createDomainDestinationDetector()->detect($editorUserAccount)
                ?: $this->createAccountDomainDetector()->detectByAccount($editorUserAccount);
        }
        return $data;
    }

    /**
     * Return an array with lot item custom field definitions
     * @return array
     */
    public function getLotItemCustomFieldsAction(): array
    {
        $user = $this->requireAdminPrivilege();

        $customFields = $this->createLotItemCustFieldReadRepository()
            ->filterActive(true)
            ->orderByOrder()
            ->loadEntities();
        $wbCustomFieldsMap = $this->cfg()->get('wavebid->lot->customFieldMap')->toArray();
        $result = [];
        foreach ($customFields as $customField) {
            $customFieldCats = $this->createLotItemCustFieldLotCategoryReadRepository()
                ->filterLotItemCustFieldId($customField->Id)
                ->joinLotCategoryFilterActive(true)
                ->select(['lc.id', 'lc.name', 'lc.parent_id'])
                ->loadRows();
            // reduce array of lot_category results into an array of category names
            $customFieldCatNames = array_reduce(
                $customFieldCats,
                function ($carry, $item) {
                    if ($item['parent_id']) {
                        // if the current category is a sub category, fetch path of categories from memory or db
                        $item['name'] = $this->getMemoryCacheManager()->load(
                            'licfCatPath' . $item['id'],
                            function () use ($item) {
                                // load the ancestors of the category and reduce to a slash separated string
                                $lotCategory = $this->getLotCategoryLoader()->load((int)$item['id']);
                                if (!$lotCategory) {
                                    log_error(
                                        "Available lot category not found, when building custom field category name" .
                                        composeSuffix(['lc' => $item['id']])
                                    );
                                    $lotAncestors = [];
                                } else {
                                    $lotAncestors = $this->getLotCategoryLoader()->loadCategoryWithAncestors($lotCategory);
                                    $lotAncestors = array_reverse($lotAncestors);
                                }

                                $item['name'] = implode(
                                    '/',
                                    array_map(
                                        static function ($cat) {
                                            $return = $cat->Name;
                                            $enclose = false;
                                            if (str_contains($return, '"')) {
                                                $return = str_replace('"', '""', $return);
                                                $enclose = true;
                                            }
                                            if (str_contains($return, '/')) {
                                                $enclose = true;
                                            }
                                            if ($enclose) {
                                                $return = '"' . $return . '"';
                                            }
                                            return $return;
                                        },
                                        $lotAncestors
                                    )
                                ); // END $item['name'] = implode('/', array_map(function($cat)
                                return $item['name'];
                            } // END function() use ($item){
                        ); // END $item['name'] = $this->getMemoryCacheManager()->load
                    } // END if($item['parent_id'])
                    $carry[] = $item['name'];
                    return $carry;
                },
                []
            ); // END $customFieldCatNames = array_reduce($customFieldCats,...
            $customFieldInfo = [
                'name' => $customField->Name,
                'id' => $customField->Id,
                'type' => $this->getBaseCustomFieldHelper()->makeTypeName($customField->Type),
                'access' => $customField->Access,
                'parameters' => $customField->Parameters,
                'unique' => $customField->Unique,
                'categories' => $customFieldCatNames,
            ];

            // if there is a remote mapping for this field by the field name, then add remoteid attribute
            if (isset($wbCustomFieldsMap[$customField->Name])) {
                $customFieldInfo['remoteid'] = $wbCustomFieldsMap[$customField->Name];
            }

            $result[] = $customFieldInfo;
        }
        return $result;
    }

    /**
     * Post bidder information of the auction to wavebid
     * @param int $auctionId
     * @return array|false
     */
    public function postBiddersToWavebidAction(int $auctionId): array|false
    {
        $accountId = $this->requireAdminPrivilege()->AccountId;

        $auctionRow = $this->createAuctionReadRepository()
            ->filterId($auctionId)
            ->filterAccountId($accountId)
            ->select(['a.wavebid_auction_guid'])
            ->loadRow();
        if (!empty($auctionRow['wavebid_auction_guid'])) {
            $endpoint = (string)$this->getSettingsManager()->get(Constants\Setting::WAVEBID_ENDPOINT, $accountId);
            $uat = (string)$this->getSettingsManager()->get(Constants\Setting::WAVEBID_UAT, $accountId);
            $uatPlain = trim(CryptoHelper::new()->decryptUat($uat));
            log_debug($uatPlain);

            $wbApi = PostAuction::new();
            $wbApi->setEndpoint($endpoint)->setUat($uatPlain);
            return $wbApi->postBidders($auctionId);
        }

        return false;
    }

    /**
     * Return array with list of auction bidder related fields available
     *
     * @return array
     */
    public function getBidderFieldsAction(): array
    {
        $user = $this->requireAdminPrivilege();
        $adminPrivilegeChecker = $this->getAdminPrivilegeChecker()->initByUserId($user->Id);
        if (!$adminPrivilegeChecker->hasPrivilegeForManageUsers()) {
            $message = "Requires manage user privileges";
            log_error($message);
            throw new RuntimeException($message);
        }

        $uiLoader = UserInfoLoader::new();
        $uiLoader->init($user);
        return $uiLoader->getListOfAvailableFields();
    }

    /**
     * Get list of bidders registered and approved for an auction
     *
     * @param int $auctionId auction.id
     * @param string|null $filters optional active or winning
     * @param array|string|null $fields array of field names, or comma separated @see getUserFieldsAvailableAction
     * @return array
     */
    public function getBiddersAction(int $auctionId, string $filters = null, array|string|null $fields = null): array
    {
        $user = $this->requireAdminPrivilege();
        $adminPrivilegeChecker = $this->getAdminPrivilegeChecker()->initByUserId($user->Id);
        if (!$adminPrivilegeChecker->hasPrivilegeForManageUsers()) {
            $message = "Requires manage user privileges";
            log_error($message);
            throw new RuntimeException($message);
        }
        if (!$adminPrivilegeChecker->hasSubPrivilegeForBidders()) {
            $message = "Requires manage auction bidders privileges";
            log_error($message);
            throw new RuntimeException($message);
        }

        $uiLoader = UserInfoLoader::new();
        $uiLoader->init($user);
        $uiLoader->setAuctionId($auctionId);
        if (is_string($fields)) {
            $fields = explode(',', $fields);
        }
        if ($fields !== null) {
            $uiLoader->setFields($fields);
        }

        $filterParts = null;
        if (
            is_string($filters)
            && str_contains($filters, ',')
        ) {
            $filterParts = explode(',', $filters);
        }

        // default method
        $method = 'getBidders';
        if ($filterParts !== null) {
            // Currently we can't combine these filters
            // We currently also don't allow to negate the filters
            // We give the winning filter priority over active bidders
            if (in_array('winning', $filterParts)) {
                $method = 'getWinningBidders';
            } elseif (in_array('active', $filterParts)) {
                $method = 'getActiveBidders';
            }
        }
        return $uiLoader->$method();
    }

    /**
     * Get list of winning lots in an auction with lot number, bidder number and hammer price
     * @param int $auctionId
     *
     * @return array
     * @throws RuntimeException
     */
    public function getWinningLotsAction(int $auctionId): array
    {
        $user = $this->requireAdminPrivilege();
        $adminPrivilegeChecker = $this->getAdminPrivilegeChecker()->initByUserId($user->Id);

        if (!$adminPrivilegeChecker->hasSubPrivilegeForLots()) {
            $message = 'Forbidden, missing manage lots privileges';
            log_error($message);
            throw new RuntimeException($message);
        }

        $count = $this->createAuctionReadRepository()
            ->filterAccountId($user->AccountId)
            ->filterId($auctionId)
            ->count();
        $isAuctionAllowed = $count === 1;

        if (!$isAuctionAllowed) {
            $message = 'Forbidden, access to auction' . composeSuffix(['a' => $auctionId]);
            log_warning($message);
            throw new RuntimeException($message);
        }

        $dataSource = DataSourceMysql::new();
        $lotList = Catalog::new();
        $dataSource->enablePublic(false);
        $dataSource->filterAuctionIds([$auctionId]);
        $dataSource->setUserId($user->Id);
        $dataSource->filterLotStatusIds([Constants\Lot::LS_SOLD]);
        $dataSource->orderBy('order_num asc, lot_num asc');
        $dataSource->setLimit(0);

        $resultSetFields = [
            'lot_num',
            'lot_num_ext',
            'lot_num_prefix',
            'winner_bidder_num',
            'hammer_price',
            'internet_bid',
        ];

        $dataSource->setResultSetFields($resultSetFields);

        $lotList->setDataSource($dataSource);
        return $lotList->getLotListArray();
    }

    public function postInvalidateSessionAction(): array
    {
        $this->requireAdminPrivilege();
        $rawPostData = ServerRequest::fromGlobals()
            ->getBody()
            ->getContents();
        try {
            $postData = json_decode($rawPostData, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            log_error($e->getMessage());
            $postData = [];
        }
        if (!isset($postData['phpsessid'])) {
            throw new RuntimeException('Invalid input. Expecting "phpsessid" json property');
        }
        $this->createPhpSessionKiller()->kill($postData['phpsessid']);
        return ['success' => true];
    }

    /**
     * Make sure there is an authenticated user
     * With admin privileges
     *
     * @return User
     */
    protected function requireAdminPrivilege(): User
    {
        $currentUser = $this->getUserLoader()->load($this->getEditorUserId());
        if (!$currentUser) {
            $message = "Missing authentication";
            log_error($message);
            throw new RuntimeException($message);
        }

        if (!$this->getRoleChecker()->isAdmin($currentUser->Id)) {
            $message = "Requires admin privileges";
            log_error($message);
            throw new RuntimeException($message);
        }
        return $currentUser;
    }
}
