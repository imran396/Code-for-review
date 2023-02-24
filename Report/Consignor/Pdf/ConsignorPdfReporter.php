<?php
/**
 * SAM-6799: Refactor consignor pdf report
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Consignor\Pdf;

use Auction;
use Sam\Core\Service\CustomizableClass;
use Sam\Report\Consignor\Pdf\Internal\Content\ConsignorPdfBuilderCreateTrait;
use Sam\Report\Consignor\Pdf\Internal\Content\ConsignorPdfReportContentMakerCreateTrait;
use Sam\Report\Consignor\Pdf\Internal\Load\DataLoaderCreateTrait;

/**
 * Class ConsignorPdfReporter
 * @package Sam\Report\Consignor\Pdf
 */
class ConsignorPdfReporter extends CustomizableClass
{
    use ConsignorPdfBuilderCreateTrait;
    use DataLoaderCreateTrait;
    use ConsignorPdfReportContentMakerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function generateAndOutput(array $consignorUserIds, ?int $auctionId, ?int $lotStatus, string $templateKey, int $editorUserId): void
    {
        $auction = $this->createDataLoader()->loadAuction($auctionId);
        if ($auctionId && !$auction) {
            log_error("Active auction not found by id" . composeSuffix(['a' => $auctionId]));
            return;
        }

        $reportPerConsignor = array_map(
            fn(int $userId) => $this->generateReportContentForConsignor($userId, $auction, $lotStatus, $templateKey, $editorUserId),
            $consignorUserIds
        );
        $reportPerConsignor = array_filter($reportPerConsignor);
        $content = $this->createConsignorPdfReportContentMaker()->concatenateReports($reportPerConsignor);
        $this->createConsignorPdfBuilder()->Process($content);
    }

    protected function generateReportContentForConsignor(int $userId, ?Auction $auction, ?int $lotStatus, string $templateKey, int $editorUserId): ?string
    {
        $user = $this->createDataLoader()->loadUser($userId, true);
        if (!$user) {
            log_error("Active user not found by id" . composeSuffix(['u' => $userId]));
            return null;
        }
        $auctionLots = $this->createDataLoader()->loadAuctionLots($auction->Id ?? null, $userId, $lotStatus);
        return $this->createConsignorPdfReportContentMaker()->makeForConsignor($user, $auctionLots, $auction, $templateKey, $editorUserId);
    }
}
