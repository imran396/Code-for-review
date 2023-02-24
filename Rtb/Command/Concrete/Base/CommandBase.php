<?php

namespace Sam\Rtb\Command\Concrete\Base;

use Auction;
use AuctionLotItem;
use BidTransaction;
use Sam\Core\Service\CustomizableClass;
use InvalidArgumentException;
use LotItem;
use RtbCurrent;
use RuntimeException;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Simultaneous\Load\SimultaneousAuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Bidding\BidTransaction\Load\BidTransactionLoaderCreateTrait;
use Sam\Core\Constants;
use Sam\Date\CurrentDateTrait;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Rtb\Base\IHelpersAware;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperFactoryCreateTrait;
use Sam\Rtb\Load\RtbLoaderAwareTrait;
use Sam\Rtb\Server\Daemon\RtbDaemonAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionLotAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotItemAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class CommandBase
 * @package Sam\Rtb\Command\Concrete\Base
 * @method Auction getAuction() - entity existence required by design
 */
abstract class CommandBase extends CustomizableClass implements IHelpersAware, CommandInterface
{
    use AuctionAwareTrait;
    use AuctionBidderLoaderAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuctionLotAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use BidTransactionLoaderCreateTrait;
    use BidderNumPaddingAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CurrencyLoaderAwareTrait;
    use CurrentDateTrait;
    use LotItemAwareTrait;
    use RtbCommandHelperFactoryCreateTrait;
    use RtbDaemonAwareTrait;
    use RtbLoaderAwareTrait;
    use SettingsManagerAwareTrait;
    use SimultaneousAuctionLoaderAwareTrait;
    use TranslatorAwareTrait;
    use UserLoaderAwareTrait;

    protected ?string $bidderNum = null;
    protected ?string $currencySign = null;
    protected ?array $responses = null;
    protected ?RtbCurrent $rtbCurrent = null;
    protected ?int $simultaneousAuctionId = null;
    protected ?Auction $simultaneousAuction = null;
    protected ?int $delayAfterBidAccepted = null;          // by Id of Auction Account
    protected ?int $userType = null;          // type of user, who run command
    protected ?int $modifierUserId = null;
    protected ?int $editorUserId = null;
    protected ?int $viewLanguageId = null;

    /**
     * @override LotItemAwareTrait::getLotItemId()
     * Running lot item
     * @return int|null
     */
    public function getLotItemId(): ?int
    {
        if (!$this->getLotItemAggregate()->getLotItemId()) {
            $this->getLotItemAggregate()->setLotItemId($this->getRtbCurrent()->LotItemId);
        }
        return $this->getLotItemAggregate()->getLotItemId();
    }

    /**
     * @override LotItemAwareTrait::getLotItem()
     * @return LotItem|null
     */
    public function getLotItem(): ?LotItem
    {
        if (!$this->getLotItemAggregate()->getLotItem()) {
            $this->getLotItemAggregate()->setLotItemId($this->getRtbCurrent()->LotItemId);
        }
        return $this->getLotItemAggregate()->getLotItem();
    }

    /**
     * @override AuctionLotAwareTrait::getAuctionLot()
     * @return AuctionLotItem|null
     */
    public function getAuctionLot(): ?AuctionLotItem
    {
        if (!$this->getAuctionLotAggregate()->hasAuctionLot()) {
            $auctionLot = $this->getAuctionLotLoader()->load($this->getRtbCurrent()->LotItemId, $this->getAuctionId());
            $this->getAuctionLotAggregate()->setAuctionLot($auctionLot);
        }
        return $this->getAuctionLotAggregate()->getAuctionLot();
    }

    /**
     * Execute $this command in context of other command and apply responses to context command
     * @param CommandBase $contextCommand
     * @return array
     */
    public function runInContext(CommandBase $contextCommand): array
    {
        $this->initByCommandContext($contextCommand);
        $this->execute();
        $responses = $this->getResponses();
        $contextCommand->setResponses($responses);
        return $responses;
    }

    /**
     * @return array
     */
    public function getResponses(): array
    {
        if (!$this->hasResponses()) {
            $this->createResponses();
        }
        return $this->responses;
    }

    /**
     * Create responses and assign to $responses property
     * Dummy method for overloading in child classes
     */
    protected function createResponses(): void
    {
        $this->responses = [];
    }

