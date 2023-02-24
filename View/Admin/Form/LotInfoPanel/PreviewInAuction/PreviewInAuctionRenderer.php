<?php
/**
 * SAM-6770: "Preview in auction" adjustments for lot item preview link
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotInfoPanel\PreviewInAuction;

use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveLotDetailsUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Lot\Render\LotPureRenderer;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\View\Admin\Form\LotInfoPanel\PreviewInAuction\Internal\Load\DataProvider;

/**
 * Class PreviewInAuctionRenderer
 */
class PreviewInAuctionRenderer extends CustomizableClass
{
    use AuctionRendererAwareTrait;
    use OptionalsTrait;
    use UrlBuilderAwareTrait;

    // Incoming values

    public const OP_LINK_TPL = 'linkTpl';
    public const OP_RESULT_BLOCK_TPL = 'resultBlockTpl';
    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB;

    // Internal values

    protected const LINK_TPL_DEF = '<a href="%s" target="_blank">%s</a>';
    protected const RESULT_BLOCK_TPL_DEF = '<div class="preview-link-items"> </br> %s</div><div class="clear"></div>';

    // Preview in auction link description

    private const ASSIGNED_LOT_DESCRIPTION = "%s - %s - %s (%s)";
    private const UNASSIGNED_LOT_DESCRIPTION = 'Unassigned';

    protected ?DataProvider $dataProvider = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return static
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @param int $lotItemId
     * @param int $accountId
     * @return string
     */
    public function render(int $lotItemId, int $accountId): string
    {
        $isReadOnlyDb = (bool)$this->fetchOptional(self::OP_IS_READ_ONLY_DB);
        $dataProvider = $this->getDataProvider();
        if (!$dataProvider->count($lotItemId, $isReadOnlyDb)) {
            return $this->renderUnassigned($lotItemId, $accountId);
        }

        $dtos = $dataProvider->load($lotItemId, $isReadOnlyDb);
        return $this->renderAssigned($dtos, $lotItemId, $accountId);
    }

    /**
     * Render output for lot item not assigned to any auction.
     * @param int $lotItemId
     * @param int $accountId
     * @return string
     */
    protected function renderUnassigned(int $lotItemId, int $accountId): string
    {
        $linkTpl = (string)$this->fetchOptional(self::OP_LINK_TPL);
        $resultTpl = (string)$this->fetchOptional(self::OP_RESULT_BLOCK_TPL);
        $lotListUrl = $this->getUrlBuilder()->build(
            ResponsiveLotDetailsUrlConfig::new()->forWeb(
                $lotItemId,
                0,
                '',
                [UrlConfigConstants::OP_ACCOUNT_ID => $accountId]
            )
        );
        $lotLink = sprintf($linkTpl, $lotListUrl, self::UNASSIGNED_LOT_DESCRIPTION);
        return sprintf($resultTpl, $lotLink);
    }

    /**
     * Render output for lot item that is assigned to one or more auctions.
     * @param array $dtos
     * @param int $lotItemId
     * @param int $accountId
     * @return string
     */
    protected function renderAssigned(array $dtos, int $lotItemId, int $accountId): string
    {
        $linkTpl = (string)$this->fetchOptional(self::OP_LINK_TPL);
        $resultTpl = (string)$this->fetchOptional(self::OP_RESULT_BLOCK_TPL);
        $urlBuilder = $this->getUrlBuilder();
        $auctionRenderer = $this->getAuctionRenderer();
        $lotPureRenderer = LotPureRenderer::new();
        $lotLinks = [];
        foreach ($dtos as $dto) {
            $saleNo = $auctionRenderer->makeSaleNo($dto->saleNum, $dto->saleNumExt);
            $lotStatus = $lotPureRenderer->makeLotStatus($dto->lotStatusId);
            $lotListUrl = $urlBuilder->build(
                ResponsiveLotDetailsUrlConfig::new()->forWeb(
                    $lotItemId,
                    $dto->auctionId,
                    null,
                    [UrlConfigConstants::OP_ACCOUNT_ID => $accountId]
                )
            );
            $description = sprintf(self::ASSIGNED_LOT_DESCRIPTION, $saleNo, $dto->auctionName, $dto->startClosingDate, $lotStatus);
            $lotLinks[] = sprintf($linkTpl, $lotListUrl, $description);
        }
        $lotLinkList = implode('</br>', $lotLinks);
        $output = sprintf($resultTpl, $lotLinkList);
        return $output;
    }

    /**
     * @param DataProvider $dataProvider
     * @return $this
     */
    public function setDataProvider(DataProvider $dataProvider): static
    {
        $this->dataProvider = $dataProvider;
        return $this;
    }

    /**
     * @return DataProvider
     */
    protected function getDataProvider(): DataProvider
    {
        if ($this->dataProvider === null) {
            $this->dataProvider = DataProvider::new();
        }
        return $this->dataProvider;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_IS_READ_ONLY_DB] = $optionals[self::OP_IS_READ_ONLY_DB] ?? false;
        $optionals[self::OP_LINK_TPL] = $optionals[self::OP_LINK_TPL] ?? self::LINK_TPL_DEF;
        $optionals[self::OP_RESULT_BLOCK_TPL] = $optionals[self::OP_RESULT_BLOCK_TPL] ?? self::RESULT_BLOCK_TPL_DEF;
        $this->setOptionals($optionals);
    }
}
