<?php
/**
 * Rendering methods for placeholders of Lot Details output
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Feb 16, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Custom methods can be used there or in customized class (SAM-1573)
 *
 * Optional method called when rendering the custom lot item field value
 * param integer $type lot_item_cust_field.type
 * param integer $numeric lot_item_cust_data.numeric
 * param integer $text lot_item_cust_data.text
 * param mixed $parameters lot_item_cust_field.parameters
 * param integer $auctionId auction.id optional
 * return string the rendered value
 * public function LotCustomField_{Field name}_Render($type, $numeric, $text, $parameters, $auctionId = null)
 *
 * {Field name} - Camel cased name of custom field (see TextTransformer::toCamelCase())
 */

namespace Sam\Details\Lot\Base\Render;

use Laminas\Math\Rand;
use LotItemCustField;
use Sam\Address\AddressFormatterCreateTrait;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveLotDetailsUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Application\Url\Build\Config\Image\LotImageUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\BuyersPremium\Calculate\BuyersPremiumCalculatorAwareTrait;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Auction\Render\AuctionPureRenderer;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Entity\Model\AuctionLotItem\Status\AuctionLotStatusPureChecker;
use Sam\Core\Lot\Render\LotPureRenderer;
use Sam\Core\Transform\Db\DbTextTransformer;
use Sam\CustomField\Lot\Help\LotCustomFieldHelperCreateTrait;
use Sam\CustomField\Lot\Render\Web\LotCustomFieldRenderValue;
use Sam\CustomField\Lot\Render\Web\LotCustomFieldWebRendererCreateTrait;
use Sam\Date\Render\DateRendererCreateTrait;
use Sam\Location\Render\LocationRendererAwareTrait;
use Sam\Lot\Quantity\Check\LotQuantityChecker;
use Sam\Lot\Render\Amount\LotAmountRendererFactoryCreateTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\User\Render\UserRendererAwareTrait;

/**
 * Class Renderer
 * @package Sam\Details
 */
class Renderer extends \Sam\Details\Core\Render\Renderer
{
    use AddressFormatterCreateTrait;
    use AuctionRendererAwareTrait;
    use BuyersPremiumCalculatorAwareTrait;
    use DateRendererCreateTrait;
    use LocationRendererAwareTrait;
    use LotCustomFieldHelperCreateTrait;
    use LotCustomFieldWebRendererCreateTrait;
    use LotRendererAwareTrait;
    use LotAmountRendererFactoryCreateTrait;
    use SettingsManagerAwareTrait;
    use UrlBuilderAwareTrait;
    use UserRendererAwareTrait;

    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function construct(): static
    {
        return $this;
    }

    public function renderAuctionName(array $row): string
    {
        return $row['auction_name'] !== null
            ? $this->getAuctionRenderer()->makeName($row['auction_name'], (bool)$row['auction_test'], true)
            : '';
    }

    public function renderAuctionType(array $row, bool $isTranslated): string
    {
        return $isTranslated
            ? $this->getAuctionRenderer()->makeAuctionTypeTranslated(
                $row['auction_type'],
                $this->getSystemAccountId(),
                $this->getLanguageId()
            )
            : AuctionPureRenderer::new()->makeAuctionType($row['auction_type']);
    }

    public function renderBoolean(array $row, string $key): string
    {
        $output = '';
        $keysConfig = $this->getConfigManager()->getKeysConfig();
        if (isset($keysConfig[Constants\Placeholder::REGULAR][$key]['select'])) {
            $select = $keysConfig[Constants\Placeholder::REGULAR][$key]['select'];
            $resultSetField = is_array($select) ? $select[0] : $select;
            $output = $row[$resultSetField]
                ? $this->translate('LD_YES', 'lot_details')
                : $this->translate('LD_NO', 'lot_details');
        }
        return $output;
    }

    public function renderBuyersPremium(array $row): string
    {
        $lotItemId = (int)$row['id'];
        $winningAuctionId = Cast::toInt($row['winning_auction_id'], Constants\Type::F_INT_POSITIVE);
        $winningUserId = Cast::toInt($row['winner_user_id'], Constants\Type::F_INT_POSITIVE);
        $systemAccountId = $this->cfg()->get('core->portal->mainAccountId');
        return (string)$this->getBuyersPremiumCalculator()->calculate(
            $lotItemId,
            $winningAuctionId,
            $winningUserId,
            $systemAccountId
        );
    }

