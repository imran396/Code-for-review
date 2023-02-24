<?php
/**
 * SAM-5819: Hybrid auction setting editor for account level settings
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Edit\Load;

use Account;
use LotItemCustField;
use Sam\Account\AuctionDomainMode\AuctionDomainModeSettingChecker;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\Locale\LocaleProviderCreateTrait;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Settings\Encoding\EncodingHelperAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Settings\Validate\SettingsExistenceCheckerCreateTrait;
use Sam\Tax\StackedTax\Schema\Load\TaxSchemaLoaderCreateTrait;
use Sam\Timezone\ApplicationTimezoneProviderAwareTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;
use Sam\Translation\TranslationLanguageProviderCreateTrait;
use Timezone;
use Wavebid\CryptoHelper;

/**
 * Class AuctionParametersDataProvider
 * @package Sam\Settings\Edit\Load
 */
class AuctionParametersDataProvider extends CustomizableClass
{
    use ApplicationTimezoneProviderAwareTrait;
    use BlockCipherProviderCreateTrait;
    use EncodingHelperAwareTrait;
    use LocaleProviderCreateTrait;
    use LotCustomFieldLoaderCreateTrait;
    use SettingsExistenceCheckerCreateTrait;
    use SettingsManagerAwareTrait;
    use TaxSchemaLoaderCreateTrait;
    use TimezoneLoaderAwareTrait;
    use TranslationLanguageProviderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function existAuctionParameters(int $accountId): bool
    {
        return $this->createSettingsExistenceChecker()->exist($accountId);
    }

    /**
     * @param string $name
     * @param int $accountId
     * @return mixed
     */
    public function getAuctionParameter(string $name, int $accountId): mixed
    {
        return $this->getSettingsManager()->get($name, $accountId);
    }

    /**
     * @return array|LotItemCustField[]
     */
    public function loadLotItemNumericCustomFields(): array
    {
        return $this->createLotCustomFieldLoader()->loadNumericFields();
    }

    /**
     * @return array
     */
    public function getAvailableAdminLanguages(): array
    {
        return $this->createTranslationLanguageProvider()->detectAvailableLanguages();
    }

    /**
     * @return array
     */
    public function getAvailableTimezoneLocations(): array
    {
        return $this->getApplicationTimezoneProvider()->getAvailableTimezoneLocations();
    }

    /**
     * @param string $timezoneLocation
     * @return Timezone
     */
    public function loadTimezoneOrCreatePersisted(string $timezoneLocation): Timezone
    {
        return $this->getTimezoneLoader()->loadByLocationOrCreatePersisted($timezoneLocation);
    }

    public function isAvailableOptionAlwaysMainDomain(Account $account): bool
    {
        return AuctionDomainModeSettingChecker::new()->isAvailableOptionAlwaysMainDomain($account);
    }

    public function getAvailableEncodings(): array
    {
        return $this->getEncodingHelper()->loadAvailable();
    }

    public function getLocales(): array
    {
        return $this->createLocaleProvider()->loadAll();
    }

    /**
     * @param string $text
     * @return string
     */
    public function encryptString(string $text): string
    {
        return $this->createBlockCipherProvider()->construct()->encrypt($text);
    }

    /**
     * @param string $plainUat
     * @return string
     */
    public function encryptWavebidUat(string $plainUat): string
    {
        return CryptoHelper::new()->encryptUat($plainUat);
    }

    public function loadHpTaxSchemaRow(?int $hpTaxSchemaId, int $accountId, bool $isReadOnlyDb = false): array
    {
        return $this->loadTaxSchemaRow($hpTaxSchemaId, $accountId, Constants\StackedTax::AS_HAMMER_PRICE, $isReadOnlyDb);
    }

    public function loadBpTaxSchemaRow(?int $bpTaxSchemaId, int $accountId, bool $isReadOnlyDb = false): array
    {
        return $this->loadTaxSchemaRow($bpTaxSchemaId, $accountId, Constants\StackedTax::AS_BUYERS_PREMIUM, $isReadOnlyDb);
    }

    public function loadServicesTaxSchemaRow(?int $servicesTaxSchemaId, int $accountId, bool $isReadOnlyDb = false): array
    {
        return $this->loadTaxSchemaRow($servicesTaxSchemaId, $accountId, Constants\StackedTax::AS_SERVICES, $isReadOnlyDb);
    }

    protected function loadTaxSchemaRow(
        ?int $hpTaxSchemaId,
        int $accountId,
        string $amountSource,
        bool $isReadOnlyDb = false
    ): array {
        return $this->createTaxSchemaLoader()->loadSelected(
            ['id', 'country'],
            $hpTaxSchemaId,
            $accountId,
            $amountSource,
            $isReadOnlyDb
        );
    }
}
