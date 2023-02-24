<?php
/**
 * Auction Seo content builder. Main module
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Jun 27, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Auction\Web\SeoMeta;

use InvalidArgumentException;
use Sam\Core\Constants;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class Builder
 * @package Sam\Details
 */
class Builder extends \Sam\Details\Auction\Web\Builder
{
    use SettingsManagerAwareTrait;

    /**
     * @var string[]
     */
    private array $availableTemplateColumns = [
        Constants\Setting::AUCTION_PAGE_DESC,
        Constants\Setting::AUCTION_PAGE_KEYWORD,
        Constants\Setting::AUCTION_PAGE_TITLE,
    ];
    /**
     * @var string|null
     */
    private ?string $templateColumn = null;

    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function getTemplate(): string
    {
        if ($this->template === null) {
            $templateColumn = $this->getTemplateColumn();
            $this->template = (string)$this->getSettingsManager()->get($templateColumn, $this->getSystemAccountId());
        }
        return $this->template;
    }

    public function getTemplateColumn(): string
    {
        if (!in_array($this->templateColumn, $this->availableTemplateColumns, true)) {
            throw new InvalidArgumentException("Unknown TemplateColumn or not set: {$this->templateColumn}");
        }
        return $this->templateColumn;
    }

    public function setTemplateColumn(string $templateColumn): static
    {
        $this->templateColumn = trim($templateColumn);
        return $this;
    }
}