    public function renderCustomField(array $row, LotItemCustField $lotCustomField): string
    {
        $alias = 'c' . DbTextTransformer::new()->toDbColumn($lotCustomField->Name);
        $isNumeric = $lotCustomField->isNumeric();
        $numeric = $isNumeric ? Cast::toInt($row[$alias]) : null;
        $text = (string)$row[$alias];
        $lotItemId = (int)$row['id'];
        $renderMethod = $this->createLotCustomFieldHelper()->makeCustomMethodName($lotCustomField->Name, 'Render');
        if (method_exists($this, $renderMethod)) {
            $output = (string)$this->$renderMethod(
                $lotCustomField,
                $numeric,
                $text,
                $lotItemId,
                $this->getSystemAccountId(),
                $this->getLanguageId()
            );
        } else {
            $lotCustomFieldRenderValue = LotCustomFieldRenderValue::new()->construct(
                $lotCustomField->Id,
                $lotCustomField->Name,
                $lotCustomField->Type,
                $numeric,
                $text,
                $lotCustomField->Parameters
            );
            $output = $this->createLotCustomFieldWebRenderer()->renderTranslated(
                $lotCustomFieldRenderValue,
                $lotItemId,
                $this->getSystemAccountId(),
                $this->getLanguageId()
            );
        }
        return $output;
    }

    public function renderImageTags(
        array $row,
        ?int $index = null,
        string $imageSize = Constants\Image::EXTRA_LARGE
    ): string {
        $output = '';
        $urlList = $this->renderImageUrls($row, $index, $imageSize);
        $urls = explode(',', $urlList);
        foreach ($urls as $url) {
            $output .= sprintf('<img src="%s" class="lot-img"  alt=""/>', $url);
        }
        return $output;
    }

    public function renderImageUrls(
        array $row,
        ?int $index = null,
        string $imageSize = Constants\Image::EXTRA_LARGE
    ): string {
        $output = '';
        $lotImageIds = array_filter(ArrayCast::castInt(explode(',', (string)$row['lot_image_ids'])));
        $accountId = (int)$row['account_id'];
        $thumbnails = $this->cfg()->get('core->image->thumbnail')->toArray();
        $imageSize = array_key_exists(
            'size' . strtoupper($imageSize),
            $thumbnails
        ) ? $imageSize : Constants\Image::EXTRA_LARGE;
        if ($index === null) {
            $urls = [];
            foreach ($lotImageIds as $lotImageId) {
                $urls[] = $this->buildImageUrl($lotImageId, $imageSize, $accountId);
            }
            $output = implode(', ', $urls);
        } elseif (isset($lotImageIds[$index])) {
            $output = $this->buildImageUrl($lotImageIds[$index], $imageSize, $accountId);
        }
        return $output;
    }

    private function buildImageUrl(int $lotImageId, string $imageSize, int $accountId): string
    {
        return $this->getUrlBuilder()->build(
            LotImageUrlConfig::new()->construct($lotImageId, $imageSize, $accountId)
        );
    }

    public function renderItemNo(array $row): string
    {
        return $this->getLotRenderer()->makeItemNo($row['item_num'], $row['item_num_ext']);
    }

    public function renderLocation(array $row): string
    {
        return $this->createAddressFormatter()->format(
            (string)$row['location_country'],
            (string)$row['location_state'],
            (string)$row['location_city'],
            (string)$row['location_zip'],
            (string)$row['location_address']
        );
    }

    public function renderLocationCountry(array $row): string
    {
        return AddressRenderer::new()->countryName((string)$row['location_country']);
    }

    public function renderLocationLogoTag(array $row): string
    {
        return $this->getLocationRenderer()->makeLogoTag((int)$row['location_id'], (int)$row['account_id']);
    }

    public function renderLocationLogoUrl(array $row): string
    {
        return $this->getLocationRenderer()->makeLogoUrl((int)$row['location_id'], (int)$row['account_id']);
    }

    public function renderLocationState(array $row): string
    {
        return AddressRenderer::new()->stateName(
            (string)$row['location_state'],
            (string)$row['location_country']
        );
    }

    public function renderLotNo(array $row): string
    {
        return $this->getLotRenderer()->makeLotNo($row['lot_num'], $row['lot_num_ext'], $row['lot_num_prefix']);
    }

    public function renderLotUrl(array $row): string
    {
        return $this->getUrlBuilder()->build(
            ResponsiveLotDetailsUrlConfig::new()->forWeb(
                (int)$row['id'],
                (int)$row['auction_id'],
                $row['lot_seo_url'],
                [UrlConfigConstants::OP_ACCOUNT_ID => (int)$row['account_id']]
            )
        );
    }

    /**
     * Render lot name
     */
    public function renderName(array $row): string
    {
        return $this->getLotRenderer()->makeName($row['lot_name'], (bool)$row['auction_test']);
    }

    /**
     * Render lot quantity
     */
    public function renderQuantity(array $row): string
    {
        $shouldDisplay = LotQuantityChecker::new()->shouldDisplayForResponsivePure(
            (float)$row['qty'],
            (int)$row['qty_scale'],
            (bool)$row['qty_x_money'],
            (bool)$row['ap_main_display_quantity']
        );
        return $shouldDisplay
            ? $this->createLotAmountRendererFactory()
                ->create((int)$row['account_id'])
                ->makeQuantity(Cast::toFloat($row['qty']), (int)$row['qty_scale'])
            : '';
    }

