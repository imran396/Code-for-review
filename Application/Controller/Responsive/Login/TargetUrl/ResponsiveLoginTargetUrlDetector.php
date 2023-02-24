<?php
/**
 * The responsibility of this class is to build redirect url after user logged in.
 *
 * SAM-6923 : Login to bid, completing signup is not redirecting into the auction registration process
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Login\TargetUrl;

use Sam\Application\Controller\Responsive\Login\TargetUrl\Internal\Load\DataProviderCreateTrait;
use Sam\Application\Url\BackPage\BackUrlParserAwareTrait;
use Sam\Application\Url\Build\Config\Auction\ResponsiveAuctionRegistrationUrlConfig;
use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Auction\Render\AuctionPureRenderer;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Core\Url\UrlParser;

/**
 * Class ResponsiveLoginTargetUrlDetector
 * @package Sam\Application\Controller\Responsive\Login\TargetUrl
 */
class ResponsiveLoginTargetUrlDetector extends CustomizableClass
{
    use BackUrlParserAwareTrait;
    use DataProviderCreateTrait;
    use UrlBuilderAwareTrait;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     *  After logged in:
     * 1) For change password request, redirect url will be change password page.
     * 2) When source url/back url is empty then redirect url will be landing page url.
     * 3) When source url/back url is not empty and auction id is not available in URI 'sale-reg' parameter then redirect url will be source page url.
     * 4) When source url/back url is not empty and valid auction id is available in URI 'sale-reg' parameter and auction is not registered yet then redirect url will be registration process page
     * and for already registered auction it will redirect to source url page .
     * 5) When source url/back url sale-reg url parameter contains invalid auction id it will redirect to source page url.
     * @param int $editorUserId
     * @param int $systemAccountId
     * @param string $backUrl
     * @param bool $hasPasswordChangeRequest
     * @return string
     */
    public function detectTargetUrl(
        int $editorUserId,
        int $systemAccountId,
        string $backUrl,
        bool $hasPasswordChangeRequest
    ): string {
        $urlParser = UrlParser::new();
        if ($hasPasswordChangeRequest) {
            $changePasswordUrl = $this->getUrlBuilder()->build(
                ZeroParamUrlConfig::new()->forRedirect(Constants\Url::P_CHANGE_PASSWORD)
            );
            return $this->getBackUrlParser()->replace($changePasswordUrl, $backUrl);
        }

        if (!$backUrl) {
            return $this->createDataProvider()->detectLandingUrl($systemAccountId);
        }

        if (!$urlParser->hasParam($backUrl, Constants\UrlParam::SALE_REG)) {
            return $backUrl;
        }

        $auctionId = (int)$urlParser->extractParam($backUrl, Constants\UrlParam::SALE_REG);
        $redirectUrl = $urlParser->removeParams($backUrl, [Constants\UrlParam::SALE_REG]);
        if (!$this->checkTargetAuction($auctionId)) {
            return $redirectUrl;
        }

        if ($auctionId) {
            $isRegister = $this->createDataProvider()->isAuctionRegistered($editorUserId, $auctionId);
            if (!$isRegister) {
                $registrationUrl = $this->getUrlBuilder()->build(
                    ResponsiveAuctionRegistrationUrlConfig::new()->forRedirect($auctionId)
                );
                $backUrl = $urlParser->removeParams($backUrl, [Constants\UrlParam::SALE_REG]);
                $redirectUrl = $this->getBackUrlParser()->replace($registrationUrl, $backUrl);
            }
        }

        return $redirectUrl;
    }

    /**
     * Checking auction existence and it's status is open or not.
     * @param int $auctionId - it can be null when auction id is absent.
     * @return bool
     */
    protected function checkTargetAuction(int $auctionId): bool
    {
        $auction = $this->createDataProvider()->loadAuction($auctionId, true);
        if (!$auction) {
            log_info('After login redirection to auction page failed, because target auction not found by id' . composeSuffix(['a' => $auctionId]));
            return false;
        }
        if (!in_array($auction->AuctionStatusId, Constants\Auction::$openAuctionStatuses, true)) {
            $logData = ['a' => $auctionId, 'status' => AuctionPureRenderer::new()->makeAuctionStatus($auction->AuctionStatusId)];
            log_info('After login redirection to auction page failed, because target auction is not open' . composeSuffix($logData));
            return false;
        }
        return true;
    }
}
