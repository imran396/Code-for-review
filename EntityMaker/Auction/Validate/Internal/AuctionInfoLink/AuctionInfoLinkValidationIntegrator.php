<?php
/**
 * SAM-10211: External Auction Info Link Breaking Auction Name Link in Invoice_Html
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           02-04, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Validate\Internal\AuctionInfoLink;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Auction\Validate\AuctionMakerValidator;
use Sam\EntityMaker\Auction\Validate\Constants\ResultCode;
use Sam\EntityMaker\Auction\Validate\Internal\AuctionInfoLink\Internal\Validate\AuctionInfoLinkValidatorCreateTrait;
use Sam\EntityMaker\Auction\Validate\Internal\AuctionInfoLink\Internal\Validate\AuctionInfoLinkValidationInput;

/**
 * Class AuctionInfoLinkValidationIntegrator
 * @package Sam\EntityMaker\Auction\Validate\Internal\AuctionInfoLink
 */
class AuctionInfoLinkValidationIntegrator extends CustomizableClass
{
    use AuctionInfoLinkValidatorCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(AuctionMakerValidator $auctionMakerValidator): void
    {
        $inputDto = $auctionMakerValidator->getInputDto();
        $configDto = $auctionMakerValidator->getConfigDto();
        $validationInput = AuctionInfoLinkValidationInput::new()->fromMakerDto($inputDto, $configDto);

        $isValid = $this->createAuctionInfoLinkValidator()->validate($validationInput);
        if ($isValid) {
            return;
        }

        $auctionMakerValidator->addError(ResultCode::AUCTION_INFO_LINK_URL_INVALID);
        $logMessage = "AuctionInfoLink validation failed" . composeSuffix($validationInput->logData());
        log_debug($logMessage);
    }
}
