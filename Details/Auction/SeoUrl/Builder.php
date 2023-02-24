<?php
/**
 * Auction Seo Url content builder. Main module
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Jul 2, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Auction\SeoUrl;

use InvalidArgumentException;
use Sam\Core\Constants;
use Sam\Details\Core\DataProviderInterface;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class Builder
 * @package Sam\Details
 * @property TemplateParser $templateParser
 */
class Builder extends \Sam\Details\Core\Builder
{
    use ConfigManagerAwareTrait;
    use SettingsManagerAwareTrait;

    /**
     * @var int[]
     */
    protected array $auctionIds = [];
    protected ?array $auctionRows = null;

    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * @return string[] [<auction.id> => <content>, ... ]
     */
    public function render(): array
    {
        if (!$this->cfg()->get('core->auction->seoUrl->enabled')) {
            log_trace('SeoUrl generating for auction is disabled');
            return [];
        }
        $this->options = Options::new()->construct($this->getSystemAccountId());
        $this->options->auctionIds = $this->getAuctionIds();
        return $this->build();
    }

    /**
     * @return int[]
     */
    public function getAuctionIds(): array
    {
        if (!$this->auctionIds) {
            throw new InvalidArgumentException("AuctionIds not defined");
        }
        return $this->auctionIds;
    }

    /**
     * @param int[] $auctionIds
     */
    public function setAuctionIds(array $auctionIds): static
    {
        $this->auctionIds = $auctionIds;
        return $this;
    }

    public function getDataProvider(): DataProviderInterface
    {
        if ($this->dataProvider === null) {
            $this->dataProvider = DataProvider::new()
                ->setPlaceholderManager($this->getPlaceholderManager())
                ->setOptions($this->options);
        }
        return $this->dataProvider;
    }

    public function getTemplate(): string
    {
        if ($this->template === null) {
            $this->template = (string)$this->getSettingsManager()->get(Constants\Setting::AUCTION_SEO_URL_TEMPLATE, $this->getSystemAccountId());
        }
        return $this->template;
    }

    public function getTemplateParser(): TemplateParser
    {
        if ($this->templateParser === null) {
            $this->templateParser = TemplateParser::new()
                ->setConfigManager($this->getConfigManager())
                ->setSystemAccountId($this->getSystemAccountId());
        }
        return $this->templateParser;
    }

    /**
     * @return string[]
     */
    protected function build(): array
    {
        $outputs = [];
        foreach ($this->getAuctionIds() as $auctionId) {
            $outputs[$auctionId] = '';
        }
        foreach ($this->getAuctionRows() as $row) {
            $auctionId = (int)$row['id'];
            $placeholders = $this->getPlaceholderManager()->getActualPlaceholders();
            $template = $this->getTemplate();
            $outputs[$auctionId] = $this->getTemplateParser()->parse($template, $placeholders, $row);
        }
        return $outputs;
    }

    protected function getAuctionRows(): array
    {
        if ($this->auctionRows === null) {
            $this->auctionRows = $this->getDataProvider()->load();
        }
        return $this->auctionRows;
    }

    public function setAuctionRows(array $rows): static
    {
        $this->auctionRows = $rows;
        return $this;
    }
}
