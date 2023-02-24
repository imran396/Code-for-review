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

namespace Sam\Application\Controller\Responsive\Download\AuctionCustomField;

use Auction;
use Sam\Application\Controller\Responsive\Download\Internal\ResponseCreateTrait;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Path\PathResolver;
use Sam\Core\Service\CustomizableClass;
use Sam\File\FilePathHelperAwareTrait;
use Sam\File\Manage\LocalFileManagerCreateTrait;
use Sam\User\Access\AuctionAccessCheckerAwareTrait;

/**
 * Class AuctionCustomFieldDownloadOutputProducer
 * @package Sam\Application\Controller\Responsive\Download
 */
class AuctionCustomFieldDownloadOutputProducer extends CustomizableClass
{
    use AuctionAccessCheckerAwareTrait;
    use AuctionLoaderAwareTrait;
    use FilePathHelperAwareTrait;
    use LocalFileManagerCreateTrait;
    use ResponseCreateTrait;
    use ServerRequestReaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Output a file content or an error if something went wrong
     *
     * @param string|null $fileName
     * @param int|null $auctionId
     * @param int|null $editorUserId
     */
    public function produce(?string $fileName, ?int $auctionId, ?int $editorUserId): void
    {
        $response = $this->createResponse();
        $auction = $this->getAuctionLoader()->load($auctionId, true);
        if (!$auction) {
            $response->notFound();
        }

        if (!$this->hasAccess($auction, $editorUserId)) {
            $response->forbidden();
        }

        $fileName = $this->getFilePathHelper()->toFilename($fileName);
        if (!$this->isFileExist($fileName, $auction->AccountId)) {
            $response->notFound();
        }

        $filePath = $this->makeFilePath($fileName, $auction->AccountId);
        $response->outputFile($fileName, $filePath);
    }

    /**
     * @param Auction $auction
     * @param int|null $userId checking user id, null for anonymous
     * @return bool
     */
    protected function hasAccess(Auction $auction, ?int $userId): bool
    {
        return $this->getAuctionAccessChecker()->isAccess(
            $auction->AuctionVisibilityAccess,
            $userId,
            $auction->AccountId,
            $auction->Id
        );
    }

    protected function isFileExist(string $fileName, int $accountId): bool
    {
        if ($fileName !== basename($fileName)) {
            $message = sprintf('%s trying to access %s', $this->getServerRequestReader()->remoteAddr(), $fileName);
            log_warning($message);
            return false;
        }

        $filePath = $this->makeFilePath($fileName, $accountId);
        $isExist = $this->createLocalFileManager()->exist($filePath);
        return $isExist;
    }

    protected function makeFilePath(string $fileName, int $accountId): string
    {
        return PathResolver::UPLOAD_AUCTION_CUSTOM_FIELD_FILE . '/' . $accountId . '/' . $fileName;
    }
}
