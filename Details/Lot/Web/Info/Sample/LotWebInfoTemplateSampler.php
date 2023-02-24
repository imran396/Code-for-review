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

namespace Sam\Details\Lot\Web\Info\Sample;

use Sam\Core\Service\CustomizableClass;
use Sam\Details\Lot\Web\Base\Template\LotWebTemplateSamplerAwareTrait;
use Sam\Details\Lot\Web\Info\Common\Config\ConfigManager;

/**
 * Class TemplateSampler
 * @package Sam\Details
 */
class LotWebInfoTemplateSampler extends CustomizableClass
{
    use LotWebTemplateSamplerAwareTrait;

    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * Web Public placeholders are depend on linked category. Eg. lot custom fields may be restricted per category.
     */
    public function construct(int $lotCategoryId): static
    {
        $configManager = ConfigManager::new()
            ->setLotCustomFieldCategoryIds([$lotCategoryId])
            ->construct();
        $this->getLotWebTemplateSampler()->construct($lotCategoryId, $configManager);
        return $this;
    }

    public function render(): string
    {
        return $this->getLotWebTemplateSampler()->render();
    }
}
