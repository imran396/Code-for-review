<?php
/**
 * SAM-6758: Rtb console output builders
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Console\Admin\Internal\Url;

use Sam\Application\Url\Build\Config\Asset\SoundUrlConfig;
use Sam\Application\Url\Build\Config\Auction\AnySingleAuctionUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\AnySingleAuctionLotUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveLotDetailsUrlConfig;
use Sam\Application\Url\Build\Config\Base\SingleIdParamUrlConfig;
use Sam\Application\Url\Build\Config\Base\TwoStringParamUrlConfig;
use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\Config\Image\LotImageUrlConfig;
use Sam\Application\Url\Build\Config\User\AdminUserEditUrlConfig;
use Sam\Application\Url\Build\Config\User\AnySingleUserUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Invoice\Common\Render\Logo\InvoiceLogoPathResolverCreateTrait;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;

/**
 * Class UrlProvider
 */
class UrlProvider extends CustomizableClass
{
    use AuctionAwareTrait;
    use InvoiceLogoPathResolverCreateTrait;
    use RtbGeneralHelperAwareTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @param array $optionals
     * @return $this
     */
    public function construct(int $auctionId, array $optionals = []): static
    {
        $this->setAuctionId($auctionId);
        return $this;
    }

    // -- Console url --

    /**
     * Public uri to rtbd server
     * @param int $userType
     * @return string
     */
    public function buildRtbdUri(int $userType): string
    {
        return $this->getRtbGeneralHelper()->getRtbdUri($userType, $this->getAuctionId());
    }

    // -- Auction urls --

