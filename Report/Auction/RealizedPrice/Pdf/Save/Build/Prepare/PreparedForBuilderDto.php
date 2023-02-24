<?php
/**
 * SAM-6367: Continue to refactor "PDF Prices Realized" report
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           08-26, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Report\Auction\RealizedPrice\Pdf\Save\Build\Prepare;

use Sam\Core\Service\CustomizableClass;


/**
 * Class PreparedForRenderDto
 * @package
 */
class PreparedForBuilderDto extends CustomizableClass
{
    public readonly string $auctionStartDate;
    public readonly string $body;
    public readonly int $count;
    public readonly string $creationDateTimeFormatted;
    public readonly int $maxCount;
    public readonly int $soldCount;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * PreparedForRenderDto constructor.
     * @param string $body
     * @param string $creationDateTimeFormatted
     * @param string $auctionStartDate
     * @param int $count
     * @param int $maxCount
     * @param int $soldCount
     * @return $this
     */
    public function construct(
        string $body,
        string $creationDateTimeFormatted,
        string $auctionStartDate,
        int $count,
        int $maxCount,
        int $soldCount
    ): static {
        $this->body = $body;
        $this->creationDateTimeFormatted = $creationDateTimeFormatted;
        $this->auctionStartDate = $auctionStartDate;
        $this->count = $count;
        $this->maxCount = $maxCount;
        $this->soldCount = $soldCount;

        return $this;
    }
}
