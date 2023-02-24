<?php

namespace Sam\Auction\Register;

use Auction;
use AuctionBidderOption;
use AuctionBidderOptionSelection;
use QMySqli5DatabaseResult;
use RuntimeException;
use Sam\AuditTrail\AuditTrailLoggerAwareTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderOptionLoaderCreateTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderOptionSelectionLoaderCreateTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Storage\DeleteRepository\Entity\AuctionBidderOptionSelection\AuctionBidderOptionSelectionDeleteRepositoryCreateTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionBidderOption\AuctionBidderOptionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionBidderOptionSelection\AuctionBidderOptionSelectionReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionBidderOptionSelection\AuctionBidderOptionSelectionWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use User;

/**
 * @property User $User the user to be registered
 * @property Auction $Auction the auction to which the user shall to be registered
 * @property int $AuthorizedUserId user.id of user who is triggering these actions. Will be used for created_by, modified_by
 */
class AuctionRegistrationManager extends CustomizableClass
{
    use AuctionBidderOptionLoaderCreateTrait;
    use AuctionBidderOptionReadRepositoryCreateTrait;
    use AuctionBidderOptionSelectionDeleteRepositoryCreateTrait;
    use AuctionBidderOptionSelectionLoaderCreateTrait;
    use AuctionBidderOptionSelectionReadRepositoryCreateTrait;
    use AuctionBidderOptionSelectionWriteRepositoryAwareTrait;
    use AuditTrailLoggerAwareTrait;
    use CurrentDateTrait;
    use EntityFactoryCreateTrait;
    use SystemAccountAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * @var int|null
     */
    protected ?int $userId;
    /**
     * @var int|null
     */
    protected ?int $auctionId;
    /**
     * @var int|null
     */
    protected ?int $editorUserId;

    /**
     * Return an instance of AuctionRegistrationManager
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @var array
     */
    public static array $registrationConfirmVariables = [
        'RETURN_URL',
        'AUCTION_ID',
        'AUCTION_TYPE',
        'AUCTION_DATE_LONG',
        'AUCTION_DATE_SHORT',
        'AUCTION_NAME',
        'AUCTION_SALE_NO',
        'USERNAME',
        'USER_FIRSTNAME',
        'USER_LASTNAME',
        'REGISTRATION_STATUS',
    ];

    /**
     * Initialize the AuctionRegistrationManager
     * Select the user to register
     * AuthorizedUser is either the user when he registers himself
     * or for example admin when he registers user
     * @param int|null $userId User object or user.id
     * @param int $auctionId Auction object or auction.id
     * @param int|null $editorUserId User object or user.id
     * @return static
     */
    public function construct(?int $userId, int $auctionId, ?int $editorUserId): static
    {
        if (!$userId) {
            throw new RuntimeException('User id undefined');
        }
        if (!$auctionId) {
            throw new RuntimeException('Auction id undefined');
        }
        if (!$editorUserId) {
            throw new RuntimeException('Authorized User id undefined');
        }

        $this->userId = $userId;
        $this->auctionId = $auctionId;
        $this->editorUserId = $editorUserId;
        return $this;
    }

    /**
     * Return array of active AuctionBidderOptions to select from
     * @param int $accountId
     * @return AuctionBidderOption[]
     */
    public function getAuctionBidderOptions(int $accountId): array
    {
        $auctionBidderOptions = $this->createAuctionBidderOptionReadRepository()
            ->filterAccountId($accountId)
            ->filterActive(true)
            ->orderByOrder()
            ->loadEntities();
        return $auctionBidderOptions;
    }

    /**
     * @param int $auctionBidderOptionId
     * @return AuctionBidderOptionSelection|null
     */
    public function getSingleBidderOptionSelection(int $auctionBidderOptionId): ?AuctionBidderOptionSelection
    {
        if ($this->auctionId === null) {
            throw new RuntimeException('Type mismatch: firstly init($User, $Auction, $AuthorizedUser) Registration Manager');
        }
        if ($this->userId === null) {
            throw new RuntimeException('Type mismatch: firstly init($User, $Auction, $AuthorizedUser) Registration Manager');
        }

        $auctionBidderOptionSelection = $this->createAuctionBidderOptionSelectionLoader()->load(
            $this->auctionId,
            $this->userId,
            $auctionBidderOptionId
        );
        return $auctionBidderOptionSelection;
    }