    /**
     * Return url template for constructing route for Run Live Auction action
     * @return string
     */
    public function buildAuctionRunUrl(): string
    {
        return $this->getUrlBuilder()->build(
            AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_RUN, $this->getAuctionId())
        );
    }

    // -- Lot urls --

    /**
     * Return url template for constructing "Lot Details" page url of responsive side.
     * @return string
     */
    public function buildLotDetailsUrlTemplate(): string
    {
        return $this->getUrlBuilder()->build(
            ResponsiveLotDetailsUrlConfig::new()->forTemplate()
        );
    }

    /**
     * Return url template for constructing "Lot Preview" page url of responsive side.
     * @return string
     */
    public function buildLotPreviewUrlTemplate(): string
    {
        return $this->getUrlBuilder()->build(
            AnySingleAuctionLotUrlConfig::new()->forTemplateByType(Constants\Url::P_LOT_ITEM_PREVIEW)
        );
    }

    // -- User urls --

    /**
     * Return url template for constructing "Edit User" page url of admin side.
     * @return string
     */
    public function buildUserEditUrlTemplate(): string
    {
        return $this->getUrlBuilder()->build(
            AdminUserEditUrlConfig::new()->forTemplate()
        );
    }

    // -- Image urls --

    /**
     * Return url of default empty image with size expected by responsive consoles
     * @param string $size
     * @return string
     */
    public function buildImageStubUrl(string $size): string
    {
        return $this->getUrlBuilder()->build(
            LotImageUrlConfig::new()->constructEmptyStub($size, $this->getAuction()->AccountId)
        );
    }

    /**
     * Return url of invoice logo (invoiced auction location logo is not used)
     * @return string
     */
    public function buildInvoiceLogoUrl(): string
    {
        return $this->createInvoiceLogoPathResolver()
            ->buildInvoiceLogoUrl($this->getAuction()->AccountId);
    }

    /**
     * --- Non web page urls (read, add, delete data requests) ---
     */

    // -- Concrete and complete urls --

    /**
     * Return url template for constructing route for Auction Re-opening action
     * @return string
     */
    public function buildAuctionReopenUrl(): string
    {
        return $this->getUrlBuilder()->build(
            AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_REOPEN, $this->getAuctionId())
        );
    }

    /**
     * Return url for constructing route to read rtb auction bidder data
     * Zero param urls doesn't need to be templates.
     * @return string
     */
    public function buildRtbBidderDataUrl(): string
    {
        return $this->getUrlBuilder()->build(
            ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_RTB_BIDDER_AUTOCOMPLETE)
        );
    }

    /**
     * Return url for constructing route to add increment data
     * Zero param urls doesn't need to be templates.
     * @return string
     */
    public function buildIncrementAddUrl(): string
    {
        return $this->getUrlBuilder()->build(
            ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_RTB_INCREMENT_ADD)
        );
    }

    // -- Js template urls --

    /**
     * Return url for constructing route to delete increment record
     * @return string
     */
    public function buildIncrementDeleteUrlTemplate(): string
    {
        return $this->getUrlBuilder()->build(
            TwoStringParamUrlConfig::new()->forTemplateByType(Constants\Url::A_RTB_INCREMENT_DEL)
        );
    }

    /**
     * Return url template for constructing route to read lot catalog data from server.
     * @return string
     */
    public function buildLotCatalogDataUrlTemplate(): string
    {
        return $this->getUrlBuilder()->build(
            AnySingleAuctionUrlConfig::new()->forTemplateByType(Constants\Url::A_LOT_ITEM_CATALOG)
        );
    }

    /**
     * Return url template for constructing route to read lot grouping data from server.
     * @return string
     */
    public function buildLotGroupDataUrlTemplate(): string
    {
        return $this->getUrlBuilder()->build(
            AnySingleAuctionUrlConfig::new()->forTemplateByType(Constants\Url::P_LOT_ITEM_GROUP)
        );
    }

    /**
     * Return url template for constructing route for reading message center data from server
     * @return string
     */
    public function buildMessageCenterDataUrlTemplate(): string
    {
        return $this->getUrlBuilder()->build(
            AnySingleAuctionUrlConfig::new()->forTemplateByType(Constants\Url::A_RTB_MESSAGE_CENTER)
        );
    }

    /**
     * Return url template for constructing route for adding data to message center
     * @return string
     */
    public function buildMessageCenterAddUrlTemplate(): string
    {
        return $this->getUrlBuilder()->build(
            AnySingleAuctionUrlConfig::new()->forTemplateByType(Constants\Url::A_RTB_MESSAGE_ADD)
        );
    }

    /**
     * Return url template for constructing route for deleting data in message center
     * @return string
     */
    public function buildMessageCenterDeleteUrlTemplate(): string
    {
        return $this->getUrlBuilder()->build(
            SingleIdParamUrlConfig::new()->forTemplateByType(Constants\Url::A_RTB_MESSAGE_DEL)
        );
    }

    /**
     * Return url template for constructing route for reading connected users data
     * @return string
     */
    public function buildConnectedUsersDataUrlTemplate(): string
    {
        return $this->getUrlBuilder()->build(
            AnySingleAuctionUrlConfig::new()->forTemplateByType(Constants\Url::A_AUCTIONS_MANAGE_RTB_USERS)
        );
    }

    /**
     * Return url template for constructing route for reading bidder interest data
     * @return string
     */
    public function buildBidderInterestDataUrlTemplate(): string
    {
        return $this->getUrlBuilder()->build(
            AnySingleAuctionUrlConfig::new()->forTemplateByType(Constants\Url::A_AUCTIONS_MANAGE_BIDDER_INTEREST)
        );
    }

    // --- Sound urls

    public function buildSoundOnlineBidIncomingOnAdmin(int $accountId): string
    {
        return $this->getUrlBuilder()->build(SoundUrlConfig::new()->forWeb(Constants\SettingSound::ONLINE_BID_INCOMING_ON_ADMIN, $accountId));
    }

    public function buildSoundClickFromSoundManagerJsVendor(): string
    {
        return $this->getUrlBuilder()->build(
            ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_SOUND_MANAGER_VENDOR_SOUND_CLICK)
        );
    }

    public function buildSoundChimeFromSoundManagerJsVendor(): string
    {
        return $this->getUrlBuilder()->build(
            ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_SOUND_MANAGER_VENDOR_SOUND_CHIME)
        );
    }
}
