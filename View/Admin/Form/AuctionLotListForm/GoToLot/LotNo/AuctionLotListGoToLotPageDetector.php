<?php
/**
 * SAM-5665: Extract page detection logic for "Go to lot#" function at Auction Lot List page of admin side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Jan 02, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\GoToLot\LotNo;

use Sam\AuctionLot\LotNo\Parse\LotNoParserCreateTrait;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionLotListGoToLotPageDetector
 * @package Sam\View\Admin\Form\AuctionLotListForm\GoToLot\LotNo
 */
class AuctionLotListGoToLotPageDetector extends CustomizableClass
{
    use DbConnectionTrait;
    use LotNoParserCreateTrait;

    public const FRAGMENT_TPL = '%s%s%s';

    protected string $lotNo = '';
    protected ?int $itemsPerPage = null;
    protected string $resultQuery = '';
    protected string $lotFragment = '';
    protected int $totalSearchAttemptCount = 0;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $lotNo
     * @return static
     */
    public function setLotNo(string $lotNo): static
    {
        $this->lotNo = trim($lotNo);
        return $this;
    }

    /**
     * @return string
     */
    public function getLotNo(): string
    {
        return $this->lotNo;
    }

    /**
     * @param int $itemsPerPage
     * @return static
     */
    public function setItemsPerPage(int $itemsPerPage): static
    {
        $this->itemsPerPage = $itemsPerPage;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getItemsPerPage(): ?int
    {
        return $this->itemsPerPage;
    }

    /**
     * @param string $resultQuery
     * @return static
     */
    public function setResultQuery(string $resultQuery): static
    {
        $this->resultQuery = trim($resultQuery);
        return $this;
    }

    /**
     * @return string
     */
    public function getResultQuery(): string
    {
        return $this->resultQuery;
    }

    /**
     * Detect go to lot
     * @return bool
     */
    public function detect(): bool
    {
        $lotNoParser = $this->createLotNoParser()->construct();
        $lotFullNumber = $this->getLotNo();
        if (!$lotNoParser->validate($lotFullNumber)) {
            return false;
        }

        $lotNoParsed = $lotNoParser->parse($lotFullNumber);
        $goToLot = sprintf(
            self::FRAGMENT_TPL,
            $lotNoParsed->lotNumPrefix,
            $lotNoParsed->lotNum,
            $lotNoParsed->lotNumExtension
        );
        $this->query($this->getResultQuery());
        $count = 1;
        $isDetected = false;
        while ($row = $this->fetchAssoc()) {
            $checkingLotNo = sprintf(self::FRAGMENT_TPL, $row['lot_num_prefix'], $row['lot_num'], $row['lot_num_ext']);
            if ($checkingLotNo === $goToLot) {
                $isDetected = true;
                $this->lotFragment = "lot{$goToLot}";
                break;
            }
            $count++;
            $this->totalSearchAttemptCount = $count;
        }
        return $isDetected;
    }

    /**
     * @return float
     */
    public function getPage(): float
    {
        $page = ceil($this->totalSearchAttemptCount / $this->getItemsPerPage());
        return $page;
    }

    /**
     * @return string
     */
    public function getFragment(): string
    {
        return $this->lotFragment;
    }
}
