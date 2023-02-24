<?php
/**
 * Shipping Info Confirmation processing at web public side
 *
 * SAM-4310: Confirm Shipping Info page manager
 *
 * @author        Igors Kotlevskis, Vahagn Hovsepyan
 * @version       SAM 2.0
 * @since         Jun 21, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 * @package       com.swb.sam2.api
 */

namespace Sam\Auction\Register\ConfirmShippingInfo;

use Auction;
use Sam\Application\RequestParam\ParamFetcherForPostAwareTrait;
use Sam\Application\Url\BackPage\BackUrlParserAwareTrait;
use Sam\Application\Url\Build\Config\Auction\AnySingleAuctionUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\CustomField\User\Help\UserCustomFieldHelper;
use Sam\CustomField\User\Load\UserCustomDataLoaderAwareTrait;
use Sam\CustomField\User\Save\UserCustomDataUpdaterAwareTrait;
use Sam\EntityMaker\Base\Common\Mode;
use Sam\EntityMaker\User\Dto\UserMakerConfigDto;
use Sam\EntityMaker\User\Dto\UserMakerDtoFactory;
use Sam\EntityMaker\User\Dto\UserMakerInputDto;
use Sam\EntityMaker\User\Lock\UserMakerLockerCreateTrait;
use Sam\EntityMaker\User\Save\UserMakerProducer;
use Sam\EntityMaker\User\Validate\UserMakerValidator;
use Sam\File\FilePathHelperAwareTrait;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserCustomFieldsAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserShipping\UserShippingWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class PageManager
 * @method Auction getAuction() should be guaranteed by entity existence check at higher level
 */
class PageManager extends CustomizableClass
{
    use AuctionAwareTrait;
    use AuctionLoaderAwareTrait;
    use BackUrlParserAwareTrait;
    use BlockCipherProviderCreateTrait;
    use ConfigRepositoryAwareTrait;
    use ConfirmShippingInfoRequiredFieldsDetectorCreateTrait;
    use EditorUserAwareTrait;
    use FileManagerCreateTrait;
    use FilePathHelperAwareTrait;
    use ParamFetcherForPostAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use SystemAccountAwareTrait;
    use UrlBuilderAwareTrait;
    use UrlParserAwareTrait;
    use UserCustomDataLoaderAwareTrait;
    use UserCustomDataUpdaterAwareTrait;
    use UserCustomFieldsAwareTrait;
    use UserLoaderAwareTrait;
    use UserMakerLockerCreateTrait;
    use UserShippingWriteRepositoryAwareTrait;

    protected ?RedirectUrlTypeDetector $urlTypeDetector = null;
    protected ?UserMakerConfigDto $userConfigDto = null;
    protected ?UserMakerInputDto $userInputDto = null;
    protected ?UserMakerValidator $userMakerValidator = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function construct(): static
    {
        return $this;
    }

    /**
     * Produce option values based on POST request params
     * @return static
     */
    public function initByRequest(): static
    {
        $auctionId = $this->getParamFetcherForPost()->getIntPositive('id');
        $auction = $this->getAuctionLoader()->load($auctionId);
        $this->setAuction($auction);
        $this->initUserDto();
        return $this;
    }

    /**
     * Initialize DTO from POST request data.
     */
    private function initUserDto(): void
    {
        [$this->userInputDto, $this->userConfigDto] = UserMakerDtoFactory::new()->createDtos(
            Mode::WEB_RESPONSIVE,
            $this->getEditorUserId(),
            $this->getAuction()->AccountId, // SAM-10510
            $this->getSystemAccountId()
        );
        $this->userConfigDto->isConfirmPage = true;
        $this->userInputDto->id = $this->getEditorUserId();
        $this->userInputDto->shippingAddress = $this->getStringFromRequest('address');
        $this->userInputDto->shippingAddress2 = $this->getStringFromRequest('address2');
        $this->userInputDto->shippingAddress3 = $this->getStringFromRequest('address3');
        $this->userInputDto->shippingCity = $this->getStringFromRequest('city');
        $this->userInputDto->shippingCompanyName = $this->getStringFromRequest('companyName');
        $this->userInputDto->shippingContactType = $this->getStringFromRequest('contactType');
        $this->userInputDto->shippingCountry = $this->getStringFromRequest('country');
        $this->userInputDto->shippingFax = $this->getStringFromRequest('fax');
        $this->userInputDto->shippingFirstName = $this->getStringFromRequest('firstName');
        $this->userInputDto->shippingLastName = $this->getStringFromRequest('lastName');
        $this->userInputDto->shippingPhone = $this->getStringFromRequest('phone');
        $this->userInputDto->shippingState = $this->getStringFromRequest('state');
        $this->userInputDto->shippingZip = $this->getStringFromRequest('zip');
        $this->userInputDto->setArray($this->getPostCustomFieldValues());
    }

