<?php
/**
 * SAM-9614: Refactor PartialUploadManager
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

namespace Sam\Application\Controller\Admin\CsvImport;

use Sam\Application\Controller\Admin\CsvImport\Options\AuctionLotImportCsvOption;
use Sam\Application\Controller\Admin\CsvImport\Options\BidderImportCsvOption;
use Sam\Application\Controller\Admin\CsvImport\Options\BidIncrementImportCsvOption;
use Sam\Application\Controller\Admin\CsvImport\Options\ImportCsvOptionInterface;
use Sam\Application\Controller\Admin\CsvImport\Options\LocationImportCsvOption;
use Sam\Application\Controller\Admin\CsvImport\Options\LotItemImportCsvOption;
use Sam\Application\Controller\Admin\CsvImport\Options\PostAuctionImportCsvOption;
use Sam\Application\Controller\Admin\CsvImport\Options\UserImportCsvOption;
use Sam\Core\Service\CustomizableClass;
use Sam\Import\Csv\Base\ImportCsvProcessStatisticInterface;
use Sam\Import\Csv\Bidder\Statistic\BidderImportCsvFinalStatMakerCreateTrait;
use Sam\Import\Csv\BidIncrement\Statistic\BidIncrementImportCsvFinalStatMakerCreateTrait;
use Sam\Import\Csv\BidIncrement\Statistic\BidIncrementImportCsvProcessStatistic;
use Sam\Import\Csv\Location\Statistic\LocationImportCsvFinalStatMakerCreateTrait;
use Sam\Import\Csv\Location\Statistic\LocationImportCsvProcessStatistic;
use Sam\Import\Csv\Lot\Statistic\LotImportCsvFinalStatMakerCreateTrait;
use Sam\Import\Csv\Lot\Statistic\LotImportCsvProcessStatistic;
use Sam\Import\Csv\PostAuction\Statistic\PostAuctionImportCsvFinalStatMakerCreateTrait;
use Sam\Import\Csv\PostAuction\Statistic\PostAuctionImportCsvProcessStatistic;
use Sam\Import\Csv\User\Statistic\UserImportCsvFinalStatMakerCreateTrait;
use Sam\Import\Csv\User\Statistic\UserImportCsvProcessStatistic;

/**
 * Facade that allows to generate messages about the results of the import process
 *
 * Class ImportCsvFinalStatMaker
 * @package Sam\Application\Controller\Admin\CsvImport
 */
class ImportCsvFinalStatMaker extends CustomizableClass
{
    use BidIncrementImportCsvFinalStatMakerCreateTrait;
    use BidderImportCsvFinalStatMakerCreateTrait;
    use LocationImportCsvFinalStatMakerCreateTrait;
    use LotImportCsvFinalStatMakerCreateTrait;
    use PostAuctionImportCsvFinalStatMakerCreateTrait;
    use UserImportCsvFinalStatMakerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Make import process result messages
     *
     * @param ImportCsvOptionInterface $option
     * @param ImportCsvProcessStatisticInterface|null $statistic
     * @param bool $isExistImageZip
     * @param bool $isExistFilesZip
     * @return array
     */
    public function make(
        ImportCsvOptionInterface $option,
        ?ImportCsvProcessStatisticInterface $statistic,
        bool $isExistImageZip,
        bool $isExistFilesZip
    ): array {
        if ($option instanceof BidderImportCsvOption) {
            return $this->createBidderImportCsvFinalStatMaker()->make();
        }

        if (
            $option instanceof PostAuctionImportCsvOption
            && $statistic instanceof PostAuctionImportCsvProcessStatistic
        ) {
            return $this->createPostAuctionImportCsvFinalStatMaker()->make($statistic, $option->auctionId);
        }

        if (
            $option instanceof BidIncrementImportCsvOption
            && $statistic instanceof BidIncrementImportCsvProcessStatistic
        ) {
            return $this->createBidIncrementImportCsvFinalStatMaker()->make($statistic);
        }

        if (
            (
                $option instanceof AuctionLotImportCsvOption
                || $option instanceof LotItemImportCsvOption
            )
            && $statistic instanceof LotImportCsvProcessStatistic
        ) {
            return $this->createLotImportCsvFinalStatMaker()->make($statistic, $isExistImageZip, $isExistFilesZip);
        }

        if (
            $option instanceof LocationImportCsvOption
            && $statistic instanceof LocationImportCsvProcessStatistic
        ) {
            return $this->createLocationImportCsvFinalStatMaker()->make($statistic);
        }

        if (
            $option instanceof UserImportCsvOption
            && $statistic instanceof UserImportCsvProcessStatistic
        ) {
            return $this->createUserImportCsvFinalStatMaker()->make($statistic);
        }

        log_error('Invalid option or statistic object' . composeLogData(['op' => get_class($option), 'st' => get_class($statistic)]));
        return [];
    }
}
