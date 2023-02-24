<?php
/**
 * SAM-4500: Front-end breadcrumb
 * https://bidpath.atlassian.net/browse/SAM-4500
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 08, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\ViewHelper\Breadcrumb\Internal\Build\Internal;

use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Core\Transform\Text\TextTransformer;
use Sam\Date\DateHelperAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lang\TranslatorInterface;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;

/**
 * Class TitleMaker
 * @package Sam\View\Responsive\ViewHelper\Breadcrumb\Build\Internal
 */
class TitleMaker extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use AuctionRendererAwareTrait;
    use ConfigRepositoryAwareTrait;
    use DateHelperAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotRendererAwareTrait;
    use OptionalsTrait;

    // --- Incoming values ---

    public const OP_AUCTION_TITLE_TYPE = 'auctionTitleType';
    public const OP_LOT_TITLE_TYPE = 'lotTitleType';
    public const OP_TITLE_LENGTH = 'titleLength';

    // --- Internal ---

    private TranslatorInterface $translator;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param TranslatorInterface $translator
     * @param array $optionals
     * @return $this
     */
    public function construct(TranslatorInterface $translator, array $optionals = []): static
    {
        $this->initOptionals($optionals);
        $this->translator = $translator;
        return $this;
    }

    // Title maker methods

    /**
     * @param int $auctionId
     * @return string
     */
    public function makeAuctionTitle(int $auctionId): string
    {
        $title = '';
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            log_error("Available auction not found, when rendering title in breadcrumb" . composeSuffix(['a' => $auctionId]));
            return '';
        }

        $titleType = (string)$this->fetchOptional(self::OP_AUCTION_TITLE_TYPE);
        if ($titleType === Constants\ResponsiveBreadcrumb::ATT_NAME) {
            $title = $this->makeTitleByName($auction->Name);
        } elseif ($titleType === Constants\ResponsiveBreadcrumb::ATT_NUMBER) {
            $title = $this->getAuctionRenderer()->renderSaleNo($auction);
        } elseif ($titleType === Constants\ResponsiveBreadcrumb::ATT_DATE) {
            $format = $this->translator->translate('AUCTIONS_DATE_SHORT', 'auctions');
            $dateTime = $auction->basicDisplayDate();
            $dateTimeTz = $this->getDateHelper()->convertUtcToTzById($dateTime, $auction->TimezoneId);
            $title = $dateTimeTz ? $dateTimeTz->format($format) : '';
        }
        return $title;
    }

    /**
     * @param int $lotItemId
     * @param int|null $auctionId
     * @return string
     */
    public function makeLotTitle(int $lotItemId, ?int $auctionId): string
    {
        $title = '';
        $lotItem = $this->getLotItemLoader()->load($lotItemId);
        if (!$lotItem) {
            log_error("Available lot item not found, when rendering title in breadcrumb" . composeSuffix(['li' => $lotItemId]));
            return '';
        }

        $titleType = (string)$this->fetchOptional(self::OP_LOT_TITLE_TYPE);
        if ($titleType === Constants\ResponsiveBreadcrumb::LTT_NAME) {
            $title = $this->makeTitleByName($lotItem->Name);
        } elseif ($titleType === Constants\ResponsiveBreadcrumb::LTT_NUMBER) {
            $auctionLot = $this->getAuctionLotLoader()->load($lotItemId, $auctionId, true);
            if ($auctionLot) {
                $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);
                $title = sprintf($this->translator->translate('LD_LOT_NUMBER', 'lot_details'), $lotNo);
            } else {
                $title = $this->makeTitleByName($lotItem->Name);
            }
        }
        return $title;
    }

    // Internal methods

    /**
     * @param string $name
     * @return string
     */
    protected function makeTitleByName(string $name): string
    {
        $length = $this->fetchTitleLength();
        return TextTransformer::new()->cut($name, $length, '..');
    }

    /**
     * @return int
     */
    protected function fetchTitleLength(): int
    {
        return (int)$this->fetchOptional(self::OP_TITLE_LENGTH);
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_AUCTION_TITLE_TYPE] = $optionals[self::OP_AUCTION_TITLE_TYPE]
            ?? $this->cfg()->get('core->responsive->breadcrumb->auction->titleType');
        $optionals[self::OP_LOT_TITLE_TYPE] = $optionals[self::OP_LOT_TITLE_TYPE]
            ?? $this->cfg()->get('core->responsive->breadcrumb->lot->titleType');
        $optionals[self::OP_TITLE_LENGTH] = $optionals[self::OP_TITLE_LENGTH]
            ?? $this->cfg()->get('core->responsive->breadcrumb->titleLength');
        $this->setOptionals($optionals);
    }
}
