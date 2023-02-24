<?php
/**
 * SAM-4449: Language label translation modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/17/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lang;

use Auction;
use Sam\Application\Language\Detect\ApplicationLanguageDetectorCreateTrait;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\Cookie\CookieHelperCreateTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class Translator
 * @package Sam\Lang
 */
class Translator extends CustomizableClass implements TranslatorInterface
{
    use ApplicationLanguageDetectorCreateTrait;
    use CookieHelperCreateTrait;
    use SystemAccountAwareTrait;
    use TranslationManagerAwareTrait;

    /**
     * @var int|null
     */
    protected ?int $accountId = null;
    /**
     * @var int|null
     */
    protected ?int $languageId = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $serviceAccountId
     * @param int $languageId
     * @return $this
     */
    public function construct(int $serviceAccountId, int $languageId): static
    {
        $this->accountId = $serviceAccountId;
        $this->languageId = $languageId;
        return $this;
    }

    /**
     * @return int
     */
    protected function getAccountId(): int
    {
        if ($this->accountId === null) {
            $this->accountId = $this->getSystemAccountId();
        }
        return $this->accountId;
    }

    /**
     * @param int|null $accountId
     * @return static
     */
    public function setAccountId(?int $accountId): static
    {
        $this->accountId = (int)$accountId;
        return $this;
    }

    /**
     * @return int
     */
    protected function getLanguageId(): int
    {
        if ($this->languageId === null) {
            $this->languageId = $this->createApplicationLanguageDetector()
                ->detectActiveLanguageId($this->getAccountId());
        }
        return $this->languageId;
    }

    /**
     * @param int|null $languageId
     * @return static
     */
    public function setLanguageId(?int $languageId): static
    {
        $this->languageId = $languageId;
        return $this;
    }

    /**
     * @param string $fieldKey
     * @param string $section
     * @param int|null $accountId
     * @param int|null $languageId
     * @param bool $isCached
     * @return string
     */
    public function translate(
        string $fieldKey,
        string $section = 'mainmenu',
        ?int $accountId = null,
        ?int $languageId = null,
        bool $isCached = true
    ): string {
        if ($accountId) {
            $this->setAccountId($accountId);
        }

        if ($languageId) {
            $this->setLanguageId($languageId);
        }

        $fileName = strtolower($section);
        $translations = $this->getTranslationManager()->getTranslations(
            $fileName,
            $this->getAccountId(),
            false,
            $this->getLanguageId(),
            $isCached
        );
        $output = array_key_exists($fieldKey, $translations)
            ? trim($translations[$fieldKey][2])
            : $fieldKey;
        return $output;
    }

    /**
     * Translations for RTB(Live, Hybrid)
     *
     * @param string $label
     * @param Auction $auction
     * @param int|null $accountId
     * @param int|null $viewLanguageId
     * @return string
     */
    public function translateForRtb(
        string $label,
        Auction $auction,
        ?int $accountId = null,
        ?int $viewLanguageId = null
    ): string {
        $file = $auction->isHybrid() ? 'hybridclient' : 'bidderclient';
        $output = $this->translate($label, $file, $accountId, $viewLanguageId);
        return $output;
    }

    /**
     * Translations for RTB(Live, Hybrid)
     * @param string $label
     * @param string $auctionType
     * @param int|null $accountId
     * @param int|null $viewLanguageId
     * @return string
     */
    public function translateForRtbByAuctionType(
        string $label,
        string $auctionType,
        ?int $accountId = null,
        ?int $viewLanguageId = null
    ): string {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        $section = $auctionStatusPureChecker->isHybrid($auctionType) ? 'hybridclient' : 'bidderclient';
        return $this->translate($label, $section, $accountId, $viewLanguageId);
    }

    /**
     * @param string $fieldKey
     * @param string $section
     * @param bool $isReverse
     * @param int|null $accountId
     * @param int|null $languageId
     * @param bool $isCached
     * @return string
     */
    public function translateByAuctionReverse(
        string $fieldKey,
        string $section,
        bool $isReverse = false,
        ?int $accountId = null,
        ?int $languageId = null,
        bool $isCached = true
    ): string {
        if ($isReverse) {
            $fieldKeyByDirection = match ($fieldKey) {
                'GENERAL_YOUR_HIGH_BID' => 'GENERAL_YOUR_LOW_BID',
                'GENERAL_YOUR_MAXBID' => 'GENERAL_YOUR_MINBID',
                'CATALOG_SOLD' => 'CATALOG_AWARDED',
                'CATALOG_SOLDCONDITIONAL' => 'CATALOG_AWARDEDCONDITIONAL',
                'GENERAL_INVALID_MAXBID' => 'GENERAL_INVALID_MINBID',
                'CATALOG_BID_TOOSMALL' => 'CATALOG_BID_TOOBIG',
                'CATALOG_BID_WASLOWER' => 'CATALOG_BID_WASHIGHER',
                'CATALOG_FORCE_BID_TOOSMALL' => 'CATALOG_FORCE_BID_TOOBIG',
                'GENERAL_YOUR_HIGH_BID_BELOW_RESERVE' => 'GENERAL_YOUR_LOW_BID_ABOVE_RESERVE',
                'GENERAL_BID_PLACED_BUT_BELOW_RESERVE' => 'GENERAL_BID_PLACED_BUT_ABOVE_RESERVE',
                'ITEM_UNSOLD' => 'ITEM_UNAWARDED',
                'ITEM_SOLD' => 'ITEM_AWARDED',
                'ITEM_LOTCLOSED_UNSOLD' => 'ITEM_LOTCLOSED_UNAWARDED',
                'CATALOG_BID_YOURHIGH' => 'CATALOG_BID_YOURLOW',
                'ITEM_ASKINGBID_ORMORE' => 'ITEM_ASKINGBID_ORLESS',
                'CATALOG_BID_FAILED_TOOSMALL' => 'CATALOG_BID_FAILED_TOOBIG',
                default => $fieldKey,
            };
        } else {
            $fieldKeyByDirection = $fieldKey;
        }
        $translation = $this->translate($fieldKeyByDirection, $section, $accountId, $languageId, $isCached);
        return $translation;
    }
}
