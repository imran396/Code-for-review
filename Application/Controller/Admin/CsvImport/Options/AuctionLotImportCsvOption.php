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

namespace Sam\Application\Controller\Admin\CsvImport\Options;

use Sam\Application\RequestParam\RequestParamFetcher;
use Sam\Core\Service\CustomizableClass;

/**
 * Class that contains all auction lot import options
 *
 * Class AuctionLotImportCsvOption
 * @package Sam\Application\Controller\Admin\CsvImport\Options
 */
class AuctionLotImportCsvOption extends CustomizableClass implements ImportCsvOptionInterface
{
    /**
     * @var int
     */
    public int $auctionId;
    /**
     * @var string
     */
    public string $encoding;
    /**
     * @var bool
     */
    public bool $lotItemOverwriteExisting;
    /**
     * @var bool
     */
    public bool $htmlBreaks;
    /**
     * @var bool
     */
    public bool $clearEmptyFields;
    /**
     * @var bool
     */
    public bool $auctionLotOverwriteExisting;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        int $auctionId,
        string $encoding,
        bool $lotItemOverwriteExisting,
        bool $auctionLotOverwriteExisting,
        bool $htmlBreaks,
        bool $clearEmptyFields
    ): static {
        $this->auctionId = $auctionId;
        $this->clearEmptyFields = $clearEmptyFields;
        $this->encoding = $encoding;
        $this->htmlBreaks = $htmlBreaks;
        $this->lotItemOverwriteExisting = $lotItemOverwriteExisting;
        $this->auctionLotOverwriteExisting = $auctionLotOverwriteExisting;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function fromRequest(RequestParamFetcher $paramFetcher): static
    {
        return $this->construct(
            $paramFetcher->getIntPositive('auctionId'),
            $paramFetcher->getString('encoding'),
            $paramFetcher->getBool('lotItemOverwriteExisting'),
            $paramFetcher->getBool('auctionLotOverwriteExisting'),
            $paramFetcher->getBool('htmlBreaks'),
            $paramFetcher->getBool('clearEmptyFields')
        );
    }
}
