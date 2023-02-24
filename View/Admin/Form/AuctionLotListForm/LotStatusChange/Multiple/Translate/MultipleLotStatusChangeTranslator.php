<?php
/**
 * SAM-10177: Decouple the "Lot status change" function at the "Auction Lot List" page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 07, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Translate;

use Sam\Application\Url\Build\Config\AuctionLot\AdminLotEditUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Lot\Render\LotPureRenderer;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Load\LotDto;
use Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Validate\LotStatusChangeValidationResult;
use Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Validate\MultipleLotStatusChangeValidationResultCollection;

/**
 * Class MultipleLotStatusChangeTranslator
 * @package Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Translate
 */
class MultipleLotStatusChangeTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;
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

    public function makeErrorMessage(
        MultipleLotStatusChangeValidationResultCollection $validationResultCollection,
        int $auctionId,
        string $language
    ): string {
        $message = '';
        $lotDtosWithAbsentHPError = $validationResultCollection->collectLotDtosWithError(
            LotStatusChangeValidationResult::ERR_ABSENT_HAMMER_PRICE
        );
        if ($lotDtosWithAbsentHPError) {
            $lotLinkList = $this->makeLotLinkList($lotDtosWithAbsentHPError, $auctionId, $language);
            $message = $this->getAdminTranslator()->trans(
                'lot.status_change.multiple.absent_hammer_price',
                ['lotLinkList' => $lotLinkList],
                'admin_validation',
                $language
            );
        }
        return $message;
    }

    public function makeSuccessMessage(
        MultipleLotStatusChangeValidationResultCollection $validationResultCollection,
        int $auctionId,
        string $language
    ): string {
        $message = '';
        $validLotDtos = $validationResultCollection->collectValidLotDtos();
        if ($validLotDtos) {
            $lotLinkList = $this->makeLotLinkList($validLotDtos, $auctionId, $language);
            $message = $this->getAdminTranslator()->trans(
                'lot.status_change.multiple.status_changed',
                ['lotLinkList' => $lotLinkList],
                'admin_message',
                $language
            );
        }
        return $message;
    }

    public function makeNoLotsProcessedMessage(string $language): string
    {
        return $this->getAdminTranslator()->trans('lot.status_change.multiple.no_lots_processed', [], 'admin_message', $language);
    }

    protected function makeLotLinkList(array $lotDtos, int $auctionId, string $language): string
    {
        $links = array_map(
            function (LotDto $lotDto) use ($auctionId, $language): string {
                return $this->makeLotLink($lotDto, $auctionId, $language);
            },
            $lotDtos
        );
        return implode(', ', $links);
    }

    protected function makeLotLink(LotDto $lotDto, int $auctionId, string $language): string
    {
        $link = sprintf(
            '<a href="%s" target="blank">%s</a>',
            $this->getUrlBuilder()->build(
                AdminLotEditUrlConfig::new()->forWeb($lotDto->lotItemId, $auctionId)
            ),
            $this->makeLotLinkTitle($lotDto, $language)
        );
        return $link;
    }

    protected function makeLotLinkTitle(LotDto $lotDto, string $language): string
    {
        $title = $this->getAdminTranslator()->trans(
            'lot.status_change.multiple.lot_link_title',
            [
                'itemNo' => $this->makeItemNo($lotDto),
                'lotNo' => $this->makeLotNo($lotDto) ?? 'absent'
            ],
            'admin_message',
            $language
        );
        return $title;
    }

    protected function makeItemNo(LotDto $lotDto): string
    {
        $itemNoExtensionSeparator = $this->cfg()->get('core->lot->itemNo->extensionSeparator');
        return LotPureRenderer::new()->makeItemNo((string)$lotDto->itemNum, $lotDto->itemNumExt, $itemNoExtensionSeparator);
    }

    protected function makeLotNo(LotDto $lotDto): ?string
    {
        if ($lotDto->lotNum === null) {
            return null;
        }
        $lotNoExtensionSeparator = $this->cfg()->get('core->lot->lotNo->extensionSeparator');
        $lotNoPrefixSeparator = $this->cfg()->get('core->lot->lotNo->prefixSeparator');
        return LotPureRenderer::new()->makeLotNo(
            $lotDto->lotNum,
            $lotDto->lotNumExt,
            $lotDto->lotNumPrefix,
            $lotNoExtensionSeparator,
            $lotNoPrefixSeparator
        );
    }
}
