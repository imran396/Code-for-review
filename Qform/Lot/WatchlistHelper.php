<?php
/**
 * Common methods for watchlist.
 * Responsive : Implementation of "Add to watchlist" checkbox
 * @see https://bidpath.atlassian.net/browse/SAM-3233
 *
 * @copyright   2018 Bidpath, Inc.
 * @author      Maxim Lyubetskiy
 * @package     com.swb.sam2
 * @version     SVN: $Id$
 * @since       JuL 19, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Qform\Lot;

use QForm;
use QWaitIcon;
use Sam\Application\UserDataStorage\UserDataStorageCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Qform\LinkButton;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\User\Watchlist\WatchlistManagerAwareTrait;
use UnexpectedValueException;

/**
 * Class WatchlistHelperAbstract
 */
class WatchlistHelper extends CustomizableClass
{
    use SystemAccountAwareTrait;
    use TranslatorAwareTrait;
    use UserDataStorageCreateTrait;
    use WatchlistManagerAwareTrait;

    private const STORAGE_KEY = "addwatch";

    protected ?QForm $parentObject = null;
    protected ?LinkButton $removeButton = null;
    protected ?LinkButton $addButton = null;
    protected ?QWaitIcon $iconWait = null;
    protected ?int $userId = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $added
     * @return string
     */
    public function generateSuccessfullyAddedMessage(int $added): string
    {
        $textFormat = $this->getTranslator()->translate(
            'CATALOG_LOT_SUCCESSFULLY_ADDED_WATCHLIST',
            'catalog',
            $this->getSystemAccountId()
        );

        $message = sprintf($textFormat, $added);
        return $message;
    }

    /**
     * Add a lot to watchlist for logged user
     * @param int $lotItemId
     * @param int $auctionId
     * @param int $editorUserId
     * @return bool
     */
    public function add(int $lotItemId, int $auctionId, int $editorUserId): bool
    {
        if (!$this->getUserId()) {
            return false;
        }

        $isFound = $this->getWatchlistManager()->exist($this->getUserId(), $lotItemId, $auctionId);
        if ($isFound) {
            return false;
        }

        $this->getWatchlistManager()->add($this->getUserId(), $lotItemId, $auctionId, $editorUserId);
        return true;
    }

    /**
     * Add lots to user's watchlist and return added lot count.
     * @param array $lotItemIdAndAuctionIdPairs [0 => <auction.id>, 1 => <lot_item.id>]
     * @param int $editorUserId
     * @return int
     */
    public function addAll(array $lotItemIdAndAuctionIdPairs, int $editorUserId): int
    {
        $totalAdded = 0;
        foreach ($lotItemIdAndAuctionIdPairs as [$lotItemId, $auctionId]) {
            $added = (int)$this->add((int)$lotItemId, (int)$auctionId, $editorUserId);
            $totalAdded += $added;
        }
        return $totalAdded;
    }

    /**
     * @param int $lotItemId
     * @param int $auctionId
     * @param int $editorUserId
     */
    public function remove(int $lotItemId, int $auctionId, int $editorUserId): void
    {
        $watchlistManager = $this->getWatchlistManager();
        if ($watchlistManager->exist($this->getUserId(), $lotItemId, $auctionId)) {
            $watchlistManager->remove($this->getUserId(), $lotItemId, $auctionId, $editorUserId);
        }
    }

    /**
     * @param int $lotItemId
     * @param int $auctionId
     */
    public function storeLotForGuestUser(int $lotItemId, int $auctionId): void
    {
        $this->createUserDataStorage()->set(self::STORAGE_KEY, [[$lotItemId, $auctionId]]);
    }

    /**
     * @param array $auctionAndLotIds
     *   [
     *   [0 => <lot_item.id>, 1 => <auction.id>],
     *   [0 => <lot_item.id>, 1 => <auction.id>]
     *   ...
     *   ]
     */
    //     public function storeLotFromGuestUser(array $auctionAndLotIds)
    //     {
    //         $this->getUtilStorage()->save('addwatch', $auctionAndLotIds);
    //     }

    /**
     * Checks if user is logged in and pending lots are stored, then add lots to watchlist
     * @return int
     */
    public function processStoredLotsAddToWatchlist(): int
    {
        $totalAdded = 0;
        if ($this->getUserId()) {
            $lotItemIdAndAuctionIdPairs = $this->createUserDataStorage()->get(self::STORAGE_KEY);
            if ($lotItemIdAndAuctionIdPairs) {
                if (is_string($lotItemIdAndAuctionIdPairs)) {
                    $lotItemIdAndAuctionIdPairs = json_decode($lotItemIdAndAuctionIdPairs);
                }
                $totalAdded = $this->addAll($lotItemIdAndAuctionIdPairs, $this->getUserId());
                $this->createUserDataStorage()->remove(self::STORAGE_KEY);
            }
        }
        return $totalAdded;
    }

    //<editor-fold desc="Getters\Setters">

    /**
     * @return QForm|null
     */
    public function getParentObject(): ?QForm
    {
        return $this->parentObject;
    }

    /**
     * @param QForm $parentObject
     * @return static
     */
    public function setParentObject(QForm $parentObject): static
    {
        $this->parentObject = $parentObject;
        return $this;
    }

    /**
     * @return QWaitIcon|null
     */
    public function getIconWait(): ?QWaitIcon
    {
        return $this->iconWait;
    }

    /**
     * @param QWaitIcon $iconWait
     * @return static
     */
    public function setIconWait(QWaitIcon $iconWait): static
    {
        $this->iconWait = $iconWait;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @param int|null $userId
     * @return static
     */
    public function setUserId(?int $userId): static
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return LinkButton
     */
    public function getRemoveButton(): LinkButton
    {
        if ($this->removeButton === null) {
            throw new UnexpectedValueException("The button must be instance of class LinkButton, null given");
        }
        return $this->removeButton;
    }

    /**
     * @param LinkButton $removeButton
     * @return static
     */
    public function setRemoveButton(LinkButton $removeButton): static
    {
        $this->removeButton = $removeButton;
        return $this;
    }

    /**
     * @return LinkButton
     */
    public function getAddButton(): LinkButton
    {
        if ($this->addButton === null) {
            throw new UnexpectedValueException("The button must be instance of class LinkButton, null given");
        }
        return $this->addButton;
    }

    /**
     * @param LinkButton $addButton
     * @return static
     */
    public function setAddButton(LinkButton $addButton): static
    {
        $this->addButton = $addButton;
        return $this;
    }
}
