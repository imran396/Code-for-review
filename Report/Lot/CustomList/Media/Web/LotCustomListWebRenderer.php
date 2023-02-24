<?php
/**
 * SAM-4629: Refactor custom lot report
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 11, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Lot\CustomList\Media\Web;

use Currency;
use LotCategory;
use LotImage;
use LotItemCustField;
use Sam\Application\Url\Build\Config\Auction\AnySingleAuctionUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\AdminLotEditUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Application\Url\Build\Config\Image\AuctionImageUrlConfig;
use Sam\Application\Url\Build\Config\Image\LotImageUrlConfig;
use Sam\Application\Url\Build\Config\LotItem\AnySingleLotItemUrlConfig;
use Sam\Application\Url\Build\Config\User\AdminUserEditUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Application\Url\UrlAdvisorAwareTrait;
use Sam\Core\Constants;
use Sam\Core\CustomField\Decimal\CustomDataDecimalPureCalculator;
use Sam\Core\Entity\Model\LotItem\SellInfo\LotSellInfoPureChecker;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\CustomField\Lot\Help\LotCustomFieldHelperCreateTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\File\Manage\FileException;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Image\ImageHelper;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Category\Renderer\LotCategoryRendererAwareTrait;
use Sam\Lot\Image\Path\LotImagePathResolverCreateTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * This class contains methods for rendering lot custom list report
 *
 * Class LotCustomListWebRenderer
 * @package Sam\Report\Lot\CustomList\Media\Web
 */
