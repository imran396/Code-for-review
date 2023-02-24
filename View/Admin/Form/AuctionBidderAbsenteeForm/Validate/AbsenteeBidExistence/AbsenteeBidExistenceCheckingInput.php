<?php
/**
 * SAM-9530: "User Absentee Bid" page - extract existing bid detection logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Validate\AbsenteeBidExistence;

use Sam\AuctionLot\LotNo\Parse\LotNoParserCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class AbsenteeBidExistenceCheckingInput
 * @package Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Validate\AbsenteeBidExistence
 */
class AbsenteeBidExistenceCheckingInput extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use LotNoParserCreateTrait;

    public ?string $lotNum = null;
    public string $lotNumExt = '';
    public string $lotNumPrefix = '';
    public ?int $auctionId = null;
    public ?int $userId = null;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $lotNum
     * @param string $lotNumExt
     * @param string $lotNumPrefix
     * @param int|null $auctionId
     * @param int|null $userId
     * @return $this
     */
    public function construct(
        string $lotNum,
        string $lotNumExt,
        string $lotNumPrefix,
        ?int $auctionId,
        ?int $userId
    ): static {
        $this->lotNum = $lotNum;
        $this->lotNumExt = $lotNumExt;
        $this->lotNumPrefix = $lotNumPrefix;
        $isLotNoConcatenated = $this->cfg()->get('core->lot->lotNo->concatenated');
        if ($isLotNoConcatenated) {
            $lotNoParsed = $this->createLotNoParser()->construct()->parse($lotNum);
            $this->lotNum = (string)$lotNoParsed->lotNum;
            $this->lotNumExt = $lotNoParsed->lotNumExtension;
            $this->lotNumPrefix = $lotNoParsed->lotNumPrefix;
        }
        $this->auctionId = $auctionId;
        $this->userId = $userId;
        return $this;
    }
}
