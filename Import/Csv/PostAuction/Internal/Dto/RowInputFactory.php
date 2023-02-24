<?php
/**
 * SAM-9360: Refactor \Lot_PostCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\PostAuction\Internal\Dto;

use Sam\Core\AuctionLot\LotNo\Parse\LotNoParsed;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Common\Mode;
use Sam\EntityMaker\User\Dto\UserMakerConfigDto;
use Sam\EntityMaker\User\Dto\UserMakerDtoFactory;
use Sam\EntityMaker\User\Dto\UserMakerInputDto;
use Sam\Import\Csv\PostAuction\Internal\Dto\Internal\Load\DataProviderCreateTrait;
use Sam\Import\Csv\Read\CsvRow;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class RowInputFactory
 */
class RowInputFactory extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Build RowInput DTO that contains the UserMaker DTO along with the bid info
     *
     * @param CsvRow $row
     * @param int $editorUserid
     * @param int $entityAccountId
     * @param int $systemAccountId
     * @return RowInput
     */
    public function create(
        CsvRow $row,
        int $editorUserid,
        int $entityAccountId,
        int $systemAccountId
    ): RowInput {
        [$userInputDto, $userConfigDto] = $this->fillUserDto(
            $row,
            $editorUserid,
            $entityAccountId,
            $systemAccountId
        );

        $lotNoParsed = $this->parseLotNumber($row);
        return RowInput::new()->construct(
            $userInputDto,
            $userConfigDto,
            $lotNoParsed,
            $row->getCell(Constants\Csv\Lot::LOT_NOTES),
            $row->getCell(Constants\Csv\Lot::HAMMER_PRICE),
            $row->getCell(Constants\Csv\Lot::INTERNET_BID),
        );
    }

    /**
     * @param CsvRow $row
     * @param int $editorUserid
     * @param int $entityAccountId
     * @param int $systemAccountId
     * @return array{UserMakerInputDto, UserMakerConfigDto}
     */
    protected function fillUserDto(
        CsvRow $row,
        int $editorUserid,
        int $entityAccountId,
        int $systemAccountId
    ): array {
        /**
         * @var UserMakerInputDto $userInputDto
         * @var UserMakerConfigDto $userConfigDto
         */
        [$userInputDto, $userConfigDto] = UserMakerDtoFactory::new()
            ->createDtos(Mode::CSV, $editorUserid, $entityAccountId, $systemAccountId);

        $email = trim($row->getCell(Constants\Csv\User::EMAIL));
        $userInputDto->email = $email;

        if ($email === '') {
            /**
             * Absent email means no identification of user, we shouldn't fill and complete input-dto,
             * because we don't want to validate and produce user data.
             * Note: We may want to identify user by username as alternative to email in further.
             */
            return [$userInputDto, $userConfigDto];
        }

        $dataProvider = $this->createDataProvider();

        $user = $dataProvider->loadUserByEmail($email);
        $userInputDto->id = $user->Id ?? null;
        if (!$user) {
            // User
            $userInputDto->customerNo = $row->getCell(Constants\Csv\User::CUSTOMER_NO);
            $userInputDto->pword = $dataProvider->generateEncryptedPassword();
            $username = $row->getCell(Constants\Csv\User::USERNAME);
            $userInputDto->username = $username;
            $userInputDto->userStatusId = Constants\User::US_ACTIVE;
            // Privileges: bidder
            $userInputDto->bidder = '1';
        }

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
        $userInputDto->firstName = $row->getCell(Constants\Csv\User::FIRST_NAME);
        $userInputDto->lastName = $row->getCell(Constants\Csv\User::LAST_NAME);
        $userInputDto->phone = $row->getCell(Constants\Csv\User::PHONE);

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
        // User Account
        $userInputDto->collateralAccountId = $entityAccountId;
        return [$userInputDto, $userConfigDto];
    }

    /**
     * @param CsvRow $row
     * @return LotNoParsed
     */
    protected function parseLotNumber(CsvRow $row): LotNoParsed
    {
        if ($this->cfg()->get('core->lot->lotNo->concatenated')) {
            $lotNo = $row->getCell(Constants\Csv\Lot::LOT_FULL_NUMBER);
            return $this->createDataProvider()->parseLotNo($lotNo);
        }
        return LotNoParsed::new()->construct(
            Cast::toInt($row->getCell(Constants\Csv\Lot::LOT_NUM)),
            $row->getCell(Constants\Csv\Lot::LOT_NUM_EXT),
            $row->getCell(Constants\Csv\Lot::LOT_NUM_PREFIX)
        );
    }
}
