<?php
/**
 * Class for validating of account input data
 *
 * SAM-8855: Account entity-maker module structural adjustments for v3-5
 * SAM-3942: Account entity maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 2, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Account\Validate;

use Sam\Account\Validate\AccountExistenceCheckerAwareTrait;
use Sam\Application\Access\ApplicationAccessCheckerCreateTrait;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Address\Validate\AddressChecker;
use Sam\Core\Constants;
use Sam\Core\Platform\Constant\Base\ConstantNameResolver;
use Sam\EntityMaker\Account\Dto\AccountMakerConfigDto;
use Sam\EntityMaker\Account\Dto\AccountMakerDtoHelperAwareTrait;
use Sam\EntityMaker\Account\Dto\AccountMakerInputDto;
use Sam\EntityMaker\Account\Validate\Constants\ResultCode;
use Sam\EntityMaker\Base\Validate\BaseMakerValidator;

/**
 * The following methods are handled by \Sam\EntityMaker\Base\Validator::__call() method:
 * GetErrorMessage Methods
 * @method getEmailErrorMessage()
 * @method getNameErrorMessage()
 * @method getSiteUrlErrorMessage()
 * @method getStateProvinceErrorMessage()
 * @method getUrlDomainErrorMessage()
 * HasError Methods
 * @method hasCountryError()
 * @method hasEmailError()
 * @method hasNameError()
 * @method hasSiteUrlError()
 * @method hasStateProvinceError()
 * @method hasUrlDomainError()
 *
 * @method AccountMakerInputDto getInputDto()
 * @method AccountMakerConfigDto getConfigDto()
 */
class AccountMakerValidator extends BaseMakerValidator
{
    use AccountExistenceCheckerAwareTrait;
    use AccountMakerDtoHelperAwareTrait;
    use ApplicationAccessCheckerCreateTrait;

    /** @var string[] */
    protected array $tagNames = [
        ResultCode::COUNTRY_UNKNOWN => 'CountryCode',
        ResultCode::EMAIL_INVALID => 'Email',
        ResultCode::SYNC_KEY_EXIST => 'SyncKey',
        ResultCode::NAME_EXIST => 'Name',
        ResultCode::NAME_INVALID => 'Name',
        ResultCode::NAME_REQUIRED => 'Name',
        ResultCode::SITE_URL_INVALID => 'SiteUrl',
        ResultCode::STATE_UNKNOWN => 'StateProvince',
        ResultCode::URL_DOMAIN_INVALID => 'UrlDomain',
        ResultCode::URL_DOMAIN_EXIST => 'UrlDomain',
        ResultCode::URL_DOMAIN_REQUIRED => 'UrlDomain',
    ];

