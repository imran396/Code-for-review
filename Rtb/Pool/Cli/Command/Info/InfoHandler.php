<?php
/**
 * Info command handler, it renders useful information about installation state.
 *
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/8/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Cli\Command\Info;

use DateTime;
use Sam\Core\Cli\HandlerBase;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Date\Render\DateRendererCreateTrait;
use Sam\Rtb\Pool\Auction\Validate\AuctionRtbdCheckerCreateTrait;
use Sam\Rtb\Pool\Config\RtbdPoolConfigManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\RtbSession\RtbSessionReadRepositoryCreateTrait;
use Symfony\Component\Console\Helper\Table;

/**
 * Class InfoHandler
 * @package
 */
class InfoHandler extends HandlerBase
{
    use AuctionReadRepositoryCreateTrait;
    use AuctionRtbdCheckerCreateTrait;
    use DateRendererCreateTrait;
    use RtbSessionReadRepositoryCreateTrait;
    use RtbdPoolConfigManagerAwareTrait;

    private bool $isAll = false;

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
        $this->renderPoolInstances();
    }

    /**
     * @return bool
     */
    public function isAll(): bool
    {
        return $this->isAll;
    }

    /**
     * @param bool $isAll
     * @return static
     */
    public function enableAll(bool $isAll): static
    {
        $this->isAll = $isAll;
        return $this;
    }

    protected function renderPoolInstances(): void
    {
        $descriptors = $this->getRtbdPoolConfigManager()->getValidDescriptors();
        $table = new Table($this->getOutput());
        $descriptorArray = [];
        foreach ($descriptors as $descriptor) {
            $descriptorArray = $descriptor->toArray();
            $descriptorArray['includeAccount'] = implode(',', $descriptorArray['includeAccount']);
            $descriptorArray['excludeAccount'] = implode(',', $descriptorArray['excludeAccount']);
            $isRunning = $this->createAuctionRtbdChecker()->isRtbdRunning($descriptor);
            $descriptorArray['status'] = $isRunning ? 'Up' : 'Down';
            $descriptorArray['PID'] = null;
            if ($isRunning) {
                $pidFileRootPath = path()->logRun() . '/' . $descriptor->getPidFileName();
                // JIC suppress error, if file has been deleted accidentally / or permission problem
                $pid = @file_get_contents($pidFileRootPath);
                $descriptorArray['PID'] = $pid;
                $fileStatus = @stat($pidFileRootPath);
                $startTs = $fileStatus['mtime'] ?? null;
                if ($startTs) {
                    $startTs = (new DateTime())->getTimestamp() - $startTs;
                    $time = $this->createDateRenderer()->renderTimeLeft($startTs);
                    $descriptorArray['status'] .= ' ' . $time;
                }
            }
            $table->addRow($descriptorArray);
        }

        $headers = array_keys($descriptorArray);
        $table->setHeaders($headers);
        $table->render();

        if ($this->isAll()) {
            foreach ($descriptors as $descriptor) {
                $rtbdName = $descriptor->getName();
                $details = $this->renderConnections($rtbdName);
                $connectionInfo = $details ? '' : 'none';
                $this->output($rtbdName . ': ' . $connectionInfo);
                if ($details) {
                    $this->output($details);
                }
            }
        }
    }

    /**
     * @param string $rtbdName
     * @return string
     */
    protected function renderConnections(string $rtbdName): string
    {
        $stats = $this->collectAuctionStats($rtbdName);
        $lines = [];
        foreach ($stats as $auctionStatusId => $perStatusStats) {
            $lineData = [];
            $linkedIds = $perStatusStats['linkedIds'];
            $activeIds = $perStatusStats['activeIds'];
            if ($linkedIds) {
                $lineData['status'] = Constants\Auction::$auctionStatusNames[$auctionStatusId];
                $lineData['linked ids'] = $linkedIds;
                $lineData['connections'] = count($activeIds);
                if ($activeIds) {
                    $lineData['active ids'] = array_values(array_unique($activeIds));
                }
            }
            if ($lineData) {
                $lines[] = composeLogData($lineData);
            }
        }
        return implode(PHP_EOL, $lines);
    }

    /**
     * @param string $rtbdName
     * @return array
     */
    protected function collectAuctionStats(string $rtbdName): array
    {
        $stats = array_flip(Constants\Auction::$openAuctionStatuses);
        foreach (Constants\Auction::$openAuctionStatuses as $auctionStatusId) {
            $rows = $this->createAuctionReadRepository()
                ->filterAuctionStatusId($auctionStatusId)
                ->joinAuctionRtbdFilterRtbdName($rtbdName)
                ->select(['id'])
                ->loadRows();
            $auctionIds = ArrayCast::arrayColumnInt($rows, 'id');
            $stats[$auctionStatusId] = ['linkedIds' => $auctionIds];
        }

        foreach ($stats as $auctionStatusId => $perStatusStats) {
            $activeSessionAuctionIds = [];
            $auctionIds = $perStatusStats['linkedIds'];
            if ($auctionIds) {
                $rows = $this->createRtbSessionReadRepository()
                    ->filterAuctionId($auctionIds)
                    ->select(['auction_id'])
                    ->loadRows();
                $activeSessionAuctionIds = ArrayCast::arrayColumnInt($rows, 'auction_id');
            }
            $stats[$auctionStatusId]['activeIds'] = $activeSessionAuctionIds;
        }
        return $stats;
    }
}
