<?php
/**
 * Special complex validation for sso->tokenLink->passphrase option.
 * It is required, when SSO TokenLink feature is enabled (sso->tokenLink->enabled = true).
 *
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           3/30/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Validate\Value\Special\SsoTokenLink;

use Sam\Installation\Config\Edit\Meta\Descriptor\Descriptor;
use Sam\Installation\Config\Edit\Meta\OptionHelperAwareTrait;
use Sam\Installation\Config\Edit\Validate\Value\Special\Base\SpecialComplexValidatorBase;

/**
 * Class Validator
 * @package
 */
class Validator extends SpecialComplexValidatorBase
{
    use OptionHelperAwareTrait;

    public const ERR_PASSPHRASE_EMPTY = 1;

    protected const OPKEY_FEATURE_ENABLED = 'sso->tokenLink->enabled';
    protected const OPKEY_FEATURE_PASSPHRASE = 'sso->tokenLink->passphrase';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check sso->tokenLink->passphrase option is filled, when SSO TokenLink feature is enabled
     * @param array $descriptors
     * @return bool
     */
    public function validateForLoad(array $descriptors): bool
    {
        $this->initResultStatusCollector();
        $enabledOptionKey = $this->getFeatureEnabledOptionKey();
        $enabledDescriptor = $descriptors[$enabledOptionKey] ?? null;
        $isFeatureEnabled = $enabledDescriptor->getActualValue();
        if ($isFeatureEnabled) {
            $passphraseOptionKey = $this->getPassphraseOptionKey();
            $passphraseDescriptor = $descriptors[$passphraseOptionKey] ?? null;
            // Use config's _global_ value, when 'passphrase' option absent in input data
            $passphrase = (string)$passphraseDescriptor->getActualValue();
            if ($passphrase === '') {
                $this->getResultStatusCollector()->addError(self::ERR_PASSPHRASE_EMPTY);
                return false;
            }
        }
        return true;
    }

    /**
     * Check sso->tokenLink->passphrase option is required, when SSO TokenLink feature is enabled
     * @param Descriptor[] $descriptors
     * @param array $data
     * @return bool
     */
    public function validateForSave(array $descriptors, array $data): bool
    {
        $this->initResultStatusCollector();
        $enabledOptionKey = $this->getFeatureEnabledOptionKey();
        $enabledDescriptor = $descriptors[$enabledOptionKey] ?? null;
        // Use config's _actual_ value, when 'enabled' option absent in input data
        $isFeatureEnabled = array_key_exists($enabledOptionKey, $data)
            ? (bool)$data[$enabledOptionKey]
            : $enabledDescriptor && $enabledDescriptor->getActualValue();

        if ($isFeatureEnabled) {
            $passphraseOptionKey = $this->getPassphraseOptionKey();
            // Use config's _global_ value, when 'passphrase' option absent in input data
            $passPhraseGlobalValue = $descriptors[$passphraseOptionKey]->getGlobalValue() ?? '';
            $passphrase = $data[$passphraseOptionKey] ?? $passPhraseGlobalValue;
            if (empty($passphrase)) {
                $this->getResultStatusCollector()->addError(self::ERR_PASSPHRASE_EMPTY);
                return false;
            }
        }
        return true;
    }

    /**
     * Cannot delete, when "SSO TokenLink" feature is enabled
     * @param Descriptor[] $descriptors
     * @return bool
     */
    public function validateForDelete(array $descriptors): bool
    {
        $this->initResultStatusCollector();
        $enabledOptionKey = $this->getFeatureEnabledOptionKey();
        $isFeatureEnabled = $descriptors[$enabledOptionKey]->getActualValue() ?? false;
        if ($isFeatureEnabled) {
            $this->getResultStatusCollector()->addError(self::ERR_PASSPHRASE_EMPTY);
            return false;
        }
        return true;
    }

    protected function initResultStatusCollector(): void
    {
        $errorMessages = [
            self::ERR_PASSPHRASE_EMPTY => 'Passphrase cannot be empty, when SSO TokenLink feature enabled',
        ];
        $this->getResultStatusCollector()->initAllErrors($errorMessages);
    }

    /**
     * @return string
     */
    protected function getFeatureEnabledOptionKey(): string
    {
        return $this->getOptionHelper()->replaceMetaToGeneralDelimiter(self::OPKEY_FEATURE_ENABLED);
    }

    /**
     * @return string
     */
    protected function getPassphraseOptionKey(): string
    {
        return $this->getOptionHelper()->replaceMetaToGeneralDelimiter(self::OPKEY_FEATURE_PASSPHRASE);
    }
}
