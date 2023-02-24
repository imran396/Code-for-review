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
 * Class that contains all bidder import options
 *
 * Class BidderImportCsvOption
 * @package Sam\Application\Controller\Admin\CsvImport\Options
 */
class BidderImportCsvOption extends CustomizableClass implements ImportCsvOptionInterface
{
    /**
     * @var int
     */
    public int $auctionId;
    /**
     * @var bool
     */
    public bool $sendRegistrationAndApprovalEmails;
    /**
     * @var bool
     */
    public bool $autoApproved;
    /**
     * @var int
     */
    public int $syncMode;
    /**
     * @var string
     */
    public string $encoding;

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
        bool $sendRegistrationAndApprovalEmails,
        bool $autoApproved,
        int $syncMode
    ): static {
        $this->auctionId = $auctionId;
        $this->autoApproved = $autoApproved;
        $this->encoding = $encoding;
        $this->sendRegistrationAndApprovalEmails = $sendRegistrationAndApprovalEmails;
        $this->syncMode = $syncMode;
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
            $paramFetcher->getBool('sendRegistrationAndApprovalEmails'),
            $paramFetcher->getBool('autoApproved'),
            $paramFetcher->getIntPositiveOrZero('syncMode'),
        );
    }
}
