<?php
/**
 * SAM-10192: Move alone end-points to controllers
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Download\ResellerCert;

use Sam\Application\Controller\Responsive\Download\Internal\ResponseCreateTrait;
use Sam\Application\Controller\Responsive\Download\ResellerCert\Internal\AccessCheckerCreateTrait;
use Sam\Application\Controller\Responsive\Download\ResellerCert\Internal\Load\DataProviderCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\LocalFileManagerCreateTrait;
use Sam\Reseller\AuctionBidderCert\AuctionBidderCertHelperAwareTrait;
use Sam\Reseller\UserCert\ResellerUserCertHelperAwareTrait;

/**
 * Class ResellerCertDownloadOutputProducer
 * @package Sam\Application\Controller\Responsive\Download
 */
class ResellerCertDownloadOutputProducer extends CustomizableClass
{
    use AccessCheckerCreateTrait;
    use AuctionBidderCertHelperAwareTrait;
    use DataProviderCreateTrait;
    use LocalFileManagerCreateTrait;
    use ResellerUserCertHelperAwareTrait;
    use ResponseCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function produce(?int $userId, ?int $auctionBidderId, ?int $editorUserId): void
    {
        $response = $this->createResponse();
        if ($userId) {
            $hasAccess = $this->createAccessChecker()->hasAccessToUserCertFile($userId, $editorUserId, true);
            if (!$hasAccess) {
                $response->forbidden();
            }
            $certFileName = $this->createDataProvider()->loadUserResellerCertFileName($userId, true);
            $certFilePath = $this->getResellerUserCertHelper()->getFilePath($certFileName);
        } else {
            $auctionBidder = $this->createDataProvider()->loadAuctionBidder($auctionBidderId, true);
            if (!$auctionBidder) {
                $response->notFound();
            }
            $hasAccess = $this->createAccessChecker()->hasAccessToAuctionBidderCertFile($auctionBidder->UserId, $editorUserId, true);
            if (!$hasAccess) {
                $response->forbidden();
            }
            $certFileName = $auctionBidder->ResellerCertificate;
            $certFilePath = $this->getAuctionBidderCertHelper()->getFilePath($certFileName, $auctionBidder->AuctionId);
        }

        if (!$this->isFileExist($certFileName, $certFilePath)) {
            $response->notFound();
        }

        $response->outputFile($certFileName, $certFilePath);
    }

    protected function isFileExist(string $fileName, string $filePath): bool
    {
        if ($fileName !== basename($fileName)) {
            return false;
        }

        $isExist = $this->createLocalFileManager()->exist($filePath);
        return $isExist;
    }
}
