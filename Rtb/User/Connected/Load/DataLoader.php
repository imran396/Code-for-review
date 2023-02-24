<?php
/**
 * SAM-5752: Rtb connected user list builder
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 24, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\User\Connected\Load;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Storage\ReadRepository\Entity\RtbSession\RtbSessionReadRepository;
use Sam\Storage\ReadRepository\Entity\RtbSession\RtbSessionReadRepositoryCreateTrait;

/**
 * Fetch list of users currently connected to the RTB for one particular auction
 *
 * Class RtbConnectedUserLoader
 * @package Sam\Rtb\User\Connected
 */
class DataLoader extends CustomizableClass
{
    use CurrentDateTrait;
    use RtbSessionReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @return RtbConnectedUserDto[]
     */
    public function load(int $auctionId): array
    {
        $rows = $this->prepareRepository($auctionId)->loadRows();
        return $this->toDtos($rows);
    }

    /**
     * @param int $auctionId
     * @return RtbConnectedUserDto[]
     */
    public function loadWithoutAdmin(int $auctionId): array
    {
        $rows = $this
            ->prepareRepository($auctionId)
            ->skipUserType(Constants\Rtb::UT_CLERK)
            ->loadRows();
        return $this->toDtos($rows);
    }

    /**
     * @param int $auctionId
     * @return RtbSessionReadRepository
     */
    protected function prepareRepository(int $auctionId): RtbSessionReadRepository
    {
        $select = [
            'aub.bidder_num AS bidder_num',
            'u.id AS user_id',
            'u.username AS username',
            'ui.company_name AS company_name',
            'ui.first_name AS first_name',
            'ui.last_name AS last_name',
        ];
        return $this->createRtbSessionReadRepository()
            ->enableDistinct(true)
            ->enableReadOnlyDb(true)
            ->filterAuctionId($auctionId)
            ->filterParticipatedOnGreaterOrEqual($this->calcPreviousDayDate())
            ->joinAuctionBidderOrderByBidderNum()
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
            ->joinUserInfo()
            ->joinUserOrderByUsername()
            ->select($select);
    }

    /**
     * @return string
     */
    protected function calcPreviousDayDate(): string
    {
        $date = $this->getCurrentDateUtc()->sub(new \DateInterval('P1D'));
        return $date->format('Y-m-d') . ' 00:00:00';
    }

    /**
     * Transform fetched mysql data to array of DTOs
     * @param array $rows
     * @return RtbConnectedUserDto[]
     */
    protected function toDtos(array $rows): array
    {
        $dtos = [];
        foreach ($rows as $row) {
            $dtos[] = RtbConnectedUserDto::new()->fromDbRow($row);
        }
        return $dtos;
    }
}
