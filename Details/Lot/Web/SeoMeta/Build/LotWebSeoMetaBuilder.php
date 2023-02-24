<?php
/**
 * Lot Seo content builder. Main module
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

namespace Sam\Details\Lot\Web\SeoMeta\Build;

use InvalidArgumentException;
use Sam\Core\Constants;
use Sam\Core\Service\ServiceAccountAwareTrait;
use Sam\Details\Lot\Web\Base\Build\Builder;
use Sam\Details\Lot\Web\SeoMeta\Common\Config\ConfigManager;
use Sam\Details\Lot\Web\SeoMeta\Common\Config\ConfigManagerAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class Builder
 * @package Sam\Details
 */
class LotWebSeoMetaBuilder extends Builder
{
    use ConfigManagerAwareTrait;
    use ServiceAccountAwareTrait;
    use SettingsManagerAwareTrait;

    /**
     * @var string[]
     */
    private array $availableTemplateColumns = [
        Constants\Setting::LOT_PAGE_DESC,
        Constants\Setting::LOT_PAGE_KEYWORD,
        Constants\Setting::LOT_PAGE_TITLE,
    ];
    /**
     * @var string
     */
    private string $templateColumn;

    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * @param array $optionals [
     *    self::OP_LOT_CATEGORIES => LotCategory[]
     * ]
     */
    public function construct(int $serviceAccountId, int $systemAccountId, string $templateColumn, int $lotItemId, ?int $auctionId = null, array $optionals = []): static
    {
        if (!in_array($templateColumn, $this->availableTemplateColumns, true)) {
            throw new InvalidArgumentException("Unknown TemplateColumn or not set: {$templateColumn}");
        }

        $this->init($systemAccountId, $lotItemId, $auctionId);
        $this->setServiceAccountId($serviceAccountId);
        $this->templateColumn = $templateColumn;

        $this->initConfigManager(ConfigManager::new(), $optionals);

        return $this;
    }

    public function getTemplate(): string
    {
        if ($this->template === null) {
            $this->template = (string)$this->getSettingsManager()->get($this->templateColumn, $this->getSystemAccountId());
        }
        return $this->template;
    }
}
