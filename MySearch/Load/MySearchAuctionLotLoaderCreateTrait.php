<?php
/**
 * SAM-6473: Move "my_search" table related logic to separate module
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 15, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\MySearch\Load;


/**
 * Trait MySearchAuctionLotLoaderCreateTrait
 * @package Sam\MySearch\Load
 */
trait MySearchAuctionLotLoaderCreateTrait
{
    /**
     * @var MySearchAuctionLotLoader|null
     */
    protected ?MySearchAuctionLotLoader $mySearchAuctionLotLoader = null;

    /**
     * @return MySearchAuctionLotLoader
     */
    protected function createMySearchAuctionLotLoader(): MySearchAuctionLotLoader
    {
        return $this->mySearchAuctionLotLoader ?: MySearchAuctionLotLoader::new();
    }

    /**
     * @param MySearchAuctionLotLoader $mySearchAuctionLotLoader
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setMySearchAuctionLotLoader(MySearchAuctionLotLoader $mySearchAuctionLotLoader): static
    {
        $this->mySearchAuctionLotLoader = $mySearchAuctionLotLoader;
        return $this;
    }
}
