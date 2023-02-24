<?php
/**
 * Helps to observe data changes related to Auction Seo Url template.
 * We describe placeholder related classes and properties in ConfigManager::$keysConfig[<key>][<type>]['observe']
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Jul 2, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Auction\SeoUrl\Observe;

use Account;
use Auction;
use AuctionCustData;
use AuctionCustField;
use SettingAuction;
use Location;
use Sam\Core\Constants;
use Sam\Details\Auction\SeoUrl\ConfigManagerAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use SettingSeo;
use SettingSystem;

/**
 * Class ObservingPropertyManager
 * @package Sam\Details
 */
class ObservingPropertyManager extends \Sam\Details\Core\Observe\ObservingPropertyManager
{
    use ConfigManagerAwareTrait;
    use SettingsManagerAwareTrait;

    /**
     * @var array<array{'class': string, 'properties': string[]}>
     */
    protected array $alwaysObservingProperties = [
        ['class' => SettingSeo::class, 'properties' => ['AuctionSeoUrlTemplate']],
    ];
    /**
     * @var string[]
     */
    protected array $availableClasses = [
        Account::class,
        Auction::class,
        AuctionCustData::class,
        AuctionCustField::class,
        Location::class,
        SettingAuction::class,
        SettingSeo::class,
        SettingSystem::class,
    ];
    /**
     * @var string
     */
    public string $keyPrefix = 'a_seo_url';

    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function getTemplate(): string
    {
        if ($this->template === null) {
            $this->template = (string)$this->getSettingsManager()->get(Constants\Setting::AUCTION_SEO_URL_TEMPLATE, $this->getSystemAccountId());
        }
        return $this->template;
    }
}
