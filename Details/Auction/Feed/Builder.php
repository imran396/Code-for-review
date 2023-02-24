<?php
/**
 * Auction Feed output builder. Main module
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Feb 16, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Auction\Feed;

use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Details\Auction\Base\Render\TemplateParser;
use Sam\Details\Core\DataProviderInterface;

/**
 * Class Builder
 * @package Sam\Details
 * @property Options $options
 * @property TemplateParser $templateParser
 */
class Builder extends \Sam\Details\Core\Feed\Builder
{
    use ConfigManagerAwareTrait;
    use EditorUserAwareTrait;

    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function render(): void
    {
        $this->initOptions();
        parent::render();
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

    public function getTemplateParser(): TemplateParser
    {
        if ($this->templateParser === null) {
            $configManager = $this->getConfigManager();
            // Customize feed rendering for web page urls should be based on domain rule (SAM-6663)
            $feedRenderer = Render\Renderer::new()
                ->setConfigManager($configManager);
            $this->templateParser = TemplateParser::new()
                ->enableHideEmptyFields($this->getFeed()->HideEmptyFields)
                ->setConfigManager($configManager)
                ->setConversionCurrencyId($this->getFeed()->CurrencyId)
                ->setEscapingTool($this->getEscapingTool())
                ->setLanguageId($this->options->languageId)
                ->setRenderer($feedRenderer)
                ->setSystemAccountId($this->getSystemAccountId());
        }
        return $this->templateParser;
    }

    /**
     * Initialize building options
     */
    protected function initOptions(): void
    {
        $this->options = Options::new()
            ->construct($this->getSystemAccountId())
            ->initByRequest();
        $this->options->itemsPerPage = $this->getFeed()->ItemsPerPage > 0
            ? $this->getFeed()->ItemsPerPage : $this->cfg()->get('core->feed->itemsPerPage');
        $this->options->order = 'status';
        $this->options->userId = $this->getEditorUserId();
        // If portal account, then show only auctions associated with this account
        if (
            $this->isPortalSystemAccount()
            && $this->cfg()->get('core->portal->domainAuctionVisibility') !== Constants\AccountVisibility::TRANSPARENT
        ) {
            $this->options->accountId = $this->getSystemAccountId();
        }
    }
}
