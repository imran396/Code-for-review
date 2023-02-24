<?php
/**
 * SAM-4598: Pacts Buyers export from SAM to Pacts
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/15/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\Pacts;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionBidder\AuctionBidderReadRepository;

/**
 * Class DataLoader
 * @package Sam\Report\Auction\Pacts
 */
class DataLoader extends CustomizableClass
{
    use DbConnectionTrait;
    use FilterAuctionAwareTrait;
    use SystemAccountAwareTrait;

    protected ?AuctionBidderReadRepository $auctionBidderRepository = null;
    protected ?int $chunkSize = 200;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return int|null
     */
    public function getChunkSize(): ?int
    {
        return $this->chunkSize;
    }

    /**
     * @param int|null $chunkSize
     * @return static
     */
    public function setChunkSize(?int $chunkSize): static
    {
        $this->chunkSize = Cast::toInt($chunkSize, Constants\Type::F_INT_POSITIVE);
        return $this;
    }

    /**
     * @return array
     */
    public function load(): array
    {
        return $this->prepareRepository()->loadRows();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        $total = $this->prepareRepository()->count();
        return $total;
    }

    /**
     * @return AuctionBidderReadRepository
     */
    protected function prepareRepository(): AuctionBidderReadRepository
    {
        if ($this->auctionBidderRepository === null) {
            $this->auctionBidderRepository = AuctionBidderReadRepository::new()
                ->enableReadOnlyDb(true)
                ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
                ->joinUserInfo()
                ->joinUserBilling()
                ->filterAuctionId($this->getFilterAuctionId())
                ->orderByBidderNum(false)
                ->select(
                    [
                        'u.username',
                        'u.email',
                        'aub.auction_id',
                        'aub.bidder_num',
                        'aub.registered_on',
                        'aub.user_id',
                        'ui.company_name',
                        'ui.first_name',
                        'ui.last_name',
                        'ui.phone',
                        'ui.news_letter',
                        'ui.referrer',
                        'ui.referrer_host',
                        'ub.contact_type AS bill_contact_type',
                        'ub.company_name AS bill_company_name',
                        'ub.first_name AS bill_first_name',
                        'ub.last_name AS bill_last_name',
                        'ub.phone AS bill_phone',
                        'ub.fax AS bill_fax',
                        'ub.country AS bill_country',
                        'ub.address AS bill_address',
                        'ub.address2 AS bill_address2',
                        'ub.address3 AS bill_address3',
                        'ub.city AS bill_city',
                        'ub.state AS bill_state',
                        'ub.zip AS bill_zip',
                    ]
                )
                ->setChunkSize($this->getChunkSize());
        }
        return $this->auctionBidderRepository;
    }
}
