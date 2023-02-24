<?php
/**
 * SAM-5667: Extract logic for Auction lot info for Consignor Schedule page at admin side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 10, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionConsignorSchedule\Render;

use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Application\Ui\Ui;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Render\Amount\LotAmountRendererFactoryCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\View\Admin\Form\AuctionConsignorSchedule\Load\AuctionConsignorScheduleLoaderCreateTrait;
use Sam\View\Base\HtmlRenderer;

/**
 * Class AuctionConsignorScheduleRenderer
 * @package Sam\View\Admin\Form\AuctionConsignorSchedule\Render
 */
class AuctionConsignorScheduleRenderer extends CustomizableClass
{
    use AuctionConsignorScheduleLoaderCreateTrait;
    use AuctionLoaderAwareTrait;
    use LotAmountRendererFactoryCreateTrait;
    use SettingsManagerAwareTrait;
    use TranslatorAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $consignorUserId
     * @param int $auctionId
     * @return string
     */
    public function render(int $consignorUserId, int $auctionId): string
    {
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            log_error(
                "Available auction not found, when rendering consignor schedule"
                . composeSuffix(['a' => $auctionId, 'cons. u' => $consignorUserId])
            );
            return '';
        }


        $lotAmountRenderer = $this->createLotAmountRendererFactory()->create($auction->AccountId);
        $lotInfos = $this->createAuctionConsignorScheduleLoader()->load($consignorUserId, $auctionId, true);
        foreach ($lotInfos as $key => $lotInfo) {
            $quantityFormatted = '';
            if (Floating::gt($lotInfo['quantity'], 0, (int)$lotInfo['quantity_scale'])) {
                $quantityFormatted = '(' . $lotAmountRenderer->makeQuantity((float)$lotInfo['quantity'], (int)$lotInfo['quantity_scale']) . ') ';
            }
            $lotInfos[$key]['QuantityFormatted'] = $quantityFormatted;
        }

        $rendererData = [
            'ConsignorScheduleAuctionAgreementNumber' => sprintf(
                $this->getTranslator()->translate('CONSIGNOR_SCHEDULE_AUCTION_AGREEMENT_NUMBER', 'auctions'),
                $this->getAuctionAgreementNumber($lotInfos[0] ?? null)
            ),
            'ConsignorScheduleHeader' => $this->getSettingsManager()->get(
                Constants\Setting::CONSIGNOR_SCHEDULE_HEADER,
                $auction->AccountId
            ),
            'ConsignorScheduleName' => $this->getTranslator()->translate('CONSIGNOR_SCHEDULE_NAME', 'auctions'),
            'ConsignorScheduleSignAuctionCompany' => $this->getTranslator()->translate(
                'CONSIGNOR_SCHEDULE_SIGN_AUCTION_COMPANY',
                'auctions'
            ),
            'ConsignorScheduleSignConsignor' => $this->getTranslator()->translate(
                'CONSIGNOR_SCHEDULE_SIGN_CONSIGNOR',
                'auctions'
            ),
            'LotInfos' => $lotInfos,
        ];
        $output = HtmlRenderer::new()->getTemplate(
            'manage-auctions/consignor-schedule-document.tpl.php',
            $rendererData,
            Ui::new()->constructWebAdmin()
        );
        return $output;
    }

    /**
     * @param array|null $lotInfo
     * @return string
     */
    private function getAuctionAgreementNumber(array $lotInfo = null): string
    {
        if ($lotInfo) {
            $output = sprintf('<u>%s-%s</u>', $lotInfo['customer_no'] ?? $lotInfo['username'], $lotInfo['sale_num']);
            return $output;
        }

        return '';
    }
}
