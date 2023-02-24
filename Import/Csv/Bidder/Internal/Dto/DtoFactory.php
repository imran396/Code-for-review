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

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Common\Mode;
use Sam\EntityMaker\User\Dto\UserMakerConfigDto;
use Sam\EntityMaker\User\Dto\UserMakerDtoFactory as EntityMakerDtoFactory;
use Sam\EntityMaker\User\Dto\UserMakerInputDto;
use Sam\Import\Csv\Read\CsvRow;

/**
 * This class is responsible for creating RowInput class that contains a user input DTO
 * and a config DTO with an additional bidder info.
 *
 * Class DtoFactory
 * @package Sam\Import\Csv\Bidder\Internal\Dto
 * @internal
 */
class DtoFactory extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Constructs a user input DTO and a config DTO and puts them to the RowInput class
     *
     * @param CsvRow $row
     * @param int|null $userId
     * @param int $editorUserId
     * @param int $auctionAccountId
     * @param int $systemAccountId
     * @return RowInput
     */
    public function create(
        CsvRow $row,
        ?int $userId,
        int $editorUserId,
        int $auctionAccountId,
        int $systemAccountId
    ): RowInput {
        /**
         * @var UserMakerInputDto $userInputDto
         * @var UserMakerConfigDto $userConfigDto
         */
        [$userInputDto, $userConfigDto] = EntityMakerDtoFactory::new()
            ->createDtos(Mode::CSV, $editorUserId, $auctionAccountId, $systemAccountId);
        // User
        $userInputDto->customerNo = $row->getCell(Constants\Csv\User::CUSTOMER_NO);
        $userInputDto->email = $row->getCell(Constants\Csv\User::EMAIL);
        $userInputDto->id = $userId;
        $userInputDto->userStatusId = Constants\User::US_ACTIVE;
        $userInputDto->username = $row->getCell(Constants\Csv\User::USERNAME);
        // User Billing
        $userInputDto->billingAddress = $row->getCell(Constants\Csv\User::BILLING_ADDRESS);
        $userInputDto->billingAddress2 = $row->getCell(Constants\Csv\User::BILLING_ADDRESS_2);
        $userInputDto->billingAddress3 = $row->getCell(Constants\Csv\User::BILLING_ADDRESS_3);
        $userInputDto->billingCity = $row->getCell(Constants\Csv\User::BILLING_CITY);
        $userInputDto->billingCompanyName = $row->getCell(Constants\Csv\User::BILLING_COMPANY_NAME);
        $userInputDto->billingCountry = $row->getCell(Constants\Csv\User::BILLING_COUNTRY);
        $userInputDto->billingFax = $row->getCell(Constants\Csv\User::BILLING_FAX);
        $userInputDto->billingFirstName = $row->getCell(Constants\Csv\User::BILLING_FIRST_NAME);
        $userInputDto->billingLastName = $row->getCell(Constants\Csv\User::BILLING_LAST_NAME);
        $userInputDto->billingPhone = $row->getCell(Constants\Csv\User::BILLING_PHONE);
        $userInputDto->billingState = $row->getCell(Constants\Csv\User::BILLING_STATE);
        $userInputDto->billingZip = $row->getCell(Constants\Csv\User::BILLING_ZIP);
        // User Info
        $userInputDto->companyName = $row->getCell(Constants\Csv\User::COMPANY_NAME);
        $userInputDto->firstName = $row->getCell(Constants\Csv\User::FIRST_NAME);
        $userInputDto->lastName = $row->getCell(Constants\Csv\User::LAST_NAME);
        // User Shipping
        $userInputDto->shippingAddress = $row->getCell(Constants\Csv\User::SHIPPING_ADDRESS);
        $userInputDto->shippingAddress2 = $row->getCell(Constants\Csv\User::SHIPPING_ADDRESS_2);
        $userInputDto->shippingAddress3 = $row->getCell(Constants\Csv\User::SHIPPING_ADDRESS_3);
        $userInputDto->shippingCity = $row->getCell(Constants\Csv\User::SHIPPING_CITY);
        $userInputDto->shippingCompanyName = $row->getCell(Constants\Csv\User::SHIPPING_COMPANY_NAME);
        $userInputDto->shippingCountry = $row->getCell(Constants\Csv\User::SHIPPING_COUNTRY);
        $userInputDto->shippingFax = $row->getCell(Constants\Csv\User::SHIPPING_FAX);
        $userInputDto->shippingFirstName = $row->getCell(Constants\Csv\User::SHIPPING_FIRST_NAME);
        $userInputDto->shippingLastName = $row->getCell(Constants\Csv\User::SHIPPING_LAST_NAME);
        $userInputDto->shippingPhone = $row->getCell(Constants\Csv\User::SHIPPING_PHONE);
        $userInputDto->shippingState = $row->getCell(Constants\Csv\User::SHIPPING_STATE);
        $userInputDto->shippingZip = $row->getCell(Constants\Csv\User::SHIPPING_ZIP);
        /**
         * Bidder role should be enabled for newly created user only (SAM-9609)
         */
        if (!$userId) {
            // Privileges
            $userInputDto->bidder = '1';
        }
        // User Account
        $userInputDto->collateralAccountId = $auctionAccountId;

        return RowInput::new()->construct(
            $row->getCell(Constants\Csv\User::BIDDER_NO),
            $userInputDto,
            $userConfigDto
        );
    }
}
