<?php
/**
 * Helps to observe data changes related to Seo Url templates.
 * We describe placeholder related classes and properties in ConfigManager::$keysConfig[<key>][<type>]['observe']
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 * SAM-6595: Templated-content building - simplify module structure for v3.5
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

namespace Sam\Details\Lot\SeoUrl\Observe\Internal\ObservingProperty;

use Auction;
use AuctionLotItem;
use LotItem;
use LotItemCustData;
use LotItemCustField;
use Sam\Core\Constants;
use Sam\Details\Lot\SeoUrl\Common\Config\ConfigManagerAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use SettingAuction;
use SettingSeo;

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
        ['class' => SettingSeo::class, 'properties' => ['LotSeoUrlTemplate']],
    ];
    /**
     * @var string[]
     */
    protected array $availableClasses = [
        Auction::class,
        AuctionLotItem::class,
        LotItem::class,
        LotItemCustData::class,
        LotItemCustField::class,
        SettingAuction::class,
        SettingSeo::class,
    ];
    /**
     * @var string
     */
    public string $keyPrefix = 'l';

    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function getTemplate(): string
    {
        if ($this->template === null) {
            $this->template = (string)$this->getSettingsManager()
                ->get(Constants\Setting::LOT_SEO_URL_TEMPLATE, $this->getSystemAccountId());
        }
        return $this->template;
    }
}
