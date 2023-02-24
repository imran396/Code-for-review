<?php
/**
 * SAM-3796: Bidder upload into auction
 * SAM-9366: Refactor Sam\Bidder\AuctionBidder\CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Bidder\Internal\Dto;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\User\Dto\UserMakerConfigDto;
use Sam\EntityMaker\User\Dto\UserMakerInputDto;

/**
 * Class that contains prepared bidder import CSV row data
 *
 * Class RowInput
 * @package Sam\Import\Csv\Bidder\Internal\Dto
 * @internal
 */
class RowInput extends CustomizableClass
{
    /**
     * @var string
     */
    public string $bidderNo;
    /**
     * @var UserMakerInputDto
     */
    public UserMakerInputDto $userInputDto;
    /**
     * @var UserMakerConfigDto
     */
    public UserMakerConfigDto $userConfigDto;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(string $bidderNo, UserMakerInputDto $userInputDto, UserMakerConfigDto $userConfigDto): static
    {
        $this->bidderNo = $bidderNo;
        $this->userInputDto = $userInputDto;
        $this->userConfigDto = $userConfigDto;
        return $this;
    }
}
