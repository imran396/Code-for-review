<?php
/**
 * SAM-4636: Refactor under bidders report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-04-19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\UnderBidder\Csv;

use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Model\LotItem\SellInfo\LotSellInfoPureChecker;
use Sam\Core\Transform\Csv\CsvTransformer;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Report\Base\Csv\RendererBase;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class Renderer
 */
class Renderer extends RendererBase
{
    use BidderNumPaddingAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CurrencyLoaderAwareTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Build Header Titles
     * @param array $fields
     * @return string
     */
    public function buildHeaderTitles(array $fields): string
    {
        $headerTitles = '';
        foreach ($fields as $fieldName) {
            $headerTitles .= '"' . $fieldName . '"' . ',';
        }
        $headerTitles = substr($headerTitles, 0, -1);
        $headerTitles .= "\r\n";
        return $headerTitles;
    }

    /**
     * @param array $row
     * @return array
     */
    public function buildBodyRow(array $row): array
    {
        $currencySign = $this->getCurrencyLoader()->detectDefaultSign();
        $encoding = $this->getSettingsManager()->getForSystem(Constants\Setting::DEFAULT_EXPORT_ENCODING);
        $csvTransformer = CsvTransformer::new();

        $lotName = $csvTransformer->convertEncoding($row['li_name'], $encoding);

        $hammerPrice = Cast::toFloat($row['li_hp']);
        $hammerPriceFormatted = LotSellInfoPureChecker::new()->isHammerPrice($hammerPrice)
            ? $currencySign . $this->getNumberFormatter()->formatMoney($hammerPrice)
            : '';
        $bidderNum = $this->getBidderNumberPadding()->clear($row['wb_bidder_num']);
        $winningBidder = $row['wb_username'];
        $auctionBidder = $this->getBidderNumberPadding()->clear($row['ub_bidder_num']);
        $underBidder = $row['ub_username'];
        $amount = $this->getNumberFormatter()->formatMoney($row['amount']);

        $lotNum = $csvTransformer->convertEncoding($row['lot_num'], $encoding);
        $lotExt = $csvTransformer->convertEncoding($row['lot_num_ext'], $encoding);
        $lotPrefix = $csvTransformer->convertEncoding($row['lot_num_prefix'], $encoding);

        if ($this->cfg()->get('core->lot->lotNo->concatenated')) {
            $lotNo = [$this->getLotRenderer()->makeLotNo($lotNum, $lotExt, $lotPrefix)];
        } else {
            $lotNo = [
                $lotPrefix,
                $lotNum,
                $lotExt,
            ];
        }
        $bodyRow = [
            $lotName,
            $hammerPriceFormatted,
            $bidderNum,
            $winningBidder,
            $auctionBidder,
            $underBidder,
            $amount,
        ];

        return array_merge($lotNo, $bodyRow);
    }
}
