<?php
/**
 * Helper for DTO
 *
 * SAM-8841: User entity-maker module structural adjustments for v3-5
 * SAM-4989: User Entity Maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 22, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Dto;

use Sam\Core\Constants;
use Sam\EntityMaker\Base\Dto\DtoHelper;
use Sam\EntityMaker\Base\Common\Mode;
use Sam\EntityMaker\User\Common\UserMakerCustomFieldManager;

/**
 * Class UserMakerDtoHelper
 * @package Sam\EntityMaker\User
 */
class UserMakerDtoHelper extends DtoHelper
{
    /** @var UserMakerCustomFieldManager */
    protected UserMakerCustomFieldManager $userMakerCustomFieldManager;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function constructUserMakerDtoHelper(
        Mode $mode,
        UserMakerCustomFieldManager $customFieldManager
    ): static {
        $this->construct($mode);
        $this->userMakerCustomFieldManager = $customFieldManager;
        return $this;
    }

    /**
     * @param UserMakerInputDto $inputDto
     * @param UserMakerConfigDto $configDto
     * @return UserMakerInputDto
     */
    public function prepareValues($inputDto, $configDto): UserMakerInputDto
    {
        /** @var UserMakerInputDto $inputDto */
        $inputDto = parent::prepareValues($inputDto, $configDto);
        $inputDto = $this->normalize($inputDto);
        return $inputDto;
    }

    /**
     * @param UserMakerInputDto $inputDto
     * @return UserMakerInputDto
     */
    protected function normalize(UserMakerInputDto $inputDto): UserMakerInputDto
    {
        $inputDto = $this->normalizeStringRepresentation($inputDto, 'billingBankAccountType', Constants\BillingBank::ACCOUNT_TYPE_NAMES);
        $inputDto = $this->normalizeStringRepresentation($inputDto, 'billingBankAccountType', Constants\BillingBank::ACCOUNT_TYPE_SOAP_VALUES);
        $inputDto = $this->normalizeStringRepresentation($inputDto, 'billingContactType', Constants\User::CONTACT_TYPE_ENUM);
        $inputDto = $this->normalizeStringRepresentation($inputDto, 'shippingContactType', Constants\User::CONTACT_TYPE_ENUM);
        $inputDto = $this->normalizeStringRepresentation($inputDto, 'phoneType', Constants\User::PHONE_TYPE_NAMES);
        $inputDto = $this->normalizeStringRepresentation($inputDto, 'identificationType', Constants\User::IDENTIFICATION_TYPE_NAMES);
        return $inputDto;
    }

    /**
     * @param UserMakerInputDto $inputDto
     * @param string $property
     * @param array $mapping
     * @return UserMakerInputDto
     */
    protected function normalizeStringRepresentation(UserMakerInputDto $inputDto, string $property, array $mapping): UserMakerInputDto
    {
        if (isset($inputDto->$property)) {
            $normalizedValue = array_search($inputDto->$property, $mapping, true);
            if ($normalizedValue !== false) {
                $inputDto->$property = $normalizedValue;
            }
        }
        return $inputDto;
    }

    /**
     * Set field default value
     * @param string $field
     * @param UserMakerInputDto $inputDto
     * @param UserMakerConfigDto $configDto
     * @return bool @c true if default value was set, @c false otherwise
     */
    protected function populateWithDefault(string $field, $inputDto, $configDto): bool
    {
        $value = null;
        // If field is customField
        if (
            $this->isCustomField($field)
            && isset($inputDto->$field)
        ) {
            $value = $this->userMakerCustomFieldManager
                ->setInputDto($inputDto)
                ->initCustomFieldByName($field);
        }
        if ($value) {
            $inputDto->$field = $value;
        }
        return (bool)$value;
    }
}
