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

namespace Sam\Report\Consignor\Pdf\Internal\Content;

use Auction;
use Email_Template;
use Sam\Core\Service\CustomizableClass;
use User;

/**
 * Class ConsignorPdfReportContentMaker
 * @package Sam\Report\Consignor\Pdf\Internal
 */
class ConsignorPdfReportContentMaker extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }


    public function makeForConsignor(
        User $consignorUser,
        array $auctionLots,
        ?Auction $auction,
        string $templateKey,
        int $editorUserId
    ): string {
        $emailManager = Email_Template::new()->construct(
            $consignorUser->AccountId,
            $templateKey,
            $editorUserId,
            [$auction, $consignorUser, $auctionLots],
            $auction->Id ?? null
        );
        $body = $emailManager->getEmail()->getHtmlBody()
            ?: $emailManager->getEmail()->getBody();

        return '<p>' . $body . '</p>';
    }

    /**
     * @param string[] $reports
     * @return string
     */
    public function concatenateReports(array $reports): string
    {
        return implode('<div style="page-break-before: always"></div>', $reports);
    }
}
