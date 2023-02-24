<?php
/**
 * SAM-4038:Refactor Additional Registration Options logic
 * https://bidpath.atlassian.net/browse/SAM-4038
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/11/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\AuctionBidder\AdditionalRegistrationOption\Save;

use Sam\Bidder\AuctionBidder\Load\AuctionBidderOptionLoaderCreateTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionBidderOption\AuctionBidderOptionWriteRepositoryAwareTrait;

/**
 * Class AdditionalRegistrationOptionProducer
 * @package Sam\Bidder\AuctionBidder\AdditionalRegistrationOption\Save
 */
class AdditionalRegistrationOptionProducer extends CustomizableClass
{
    use AuctionBidderOptionLoaderCreateTrait;
    use AuctionBidderOptionWriteRepositoryAwareTrait;
    use CurrentDateTrait;
    use EntityFactoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Class method
     * Method to save data in AuctionBidderOption
     * @param int|null $aboId AuctionBidderOption->Id. Null - on create new abo
     * @param int $accountId
     * @param bool $isRequired AuctionBidderOption->Required
     * @param string $name AuctionBidderOption->Name
     * @param string $option AuctionBidderOption->Option
     * @param int $editorUserId
     * @return void
     */
    public function save(
        ?int $aboId,
        int $accountId,
        bool $isRequired,
        string $name,
        string $option,
        int $editorUserId
    ): void {
        $auctionBidderOption = $this->createAuctionBidderOptionLoader()->load($aboId);
        if (!$auctionBidderOption) {
            $auctionBidderOption = $this->createEntityFactory()->auctionBidderOption();
        }
        $auctionBidderOption->AccountId = $accountId;
        $auctionBidderOption->Active = true;
        $auctionBidderOption->Name = $name;
        $auctionBidderOption->Option = $option;
        $auctionBidderOption->Required = $isRequired;
        $this->getAuctionBidderOptionWriteRepository()->saveWithModifier($auctionBidderOption, $editorUserId);
    }

    /**
     * Class method
     * Method to save ordering
     * @param array $orderMap
     * @param int $editorUserId
     * @return void
     */
    public function updateOrders(array $orderMap, int $editorUserId): void
    {
        foreach ($orderMap as $order) {
            $auctionBidderOption = $this->createAuctionBidderOptionLoader()->load($order['id']);
            if (!$auctionBidderOption) {
                log_error("Available AuctionBidderOption not found" . composeSuffix(['id' => $order['id']]));
                continue;
            }
            $auctionBidderOption->Order = $order['value'];
            $this->getAuctionBidderOptionWriteRepository()->saveWithModifier($auctionBidderOption, $editorUserId);
        }
    }
}
