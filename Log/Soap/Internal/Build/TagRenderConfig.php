<?php
/**
 * SAM-10338: Redact sensitive information in Soap error.log
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 01, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Log\Soap\Internal\Build;

use Sam\Core\Service\CustomizableClass;
use Sam\Log\Render\Config\RenderConfig;
use Sam\Log\Render\Config\RenderMode;

/**
 * Class TagRenderConfig
 * @package Sam\Log\Soap\Internal\Build
 */
class TagRenderConfig extends CustomizableClass
{
    protected const CONFIGURATION = [
        'Password' => [
            'maxLength' => 20,
            'mask' => ['start' => 1, 'length' => -1, 'replacement' => 'x'],
            'crc32' => true
        ],

        //User
        'Pword' => [
            'maxLength' => 20,
            'mask' => ['start' => 1, 'length' => -1, 'replacement' => 'x'],
            'crc32' => true
        ],
        'BillingCcNumber' => [
            'renderMode' => RenderMode::BASE64,
            'maxLength' => 8,
            'crc32' => true,
            'mask' => ['start' => 2, 'length' => -4],
        ],
        'BillingCcNumberHash' => [
            'maxLength' => 30,
            'crc32' => true,
            'mask' => ['start' => 15, 'length' => -8],
        ],
        'CcNumberEway' => [
            'maxLength' => 30,
            'crc32' => true,
            'mask' => ['start' => 15, 'length' => -8],
        ],
        'BillingBankRoutingNumber' => [
            'maxLength' => 8,
            'crc32' => true,
            'mask' => ['start' => 2, 'length' => -4],
        ],
        'BillingBankAccountNumber' => [
            'maxLength' => 8,
            'mask' => ['length' => -4],
            'crc32' => true
        ]
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function getKnownTags(): array
    {
        return array_keys(self::CONFIGURATION);
    }

    public function getConfig(string $tagName): RenderConfig
    {
        $config = RenderConfig::new();
        if (isset(self::CONFIGURATION[$tagName])) {
            $config = $config->fromArray(self::CONFIGURATION[$tagName]);
        }
        return $config;
    }
}
