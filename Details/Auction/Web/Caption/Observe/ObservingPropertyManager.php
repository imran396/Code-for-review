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

namespace Sam\Details\Auction\Web\Caption\Observe;

use Account;
use Auction;
use AuctionAuctioneer;
use AuctionCache;
use AuctionCustData;
use AuctionCustField;
use Location;
use Sam\Details\Auction\Web\ConfigManagerAwareTrait;
use Sam\Settings\TermsAndConditionsManagerAwareTrait;
use Sam\Core\Constants;
use SamTaxCountryStates;
use SettingAuction;
use SettingSystem;
use TermsAndConditions;

/**
 * Class ObservingPropertyManager
 * @package Sam\Details
 */
class ObservingPropertyManager extends \Sam\Details\Core\Observe\ObservingPropertyManager
{
    use ConfigManagerAwareTrait;
    use TermsAndConditionsManagerAwareTrait;

    /**
     * @var array<array{'class': string, 'properties': string[]}>
     */
    protected array $alwaysObservingProperties = [
        [
            'class' => TermsAndConditions::class,
            'properties' => ['Content'],
        ],
    ];
    /**
     * @var string[]
     */
    protected array $availableClasses = [
        Account::class,
        Auction::class,
        AuctionAuctioneer::class,
        AuctionCache::class,
        AuctionCustData::class,
        AuctionCustField::class,
        Location::class,
        SamTaxCountryStates::class,
        SettingAuction::class,
        SettingSystem::class,
        TermsAndConditions::class,
    ];
    /**
     * @var string
     */
    public string $keyPrefix = 'a_caption';

    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function getTemplate(): string
    {
        if (!$this->template) {
            $auctionCaption = $this->getTermsAndConditionsManager()->load(
                $this->getSystemAccountId(),
                Constants\TermsAndConditions::AUCTION_CAPTION,
                true
            );
            $this->template = $auctionCaption->Content ?? '';
        }
        return $this->template;
    }
}
