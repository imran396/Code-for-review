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
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Import\Csv\Base\ImportCsvFileProcessorInterface;
use Sam\Import\Csv\Bidder\BidderImportCsvFileProcessor;
use Sam\Import\Csv\BidIncrement\BidIncrementImportCsvFileProcessor;
use Sam\Import\Csv\Location\LocationImportCsvFileProcessor;
use Sam\Import\Csv\Lot\AuctionLot\AuctionLotImportCsvFileProcessor;
use Sam\Import\Csv\Lot\LotItem\LotItemImportCsvFileProcessor;
use Sam\Import\Csv\PostAuction\PostAuctionImportCsvFileProcessor;
use Sam\Import\Csv\User\UserImportCsvFileProcessor;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class ImportCsvFileProcessorFactory
 * @package Sam\Application\Controller\Admin\CsvImport
 */
class ImportCsvFileProcessorFactory extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use EditorUserAwareTrait;
    use SystemAccountAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Construct csv import processor based on option class name and data
     *
     * @param string $file
     * @param ImportCsvOptionInterface $option
     * @return ImportCsvFileProcessorInterface
     */
    public function create(string $file, ImportCsvOptionInterface $option): ImportCsvFileProcessorInterface
    {
        $editorUserId = $this->getEditorUserId();
        $systemAccountId = $this->getSystemAccountId();

        if ($option instanceof BidderImportCsvOption) {
            $fileProcessor = BidderImportCsvFileProcessor::new()
                ->construct(
                    $editorUserId,
                    $file,
                    $option->auctionId,
                    $option->sendRegistrationAndApprovalEmails,
                    $option->autoApproved,
                    $option->syncMode,
                    $systemAccountId,
                    $option->encoding
                );
            return $fileProcessor;
        }

        if ($option instanceof PostAuctionImportCsvOption) {
            $fileProcessor = PostAuctionImportCsvFileProcessor::new()
                ->construct(
                    $editorUserId,
                    $file,
                    $option->auctionId,
                    $option->encoding,
                    $option->additionalPremium,
                    $option->unassignUnsold
                );
            return $fileProcessor;
        }

        if ($option instanceof BidIncrementImportCsvOption) {
            $fileProcessor = BidIncrementImportCsvFileProcessor::new()
                ->construct(
                    $editorUserId,
                    $file,
                    $systemAccountId,
                    $option->auctionType
                );
            return $fileProcessor;
        }

        if ($option instanceof AuctionLotImportCsvOption) {
            $auction = $this->getAuctionLoader()->load($option->auctionId);
            if (!$auction) {
                throw new InvalidArgumentException("Auction not found by id \"{$option->auctionId}\"");
            }
            $fileProcessor = AuctionLotImportCsvFileProcessor::new()->construct(
                $editorUserId,
                $file,
                $auction,
                $option->encoding,
                $option->lotItemOverwriteExisting,
                $option->auctionLotOverwriteExisting,
                $option->htmlBreaks,
                $option->clearEmptyFields
            );
            return $fileProcessor;
        }

        if ($option instanceof LocationImportCsvOption) {
            $fileProcessor = LocationImportCsvFileProcessor::new()
                ->construct(
                    $editorUserId,
                    $file,
                    $option->encoding
                );
            return $fileProcessor;
        }

        if ($option instanceof LotItemImportCsvOption) {
            $fileProcessor = LotItemImportCsvFileProcessor::new()->construct(
                $editorUserId,
                $file,
                $option->encoding,
                $option->overwriteExisting,
                $option->htmlBreaks,
                $option->clearEmptyFields
            );
            return $fileProcessor;
        }

        if ($option instanceof UserImportCsvOption) {
            $fileProcessor = UserImportCsvFileProcessor::new()
                ->construct(
                    $editorUserId,
                    $file,
                    $systemAccountId,
                    $option->encoding,
                    $option->clearEmptyFields
                );
            return $fileProcessor;
        }

        throw new InvalidArgumentException(sprintf('Invalid option class "%s"', get_class($option)));
    }
}
