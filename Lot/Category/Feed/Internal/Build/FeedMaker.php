<?php
/**
 * SAM-9677: Refactor \Feed\CategoryGet
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Category\Feed\Internal\Build;

use Sam\Application\Url\Build\Config\Search\ResponsiveSearchUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Category\Feed\Internal\Load\DataProviderCreateTrait;
use SimpleXMLElement;

/**
 * Class FeedMaker
 * @package Sam\Lot\Category\Feed\Internal
 * @internal
 */
class FeedMaker extends CustomizableClass
{
    use DataProviderCreateTrait;
    use UrlBuilderAwareTrait;

    /**
     * @var string|null
     */
    protected ?string $categoryUrlTemplate = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function make(): string
    {
        $xml = new SimpleXMLElement('<Categories/>');
        $xml = $this->makeLotCategoriesXml($xml);
        $output = $xml->asXML();
        return $output;
    }

    protected function makeLotCategoriesXml(SimpleXMLElement $xml, ?int $parentCategoryId = null): SimpleXMLElement
    {
        $categories = $this->createDataProvider()->loadLotCategories($parentCategoryId);
        if (
            $categories
            && $parentCategoryId
        ) {
            $xml = $xml->addChild('SubCats');
        }

        foreach ($categories as $category) {
            if (!$parentCategoryId) {
                $categoryElement = $xml->addChild('Category');
            } else {
                $categoryElement = $xml->addChild('SubCat');
            }

            $categoryElement->addChild('Name', htmlspecialchars($category['name']));
            $categoryElement->addChild('Id', $category['id']);
            $categoryElement->addChild('Link', $this->makeCategoryUrl((int)$category['id']));
            $this->makeLotCategoriesXml($categoryElement, ($category['id']));
        }
        return $xml;
    }

    protected function makeCategoryUrl(int $categoryId): string
    {
        return sprintf($this->getCategoryUrlTemplate(), $categoryId);
    }

    protected function getCategoryUrlTemplate(): string
    {
        if ($this->categoryUrlTemplate === null) {
            $this->categoryUrlTemplate = $this->getUrlBuilder()->build(ResponsiveSearchUrlConfig::new()->forWeb()) . '?cat=%s';
        }
        return $this->categoryUrlTemplate;
    }
}
