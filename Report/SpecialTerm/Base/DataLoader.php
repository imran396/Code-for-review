<?php
/**
 * SAM-4634:Refactor special terms report
 * https://bidpath.atlassian.net/browse/SAM-4634
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/8/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\SpecialTerm\Base;

use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItemBidderTerms\AuctionLotItemBidderTermsReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionLotItemBidderTerms\AuctionLotItemBidderTermsReadRepositoryCreateTrait;

/**
 * Class DataLoader
 * @package Sam\Report\SpecialTerm\Base
 */
abstract class DataLoader extends CustomizableClass
{
    use AuctionLotItemBidderTermsReadRepositoryCreateTrait;
    use FilterAuctionAwareTrait;
    use LimitInfoAwareTrait;
    use SortInfoAwareTrait;

    /**
     * @var array
     */
    protected static array $returnFields = [
        'alibt.agreed_on',
        'alibt.auction_id',
        'aub.bidder_num',
        'u.customer_no',
        'u.id as bidder_id',
        'u.username',
        'u.email',
        'li.item_num',
        'li.item_num_ext',
        'li.name',
        'li.id as li_id',
        '`ali`.`lot_num` AS `lot_number`',
        '`ali`.`lot_num_ext` AS `lot_number_ext`',
        '`ali`.`lot_num_prefix` AS `lot_number_prefix`',
        'ali.terms_and_conditions',
        'ui.first_name',
        'ui.last_name',
    ];

    /**
     * @return AuctionLotItemBidderTermsReadRepository
     */
    public function prepareAuctionLotItemBidderTermsRepository(): AuctionLotItemBidderTermsReadRepository
    {
        $repo = $this->createAuctionLotItemBidderTermsReadRepository()
            ->joinUser()
            ->joinLotItem()
            ->joinUserInfo()
            ->filterAuctionId($this->getFilterAuctionId())
            ->joinAuctionLotItemFilterAuctionId($this->getFilterAuctionId())
            ->joinAuctionBidderFilterAuctionId($this->getFilterAuctionId())
            ->select(self::$returnFields)
            ->offset($this->getOffset())
            ->limit($this->getLimit());

        switch ($this->getSortColumn()) {
            case 'item_num':
                $repo
                    ->joinLotItemOrderByItemNum($this->isAscendingOrder())
                    ->joinLotItemOrderByItemNumberExt($this->isAscendingOrder());
                break;
            case 'name':
                $repo->joinLotItemOrderByName($this->isAscendingOrder());
                break;
            case 'bidder_num':
                $repo->joinAuctionBidderOrderByBidderNum($this->isAscendingOrder());
                break;
            case 'first_name':
                $repo->joinUserInfoOrderByFirstName($this->isAscendingOrder());
                break;
            case 'last_name':
                $repo->joinUserInfoOrderByLastName($this->isAscendingOrder());
                break;
            case 'customer_no':
                $repo->joinUserOrderByCustomerNo($this->isAscendingOrder());
                break;
            case 'username':
                $repo->joinUserOrderByUsername($this->isAscendingOrder());
                break;
            case 'email':
                $repo->joinUserOrderByEmail($this->isAscendingOrder());
                break;
            case 'agreed_on':
                $repo->orderByAgreedOn($this->isAscendingOrder());
                break;
            case 'terms_and_conditions':
                $repo->joinAuctionLotItemOrderByTermsAndConditions($this->isAscendingOrder());
                break;
        }
        $repo
            ->joinAuctionLotItemOrderByLotNumPrefix($this->isAscendingOrder())
            ->joinAuctionLotItemOrderByLotNum($this->isAscendingOrder())
            ->joinAuctionLotItemOrderByLotNumExt($this->isAscendingOrder());

        return $repo;
    }
}
