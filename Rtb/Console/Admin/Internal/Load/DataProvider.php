<?php
/**
 * SAM-6758: Rtb console output builders
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Console\Admin\Internal\Load;

use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Rtb\State\History\HistoryServiceFactoryCreateTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;

/**
 * Class DataProvider
 */
class DataProvider extends CustomizableClass
{
    use AuctionAwareTrait;
    use EditorUserAwareTrait;
    use HistoryServiceFactoryCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @param int|null $editorUserId null for anonymous visitor
     * @return $this
     */
    public function construct(int $auctionId, ?int $editorUserId): static
    {
        $this->setAuctionId($auctionId);
        $this->setEditorUserId($editorUserId);
        return $this;
    }

    /**
     * @return array
     */
    public function detectUndoCommands(): array
    {
        return $this->createHistoryServiceFactory()
            ->createByAuctionType($this->getAuction()->AuctionType)
            ->getCommands();
    }
}
