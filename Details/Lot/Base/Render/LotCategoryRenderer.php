<?php
/**
 * Rendering methods for category related placeholders
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Jun 11, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Lot\Base\Render;

use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Application\Url\Build\Config\Search\ResponsiveSearchUrlConfig;
use Sam\Core\Service\CustomizableClass;
use LotCategory;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;

/**
 * Class LotCategoryRenderer
 * @package Sam\Details
 */
class LotCategoryRenderer extends CustomizableClass
{
    use LotCategoryLoaderAwareTrait;
    use UrlBuilderAwareTrait;
    use UrlParserAwareTrait;

    /**
     * @var LotCategory[][]
     */
    protected array $branches = [];

    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function renderCategories(array $row, bool $isTag = false, ?int $index = null): string
    {
        $accountId = (int)$row['account_id'];
        $output = $searchUrl = '';
        $names = [];
        if ($isTag) {
            $searchUrl = $this->getUrlBuilder()->build(
                ResponsiveSearchUrlConfig::new()->forDomainRule(
                    [UrlConfigConstants::OP_ACCOUNT_ID => $accountId]
                )
            );
            $searchUrl = $this->getUrlParser()->replaceParams($searchUrl, ['key' => '']);
            $searchUrl .= '&cat=%s'; // prevent url-encoding of query string param
        }
        $allValues = $this->parseCategoryValues((string)$row['category_values']);
        foreach ($allValues as $values) {
            $names[] = $isTag
                ? sprintf('<a href="' . $searchUrl . '" class="category-link">%s</a>', $values['id'], $values['name'])
                : $values['name'];
        }
        if ($index === null) {
            $output = implode(', ', $names);
        } elseif (isset($names[$index])) {
            $output = $names[$index];
        }
        return $output;
    }

    public function renderCategoryPaths(array $row, bool $isTag = false, ?int $index = null, ?int $level = null): string
    {
        $output = $linkTpl = '';
        $names = [];
        if ($isTag) {
            $searchUrl = $this->getUrlBuilder()->build(
                ResponsiveSearchUrlConfig::new()->forDomainRule(
                    [UrlConfigConstants::OP_ACCOUNT_ID => (int)$row['account_id']]
                )
            );
            $searchUrl = $this->getUrlParser()->replaceParams($searchUrl, ['key' => '']);
            $searchUrl .= '&cat=%s'; // prevent url-encoding of query string param
            $linkTpl = '<a href="' . $searchUrl . '" class="category-link">%s</a>';
        }
        $branches = $this->loadBranchesForLotItem((int)$row['id']);
        foreach ($branches as $i => $lotCategoryBranch) {
            $names[$i] = [];
            foreach ($lotCategoryBranch as $lotCategory) {
                $names[$i][] = $isTag
                    ? sprintf($linkTpl, $lotCategory->Id, $lotCategory->Name)
                    : $lotCategory->Name;
            }
        }
        if ($index === null) {
            $branchLines = [];
            foreach ($names as $branchNames) {
                $branchLines[] = $this->renderForCategoryBranch($branchNames, $level);
            }
            $output = implode(', ', $branchLines);
        } elseif (isset($names[$index])) {
            $branchNames = $names[$index];
            $output = $this->renderForCategoryBranch($branchNames, $level);
        }
        return $output;
    }

    /**
     * @param string $categoriesLine concatenated data fetched from db
     */
    protected function parseCategoryValues(string $categoriesLine): array
    {
        $categoryValues = [];
        $lines = explode('||', $categoriesLine);
        foreach ($lines as $line) {
            if (preg_match('/^id=(\d+):name=(.*)$/', $line, $matches)) {
                $values['id'] = $matches[1];
                $values['name'] = $matches[2];
                $categoryValues[] = $values;
            }
        }
        return $categoryValues;
    }

    /**
     * @param string[] $branchNames
     */
    protected function renderForCategoryBranch(array $branchNames, ?int $level): string
    {
        $output = '';
        if ($level === null) {
            $output = implode(' > ', $branchNames);
        } elseif (isset($branchNames[$level])) {
            $output = $branchNames[$level];
        }
        return $output;
    }

    /**
     * @return array<LotCategory[]>
     */
    protected function loadBranchesForLotItem(int $lotItemId): array
    {
        if (!isset($this->branches[$lotItemId])) {
            $this->branches = []; // clean previous cache
            $leaves = $this->getLotCategoryLoader()->loadForLot($lotItemId);
            $this->branches[$lotItemId] = $this->getLotCategoryLoader()->loadBranches($leaves);
        }
        return $this->branches[$lotItemId];
    }
}
