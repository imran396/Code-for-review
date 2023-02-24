<?php
/**
 * Placeholder informational section rendering special for seo content
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 * SAM-6595: Templated-content building - simplify module structure for v3.5
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Jul 27, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Lot\SeoUrl\Hint;

use Sam\Core\Service\CustomizableClass;
use Sam\Details\Core\Hint\CoreHintRendererAwareTrait;
use Sam\Details\Lot\SeoUrl\Common\Config\ConfigManagerAwareTrait;
use Sam\Core\Constants;

/**
 * Class HintRenderer
 * @package Sam\Details
 */
class LotSeoUrlHintRenderer extends CustomizableClass
{
    use ConfigManagerAwareTrait;
    use CoreHintRendererAwareTrait;

    /**
     * @var string
     */
    protected string $compositeView = Constants\LotDetail::PL_LOT_NO . '|' . Constants\LotDetail::PL_ITEM_NO . '|' . Constants\LotDetail::NOT_AVAILABLE;
    /**
     * @var bool
     */
    protected bool $isOptionSection = false;

    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function render(): string
    {
        $lotConfigManager = $this->getConfigManager();
        $optionals = [
            'compositeView' => $this->compositeView,
            'isOptionSection' => $this->isOptionSection
        ];
        return $this->getCoreHintRenderer()
            ->construct($lotConfigManager)
            ->render($optionals);
    }
}
