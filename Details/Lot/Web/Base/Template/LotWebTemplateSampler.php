<?php
/**
 * Template sample renderer
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 * SAM-6595: Templated-content building - simplify module structure for v3.5
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         May 9, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Lot\Web\Base\Template;

use Sam\Core\Service\CustomizableClass;
use Sam\Details\Core\Sample\CoreTemplateSamplerAwareTrait;
use Sam\Details\Lot\Web\Base\Config\ConfigManager;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Core\Constants;

/**
 * Class TemplateSampler
 * @package Sam\Details
 */
class LotWebTemplateSampler extends CustomizableClass
{
    use CoreTemplateSamplerAwareTrait;
    use LotCategoryLoaderAwareTrait;

    /**
     * Template's selected lot category id
     * @var int
     */
    protected int $lotCategoryIdOfTemplate;
    /** @var array */
    protected array $options = [
        'beginEndKeys' => [Constants\LotDetail::PL_NAME, Constants\LotDetail::PL_DESCRIPTION],
        'composeViews' => [
            Constants\LotDetail::PL_LOT_NO . '|' . Constants\LotDetail::PL_ITEM_NO,
            Constants\LotDetail::PL_NAME . '|' . Constants\LotDetail::PL_DESCRIPTION . '[flt=StripTags;Length(20)]|' . Constants\LotDetail::NOT_AVAILABLE
        ],
        'newLine' => "<br />\n",
        'sectionTpl' => "<h1>%s<h1><br />\n%s<br />\n<br />\n",
        'viewsWithOptions' => [
            Constants\LotDetail::PL_NAME . '[flt=Length(10)]',
            Constants\LotDetail::PL_CATEGORY_PATHS . '[idx=0]',
            Constants\LotDetail::PL_CATEGORY_PATH_LINKS . '[idx=0][lvl=0]',
            Constants\LotDetail::PL_IMAGE_URLS . '[isz=4]',
            Constants\LotDetail::PL_AUCTION_START_DATE . '[fmt=d, m-Y]',
        ],
    ];

    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * Web Public placeholders are depend on linked category. Eg. lot custom fields may be restricted per category.
     */
    public function construct(int $lotCategoryId, ConfigManager $configManager): static
    {
        $this->lotCategoryIdOfTemplate = $lotCategoryId;
        $this->getCoreTemplateSampler()->construct($configManager, $this->options);
        return $this;
    }

    public function render(): string
    {
        $output = $this->getCoreTemplateSampler()->render();
        if ($this->lotCategoryIdOfTemplate) {
            $lotCategory = $this->getLotCategoryLoader()->load($this->lotCategoryIdOfTemplate, true);
            if ($lotCategory === null) {
                log_error(
                    "Available lot category not found"
                    . composeSuffix(['lc' => $this->lotCategoryIdOfTemplate])
                );
                return '';
            }
            $output = <<<OUTPUT
Lot's main category: {category_paths[idx=0]}<br />
Template category: {$lotCategory->Name}<br />
<br />
{$output}
OUTPUT;
        }
        return $output;
    }
}
