<?php
/**
 * SAM-9360: Refactor \Lot_PostCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\PostAuction\Statistic;

/**
 * Trait PostAuctionImportCsvFinalStatMakerCreateTrait
 * @package Sam\Import\Csv\PostAuction\Statistic
 */
trait PostAuctionImportCsvFinalStatMakerCreateTrait
{
    /**
     * @var PostAuctionImportCsvFinalStatMaker|null
     */
    protected ?PostAuctionImportCsvFinalStatMaker $postAuctionImportCsvFinalStatMaker = null;

    /**
     * @return PostAuctionImportCsvFinalStatMaker
     */
    protected function createPostAuctionImportCsvFinalStatMaker(): PostAuctionImportCsvFinalStatMaker
    {
        return $this->postAuctionImportCsvFinalStatMaker ?: PostAuctionImportCsvFinalStatMaker::new();
    }

    /**
     * @param PostAuctionImportCsvFinalStatMaker $postAuctionImportCsvFinalStatMaker
     * @return static
     * @internal
     */
    public function setPostAuctionImportCsvFinalStatMaker(PostAuctionImportCsvFinalStatMaker $postAuctionImportCsvFinalStatMaker): static
    {
        $this->postAuctionImportCsvFinalStatMaker = $postAuctionImportCsvFinalStatMaker;
        return $this;
    }
}
