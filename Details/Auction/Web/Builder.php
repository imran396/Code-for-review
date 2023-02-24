<?php
/**
 * Auction Info page details output builder. Main module
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Mar 2, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Auction\Web;

use InvalidArgumentException;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Details\Auction\Base\Render\TemplateParser;
use Sam\Details\Core\DataProviderInterface;
use Sam\Details\Core\Placeholder\Placeholder;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Core\Constants;

/**
 * Class Builder
 * @package Sam\Details
 * @method int getSystemAccountId() : int should be defined by auction's account
 * @property TemplateParser $templateParser
 */
abstract class Builder extends \Sam\Details\Core\Builder
{
    use AuctionAwareTrait;
    use AuctionLoaderAwareTrait;
    use ConfigManagerAwareTrait;
    use EditorUserAwareTrait;

    protected bool $needEscaping = false;

    public function render(int $auctionId, int $systemAccountId): string
    {
        $this->init($auctionId, $systemAccountId);
        return $this->build();
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
            $this->templateParser = TemplateParser::new()
                ->enableHideEmptyFields($this->shouldHideEmptyFields())
                ->setConfigManager($this->getConfigManager());
            if ($this->needEscaping) {
                $this->templateParser->setEscapingTool($this->getEscapingTool());
            }
            $this->templateParser->setSystemAccountId($this->getSystemAccountId());
        }
        return $this->templateParser;
    }

    protected function init(int $auctionId, int $systemAccountId): void
    {
        $auction = $this->getAuctionLoader()
            ->clear()
            ->load($auctionId);
        if ($auction === null) {
            throw new InvalidArgumentException("Auction not found" . composeSuffix(['a' => $auctionId]));
        }
        $this->setAuction($auction);
        $this->setSystemAccountId($systemAccountId);

        $this->options = Options::new()->construct($systemAccountId);
        $this->options->auctionId = $auctionId;
        $this->options->userId = $this->getEditorUserId();
    }

    protected function build(): string
    {
        $template = $this->getTemplate();
        $placeholders = $this->getPlaceholderManager()->getActualPlaceholders();
        if ($this->isPreBuilding) {
            $placeholders = $this->excludeNonObservablePlaceholders($placeholders);
            $this->getPlaceholderManager()->setActualPlaceholders($placeholders);
        }
        $output = '';
        foreach ($this->getAuctionRows() as $row) {
            $output .= $this->getTemplateParser()->parse($template, $placeholders, $row);
        }
        return $output;
    }

    protected function getAuctionRows(): array
    {
        $rows = $this->getDataProvider()->load();
        return $rows ?: [];
    }

    /**
     * Exclude non-observable placeholders, because they should be parsed on-the-fly right before rendering (SAM-4304).
     * @param Placeholder[] $placeholders array of source placeholders
     * @return Placeholder[] without non-observable placeholders
     */
    protected function excludeNonObservablePlaceholders(array $placeholders): array
    {
        $resultPlaceholders = [];
        foreach ($placeholders as $placeholder) {
            $keys = [];
            if ($placeholder->getType() === Constants\Placeholder::COMPOSITE) {
                foreach ($placeholder->getSubPlaceholders() as $subPlaceholder) {
                    $keys[] = $subPlaceholder->getKey();
                }
            } else {
                $keys[] = $placeholder->getKey();
            }
            $keys = array_filter($keys);
            foreach ($keys as $key) {
                $observingProperties = $this->getConfigManager()->getObservingProperties($key);
                if ($observingProperties) {
                    $resultPlaceholders[] = $placeholder;
                }
            }
        }
        return $resultPlaceholders;
    }

    // SAM-5325:
    // IK: we don't escape placeholders, because html content is expected (links, image tags, {description}, etc)
    // TB: that’s acceptable approach for data entered from the admin side (via editing, soap API, csv uploads)
    //
    // /**
    //  * @return EscapingTool
    //  */
    // public function getEscapingTool()
    // {
    //     if ($this->escapingTool === null) {
    //         $this->escapingTool = EscapingTool::new()
    //             ->setEscapingType(EscapingTool::HTMLENTITIES)
    //             ->enableProfiling($this->isProfiling());
    //     }
    //     return $this->escapingTool;
    // }
}
