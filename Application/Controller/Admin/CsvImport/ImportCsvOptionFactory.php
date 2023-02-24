<?php
/**
 * SAM-9614: Refactor PartialUploadManager
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 31, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\CsvImport;

use InvalidArgumentException;
use Sam\Application\Controller\Admin\CsvImport\Options\AuctionLotImportCsvOption;
use Sam\Application\Controller\Admin\CsvImport\Options\BidderImportCsvOption;
use Sam\Application\Controller\Admin\CsvImport\Options\BidIncrementImportCsvOption;
use Sam\Application\Controller\Admin\CsvImport\Options\ImportCsvOptionInterface;
use Sam\Application\Controller\Admin\CsvImport\Options\LocationImportCsvOption;
use Sam\Application\Controller\Admin\CsvImport\Options\LotItemImportCsvOption;
use Sam\Application\Controller\Admin\CsvImport\Options\PostAuctionImportCsvOption;
use Sam\Application\Controller\Admin\CsvImport\Options\UserImportCsvOption;
use Sam\Application\RequestParam\RequestParamFetcher;
use Sam\Core\Constants\Csv\ImportCsvType;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ImportCsvOptionFactory
 * @package Sam\Application\Controller\Admin\CsvImport
 */
class ImportCsvOptionFactory extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Construct import options object based on type and request data
     *
     * @param string $type
     * @param RequestParamFetcher $paramFetcher
     * @return ImportCsvOptionInterface
     */
    public function create(string $type, RequestParamFetcher $paramFetcher): ImportCsvOptionInterface
    {
        $optionInstance = match ($type) {
            ImportCsvType::BIDDERS => BidderImportCsvOption::new(),
            ImportCsvType::BIDS => PostAuctionImportCsvOption::new(),
            ImportCsvType::INCREMENTS => BidIncrementImportCsvOption::new(),
            ImportCsvType::LOCATIONS => LocationImportCsvOption::new(),
            ImportCsvType::LOTS => $paramFetcher->getIntPositiveOrZero('auctionId')
                ? AuctionLotImportCsvOption::new()
                : LotItemImportCsvOption::new(),
            ImportCsvType::USERS => UserImportCsvOption::new(),
            default => throw new InvalidArgumentException("Invalid import type '{$type}'"),
        };
        $optionInstance = $optionInstance->fromRequest($paramFetcher);
        return $optionInstance;
    }
}