class LotCustomListWebRenderer extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use CurrencyLoaderAwareTrait;
    use DateHelperAwareTrait;
    use FileManagerCreateTrait;
    use LotCategoryRendererAwareTrait;
    use LotCustomFieldHelperCreateTrait;
    use LotImagePathResolverCreateTrait;
    use NumberFormatterAwareTrait;
    use UrlAdvisorAwareTrait;
    use UrlBuilderAwareTrait;

    /** @var Currency[]|null */
    private ?array $currencies = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param LotItemCustField $lotCustomField
     * @param string $lotCustomFieldValue
     * @param int|null $auctionId
     * @return string
     */
    public function renderLotCustomFieldValue(
        LotItemCustField $lotCustomField,
        string $lotCustomFieldValue,
        int $auctionId = null
    ): string {
        $renderMethod = $this->createLotCustomFieldHelper()->makeCustomMethodName($lotCustomField->Name, 'Render'); // SAM-1570
        if (method_exists($this, $renderMethod)) {
            $value = $this->$renderMethod($lotCustomField, $lotCustomFieldValue, $auctionId);
        } else {
            switch ($lotCustomField->Type) {
                case Constants\CustomField::TYPE_INTEGER:
                    $value = $this->getNumberFormatter()->formatInteger($lotCustomFieldValue);
                    break;
                case Constants\CustomField::TYPE_DECIMAL:
                    $precision = (int)$lotCustomField->Parameters;
                    $realValue = CustomDataDecimalPureCalculator::new()->calcRealValue((int)$lotCustomFieldValue, $precision);
                    $value = $this->getNumberFormatter()->format($realValue, $precision);
                    break;
                case Constants\CustomField::TYPE_DATE:
                    $value = $this->getDateHelper()->formattedDateByTimestamp((int)$lotCustomFieldValue);
                    break;
                default:
                    $value = $lotCustomFieldValue;
                    break;
            }
        }
        return $value;
    }

    /**
     * @param LotCategory[] $lotCategories
     * @return string
     */
    public function renderLotCategories(array $lotCategories): string
    {
        $categoryNames = array_map(
            static function (LotCategory $lotCategory) {
                return ee($lotCategory->Name);
            },
            $lotCategories
        );

        return implode('; ', $categoryNames);
    }

    /**
     * @param array<int, LotCategory[]> $categoryPaths
     * @return string
     */
    public function renderLotCategoryTree(array $categoryPaths): string
    {
        $treeItems = array_map(
            function (array $lotCategories) {
                return $this->getLotCategoryRenderer()->buildCategoryTreeText($lotCategories);
            },
            $categoryPaths
        );
        return implode('; ', $treeItems);
    }

    /**
     * @param array<int, LotCategory[]> $categoryPaths
     * @param int $level
     * @return string
     */
    public function renderLotCategoryLevel(array $categoryPaths, int $level): string
    {
        $categoryNames = [];
        foreach ($categoryPaths as $lotCategories) {
            if (isset($lotCategories[$level])) {
                $categoryNames[] = $lotCategories[$level]->Name;
            }
        }
        return implode('; ', $categoryNames);
    }

    /**
     * @param LotCategory[] $lotCategories
     * @param int $colX
     * @return string
     */
    public function renderLotCategoryColX(array $lotCategories, int $colX): string
    {
        $colX--;
        return isset($lotCategories[$colX]) ? $lotCategories[$colX]->Name : '';
    }

    /**
     * @param LotCategory[] $lotCategories
     * @param int $colX
     * @return string
     */
    public function renderLotCategoryTreeColX(array $lotCategories, int $colX): string
    {
        $colX--;
        return isset($lotCategories[$colX])
            ? $this->getLotCategoryRenderer()->getCategoryTreeText($lotCategories[$colX])
            : '';
    }

    /**
     * @param array<int, LotCategory[]> $categoryPaths
     * @param int $level
     * @param int $colX
     * @return string
     */
    public function renderLotCategoryLevelColX(array $categoryPaths, int $level, int $colX): string
    {
        $categoryTree = '';
        $colX--;
        if (isset($categoryPaths[$colX][$level])) {
            $categoryTree = $categoryPaths[$colX][$level]->Name;
        }
        return $categoryTree;
    }

    /**
     * @param string $itemNumber
     * @param int $lotItemId
     * @param int $accountId
     * @return string
     */
    public function renderInventoryLink(string $itemNumber, int $lotItemId, int $accountId): string
    {
        $url = $this->getUrlBuilder()->build(
            AnySingleLotItemUrlConfig::new()->forWeb(
                Constants\Url::A_INVENTORY_LOT_EDIT,
                $lotItemId,
                [
                    // UrlConfigConstants::SEARCH_BACK_URL => true, // TODO
                    UrlConfigConstants::OP_ACCOUNT_ID => $accountId,
                ]
            )
        );
        $url = $this->getUrlAdvisor()->addBackUrl($url);
        return $this->renderLink($url, $itemNumber);
    }

    /**
     * @param string $lotNo
     * @param int $lotItemId
     * @param int $auctionId 0 when auction absent
     * @return string
     */
    public function renderLotEditLink(string $lotNo, int $lotItemId, int $auctionId): string
    {
        $url = $this->getUrlBuilder()->build(
            AdminLotEditUrlConfig::new()->forWeb($lotItemId, $auctionId)
        );
        $url = $this->getUrlAdvisor()->addBackUrl($url);
        return $this->renderLink($url, $lotNo);
    }

    /**
     * @param string $userName
     * @param int $userId
     * @param int $accountId
     * @return string
     */
    public function renderUserEditLink(string $userName, int $userId, int $accountId): string
    {
        $url = $this->getUrlBuilder()->build(
            AdminUserEditUrlConfig::new()->forWeb(
                $userId,
                [UrlConfigConstants::OP_ACCOUNT_ID => $accountId]
            )
        );
        $url = $this->getUrlAdvisor()->addBackUrl($url);
        return $this->renderLink($url, $userName);
    }

    /**
     * @param string $saleNo
     * @param int $auctionId 0 when auction absent
     * @param int $accountId
     * @return string
     */
    public function renderAuctionLotsPageUrl(string $saleNo, int $auctionId, int $accountId): string
    {
        $url = $this->getUrlBuilder()->build(
            AnySingleAuctionUrlConfig::new()->forWeb(
                Constants\Url::A_AUCTIONS_LOT_LIST,
                $auctionId,
                [
                    // UrlConfigConstants::SEARCH_BACK_URL => true,  // TODO
                    UrlConfigConstants::OP_ACCOUNT_ID => $accountId,
                ]
            )
        );
        $url = $this->getUrlAdvisor()->addBackUrl($url);
        return $this->renderLink($url, $saleNo);
    }

    /**
     * @param float $commission
     * @param float|null $hammerPrice
     * @return string
     */
    public function renderCommissionPercent(float $commission, ?float $hammerPrice): string
    {
        if (!LotSellInfoPureChecker::new()->isHammerPrice($hammerPrice)) {
            return '';
        }

        $commissionPercentAmount = Floating::gt($hammerPrice, 0.)
            ? $commission / $hammerPrice * 100
            : 0.;
        return $this->getNumberFormatter()->formatPercent($commissionPercentAmount);
    }

    /**
     * @param LotImage[] $lotImages
     * @param int $qty
     * @param int $accountId
     * @return string
     * @throws FileException
     */
    public function renderLotImages(array $lotImages, int $qty, int $accountId): string
    {
        $output = '';
        $image = 1;
        foreach ($lotImages as $lotImage) {
            $imageFilePath = $this->createLotImagePathResolver()->makeLotImageRelativePath($accountId, $lotImage->ImageLink);
            if ($this->createFileManager()->exist($imageFilePath)) {
                $lotDetailThumbSize = ImageHelper::new()->detectSizeFromMapping($this->cfg()->get('core->image->mapping->lotDetailThumb'));
                $lotImageUrl = $this->getUrlBuilder()->build(
                    LotImageUrlConfig::new()->construct($lotImage->Id, $lotDetailThumbSize, $accountId)
                );

                $output .= "<td style=\"border-style:none\"><img src=\"{$lotImageUrl}\"></td>";
                $image++;
            }
            if ($image > $qty) {
                break;
            }
        }
        if (!empty($output)) {
            $output = '<table><tr>' . $output . '</tr></table>';
        }
        return $output;
    }

    /**
     * @param int $auctionImageId
     * @param int|null $accountId null for main account
     * @return string
     */
    public function renderAuctionImage(int $auctionImageId, ?int $accountId = null): string
    {
        $size = ImageHelper::new()->detectSizeFromMapping($this->cfg()->get('core->image->mapping->customLotReportAuction'));
        $auctionImageUrl = $this->getUrlBuilder()->build(
            AuctionImageUrlConfig::new()->construct($auctionImageId, $size, $accountId)
        );
        $output = "<img src=\"{$auctionImageUrl}\" />";
        return $output;
    }

    /**
     * @param array $currencyIds
     * @return string
     */
    public function renderCurrencies(array $currencyIds): string
    {
        $currencyLines = [];
        foreach ($this->getCurrencies($currencyIds) as $currency) {
            $currencyLines[] = $currency->Name . '(' . $currency->Sign . ')';
        }
        return implode(', ', $currencyLines);
    }

    /**
     * @param string $url
     * @param string $title
     * @return string
     */
    private function renderLink(string $url, string $title): string
    {
        return '<a href="' . $url . '">' . ee($title) . '</a>';
    }

    /**
     * Return array of all available currencies or filtered by Id
     * It is cached to prevent double loading
     *
     * @param array|null $currencyIds null - for loading all currencies
     * @return Currency[]
     */
    private function getCurrencies(?array $currencyIds = null): array
    {
        if ($this->currencies === null) {
            $this->currencies = $this->getCurrencyLoader()->loadAll();
        }

        if ($currencyIds !== null) {
            return array_filter(
                $this->currencies,
                static function (Currency $currency) use ($currencyIds) {
                    return in_array($currency->Id, $currencyIds, false);
                }
            );
        }
        return $this->currencies;
    }
}
