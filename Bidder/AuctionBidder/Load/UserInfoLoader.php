<?php
/**
 * WAS-6: Post auction SAM to Wavebid integration
 */

namespace Sam\Bidder\AuctionBidder\Load;

use Admin;
use AuctionBidder;
use InvalidArgumentException;
use OutOfBoundsException;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Db\DbTextTransformer;
use Sam\Core\Transform\Text\TextTransformer;
use Sam\CustomField\User\Help\UserCustomFieldHelperAwareTrait;
use Sam\CustomField\User\Load\UserCustomFieldLoaderAwareTrait;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionBidder\AuctionBidderReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionBidder\AuctionBidderReadRepositoryCreateTrait;
use Sam\User\Load\UserLoader;
use Sam\User\Privilege\Validate\AdminPrivilegeChecker;
use User;

/**
 * Class UserInfoLoader
 * @package Sam\Bidder\AuctionBidder\Load
 */
class UserInfoLoader extends CustomizableClass
{
    use AuctionBidderReadRepositoryCreateTrait;
    use AuctionReadRepositoryCreateTrait;
    use UserCustomFieldHelperAwareTrait;
    use UserCustomFieldLoaderAwareTrait;

    private const CUSTOM_FIELD_PANEL_NAME = [
        Constants\UserCustomField::PANEL_BILLING => 'billing',
        Constants\UserCustomField::PANEL_INFO => 'info',
        Constants\UserCustomField::PANEL_SHIPPING => 'shipping',
    ];
    /**
     * @var int|null
     */
    protected ?int $auctionId = null;
    /**
     * @var User|null
     */
    protected ?User $authenticatedUser = null;
    /**
     * @var Admin|null
     */
    protected ?Admin $authenticatedAdmin = null;
    /**
     * @var array
     */
    protected array $fields = [];
    /**
     * @var array<string, array{'table': string, 'field': string}>
     */
    protected array $availableFields = [
        'infoUserId' => ['table' => 'user', 'field' => 'u.id'],
        'infoUsername' => ['table' => 'user', 'field' => 'u.username'],
        'infoEmail' => ['table' => 'user', 'field' => 'u.email'],

        'infoFirstName' => ['table' => 'user_info', 'field' => 'ui.first_name'],
        'infoLastName' => ['table' => 'user_info', 'field' => 'ui.last_name'],
        'infoPhone' => ['table' => 'user_info', 'field' => 'ui.phone'],
        'infoCompany' => ['table' => 'user_info', 'field' => 'ui.company_name'],
        'infoTaxExempt' => ['table' => 'user_info', 'field' => 'IF(ui.sales_tax IS NOT NULL AND ui.sales_tax=0,1,0)'],

        'shippingCompany' => ['table' => 'user_shipping', 'field' => 'us.company_name'],
        'shippingFirstName' => ['table' => 'user_shipping', 'field' => 'us.first_name'],
        'shippingLastName' => ['table' => 'user_shipping', 'field' => 'us.last_name'],
        'shippingPhone' => ['table' => 'user_shipping', 'field' => 'us.phone'],
        'shippingFax' => ['table' => 'user_shipping', 'field' => 'us.fax'],
        'shippingAddress' => ['table' => 'user_shipping', 'field' => 'us.address'],
        'shippingAddress2' => ['table' => 'user_shipping', 'field' => 'us.address2'],
        'shippingAddress3' => ['table' => 'user_shipping', 'field' => 'us.address3'],
        'shippingCity' => ['table' => 'user_shipping', 'field' => 'us.city'],
        'shippingState' => ['table' => 'user_shipping', 'field' => 'us.state'],
        'shippingZip' => ['table' => 'user_shipping', 'field' => 'us.zip'],
        'shippingCountry' => ['table' => 'user_shipping', 'field' => 'us.country'],

        'billingCompany' => ['table' => 'user_billing', 'field' => 'ub.company_name'],
        'billingFirstName' => ['table' => 'user_billing', 'field' => 'ub.first_name'],
        'billingLastName' => ['table' => 'user_billing', 'field' => 'ub.last_name'],
        'billingPhone' => ['table' => 'user_billing', 'field' => 'ub.phone'],
        'billingFax' => ['table' => 'user_billing', 'field' => 'ub.fax'],
        'billingAddress' => ['table' => 'user_billing', 'field' => 'ub.address'],
        'billingAddress2' => ['table' => 'user_billing', 'field' => 'ub.address2'],
        'billingAddress3' => ['table' => 'user_billing', 'field' => 'ub.address3'],
        'billingCity' => ['table' => 'user_billing', 'field' => 'ub.city'],
        'billingState' => ['table' => 'user_billing', 'field' => 'ub.state'],
        'billingZip' => ['table' => 'user_billing', 'field' => 'ub.zip'],
        'billingCountry' => ['table' => 'user_billing', 'field' => 'ub.country'],
        'billingEmail' => ['table' => 'user_billing', 'field' => 'ub.email'],
        'auctionBidderNum' => ['table' => 'auction_bidder', 'field' => 'aub.bidder_num'],
    ];
    /**
     * @var string[]
     */
    protected array $defaultFields = ['infoUsername', 'infoEmail', 'auctionBidderNum'];
    /**
     * Repository customized for this service specific usage
     * @var AuctionBidderReadRepository|null
     */
    protected ?AuctionBidderReadRepository $localAuctionBidderRepository = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Init the class, authenticate executing user
     * @param User $authenticatedUser
     */
    public function init(User $authenticatedUser): void
    {
        $this->authenticatedUser = $authenticatedUser;
        $this->authenticatedAdmin = isset($authenticatedUser->Id)
            ? UserLoader::new()->loadAdmin($authenticatedUser->Id)
            : null;
        $this->initAvailableUserCustomFields();
        $this->localAuctionBidderRepository = $this->createAuctionBidderReadRepository()
            ->joinUserFilterUserStatusId([Constants\User::US_ACTIVE])
            ->addUserCustomDataOptionsToMapping()
            ->filterApproved(true);

        $this->setFields($this->defaultFields);
    }

