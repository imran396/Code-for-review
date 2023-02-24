<?php
/**
 * SAM-6585: Refactor auction custom field management to separate module
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Ivan Zgoniaiko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionCustomFieldEditForm\Dto;

use AuctionCustField;
use Laminas\Diactoros\ServerRequest;
use Sam\Core\Constants\Admin\AuctionCustomFieldEditFormConstants;
use Sam\Core\Dto\FormDataReaderCreateTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionCustomFieldEditFormDtoBuilder
 * @package Sam\View\Admin\Form\AuctionCustomFieldEditForm\Dto
 */
class AuctionCustomFieldEditFormDtoBuilder extends CustomizableClass
{
    use FormDataReaderCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AuctionCustField|null $auctionCustomField
     * @param ServerRequest $psrRequest
     * @return AuctionCustomFieldEditFormDto
     */
    public function fromPsrRequest(?AuctionCustField $auctionCustomField, ServerRequest $psrRequest): AuctionCustomFieldEditFormDto
    {
        $body = $psrRequest->getParsedBody();
        $formDataReader = $this->createFormDataReader();

        $dto = AuctionCustomFieldEditFormDto::new();
        $dto->adminList = $formDataReader->readCheckbox(AuctionCustomFieldEditFormConstants::CID_CHK_ADMIN_LIST, $body);
        $dto->clone = $formDataReader->readCheckbox(AuctionCustomFieldEditFormConstants::CID_CHK_CLONE, $body);
        $dto->name = $formDataReader->readString(AuctionCustomFieldEditFormConstants::CID_TXT_NAME, $body);
        $dto->order = $formDataReader->readString(AuctionCustomFieldEditFormConstants::CID_TXT_ORDER, $body);
        $dto->parameters = $formDataReader->readString(AuctionCustomFieldEditFormConstants::CID_TXT_PARAMETER, $body);
        $dto->publicList = $formDataReader->readCheckbox(AuctionCustomFieldEditFormConstants::CID_CHK_PUBLIC_LIST, $body);
        $dto->required = $formDataReader->readCheckbox(AuctionCustomFieldEditFormConstants::CID_CHK_REQUIRED, $body);
        $dto->type = $formDataReader->readString(AuctionCustomFieldEditFormConstants::CID_LST_TYPE, $body);

        $dto = $this->postBuild($auctionCustomField, $dto);
        return $dto;
    }

    /**
     * @param AuctionCustField|null $auctionCustomField
     * @param AuctionCustomFieldEditFormDto $dto
     * @return AuctionCustomFieldEditFormDto
     */
    private function postBuild(?AuctionCustField $auctionCustomField, AuctionCustomFieldEditFormDto $dto): AuctionCustomFieldEditFormDto
    {
        unset($dto->id);
        if ($auctionCustomField !== null) {
            $dto->id = $auctionCustomField->Id;
            $dto->type = $dto->type ?? (string)$auctionCustomField->Type;
        }
        return $dto;
    }
}