    /**
     * @param array $responses
     * @return static
     */
    public function setResponses(array $responses): static
    {
        $responses = $this->getResponseHelper()->addMetaData($responses);
        $this->responses = $responses;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasResponses(): bool
    {
        return $this->responses !== null;
    }

    /**
     * @param RtbCurrent $rtbCurrent
     * @return static
     */
    public function setRtbCurrent(RtbCurrent $rtbCurrent): static
    {
        $this->rtbCurrent = $rtbCurrent;
        return $this;
    }

    /**
     * @return RtbCurrent
     */
    public function getRtbCurrent(): RtbCurrent
    {
        if (!$this->rtbCurrent) {
            $this->reloadRtbCurrent();
        }
        return $this->rtbCurrent;
    }

    /**
     * @return RtbCurrent
     */
    public function reloadRtbCurrent(): RtbCurrent
    {
        if (!$this->getAuction()) { // @phpstan-ignore-line
            throw new RuntimeException("Auction not set");
        }
        $this->rtbCurrent = $this->getRtbLoader()->loadByAuctionIdFromDb($this->getAuctionId());
        if (!$this->rtbCurrent) {
            $rtbCommandHelper = $this->createRtbCommandHelperFactory()->createByAuction($this->getAuction());
            $this->rtbCurrent = $rtbCommandHelper->createRtbCurrent($this->getAuctionId());
        }
        return $this->rtbCurrent;
    }

    /**
     * @param int|null $simultaneousAuctionId
     * @return static
     */
    public function setSimultaneousAuctionId(?int $simultaneousAuctionId): static
    {
        $this->simultaneousAuctionId = $simultaneousAuctionId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getSimultaneousAuctionId(): ?int
    {
        if ($this->simultaneousAuctionId === null) {
            $this->simultaneousAuctionId = $this->getSimultaneousAuctionLoader()
                ->findSimultaneousAuctionId($this->getAuction(), true);
        }
        return $this->simultaneousAuctionId;
    }

    /**
     * @param Auction $auction
     * @return static
     * @noinspection PhpUnused
     */
    public function setSimultaneousAuction(Auction $auction): static
    {
        $this->simultaneousAuction = $auction;
        return $this;
    }

    /**
     * @return Auction|null
     * @noinspection PhpUnused
     */
    public function getSimultaneousAuction(): ?Auction
    {
        if ($this->simultaneousAuction === null) {
            $this->simultaneousAuction = $this->getAuctionLoader()
                ->load($this->getSimultaneousAuctionId(), true);
        }
        return $this->simultaneousAuction;
    }

    /**
     * @param int $delayAfterBidAccepted
     * @return static
     * @noinspection PhpUnused
     */
    public function setDelayAfterBidAccepted(int $delayAfterBidAccepted): static
    {
        $this->delayAfterBidAccepted = $delayAfterBidAccepted;
        return $this;
    }

    /**
     * @return int
     */
    protected function getDelayAfterBidAccepted(): int
    {
        if ($this->delayAfterBidAccepted === null) {
            $this->delayAfterBidAccepted = (int)$this->getSettingsManager()
                ->get(Constants\Setting::DELAY_AFTER_BID_ACCEPTED, $this->getAuction()->AccountId);
        }
        return $this->delayAfterBidAccepted;
    }

    /**
     * @return int
     */
    protected function getViewLanguageId(): int
    {
        if ($this->viewLanguageId === null) {
            $this->viewLanguageId = (int)$this->getSettingsManager()
                ->get(Constants\Setting::VIEW_LANGUAGE, $this->getAuction()->AccountId);
        }
        return $this->viewLanguageId;
    }

    public function setViewLanguageId(int $viewLanguageId): static
    {
        $this->viewLanguageId = $viewLanguageId;
        return $this;
    }

    /**
     * Set type of user, who run command
     * @param int $userType
     * @return static
     */
    public function setUserType(int $userType): static
    {
        $this->userType = $userType;
        return $this;
    }

    /**
     * Return console type
     * @return int
     */
    public function getUserType(): int
    {
        if (!$this->userType) {
            throw new InvalidArgumentException('User type not defined');
        }
        return $this->userType;
    }

    /**
     * @param string $currencySign
     * @return static
     */
    public function setCurrency(string $currencySign): static
    {
        $this->currencySign = $currencySign;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        if ($this->currencySign === null) {
            $this->currencySign = $this->getCurrencyLoader()->detectDefaultSign($this->getAuctionId());
        }
        return $this->currencySign;
    }

    /**
     * @param string $bidderNum
     * @return static
     */
    public function setBidderNum(string $bidderNum): static
    {
        $this->bidderNum = $bidderNum;
        return $this;
    }

    /**
     * @return string
     */
    public function getBidderNum(): string
    {
        if ($this->bidderNum === null) {
            $auctionBidder = $this->getAuctionBidderLoader()->load($this->getEditorUserId(), $this->getAuctionId(), true);
            $this->bidderNum = $auctionBidder
                ? $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum)
                : '';
        }
        return $this->bidderNum;
    }

    /**
     * @return int|null null for anonymous user at visitor console.
     */
    public function getEditorUserId(): ?int
    {
        return $this->editorUserId;
    }

    /**
     * @param int|null $editorUserId
     * @return $this
     */
    public function setEditorUserId(?int $editorUserId): static
    {
        $this->editorUserId = $editorUserId;
        return $this;
    }

    /**
     * Return entity modifier user - he is either authorized user, or system user when the current user is anonymous or not defined.
     * @return int
     */
    public function detectModifierUserId(): int
    {
        if ($this->modifierUserId === null) {
            $this->modifierUserId = $this->getEditorUserId() ?: $this->getUserLoader()->loadSystemUserId();
        }
        return $this->modifierUserId;
    }

    /**
     * Check, if console operates with actually running lot. (SAM-3512)
     * We passed console rendered lot via "LotId" parameter in command request.
     * @return bool
     */
    protected function checkConsoleSync(): bool
    {
        $success = true;
        if ($this->getLotItemId()) {
            $rtbCurrent = $this->getRtbCurrent();
            if ($this->getLotItemId() !== $rtbCurrent->LotItemId) {
                $success = false;
                $logData = [
                    'console li' => $this->getLotItemId(),
                    'rtbc li' => $rtbCurrent->LotItemId,
                    'a' => $rtbCurrent->AuctionId,
                    'u' => $this->getEditorUserId(),
                ];
                log_warning("Out of sync check failed" . composeSuffix($logData));
            }
        }
        return $success;
    }

    /**
     * Check current bid exists
     * @return bool
     */
    protected function checkCurrentBid(): bool
    {
        $auctionLot = $this->getAuctionLot();
        if (!$auctionLot) {
            log_error(
                "Available running lot not found, when checking current bid"
                . composeSuffix(['li' => $this->getLotItemId(), 'a' => $this->getAuctionId()])
            );
            return false;
        }
        $auctionLotCurrentBid = $this->createBidTransactionLoader()->loadById($auctionLot->CurrentBidId);
        $exists = $auctionLotCurrentBid instanceof BidTransaction;
        if (!$exists) {
            log_warning(
                "Current bid not found for running lot"
                . composeSuffix(['a' => $auctionLot->AuctionId, 'li' => $auctionLot->LotItemId])
            );
        }
        return $exists;
    }

    /**
     * Check if running lot is available for play (e.g. it can be deleted)
     * @return bool
     */
    protected function checkRunningLot(): bool
    {
        // Load object instead of count(*) query, because we use it too often
        $auctionLot = $this->getAuctionLot();
        $isFound = $auctionLot instanceof AuctionLotItem;
        if (!$isFound) {
            $rtbCurrent = $this->getRtbCurrent();
            log_warning(
                "Running lot does not exist in auction"
                . composeSuffix(['a' => $rtbCurrent->AuctionId, 'li' => $rtbCurrent->LotItemId])
            );
        }
        return $isFound;
    }

    /**
     * Init by passed command context
     * @param CommandBase $contextCommand
     */
    protected function initByCommandContext(CommandBase $contextCommand): void
    {
        $this->setAuction($contextCommand->getAuction());
        $this->setLogger($contextCommand->getLogger());
        $this->setRtbCurrent($contextCommand->getRtbCurrent());
        $this->setRtbDaemon($contextCommand->getRtbDaemon());
        $this->setUserType($contextCommand->getUserType());
    }

    /**
     * @param string $label
     * @return string
     */
    protected function translate(string $label): string
    {
        $lang = $this->getTranslator()->translateForRtb($label, $this->getAuction());
        return $lang;
    }

}