    /**
     * @return array
     */
    public function detectRequiredFields(): array
    {
        return $this->createConfirmShippingInfoRequiredFieldsDetector()->detect();
    }

    public function lockUser(): bool
    {
        $lockingResult = $this->createUserMakerLocker()->lock($this->userInputDto, $this->userConfigDto); // #user-lock-11
        return $lockingResult->isSuccess();
    }

    public function unlockUser(): void
    {
        $this->createUserMakerLocker()->unlock($this->userConfigDto); // #user-lock-11, unlock
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->getUserMakerValidator()->validate();
    }

    /**
     * @return ResultStatus[]
     */
    public function getErrors(): array
    {
        $this->getResultStatusCollector()->construct([]);
        $validator = $this->userMakerValidator;
        if ($validator->hasShippingAddressError()) {
            $this->getResultStatusCollector()->addError(0, $validator->getShippingAddressErrorMessage(), ['property' => 'address']);
        }
        if ($validator->hasShippingCityError()) {
            $this->getResultStatusCollector()->addError(0, $validator->getShippingCityErrorMessage(), ['property' => 'city']);
        }
        if ($validator->hasShippingContactTypeError()) {
            $this->getResultStatusCollector()->addError(0, $validator->getShippingContactTypeErrorMessage(), ['property' => 'contactType']);
        }
        if ($validator->hasShippingCountryError()) {
            $this->getResultStatusCollector()->addError(0, $validator->getShippingCountryErrorMessage(), ['property' => 'country']);
        }
        if ($validator->hasShippingFaxError()) {
            $this->getResultStatusCollector()->addError(0, $validator->getShippingFaxErrorMessage(), ['property' => 'fax']);
        }
        if ($validator->hasShippingFirstNameError()) {
            $this->getResultStatusCollector()->addError(0, $validator->getShippingFirstNameErrorMessage(), ['property' => 'firstName']);
        }
        if ($validator->hasShippingLastNameError()) {
            $this->getResultStatusCollector()->addError(0, $validator->getShippingLastNameErrorMessage(), ['property' => 'lastName']);
        }
        if ($validator->hasShippingPhoneError()) {
            $this->getResultStatusCollector()->addError(0, $validator->getShippingPhoneErrorMessage(), ['property' => 'phone']);
        }
        if ($validator->hasShippingStateError()) {
            $this->getResultStatusCollector()->addError(0, $validator->getShippingStateErrorMessage(), ['property' => 'state']);
        }
        if ($validator->hasShippingZipError()) {
            $this->getResultStatusCollector()->addError(0, $validator->getShippingZipErrorMessage(), ['property' => 'zip']);
        }
        if ($validator->hasCustomFieldsErrors()) {
            foreach ($validator->getCustomFieldsErrors() as $id => $message) {
                $this->getResultStatusCollector()->addError(
                    0,
                    lcfirst($message),
                    [
                        'property' => 'UsrCustFldEdt' . $id,
                        'customFieldName' => $this->getCustomFieldNameById($id)
                    ]
                );
            }
        }
        return $this->getResultStatusCollector()->getErrorStatuses();
    }

    /**
     * Update user data on successful confirmation
     */
    public function update(): void
    {
        $this->saveUploadedFiles();
        UserMakerProducer::new()->construct($this->userInputDto, $this->userConfigDto)->produce();
    }

