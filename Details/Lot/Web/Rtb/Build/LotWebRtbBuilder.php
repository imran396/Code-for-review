<?php
/**
 * Lot Rtb Info page details output builder. Main module
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

namespace Sam\Details\Lot\Web\Rtb\Build;

use Sam\Core\Constants;
use Sam\Details\Lot\Web\Base\Build\Builder;
use Sam\Details\Lot\Web\Rtb\Common\Config\ConfigManager;
use Sam\Details\Lot\Web\Rtb\Common\Config\ConfigManagerAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class Builder
 * @package Sam\Details
 */
class LotWebRtbBuilder extends Builder
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
        return parent::shouldHideEmptyFields();
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
        $accountId = $this->getSystemAccountId();
        $lotCategory = $this->getLotCategoryLoader()
            ->loadFirstForLot($this->getLotItemId(), true);
        if ($lotCategory !== null) {
            $lotCategoryTemplate = $this->getLotCategoryTemplateLoader()
                ->loadTemplate($lotCategory->Id, $this->getLotItem()->AccountId, true);
            if ($lotCategoryTemplate !== null) {
                $rtbDetailTemplate = $lotCategoryTemplate->RtbDetailTemplate;
                $shouldHideEmptyField = $lotCategory->HideEmptyFields;
            } else {
                // null means unset, hence type cast:
                $rtbDetailTemplate = $sm->get(Constants\Setting::RTB_DETAIL_TEMPLATE, $accountId);
                $shouldHideEmptyField = (bool)$sm->get(Constants\Setting::CUSTOM_TEMPLATE_HIDE_EMPTY_FIELDS_FOR_ALL_CATEGORIES, $accountId);
            }
        } else {
            $rtbDetailTemplate = $sm->get(Constants\Setting::RTB_DETAIL_TEMPLATE_FOR_NO_CATEGORY, $accountId);
            $shouldHideEmptyField = (bool)$sm->get(Constants\Setting::CUSTOM_TEMPLATE_HIDE_EMPTY_FIELDS_FOR_NO_CATEGORY_LOT, $accountId);
        }
        return [$rtbDetailTemplate, $shouldHideEmptyField];
    }
}
