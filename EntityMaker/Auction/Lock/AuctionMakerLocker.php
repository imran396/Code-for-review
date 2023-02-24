<?php
/**
 * SAM-10615: Supply uniqueness of auction fields: sale#
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 09, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Lock;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Auction\Dto\AuctionMakerConfigDto;
use Sam\EntityMaker\Auction\Dto\AuctionMakerInputDto;
use Sam\EntityMaker\Auction\Lock\AuctionMakerLockingResult as Result;
use Sam\EntityMaker\Auction\Lock\SaleNo\AuctionUniqueSaleNoLockerCreateTrait;

/**
 * Class AuctionMakerLocker
 * @package Sam\EntityMaker\Auction\Lock
 */
class AuctionMakerLocker extends CustomizableClass
{
    use AuctionUniqueSaleNoLockerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function lock(
        AuctionMakerInputDto $inputDto,
        AuctionMakerConfigDto $configDto
    ): Result {
        $result = Result::new();
        $itemNoLockingResult = $this->createAuctionUniqueSaleNoLocker()->lock($inputDto, $configDto);
        $result->addLockingResult($itemNoLockingResult);
        return $result;
    }

    public function unlock(AuctionMakerConfigDto $configDto): AuctionMakerConfigDto
    {
        $configDto = $this->createAuctionUniqueSaleNoLocker()->unlock($configDto);
        return $configDto;
    }
}