    public function renderSaleNo(array $row): string
    {
        return $row['sale_num'] !== null
            ? $this->getAuctionRenderer()->makeSaleNo($row['sale_num'], $row['sale_num_ext'])
            : '';
    }

    public function renderSaleSoldIn(array $row): string
    {
        return $row['auction_sold_name'] !== null
            ? $this->getAuctionRenderer()->makeName(
                $row['auction_sold_name'],
                (bool)$row['auction_sold_test_auction'],
                true
            )
            : '';
    }

    public function renderSaleSoldInNo(array $row): string
    {
        return $row['auction_sold_sale_num'] !== null
            ? $this->getAuctionRenderer()->makeSaleNo($row['auction_sold_sale_num'], $row['auction_sold_sale_num_ext'])
            : '';
    }

    /**
     * Render lot status
     */
    public function renderStatus(array $row): string
    {
        $lotStatusId = (int)$row['lot_status_id'];
        $isAuctionReverse = (bool)$row['auction_reverse'];
        return LotPureRenderer::new()->makeLotStatus($lotStatusId, $isAuctionReverse);
    }

    /**
     * Render lot status
     */
    public function renderStatusTranslated(array $row): string
    {
        $lotStatusId = (int)$row['lot_status_id'];
        $isAuctionReverse = (bool)$row['auction_reverse'];
        $accountId = (int)$row['account_id'];
        return $this->getLotRenderer()->makeLotStatusTranslated(
            $lotStatusId,
            $isAuctionReverse,
            false,
            $accountId
        );
    }

    public function renderTimeLeft(array $row): string
    {
        $output = '';
        $auctionStatus = (int)$row['auction_status_id'];
        $auctionType = $row['auction_type'];
        $lotStatus = (int)$row['lot_status_id'];
        $secondsBefore = (int)$row['seconds_before'];
        $secondsLeft = (int)$row['seconds_left'];
        $lotAccountId = (int)$row['account_id'];
        $langSaleStarted = $this->translate('GENERAL_SALESTARTED', 'general');
        $langSaleClosed = $this->translate('GENERAL_SALECLOSED', 'general');
        $langLotClosed = $this->translate('GENERAL_CLOSED', 'general');
        $isShowCountdownSeconds = $this->getSettingsManager()
            ->get(Constants\Setting::SHOW_COUNTDOWN_SECONDS, $lotAccountId);
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();

        if ($auctionStatusPureChecker->isTimed($auctionType)) {
            if ($auctionLotStatusPureChecker->isAmongClosedStatuses($lotStatus)) {
                $output = $langLotClosed;
            } elseif ($auctionStatusPureChecker->isClosed($auctionStatus)) {
                $output = $langSaleClosed;
            } elseif ($secondsBefore > 0) {
                $output = $this->produceTimeLeft($secondsLeft, $isShowCountdownSeconds);
            } elseif ($secondsLeft > 0) {
                $timeLeftRandId = substr(md5(Rand::getBytes(32)), 0, 5);
                $output = '<span id="' . Constants\Placeholder::HTML_ID_TIME_LEFT_COUNTDOWN . $timeLeftRandId . '">'
                    . $this->produceTimeLeft($secondsLeft, $isShowCountdownSeconds)
                    . '</span>';
            } else {
                $output = $langSaleClosed;
            }
        } elseif ($auctionStatusPureChecker->isLiveOrHybrid($auctionType)) {
            if ($auctionLotStatusPureChecker->isAmongClosedStatuses($lotStatus)) {
                $output = $langLotClosed;
            } elseif ($auctionStatusPureChecker->isClosed($auctionStatus)) {
                $output = $langSaleClosed;
            } elseif ($secondsBefore > 0) {
                if ($auctionStatusPureChecker->isStartedOrPaused($auctionStatus)) {
                    $output = $langSaleStarted;
                } else { // Not started yet
                    $timeLeftRandId = substr(md5(Rand::getBytes(32)), 0, 5);
                    $output = '<span id="' . Constants\Placeholder::HTML_ID_TIME_LEFT_COUNTDOWN . $timeLeftRandId . '">'
                        . $this->produceTimeLeft($secondsBefore, $isShowCountdownSeconds)
                        . '</span>';
                }
            } else {
                $output = $langSaleClosed;
            }
        }

        return $output;
    }

    protected function produceTimeLeft(int $seconds, bool $isShowCountdownSeconds): string
    {
        $output = $this->createDateRenderer()->renderTimeLeft($seconds);
        if (!$isShowCountdownSeconds) {
            $output = preg_replace('/ \d+s/', '', $output);
        }
        return $output;
    }

    /**
     * Render username and mask it if it contains e-mail.
     */
    public function renderUsername(array $row): string
    {
        return $this->getUserRenderer()->maskUsernameIfAlikeEmail((string)$row['winner_username']);
    }
}
