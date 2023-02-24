<?php
/**
 * SAM-10211: External Auction Info Link Breaking Auction Name Link in Invoice_Html
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           02-05, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\EntityMaker\Auction\Validate\Internal\AuctionInfoLink\Internal\Validate;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Auction\Dto\AuctionMakerConfigDto;
use Sam\EntityMaker\Auction\Dto\AuctionMakerInputDto;

/**
 * Class AuctionInfoLinkValidationInput
 * @package Sam\EntityMaker\Auction\Validate\Internal\AuctionInfoLink\Internal\Validate
 */
class AuctionInfoLinkValidationInput extends CustomizableClass
{
    /** @var int|null - null means a new auction, int - existing auction. */
    public ?int $auctionId;
    public string $url;
    public int $accountId;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        int $accountId,
        ?int $auctionId,
        string $url,
    ): static {
        $this->accountId = $accountId;
        $this->auctionId = $auctionId;
        $this->url = $url;
        return $this;
    }

    public function fromMakerDto(AuctionMakerInputDto $inputDto, AuctionMakerConfigDto $configDto): static
    {
        return $this->construct(
            $configDto->serviceAccountId,
            Cast::toInt($inputDto->id),
            (string)$inputDto->auctionInfoLink
        );
    }

    public function logData(): array
    {
        return [
            'accountId' => $this->accountId,
            'auctionId' => $this->auctionId,
            'url' => $this->url,
        ];
    }
}
