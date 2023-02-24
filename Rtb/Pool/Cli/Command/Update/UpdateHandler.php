<?php
/**
 * Update command handler, it refreshes auction bindings to rtbd instances in pool
 *
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/7/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Cli\Command\Update;

use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Cli\HandlerBase;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Rtb\Pool\Auction\Save\AuctionRtbdUpdaterAwareTrait;
use Sam\Rtb\Pool\Auction\Validate\AuctionRtbdCheckerCreateTrait;
use Sam\Rtb\Pool\Config\RtbdPoolConfigManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\Account\AccountReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;

/**
 * Class UpdateHandler
 * @package Sam\Rtb\Pool\Cli\Command\Update
 */
class UpdateHandler extends HandlerBase
{
    use AccountReadRepositoryCreateTrait;
    use AuctionRendererAwareTrait;
    use AuctionReadRepositoryCreateTrait;
    use AuctionRtbdCheckerCreateTrait;
    use AuctionRtbdUpdaterAwareTrait;
    use EditorUserAwareTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use RtbdPoolConfigManagerAwareTrait;

    /**
     * @var int[]|null
     */
    private ?array $filterAuctionStatusId = null;
    private bool $shouldUpdateLinked = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function handle(): void
    {
        $chunkSize = 100;
        $rtbdPoolConfigManager = $this->getRtbdPoolConfigManager();
        $accountRepo = $this->createAccountReadRepository()
            ->enableDistinct(true)
            ->filterActive(true);
        if ($this->getFilterAccountId()) {
            $accountRepo->filterId($this->getFilterAccountId());
        }
        if ($this->getFilterAuctionId()) {
            $accountRepo->joinAuctionFilterId($this->getFilterAuctionId());
        }
        $accounts = $accountRepo->loadEntities();

        $rtbdNames = $rtbdPoolConfigManager->getRtbdNames();
        foreach ($accounts as $account) {
            $auctionRepo = $this->createAuctionReadRepository()
                ->joinAuctionRtbd()
                ->filterAccountId($account->Id)
                ->filterAuctionType(Constants\Auction::RTB_AUCTION_TYPES)
                ->filterAuctionStatusId($this->getFilterAuctionStatusId())
                ->limit($chunkSize)
                ->orderById()
                ->select(
                    [
                        'a.account_id',
                        'a.id',
                        'a.sale_num',
                        'a.sale_num_ext',
                        'artbd.rtbd_name'
                    ]
                );
            if ($this->shouldUpdateLinked()) {
                // we have to go over every record chunk by chunk
                $auctionRepo->setChunkSize($chunkSize);
            }
            if (!$this->shouldUpdateLinked()) {
                // it skips already registered and correct instances.
                // when we update, we shouldn't setChunkSize, because it increases offset in LIMIT clause
                // but we update db state and next query returns another smaller dataset
                $auctionRepo->joinAuctionRtbdSkipRtbdName($rtbdNames);
            }
            if ($this->getFilterAuctionId()) {
                $auctionRepo->filterId($this->getFilterAuctionId());
            }
            $isUpdateStartedForAccount = false;
            while ($auctionRows = $auctionRepo->loadRows()) {
                if (!$isUpdateStartedForAccount) {
                    $this->output(
                        '# Start updating auctions for account'
                        . composeSuffix(['acc' => $account->Id, 'name' => $account->Name])
                    );
                    $isUpdateStartedForAccount = true;
                }
                foreach ($auctionRows as $row) {
                    if (
                        $this->shouldUpdateLinked()
                        || !in_array($row['rtbd_name'], $rtbdNames, true)
                        // TODO: do we want to check, if a.account_id corresponds includeAccount, excludeAccount rules? Then we should process all auctions each time.
                        // TODO: do we want another check, that may be dependent on discovery strategy?
                    ) {
                        $updater = $this->getAuctionRtbdUpdater()
                            ->setAuctionId(Cast::toInt($row['id']))
                            ->setEditorUser($this->getEditorUser());
                        $updater->updateBySuggestedAndPersist();
                        $auctionRtbd = $updater->getAuctionRtbd();
                        $newRtbdName = $auctionRtbd->RtbdName ?? '';
                        $oldRtbdName = $updater->getOldRtbdName();
                        if ($oldRtbdName !== $newRtbdName) {
                            $message = 'Auction updated '
                                . composeLogData(
                                    [
                                        'id' => (int)$row['id'],
                                        'new rtbd' => $newRtbdName,
                                        'old rtbd' => $oldRtbdName,
                                        'sale#' => $this->getAuctionRenderer()->makeSaleNo($row['sale_num'], $row['sale_num_ext']),
                                        'acc' => (int)$row['account_id'],
                                    ]
                                );
                            $this->output($message);
                        }
                    }
                }
            }
        }
    }

    /**
     * @return int[]
     */
    public function getFilterAuctionStatusId(): array
    {
        if ($this->filterAuctionStatusId === null) {
            $this->filterAuctionStatusId = Constants\Auction::$openAuctionStatuses;
        }
        return $this->filterAuctionStatusId;
    }

    /**
     * @param int[] $auctionStatusIds
     * @return static
     */
    public function filterAuctionStatusId(array $auctionStatusIds): static
    {
        $this->filterAuctionStatusId = $auctionStatusIds;
        return $this;
    }

    /**
     * @return bool
     */
    public function shouldUpdateLinked(): bool
    {
        return $this->shouldUpdateLinked;
    }

    /**
     * @param bool $isForceUpdate
     * @return static
     */
    public function enableUpdateLinked(bool $isForceUpdate): static
    {
        $this->shouldUpdateLinked = $isForceUpdate;
        return $this;
    }
}
