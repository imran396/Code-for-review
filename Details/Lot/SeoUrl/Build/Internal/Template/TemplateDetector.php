<?php
/**
 * SAM-6595: Templated-content building - simplify module structure for v3.5
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 03, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Lot\SeoUrl\Build\Internal\Template;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class TemplateDetector
 * @package ${NAMESPACE}
 */
class TemplateDetector extends CustomizableClass
{
    use SettingsManagerAwareTrait;

    /** @var int */
    protected int $ambienceAccountId;

    /**
     * Class instantiation method
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(int $ambienceAccountId): static
    {
        $this->ambienceAccountId = $ambienceAccountId;
        return $this;
    }

    public function detect(): string
    {
        return (string)$this->getSettingsManager()
            ->get(Constants\Setting::LOT_SEO_URL_TEMPLATE, $this->ambienceAccountId);
    }
}
