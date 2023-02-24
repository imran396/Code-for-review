<?php
/**
 * SAM-3693: Rtb related repositories  https://bidpath.atlassian.net/browse/SAM-3693
 *
 * @copyright        2018 Bidpath, Inc.
 * @author           Oleg Kovalyov
 * @package          com.swb.sam2
 * @version          SVN: $Id$
 * @since            14 Jul, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\ReadRepository\Entity\RtbSession;

/**
 * Class RtbSessionReadRepository
 * @package Sam\Storage\ReadRepository\Entity\RtbSession
 */
class RtbSessionReadRepository extends AbstractRtbSessionReadRepository
{
    /**
     * @var string[]
     */
    protected array $joins = [
        'auction_bidder' => 'JOIN auction_bidder aub ON rtbs.user_id = aub.user_id AND rtbs.auction_id = aub.auction_id',
        'user' => 'JOIN user u ON rtbs.user_id = u.id',
        'user_info' => 'JOIN user_info ui ON aub.user_id = ui.user_id',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Left join `user` table
     * @return static
     */
    public function joinUser(): static
    {
        $this->join('user');
        return $this;
    }

    /**
     * Left join user_info table
     * @return static
     */
    public function joinUserInfo(): static
    {
        $this->join('user_info');
        return $this;
    }

    /**
     * Left join auction_bidder table
     * @return static
     */
    public function joinAuctionBidder(): static
    {
        $this->join('auction_bidder');
        return $this;
    }

    /**
     * Left join user table and filter by user status
     * Define filtering by u.user_status_id
     * @param int|int[] $userStatusIds
     * @return static
     */
    public function joinUserFilterUserStatusId(int|array|null $userStatusIds): static
    {
        $this->joinUser();
        $this->filterArray('u.user_status_id', $userStatusIds);
        return $this;
    }

    /**
     * Left join user table and define ORDER BY u.username
     * @param bool $ascending
     * @return static
     */
    public function joinUserOrderByUsername(bool $ascending = true): static
    {
        $this->joinUser();
        $this->order('u.username', $ascending);
        return $this;
    }

    /**
     * Left join auction_bidder and define ORDER BY aub.bidder_num
     * @param bool $ascending
     * @return static
     */
    public function joinAuctionBidderOrderByBidderNum(bool $ascending = true): static
    {
        $this->joinAuctionBidder();
        $this->order('aub.bidder_num', $ascending);
        return $this;
    }
}