    /**
     * Get list of available user fields
     * @return array
     */
    public function getListOfAvailableFields(): array
    {
        $this->assertAuthorizedUser();
        return array_keys($this->availableFields);
    }

    /**
     * Initiate available user fields and add custom user fields to mapping
     */
    protected function initAvailableUserCustomFields(): void
    {
        $dbTransformer = DbTextTransformer::new();
        $userCustomFields = $this->getUserCustomFieldLoader()->loadAll();
        foreach ($userCustomFields as $userCustomField) {
            $prefix = self::CUSTOM_FIELD_PANEL_NAME[$userCustomField->Panel] ?? '';
            $ucfName = $prefix . $this->getUserCustomFieldHelper()->makeSoapTagByName($userCustomField->Name);
            $ucfJoinName = 'ucf_' . $dbTransformer->toDbColumn($userCustomField->Name);
            $ucfType = $userCustomField->isNumeric() ? 'numeric' : 'text';
            $this->availableFields[$ucfName] = ['table' => $ucfJoinName, 'field' => $ucfJoinName . '.' . $ucfType];
        }
    }

    /**
     * Set list of fields for the return set
     * @param array $fields
     */
    public function setFields(array $fields): void
    {
        $this->assertAuthorizedUser();

        $availableFields = array_keys($this->availableFields);

        $invalidFields = array_diff($fields, $availableFields);
        if (count($invalidFields) > 0) {
            $message = composeLogData(['Invalid fields' => implode(',', $invalidFields)]);
            log_error($message);
            throw new OutOfBoundsException($message);
        }

        $select = [];
        foreach ($fields as $field) {
            $fieldDetail = $this->availableFields[$field];

            $this->fields[$field] = $fieldDetail;
            $table = $fieldDetail['table'];
            $attribute = $fieldDetail['field'] . ' AS ' . $field;

            $joinMethod = null;
            $param = null;
            if (str_starts_with($table, 'ucf_')) {
                // user cust data table join
                $joinMethod = 'joinUserCustDataByName';
                $param = str_replace('ucf_', '', $table);
            } elseif (!in_array($table, ['auction_bidder', 'user'])) {
                // other tables except auction_bidder
                $joinMethod = 'join' . TextTransformer::new()->toCamelCase($table);
            }

            if ($joinMethod) {
                //log_debug($joinMethod);
                call_user_func([$this->localAuctionBidderRepository, $joinMethod], $param);
            }

            $select[] = $attribute;
        }

        $this->localAuctionBidderRepository->select($select);
    }