    /**
     * Return array with bidders options
     * @return array([0 => abo.name, 1 => abos.option])
     */
    public function getBiddersOptions(): array
    {
        $rows = $this->createAuctionBidderOptionSelectionReadRepository()
            ->filterAuctionId($this->auctionId)
            ->filterUserId($this->userId)
            ->joinAuctionBidderOption()
            ->select(
                [
                    'abo.name',
                    'abos.option',
                ]
            )
            ->setArrayResultType(QMySqli5DatabaseResult::FETCH_NUM)
            ->loadRows();
        return $rows;
    }

    /**
     * Register Bidders Options Selections
     * @param array $options key => value key = AuctionBidderOption -> Id ,value = AuctionBidderOptionSelection ->  Option
     * @return bool
     */
    public function setAuctionBidderOptionsSelections(array $options): bool
    {
        if ($this->auctionId === null) {
            throw new RuntimeException('Type mismatch: firstly init($User, $Auction, $AuthorizedUser) Registration Manager');
        }
        if ($this->userId === null) {
            throw new RuntimeException('Type mismatch: firstly init($User, $Auction, $AuthorizedUser) Registration Manager');
        }
        if ($this->editorUserId === null) {
            throw new RuntimeException('Type mismatch: firstly init($User, $Auction, $AuthorizedUser) Registration Manager');
        }
        if (empty($options)) {
            throw new RuntimeException('Param can not be empty');
        }

        foreach ($options as $auctionBidderOptionId => $auctionBidderOptionSelectionOption) {
            $auctionBidderOptionSelectionRestored = $this->createAuctionBidderOptionSelectionLoader()->load(
                $this->auctionId,
                $this->userId,
                $auctionBidderOptionId
            );

            if ($auctionBidderOptionSelectionRestored) {
                $auctionBidderOptionSelectionRestored->Option = $auctionBidderOptionSelectionOption;
                $this->getAuctionBidderOptionSelectionWriteRepository()->saveWithModifier($auctionBidderOptionSelectionRestored, $this->editorUserId);
            } else {
                $auctionBidderOptionSelection = $this->createEntityFactory()->auctionBidderOptionSelection();
                $auctionBidderOptionSelection->AuctionBidderOptionId = $auctionBidderOptionId;
                $auctionBidderOptionSelection->AuctionId = $this->auctionId;
                $auctionBidderOptionSelection->Option = $auctionBidderOptionSelectionOption;
                $auctionBidderOptionSelection->UserId = $this->userId;
                $this->getAuctionBidderOptionSelectionWriteRepository()->saveWithModifier($auctionBidderOptionSelection, $this->editorUserId);
            }
            $section = 'register/confirm-bidder-options';
            if ($auctionBidderOptionSelectionOption) {
                $isAccepted = 'accepted Option ';
            } else {
                $isAccepted = 'not accepted Option ';
            }
            $optionName = $this->createAuctionBidderOptionLoader()->load($auctionBidderOptionId, true)->Name ?? '';
            $editorUser = $this->getUserLoader()->load($this->editorUserId, true);
            if (!$editorUser) {
                log_error("Available current user not found");
                return false;
            }
            $event = $editorUser->Username . "  " . $isAccepted . $optionName;
            $accountId = $this->getSystemAccountId();
            $this->getAuditTrailLogger()
                ->setAccountId($accountId)
                ->setEditorUserId($this->editorUserId)
                ->setEvent($event)
                ->setSection($section)
                ->setUserId($this->editorUserId)
                ->log();
        }

        return true;
    }

    /**
     * Reset existing accepted options
     */
    public function resetBiddersAcceptedOptions(): void
    {
        if ($this->auctionId === null) {
            throw new RuntimeException('Type mismatch: firstly init($User, $Auction, $AuthorizedUser) Registration Manager');
        }
        if ($this->userId === null) {
            throw new RuntimeException('Type mismatch: firstly init($User, $Auction, $AuthorizedUser) Registration Manager');
        }

        $this->createAuctionBidderOptionSelectionDeleteRepository()
            ->filterAuctionId($this->auctionId)
            ->filterUserId($this->userId)
            ->delete();
    }
}