    /** @var string[] */
    protected array $errorMessages = [
        ResultCode::COUNTRY_UNKNOWN => 'Unknown',
        ResultCode::EMAIL_INVALID => 'Invalid format',
        ResultCode::SYNC_KEY_EXIST => 'Already exist',
        ResultCode::NAME_EXIST => 'Already exist',
        ResultCode::NAME_INVALID => 'Should be a valid sub domain name',
        ResultCode::NAME_REQUIRED => 'Required',
        ResultCode::SITE_URL_INVALID => 'Invalid format',
        ResultCode::STATE_UNKNOWN => 'Unknown',
        ResultCode::URL_DOMAIN_INVALID => 'Should be a valid domain',
        ResultCode::URL_DOMAIN_EXIST => 'Already exist',
        ResultCode::URL_DOMAIN_REQUIRED => 'Required',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AccountMakerInputDto $inputDto
     * @param AccountMakerConfigDto $configDto
     * @return static
     */
    public function construct(
        AccountMakerInputDto $inputDto,
        AccountMakerConfigDto $configDto
    ): static {
        $this->setInputDto($inputDto);
        $this->setConfigDto($configDto);
        $this->getAccountMakerDtoHelper()->construct($configDto->mode);
        return $this;
    }

    /**
     * Validate data
     * @return bool
     */
    public function validate(): bool
    {
        $inputDto = $this->getAccountMakerDtoHelper()->prepareValues($this->getInputDto(), $this->getConfigDto());
        $this->setInputDto($inputDto);
        $configDto = $this->getConfigDto();
        $countryCode = AddressRenderer::new()->normalizeCountry($inputDto->country);

        if (!$configDto->mode->isWebAdmin()) {
            $this->addTagNamesToErrorMessages();
        }

        if ($this->cfg()->get('core->portal->urlHandling') === Constants\PortalUrlHandling::MAIN_DOMAIN) {
            $this->checkHostname('urlDomain', ResultCode::URL_DOMAIN_INVALID);
            $this->checkNotExistAccountUrlDomain('urlDomain', ResultCode::URL_DOMAIN_EXIST);
            $this->checkRequired('urlDomain', ResultCode::URL_DOMAIN_REQUIRED);
        }
        if ($this->cfg()->get('core->portal->urlHandling') === Constants\PortalUrlHandling::SUBDOMAINS) {
            $this->checkSubDomain('name', ResultCode::NAME_INVALID);
        }
        if (AddressChecker::new()->isCountryWithStates($countryCode)) {
            $this->checkState('country', 'stateProvince', ResultCode::STATE_UNKNOWN);
        }
        $this->checkCountry('country', ResultCode::COUNTRY_UNKNOWN);
        $this->checkEmail('email', ResultCode::EMAIL_INVALID);
        $this->checkNotExistAccountName('name', ResultCode::NAME_EXIST);
        $this->checkSyncKeyUnique('syncKey', ResultCode::SYNC_KEY_EXIST, Constants\EntitySync::TYPE_ACCOUNT);
        $this->checkRequired('name', ResultCode::NAME_REQUIRED);
        $this->checkUrl('siteUrl', ResultCode::SITE_URL_INVALID);

        $this->log();
        $isValid = empty($this->errors);
        $configDto->enableValidStatus($isValid);
        return $isValid;
    }

    /** GetErrors Methods */

    /**
     * Get country errors
     * @return int[]
     */
    public function getCountryErrors(): array
    {
        return [ResultCode::COUNTRY_UNKNOWN];
    }

    /**
     * Get email errors
     * @return int[]
     */
    public function getEmailErrors(): array
    {
        return [ResultCode::EMAIL_INVALID];
    }

    /**
     * Get account name errors
     * @return int[]
     */
    public function getNameErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::NAME_REQUIRED,
                ResultCode::NAME_INVALID,
                ResultCode::NAME_EXIST
            ]
        );
        return $intersected;
    }

    /**
     * Get site URL errors
     * @return int[]
     */
    public function getSiteUrlErrors(): array
    {
        return [ResultCode::SITE_URL_INVALID];
    }

    public function getStateProvinceErrors(): array
    {
        return [ResultCode::STATE_UNKNOWN];
    }

    /**
     * Get URL domain errors
     * @return int[]
     */
    public function getUrlDomainErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::URL_DOMAIN_REQUIRED,
                ResultCode::URL_DOMAIN_INVALID,
                ResultCode::URL_DOMAIN_EXIST
            ]
        );
        return $intersected;
    }

    /**
     * Support logging of found errors or success
     */
    protected function log(): void
    {
        $inputDto = $this->getInputDto();
        if (empty($this->errors)) {
            log_trace('Account validation done' . composeSuffix(['acc' => $inputDto->id]));
        } else {
            // detect names of constants for error codes
            [$foundNamesToCodes, $notFoundCodes] = ConstantNameResolver::new()
                ->construct()
                ->resolveManyFromClass($this->errors, ResultCode::class);

            $foundNamesWithCodes = array_map(
                static function ($v) {
                    return "{$v[1]} ({$v[0]})";
                },
                $foundNamesToCodes
            );
            $logData = [
                'acc' => $inputDto->id,
                'errors' => array_merge(array_values($foundNamesWithCodes), $notFoundCodes),
            ];
            log_debug('Account validation failed' . composeSuffix($logData));
        }
    }

    /* Account validation rules */

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkNotExistAccountName(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $skipIds = $inputDto->id ? [(int)$inputDto->id] : [];
        $isFoundByName = $this->getAccountExistenceChecker()->existByName($inputDto->$field, $skipIds);
        $this->addErrorIfFail($error, !$isFoundByName);
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkNotExistAccountUrlDomain(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $isExistUrlDomain = $this->existUrlDomain($inputDto->$field);
        $this->addErrorIfFail($error, !$isExistUrlDomain);
    }

    protected function existUrlDomain(mixed $urlDomain): bool
    {
        if (
            $this->isPortalAccount()
            && strcasecmp($urlDomain, $this->cfg()->get('core->app->httpHost')) === 0
        ) {
            return true;
        }

        $skipIds = $this->getInputDto()->id ? [(int)$this->getInputDto()->id] : [];
        $isFoundByByUrlDomain = $this->getAccountExistenceChecker()->existByUrlDomain($urlDomain, $skipIds);
        return $isFoundByByUrlDomain;
    }

    protected function isPortalAccount(): bool
    {
        $inputDto = $this->getInputDto();
        return !$inputDto->id
            || $this->createApplicationAccessChecker()->isPortalAccount((int)$inputDto->id);
    }
}
