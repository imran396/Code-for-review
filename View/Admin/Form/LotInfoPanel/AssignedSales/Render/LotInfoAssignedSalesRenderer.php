<?php
/**
 * SAM-6717: Refactor assigned sales label at Lot Item Edit page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 25, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotInfoPanel\AssignedSales\Render;

use Sam\Application\Url\Build\Config\Auction\AnySingleAuctionUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\View\Admin\Form\LotInfoPanel\AssignedSales\Render\Internal\Load\LotInfoAssignedSalesDataLoaderCreateTrait;

/**
 * Class AssignedSalesRenderer
 * @package Sam\View\Admin\Form\LotInfoPanel\AssignedSales\Render
 */
class LotInfoAssignedSalesRenderer extends CustomizableClass
{
    use AuctionRendererAwareTrait;
    use LotInfoAssignedSalesDataLoaderCreateTrait;
    use OptionalsTrait;
    use UrlBuilderAwareTrait;

    // Incoming values

    public const OP_LINK_TPL = 'linkTpl';
    public const OP_RESULT_TPL = 'resultTpl';

    // Internal values

    protected const LINK_TPL_DEF = '<a href="%s" target="_blank">%s</a>';
    protected const RESULT_TPL_DEF = '<div>This item is already in the following sales: (%s)%s</div><div class="clear"></div>';

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
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @param int $filterLotItemId
     * @param int|null $skipAuctionId null - no auction to skip
     * @param string $removeFromSaleLink
     * @return string
     */
    public function render(int $filterLotItemId, ?int $skipAuctionId, string $removeFromSaleLink): string
    {
        $dataLoader = $this->createLotInfoAssignedSalesDataLoader()
            ->construct($filterLotItemId, $skipAuctionId);
        if (!$dataLoader->count()) {
            return '';
        }

        $linkTpl = (string)$this->fetchOptional(self::OP_LINK_TPL);
        $resultTpl = (string)$this->fetchOptional(self::OP_RESULT_TPL);
        $auctionRenderer = $this->getAuctionRenderer();
        $urlBuilder = $this->getUrlBuilder();
        $lotLinks = [];
        foreach ($dataLoader->load() as $dto) {
            $saleNo = $auctionRenderer->makeSaleNo($dto->saleNum, $dto->saleNumExt);
            $lotListUrl = $urlBuilder->build(
                AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_LOT_LIST, $dto->auctionId)
            );
            $lotLinks[] = sprintf($linkTpl, $lotListUrl, $saleNo);
        }
        $lotLinkList = implode(',', $lotLinks);
        $output = sprintf($resultTpl, $lotLinkList, $removeFromSaleLink);
        return $output;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_RESULT_TPL] = $optionals[self::OP_RESULT_TPL] ?? self::RESULT_TPL_DEF;
        $optionals[self::OP_LINK_TPL] = $optionals[self::OP_LINK_TPL] ?? self::LINK_TPL_DEF;
        $this->setOptionals($optionals);
    }
}
