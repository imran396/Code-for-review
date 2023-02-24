<?php
/**
 * Placeholder informational section rendering special for Lot Feed
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

namespace Sam\Details\Lot\Feed\Hint;

use Sam\Core\Service\CustomizableClass;
use Sam\Details\Core\Hint\CoreHintRendererAwareTrait;
use Sam\Details\Lot\Base\Hint\HintConfig;
use Sam\Details\Lot\Feed\ConfigManagerAwareTrait;

/**
 * Class HintRenderer
 * @package Sam\Details
 */
class LotFeedHintRenderer extends CustomizableClass
{
    use ConfigManagerAwareTrait;
    use CoreHintRendererAwareTrait;

    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function render(): string
    {
        $lotConfigManager = $this->getConfigManager();
        $optionals = HintConfig::new()->optionals;
        return $this->getCoreHintRenderer()
            ->construct($lotConfigManager)
            ->render($optionals);
    }
}
