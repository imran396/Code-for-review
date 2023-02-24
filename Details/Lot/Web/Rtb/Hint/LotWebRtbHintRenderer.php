<?php
/**
 * Placeholder informational section rendering special for Rtb Lot page
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

namespace Sam\Details\Lot\Web\Rtb\Hint;

use Sam\Core\Service\CustomizableClass;
use Sam\Details\Core\Hint\CoreHintRendererAwareTrait;
use Sam\Details\Lot\Base\Hint\HintConfig;
use Sam\Details\Lot\Web\Rtb\Common\Config\ConfigManager;

/**
 * Class HintRenderer
 * @package Sam\Details
 */
class LotWebRtbHintRenderer extends CustomizableClass
{
    use CoreHintRendererAwareTrait;

    /**
     * Template's selected lot category id
     * @var int
     */
    protected int $lotCategoryIdOfTemplate;

    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * Web Public placeholders are depend on linked category. Eg. lot custom fields may be restricted per category.
     */
    public function construct(int $lotCategoryId): static
    {
        $this->lotCategoryIdOfTemplate = $lotCategoryId;
        $configManager = ConfigManager::new()
            ->setLotCustomFieldCategoryIds([$this->lotCategoryIdOfTemplate])
            ->construct();
        $this->getCoreHintRenderer()->construct($configManager);
        return $this;
    }

    public function render(): string
    {
        $optionals = HintConfig::new()->optionals;
        return $this->getCoreHintRenderer()->render($optionals);
    }
}