    /**
     * Set the auction id
     * @param int $auctionId
     */
    public function setAuctionId(int $auctionId): void
    {
        $this->assertAuthorizedUser();

        if (!is_numeric($auctionId)) {
            $message = 'Invalid auction id of type ' . gettype($auctionId);
            log_error($message);
            throw new InvalidArgumentException($message);
        }

        $count = $this->createAuctionReadRepository()
            ->filterAccountId($this->authenticatedUser->AccountId)
            ->filterId($auctionId)
            ->count();
        $auctionAllowed = $count === 1;

        if (!$auctionAllowed) {
            $message = 'Forbidden, access to auction' . composeSuffix(['a' => $auctionId]);
            log_warning($message);
            throw new InvalidArgumentException($message);
        }

        $this->auctionId = $auctionId;
    }

    /**
     * Get list of bidders
     *
     * @return AuctionBidder[]
     */
    public function getBidders(): array
    {
        $this->assertAuthorizedUser();

        $adminPrivilegeChecker = AdminPrivilegeChecker::new()->initByAdmin($this->authenticatedAdmin);

        if (!$adminPrivilegeChecker->hasSubPrivilegeForBidders()) {
            $message = 'Forbidden, missing manage bidder privileges';
            log_error($message);
            throw new InvalidArgumentException($message);
        }

        if (!$adminPrivilegeChecker->hasPrivilegeForManageUsers()) {
            $message = 'Forbidden, missing manage user privileges';
            log_error($message);
            throw new InvalidArgumentException($message);
        }

        if (!$this->auctionId) {
            $message = 'No auction id set';
            log_error($message);
            throw new InvalidArgumentException($message);
        }

        $this->localAuctionBidderRepository
            ->filterAuctionId($this->auctionId);
        $results = $this->localAuctionBidderRepository->shouldHydrateEntity()
            ? $this->localAuctionBidderRepository->loadEntities()
            : $this->localAuctionBidderRepository->loadRows();

        return $results;
    }

    /**
     * @return AuctionBidder[]
     */
    /**
     * Get list of winning bidders
     * @return array
     */
    public function getWinningBidders(): array
    {
        $this->localAuctionBidderRepository
            ->filterSubqueryHasWinningLot(true);
        return $this->getBidders();
    }

    /**
     * Get list of active bidders
     * @return AuctionBidder[]
     */
    public function getActiveBidders(): array
    {
        $this->localAuctionBidderRepository
            ->filterSubqueryHasAnyBid(true);
        return $this->getBidders();
    }

    /**
     * Check whether init method setting a user with admin privileges at the minimum
     */
    protected function assertAuthorizedUser(): void
    {
        if (!$this->authenticatedUser) {
            $message = 'Forbidden, authentication missing';
            log_warning($message);
            throw new InvalidArgumentException($message);
        }

        if (!$this->authenticatedAdmin) {
            $message = 'Forbidden, missing admin privileges';
            log_warning($message);
            throw new InvalidArgumentException($message);
        }
    }

}
