<?php
/**
 * AuctionDataIntegrityChecker prepares auction CustomFields, SaleNo duplicates
 *
 * SAM-5070: Data integrity checker - there shall only be one active auction_cust_data record for one auction
 * and one auction_cust_field
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           11 Sep, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Validate;

use Sam\Core\Constants;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepository;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionCustData\AuctionCustDataReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionCustData\AuctionCustDataReadRepositoryCreateTrait;

/**
 * Class AuctionDataIntegrityChecker
 * @package Sam\Auction\Validate
 */
class AuctionDataIntegrityChecker extends CustomizableClass
{
    use AuctionCustDataReadRepositoryCreateTrait;
    use AuctionReadRepositoryCreateTrait;
    use FilterAccountAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return AuctionCustDataReadRepository
     */
    public function prepareCustomFieldDuplicateSearch(): AuctionCustDataReadRepository
    {
        $repo = $this->createAuctionCustDataReadRepository()
            ->select(
                [
                    'acf.name',
                    'a.id',
                    'COUNT(1) as count_records',
                    'a.account_id',
                    'acc.name as account_name',
                ]
            )
            ->joinAuction()
            ->joinAuctionCustomField()
            ->joinAccount()
            ->filterActive(true)
            ->groupByAuctionCustFieldId()
            ->groupByAuctionId()
            ->having('COUNT(1) > 1')
            ->orderByAccountId()
            ->orderByAuctionId()
            ->setChunkSize(200);

        if ($this->getFilterAccountId()) {
            $repo->joinAuctionFilterAccountId($this->getFilterAccountId());
        }

        return $repo;
    }

    /**
     * @return AuctionReadRepository
     */
    public function prepareSaleNoDuplicateSearch(): AuctionReadRepository
    {
        $repo = $this->createAuctionReadRepository()
            ->select(
                [
                    'a.sale_num',
                    'a.sale_num_ext',
                    'GROUP_CONCAT(a.id) as auction_ids',
                    'GROUP_CONCAT(a.name) as auction_names',
                    'COUNT(1) as count_records',
                    'a.account_id',
                    'acc.name as account_name',
                ]
            )
            ->joinAccount()
            ->filterAuctionStatusId(Constants\Auction::$openAuctionStatuses)
            ->groupByAccountId()
            ->groupBySaleNum()
            ->groupBySaleNumExt()
            ->having('COUNT(1) > 1')
            ->orderByAccountId()
            ->orderById()
            ->setChunkSize(200);

        if ($this->getFilterAccountId()) {
            $repo->filterAccountId($this->getFilterAccountId());
        }

        return $repo;
    }
}