    /**
     * Returns page url for next step in auction registration pages chain
     * We should extract this logic to separate class in further. Unite and unify it with logic for other steps.
     * @return string
     */
    public function detectRedirectUrl(): string
    {
        $userId = $this->getEditorUserId();
        $auction = $this->getAuction();
        $accountId = $auction->AccountId;
        $urlType = $this->getRedirectUrlTypeDetector()->detect($accountId, $userId);
        $redirectUrl = $this->getUrlBuilder()->build(
            AnySingleAuctionUrlConfig::new()->forRedirect($urlType, $auction->Id)
        );
        return $redirectUrl;
    }

    /**
     * YV, SAM-8138, 02.05.2021. Can be moved to RedirectUrlTypeDetectorAwareTrait
     *
     * @return RedirectUrlTypeDetector
     */
    public function getRedirectUrlTypeDetector(): RedirectUrlTypeDetector
    {
        if ($this->urlTypeDetector === null) {
            $this->urlTypeDetector = RedirectUrlTypeDetector::new();
        }
        return $this->urlTypeDetector;
    }

    /**
     * YV, SAM-8138, 02.05.2021. Can be moved to RedirectUrlTypeDetectorAwareTrait
     *
     * @param RedirectUrlTypeDetector $redirectUrlTypeDetector
     * @return static
     */
    public function setRedirectUrlTypeDetector(RedirectUrlTypeDetector $redirectUrlTypeDetector): static
    {
        $this->urlTypeDetector = $redirectUrlTypeDetector;
        return $this;
    }

    protected function getCustomFieldNameById(int $id): string
    {
        foreach ($this->getUserCustomFields() as $userCustomField) {
            if ($id === $userCustomField->Id) {
                return $userCustomField->Name;
            }
        }
        return '';
    }

    /**
     * @return array Custom field name and its post value ['customFieldShippingInformation' => 'info', ...]
     */
    protected function getPostCustomFieldValues(): array
    {
        $customFieldValues = [];
        $postCustomFields = $this->getParamFetcherForPost()->getArray('customFields');
        foreach ($this->getUserCustomFields() as $userCustomField) {
            if ($userCustomField->Panel === Constants\UserCustomField::PANEL_SHIPPING) {
                foreach ($postCustomFields as $postCustomField) {
                    if ($postCustomField['name'] === 'UsrCustFldEdt' . $userCustomField->Id) {
                        $key = lcfirst(UserCustomFieldHelper::new()->makeSoapTagByName($userCustomField->Name));
                        $customFieldValues[$key] = $postCustomField['value'];
                    }
                }
            }
        }
        return $customFieldValues;
    }

    /**
     * Move uploaded files from temporary folder to permanent location
     */
    protected function saveUploadedFiles(): void
    {
        foreach ($this->getParamFetcherForPost()->getArray('tempFiles') as $file) {
            $files = explode(':', $file['name']);
            if (!isset($files[1])) {
                continue;
            }

            $fileName = $files[0];
            $sourceFilePath = $files[1];
            $permanentPath = substr(path()->uploadUserCustomFieldFile() . '/' . $this->getEditorUserId(), strlen(path()->sysRoot()));
            $fileName = $this->getFilePathHelper()->toFilename($fileName);

            $extension = '';
            $filePath = $permanentPath . '/' . $fileName;
            if ($this->createFileManager()->exist($filePath)) {
                do {
                    $segArray = explode('.', $fileName);
                    if (count($segArray) > 1) {
                        $extension = mb_strtolower(array_pop($segArray));
                    }
                    $baseFileName = implode('.', $segArray);
                    $fileName = $baseFileName . '__' . $this->getEditorUserId() . '.' . $extension;
                    $filePath = $permanentPath . '/' . $fileName;
                } while ($this->createFileManager()->exist($filePath));
            }
            $filePath = str_replace(' ', '', utf8_encode($filePath));
            $this->createFileManager()->move($sourceFilePath, $filePath);
        }
    }

    /**
     * @param string $paramName
     * @return string
     */
    private function getStringFromRequest(string $paramName): string
    {
        return trim($this->getParamFetcherForPost()->getString($paramName));
    }

    /**
     * @return UserMakerValidator
     */
    private function getUserMakerValidator(): UserMakerValidator
    {
        if (!$this->userMakerValidator) {
            $this->userMakerValidator = UserMakerValidator::new()->construct($this->userInputDto, $this->userConfigDto);
        }

        return $this->userMakerValidator;
    }
}
