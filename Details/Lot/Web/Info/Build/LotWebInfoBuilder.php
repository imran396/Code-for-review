<?php
/**
 * Lot Info page details output builder. Main module
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

namespace Sam\Details\Lot\Web\Info\Build;

use Sam\Details\Lot\Web\Base\Build\Builder;
use Sam\Details\Lot\Web\Info\Common\Config\ConfigManager;
use Sam\Details\Lot\Web\Info\Common\Config\ConfigManagerAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Core\Constants;

/**
 * Class Builder
 * @package Sam\Details
 */
class LotWebInfoBuilder extends Builder
{
    use ConfigManagerAwareTrait;
    use SettingsManagerAwareTrait;

    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function construct(int $systemAccountId, int $lotItemId, ?int $auctionId = null): static
    {
        $this->init($systemAccountId, $lotItemId, $auctionId);
        $this->initConfigManager(ConfigManager::new());
        return $this;
    }

    public function shouldHideEmptyFields(): bool
    {
        if ($this->shouldHideEmptyFields === null) {
            [$this->template, $this->shouldHideEmptyFields] = $this->detectTemplate();
        }
        return $this->shouldHideEmptyFields;
    }

    public function getTemplate(): string
    {
        if ($this->template === null) {
            [$this->template, $this->shouldHideEmptyFields] = $this->detectTemplate();
        }
        return $this->template;
    }

    /**
     * Get rtb lot details template
     */
    protected function detectTemplate(): array
    {
        $sm = $this->getSettingsManager();
        $accountId = $this->getLotItem()->AccountId;
        $lotCategory = $this->getLotCategoryLoader()
            ->loadFirstForLot($this->getLotItemId(), true);
        if ($lotCategory !== null) {
            $lotCategoryTemplate = $this->getLotCategoryTemplateLoader()
                ->loadTemplate($lotCategory->Id, $accountId, true);
            if ($lotCategoryTemplate !== null) {
                $template = $lotCategoryTemplate->LotItemDetailTemplate;
                $shouldHideEmptyFields = $lotCategory->HideEmptyFields;
            } else {
                $template = $sm->get(Constants\Setting::LOT_ITEM_DETAIL_TEMPLATE, $accountId);
                $shouldHideEmptyFields = (bool)$sm->get(Constants\Setting::CUSTOM_TEMPLATE_HIDE_EMPTY_FIELDS_FOR_ALL_CATEGORIES, $accountId);
            }
        } else {
            $template = $sm->get(Constants\Setting::LOT_ITEM_DETAIL_TEMPLATE_FOR_NO_CATEGORY, $accountId);
            $shouldHideEmptyFields = (bool)$sm->get(Constants\Setting::CUSTOM_TEMPLATE_HIDE_EMPTY_FIELDS_FOR_NO_CATEGORY_LOT, $accountId);
        }
        return [$template, $shouldHideEmptyFields];
    }
}
 
