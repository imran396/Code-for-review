<?php
/**
 * Lot Feed output builder
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
 */

namespace Sam\Details\Lot\Base\Render;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Details\Core\Placeholder\Placeholder;

/**
 * Class TemplateParser
 * @package Sam\Details
 * @property Renderer $renderer
 */
class TemplateParser extends \Sam\Details\Core\Render\TemplateParser
{
    /**
     * @var LotCategoryRenderer|null
     */
    protected ?LotCategoryRenderer $lotCategoryRenderer = null;
    /**
     * @var BulkPieceRenderer|null
     */
    protected ?BulkPieceRenderer $bulkPieceRenderer = null;

    /**
     * Class instantiation method
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    protected function produceValueForRegularPlaceholder(Placeholder $placeholder, array $row): string
    {
        $key = $placeholder->getKey();
        if (in_array(
            $key,
            [
                Constants\LotDetail::PL_FEATURED,
                Constants\LotDetail::PL_INTERNET_BID,
                Constants\LotDetail::PL_NO_TAX_OUTSIDE_STATE,
                Constants\LotDetail::PL_RETURNED,
            ],
            true
        )) {
            $value = $this->getRenderer()->renderBoolean($row, $key);
        } elseif ($key === Constants\LotDetail::PL_AUCTION_NAME) {
            $value = $this->getRenderer()->renderAuctionName($row);
        } elseif ($key === Constants\LotDetail::PL_AUCTION_TYPE) {
            $value = $this->getRenderer()->renderAuctionType($row, false);
        } elseif ($key === Constants\LotDetail::PL_AUCTION_TYPE_LANG) {
            $value = $this->getRenderer()->renderAuctionType($row, true);
        } elseif ($key === Constants\LotDetail::PL_BULK_PIECE_INFO) {
            $value = $this->getBulkPieceRenderer()->renderBulkPieceInfo($row);
        } elseif ($key === Constants\LotDetail::PL_BUYERS_PREMIUM) {
            $value = $this->getRenderer()->renderBuyersPremium($row);
        } elseif ($key === Constants\LotDetail::PL_ITEM_NO) {
            $value = $this->getRenderer()->renderItemNo($row);
        } elseif ($key === Constants\LotDetail::PL_LOCATION) {
            $value = $this->getRenderer()->renderLocation($row);
        } elseif ($key === Constants\LotDetail::PL_LOCATION_COUNTRY) {
            $value = $this->getRenderer()->renderLocationCountry($row);
        } elseif ($key === Constants\LotDetail::PL_LOCATION_LOGO_TAG) {
            $value = $this->getRenderer()->renderLocationLogoTag($row);
        } elseif ($key === Constants\LotDetail::PL_LOCATION_LOGO_URL) {
            $value = $this->getRenderer()->renderLocationLogoUrl($row);
        } elseif ($key === Constants\LotDetail::PL_LOCATION_STATE) {
            $value = $this->getRenderer()->renderLocationState($row);
        } elseif ($key === Constants\LotDetail::PL_LOT_NO) {
            $value = $this->getRenderer()->renderLotNo($row);
        } elseif ($key === Constants\LotDetail::PL_LOT_URL) {
            $value = $this->getRenderer()->renderLotUrl($row);
        } elseif ($key === Constants\LotDetail::PL_NAME) {
            $value = $this->getRenderer()->renderName($row);
        } elseif ($key === Constants\LotDetail::PL_SALE_NO) {
            $value = $this->getRenderer()->renderSaleNo($row);
        } elseif ($key === Constants\LotDetail::PL_SALE_SOLD_IN) {
            $value = $this->getRenderer()->renderSaleSoldIn($row);
        } elseif ($key === Constants\LotDetail::PL_SALE_SOLD_IN_NO) {
            $value = $this->getRenderer()->renderSaleSoldInNo($row);
        } elseif ($key === Constants\LotDetail::PL_STATUS) {
            $value = $this->getRenderer()->renderStatus($row);
        } elseif ($key === Constants\LotDetail::PL_STATUS_LANG) {
            $value = $this->getRenderer()->renderStatusTranslated($row);
        } elseif ($key === Constants\LotDetail::PL_TIME_LEFT) {
            $value = $this->getRenderer()->renderTimeLeft($row);
        } elseif ($key === Constants\LotDetail::PL_QUANTITY) {
            $value = $this->getRenderer()->renderQuantity($row);
        } elseif ($key === Constants\LotDetail::PL_WINNING_BIDDER_USERNAME) {
            $value = $this->getRenderer()->renderUsername($row);
        } else {
            $value = $this->getRenderer()->renderAsIs($row, $key);
        }
        return $value;
    }

    protected function produceValueForIndexedArrayPlaceholder(Placeholder $placeholder, array $row): string
    {
        $key = $placeholder->getKey();
        $index = Cast::toInt($placeholder->getOptionValue("idx"));
        $level = Cast::toInt($placeholder->getOptionValue("lvl"));
        $value = '';
        if ($key === Constants\LotDetail::PL_CATEGORIES) {
            $value = $this->getLotCategoryRenderer()->renderCategories($row, false, $index);
        } elseif ($key === Constants\LotDetail::PL_CATEGORY_LINKS) {
            $value = $this->getLotCategoryRenderer()->renderCategories($row, true, $index);
        } elseif ($key === Constants\LotDetail::PL_CATEGORY_PATHS) {
            $value = $this->getLotCategoryRenderer()->renderCategoryPaths($row, false, $index, $level);
        } elseif ($key === Constants\LotDetail::PL_CATEGORY_PATH_LINKS) {
            $value = $this->getLotCategoryRenderer()->renderCategoryPaths($row, true, $index, $level);
        } elseif ($key === Constants\LotDetail::PL_IMAGE_URLS) {
            $imageSize = $placeholder->getOptionValue('isz') ?? Constants\Image::EXTRA_LARGE;
            $value = $this->getRenderer()->renderImageUrls($row, $index, $imageSize);
        } elseif ($key === Constants\LotDetail::PL_IMAGE_TAGS) {
            $imageSize = $placeholder->getOptionValue('isz') ?? Constants\Image::EXTRA_LARGE;
            $value = $this->getRenderer()->renderImageTags($row, $index, $imageSize);
        }
        return $value;
    }

    protected function produceValueForCustomFieldPlaceholder(Placeholder $placeholder, array $row): string
    {
        $customField = $this->getConfigManager()->getLotCustomFieldByPlaceholderKey($placeholder->getKey());
        if ($customField === null) {
            return '';
        }
        if ($customField->Type === Constants\CustomField::TYPE_DATE) {
            $format = $placeholder->getOptionValue('fmt') ?? '';
            $customField->Parameters = $format;
        }
        return $this->getRenderer()->renderCustomField($row, $customField);
    }

    public function getRenderer(): Renderer
    {
        if ($this->renderer === null) {
            $this->renderer = Renderer::new()
                ->setConfigManager($this->getConfigManager())
                ->setLanguageId($this->getLanguageId())
                ->setSystemAccountId($this->getSystemAccountId())
                ->construct();
        }
        return $this->renderer;
    }

    public function getBulkPieceRenderer(): BulkPieceRenderer
    {
        if ($this->bulkPieceRenderer === null) {
            $this->bulkPieceRenderer = BulkPieceRenderer::new()
                ->setLanguageId($this->getLanguageId())
                ->setSystemAccountId($this->getSystemAccountId());
        }
        return $this->bulkPieceRenderer;
    }

    public function setBulkPieceRenderer(BulkPieceRenderer $bulkPieceRenderer): static
    {
        $this->bulkPieceRenderer = $bulkPieceRenderer;
        return $this;
    }

    public function getLotCategoryRenderer(): LotCategoryRenderer
    {
        if ($this->lotCategoryRenderer === null) {
            $this->lotCategoryRenderer = LotCategoryRenderer::new();
        }
        return $this->lotCategoryRenderer;
    }

    public function setLotCategoryRenderer(LotCategoryRenderer $lotCategoryRenderer): static
    {
        $this->lotCategoryRenderer = $lotCategoryRenderer;
        return $this;
    }
}
