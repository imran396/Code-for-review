<?php
/**
 * Rendering methods for {bulk_piece_info} placeholder
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Jun 12, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Lot\Base\Render;

use InvalidArgumentException;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveLotDetailsUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Laminas\Filter\StripNewlines;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\AuctionLot\BulkGroup\Load\LotBulkGroupLoaderAwareTrait;
use Sam\Application\Url\BackPage\BackUrlParserAwareTrait;
use Sam\Core\Url\BackPage\BackUrlPureParser;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Core\Entity\Model\AuctionLotItem\LotBulkGrouping\LotBulkGroupingRole;

/**
 * Class BulkPieceRenderer
 * @package Sam\Details
 */
class BulkPieceRenderer extends CustomizableClass
{
    use BackUrlParserAwareTrait;
    use LotBulkGroupLoaderAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotRendererAwareTrait;
    use TranslatorAwareTrait;
    use UrlBuilderAwareTrait;

    /**
     * Language of translation
     */
    protected ?int $languageId = null;
    /**
     * Used for translating
     */
    protected ?int $systemAccountId = null;
    /**
     * @var string[][]
     */
    protected array $translations = [];

    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function renderBulkPieceInfo(array $row): string
    {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if (!$auctionStatusPureChecker->isTimed($row['auction_type'])) {
            return '';
        }

        $output = '';
        $lotBulkGrouping = LotBulkGroupingRole::new()->fromDbRow($row);
        if ($lotBulkGrouping->isMaster()) {
            $html = $this->renderBulkGroupListHtml((int)$row['alid']);
            $output = sprintf($this->translate('CATALOG_BULKGROUP_AGAINST', 'catalog'), $html);
        } elseif ($lotBulkGrouping->isPiecemeal()) {
            $html = $this->renderBulkMasterHtml($row);
            $output = sprintf($this->translate('CATALOG_PIECEMEAL_AGAINST', 'catalog'), $html);
        }
        return $output;
    }

    protected function renderBulkGroupListHtml(int $masterAuctionLotId): string
    {
        $output = '';
        $piecemealLotRows = $this->getLotBulkGroupLoader()->loadPiecemealLotRows($masterAuctionLotId);
        foreach ($piecemealLotRows as $row) {
            $catalogLotUrl = $this->getUrlBuilder()->build(
                ResponsiveLotDetailsUrlConfig::new()->forDomainRule(
                    (int)$row['lot_item_id'],
                    (int)$row['auction_id'],
                    $row['lot_seo_url'],
                    [UrlConfigConstants::OP_ACCOUNT_ID => (int)$row['account_id']]
                )
            );
            $lotLink = BackUrlPureParser::new()->remove($catalogLotUrl);
            $title = mb_check_encoding($row['lot_name'], 'UTF-8')
                ? (new StripNewlines())->filter(substr($row['lot_name'], 0, 200)) : '';
            $lotNo = $this->getLotRenderer()->makeLotNo(
                $row['lot_num'],
                $row['lot_num_ext'],
                $row['lot_num_prefix']
            );
            $linkTpl = '<a href="%s" title="%s" class="bulk-group-item"> %s</a>&nbsp;';
            $output .= sprintf($linkTpl, $lotLink, $title, $lotNo);
        }
        return $output;
    }

    protected function renderBulkMasterHtml(array $row): string
    {
        $output = '';
        $lotDetailsUrl = $this->getUrlBuilder()->build(
            ResponsiveLotDetailsUrlConfig::new()->forDomainRule(
                (int)$row['bulk_master_lot_item_id'],
                (int)$row['bulk_master_auction_id'],
                $row['bulk_master_seo_url'],
                [UrlConfigConstants::OP_ACCOUNT_ID => (int)$row['account_id']]
            )
        );
        $lotDetailsUrl = BackUrlPureParser::new()->remove($lotDetailsUrl);
        $name = $row['bulk_master_name'];
        $lotName = mb_check_encoding($name, 'UTF-8') ? (new StripNewlines())->filter($name) : '';
        $lotNo = $this->getLotRenderer()->makeLotNo(
            $row['bulk_master_lot_num'],
            $row['bulk_master_lot_num_ext'],
            $row['bulk_master_lot_num_prefix']
        );
        return $output . ('<a href="' . $lotDetailsUrl . '" title="' . substr($lotName, 0, 200) .
                '" class="bulk-master-item"> ' .
                $lotNo . ' - ' . ee($lotName) . '</a>&nbsp;');
    }

    /**
     * Translate and cache translated value
     */
    protected function translate(string $key, string $section): string
    {
        if (!isset($this->translations[$key][$section])) {
            $this->translations[$key][$section] = $this->getTranslator()->translate(
                $key,
                $section,
                $this->getSystemAccountId(),
                $this->getLanguageId()
            );
        }
        return $this->translations[$key][$section];
    }

    public function getLanguageId(): ?int
    {
        return $this->languageId;
    }

    public function setLanguageId(?int $languageId): static
    {
        $this->languageId = $languageId;
        return $this;
    }

    public function getSystemAccountId(): int
    {
        if (!$this->systemAccountId) {
            throw new InvalidArgumentException('SystemAccountId not defined');
        }
        return $this->systemAccountId;
    }

    public function setSystemAccountId(int $systemAccountId): static
    {
        $this->systemAccountId = $systemAccountId;
        return $this;
    }
}
