<?php

namespace Sam\Reseller\AuctionBidderCert;

use Sam\Application\Url\Build\Config\Base\SingleIdParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\User\Privilege\Validate\AdminPrivilegeCheckerAwareTrait;
use User;

/**
 * Different functionality related to reseller certificates linked to auction bidder (certificate per auction)
 * SAM-2483: Adjustments for "auction bidder certificates" feature
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Sep 23, 2014
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */
class AuctionBidderCertHelper extends CustomizableClass
{
    use AdminPrivilegeCheckerAwareTrait;
    use ConfigRepositoryAwareTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return url for certificate downloading
     * @param int $auctionBidderId
     * @return string
     */
    public function buildDownloadUrl(int $auctionBidderId): string
    {
        $urlConfig = SingleIdParamUrlConfig::new()->forWeb(
            Constants\Url::P_DOWNLOADS_RESELLER_CERT_DOWNLOAD_FOR_AUCTION_BIDDER,
            $auctionBidderId
        );
        $url = $this->getUrlBuilder()->build($urlConfig);
        return $url;
    }

    /**
     * Return root path for certificate file with passed name
     * @param string $fileName
     * @param int $auctionId
     * @return string
     */
    public function getFileRootPath(string $fileName, int $auctionId): string
    {
        $fileRootPath = path()->uploadReseller()
            . $this->cfg()->get('core->user->reseller->auctionBidderCertUploadDir')
            . '/' . $auctionId . '/' . $fileName;
        return $fileRootPath;
    }

    /**
     * Return relative path to certificate file
     * @param string $fileName
     * @param int $auctionId
     * @return string
     */
    public function getFilePath(string $fileName, int $auctionId): string
    {
        $filePath = substr($this->getFileRootPath($fileName, $auctionId), strlen(path()->sysRoot()));
        return $filePath;
    }

    /**
     * Check if certificate file is accessible for passed user
     * @param User $owner whose certificate to check
     * @param User|null $me whose access rights are checked
     * @return bool
     */
    public function isAllowedDownload(User $owner, User $me = null): bool
    {
        if (!$me) {
            return false;
        }

        if ($me->Id === $owner->Id) {    // owner allowed
            return true;
        }

        return $this->isAllowedDelete($owner, $me);
    }

    /**
     * Check if certificate file is allowed to delete for passed user
     * @param User $owner whose certificate to check
     * @param User|null $me whose access rights are checked
     * @return bool
     */
    public function isAllowedDelete(User $owner, User $me = null): bool
    {
        if (!$me) {
            return false;
        }

        $checker = $this->getAdminPrivilegeChecker()->initByUser($me);
        if (!$checker->hasPrivilegeForManageUsers()) {
            return false;
        }

        if (!$this->cfg()->get('core->portal->enabled')) {
            return true;
        }

        if (
            $checker->hasPrivilegeForSuperadmin()
            || $me->AccountId === $owner->AccountId
        ) {
            return true;
        }

        return false;
    }
}
