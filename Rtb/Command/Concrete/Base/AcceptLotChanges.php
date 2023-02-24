<?php

namespace Sam\Rtb\Command\Concrete\Base;

use AuctionLotItemChanges;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\AuctionLot\Load\AuctionLotItemChangesLoaderCreateTrait;
use Sam\AuctionLot\Validate\AuctionLotExistenceCheckerAwareTrait;
use Sam\AuditTrail\AuditTrailLoggerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Storage\WriteRepository\Entity\AuctionLotItemChanges\AuctionLotItemChangesWriteRepositoryAwareTrait;

/**
 * Class AcceptLotChanges
 * @package Sam\Rtb\Command\Concrete\Base
 */
abstract class AcceptLotChanges extends CommandBase implements RtbCommandHelperAwareInterface
{
    use AuctionLotExistenceCheckerAwareTrait;
    use AuctionLotItemChangesLoaderCreateTrait;
    use AuctionLotItemChangesWriteRepositoryAwareTrait;
    use AuctionRendererAwareTrait;
    use AuditTrailLoggerAwareTrait;
    use EntityFactoryCreateTrait;
    use LotRendererAwareTrait;

    protected string $lotChanges = '';
    protected string $lotChangesStatus = '';

    /**
     * @param string $lotChanges
     * @return static
     */
    public function setLotChanges(string $lotChanges): static
    {
        $this->lotChanges = $lotChanges;
        return $this;
    }

    /**
     * @param string $lotChangesStatus
     * @return static
     */
    public function setLotChangesStatus(string $lotChangesStatus): static
    {
        $this->lotChangesStatus = $lotChangesStatus;
        return $this;
    }

    public function execute(): void
    {
        if (!$this->checkProcessingLot()) {
            $this->getRtbCommandHelper()->createCommand('Sync')->runInContext($this);
            return;
        }

        $status = '';
        // TODO: why "true" value?
        if ($this->lotChangesStatus !== "true") {
            $status .= ' accepted';
            $auctionLotChanges = $this->createAuctionLotItemChangesLoader()->load(
                $this->getEditorUserId(),
                $this->getLotItemId(),
                $this->getAuctionId()
            );
            if (!$auctionLotChanges instanceof AuctionLotItemChanges) {
                $auctionLotChanges = $this->createEntityFactory()->auctionLotItemChanges();
                $auctionLotChanges->AuctionId = $this->getAuctionId();
                $auctionLotChanges->LotItemId = $this->getLotItemId();
                $auctionLotChanges->UserId = $this->getEditorUserId();
                $this->getAuctionLotItemChangesWriteRepository()->saveWithModifier($auctionLotChanges, $this->detectModifierUserId());
                // log event in Audit Trail
            }
        } else {
            $status .= ' refused';
        }
        $saleNo = $this->getAuctionRenderer()->renderSaleNo($this->getAuction());
        $lotName = $this->getLotRenderer()->makeName($this->getLotItem()->Name, $this->getAuction()->TestAuction);
        $auctionName = $this->getAuctionRenderer()->renderName($this->getAuction());
        // add new event to Audit_trail for changed settings
        $section = 'Rtb/lot-changes';
        $editorUser = $this->getUserLoader()->load($this->getEditorUserId(), true);
        $username = $editorUser->Username ?? '';
        $event = "{$username} in Live sale {$auctionName} {$saleNo}{$status} changes, on lot {$lotName} {$this->lotChanges}";
        $accountId = $this->getAuction()->AccountId;
        $this->getAuditTrailLogger()
            ->setAccountId($accountId)
            ->setEditorUserId($this->getEditorUserId())
            ->setEvent(html_entity_decode($event))
            ->setSection($section)
            ->setUserId($this->getEditorUserId())
            ->log();

        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SEND_MESSAGE_S,
            Constants\Rtb::RES_DATA => [
                Constants\Rtb::RES_MESSAGE => html_entity_decode($event),
            ],
        ];
        $responses[Constants\Rtb::RT_CLERK] = json_encode($response);
        $this->setResponses($responses);
    }

    /**
     * Check if processing lot (that accepts changes) exists in auction
     * @return bool
     */
    protected function checkProcessingLot(): bool
    {
        $isFound = $this->getAuctionLotExistenceChecker()->exist($this->getLotItemId(), $this->getAuctionId());
        return $isFound;
    }
}
