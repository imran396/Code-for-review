<?php
/**
 * My Alerts List Updater
 *
 * SAM-6299: Refactor My Alerts List page at client side
 * SAM-5454: Extract data loading from form classes
 * SAM-8033: Issues with my searches update on Sign Up and at My Alerts pages
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 17, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\MyAlertsList\Save;

use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Core\Constants;
use Sam\Storage\WriteRepository\Entity\MySearch\MySearchWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\MySearchCategory\MySearchCategoryWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\MySearchCustom\MySearchCustomWriteRepositoryAwareTrait;

/**
 * Class MyAlertsUpdater
 */
class MyAlertsUpdater extends CustomizableClass
{
    use CurrentDateTrait;
    use EditorUserAwareTrait;
    use EntityFactoryCreateTrait;
    use MySearchCategoryWriteRepositoryAwareTrait;
    use MySearchCustomWriteRepositoryAwareTrait;
    use MySearchWriteRepositoryAwareTrait;
    use SortInfoAwareTrait;
    use SystemAccountAwareTrait;

    protected string $title = '';
    protected string $keywords = '';
    protected string $minValue = '';
    protected string $maxValue = '';
    protected bool $isLiveBidding = false;
    protected bool $isTimed = false;
    protected bool $isHybrid = false;
    protected bool $isRegularBidding = false;
    protected bool $isBuyNow = false;
    protected bool $isBestOffer = false;
    protected bool $isSendEmail = false;
    protected bool $isMySearchExcludeClosed = false;
    protected ?int $lotCategoryId = null;
    protected ?int $mySearchId = null;
    protected ?int $mySearchAuctioneerId = null;
    protected ?int $lotCustomFieldId = null;
    protected int $categoryMatch = Constants\MySearch::CATEGORY_MATCH_ANY;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $id
     * @return static
     */
    public function setMySearchId(int $id): static
    {
        $this->mySearchId = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getMySearchId(): int
    {
        return $this->mySearchId;
    }

    /**
     * @param string $title
     * @return static
     */
    public function setTitle(string $title): static
    {
        $this->title = trim($title);
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $keywords
     * @return static
     */
    public function setKeywords(string $keywords): static
    {
        $this->keywords = trim($keywords);
        return $this;
    }

    /**
     * @return string
     */
    public function getKeywords(): string
    {
        return $this->keywords;
    }

    /**
     * @param bool $isLiveBidding
     * @return static
     */
    public function enableLiveBidding(bool $isLiveBidding): static
    {
        $this->isLiveBidding = $isLiveBidding;
        return $this;
    }

    /**
     * @return bool
     */
    public function isLiveBidding(): bool
    {
        return $this->isLiveBidding;
    }

    /**
     * @param bool $isTimed
     * @return static
     */
    public function enableTimed(bool $isTimed): static
    {
        $this->isTimed = $isTimed;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTimed(): bool
    {
        return $this->isTimed;
    }

    /**
     * @param bool $isHybrid
     * @return static
     */
    public function enableHybrid(bool $isHybrid): static
    {
        $this->isHybrid = $isHybrid;
        return $this;
    }

    /**
     * @return bool
     */
    public function isHybrid(): bool
    {
        return $this->isHybrid;
    }

    /**
     * @param bool $isRegularBidding
     * @return static
     */
    public function enableRegularBidding(bool $isRegularBidding): static
    {
        $this->isRegularBidding = $isRegularBidding;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRegularBidding(): bool
    {
        return $this->isRegularBidding;
    }

    /**
     * @param bool $isBuyNow
     * @return static
     */
    public function enableBuyNow(bool $isBuyNow): static
    {
        $this->isBuyNow = $isBuyNow;
        return $this;
    }

    /**
     * @return bool
     */
    public function isBuyNow(): bool
    {
        return $this->isBuyNow;
    }

    /**
     * @param bool $isBestOffer
     * @return static
     */
    public function enableBestOffer(bool $isBestOffer): static
    {
        $this->isBestOffer = $isBestOffer;
        return $this;
    }

    /**
     * @return bool
     */
    public function isBestOffer(): bool
    {
        return $this->isBestOffer;
    }

    /**
     * @param bool $isSendEmail
     * @return static
     */
    public function enableSendEmail(bool $isSendEmail): static
    {
        $this->isSendEmail = $isSendEmail;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSendEmail(): bool
    {
        return $this->isSendEmail;
    }

    /**
     * @param int|null $mySearchAuctioneerId
     * @return static
     */
    public function setMySearchAuctioneerId(?int $mySearchAuctioneerId): static
    {
        $this->mySearchAuctioneerId = $mySearchAuctioneerId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMySearchAuctioneerId(): ?int
    {
        return $this->mySearchAuctioneerId;
    }

    /**
     * @param int $categoryMatch
     * @return static
     */
    public function setCategoryMatch(int $categoryMatch): static
    {
        $this->categoryMatch = $categoryMatch;
        return $this;
    }

    /**
     * @return int
     */
    public function getCategoryMatch(): int
    {
        return $this->categoryMatch;
    }

    /**
     * @param bool $isMySearchExcludeClosed
     * @return static
     */
    public function enableMySearchExcludeClosed(bool $isMySearchExcludeClosed): static
    {
        $this->isMySearchExcludeClosed = $isMySearchExcludeClosed;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMySearchExcludeClosed(): bool
    {
        return $this->isMySearchExcludeClosed;
    }

    /**
     * @param int $lotCategoryId
     * @return static
     */
    public function setLotCategoryId(int $lotCategoryId): static
    {
        $this->lotCategoryId = $lotCategoryId;
        return $this;
    }

    /**
     * @return int
     */
    public function getLotCategoryId(): int
    {
        return $this->lotCategoryId;
    }

    /**
     * @param int $id
     * @return static
     */
    public function setLotCustomFieldId(int $id): static
    {
        $this->lotCustomFieldId = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getLotCustomFieldId(): int
    {
        return $this->lotCustomFieldId;
    }

    /**
     * @param string $minValue
     * @return static
     */
    public function setMinValue(string $minValue): static
    {
        $this->minValue = $minValue;
        return $this;
    }

    /**
     * @return string
     */
    public function getMinValue(): string
    {
        return $this->minValue;
    }

    /**
     * @param string $maxValue
     * @return static
     */
    public function setMaxValue(string $maxValue): static
    {
        $this->maxValue = $maxValue;
        return $this;
    }

    /**
     * @return string
     */
    public function getMaxValue(): string
    {
        return $this->maxValue;
    }

    /**
     * Update My Search
     */
    public function updateMySearch(): void
    {
        $mySearch = $this->createEntityFactory()->mySearch();
        $mySearch->UserId = $this->getEditorUserId();
        $mySearch->AccountId = $this->getSystemAccountId();
        $mySearch->Title = $this->getTitle();
        $mySearch->Keywords = $this->getKeywords();
        $mySearch->LiveBidding = $this->isLiveBidding();
        $mySearch->Timed = $this->isTimed();
        $mySearch->Hybrid = $this->isHybrid();
        $mySearch->RegularBidding = $this->isRegularBidding();
        $mySearch->BuyNow = $this->isBuyNow();
        $mySearch->BestOffer = $this->isBestOffer();
        $mySearch->SortOrder = $this->getSortColumn();
        $mySearch->SendMail = $this->isSendEmail();
        $mySearch->MySearchAuctioneerId = $this->getMySearchAuctioneerId();
        $mySearch->CategoryMatch = $this->getCategoryMatch();
        $mySearch->MySearchExcludeClosed = $this->isMySearchExcludeClosed();
        $this->getMySearchWriteRepository()->saveWithModifier($mySearch, $this->getEditorUserId());
        $this->setMySearchId($mySearch->Id);
    }

    /**
     * Update My Search Categories
     * @param array $lotCategoryIds
     */
    public function updateMySearchCategories(array $lotCategoryIds): void
    {
        foreach ($lotCategoryIds as $lotCategoryId) {
            $mySearchCategory = $this->createEntityFactory()->mySearchCategory();
            $mySearchCategory->CategoryId = $lotCategoryId;
            $mySearchCategory->MySearchId = $this->getMySearchId();
            $this->getMySearchCategoryWriteRepository()->saveWithModifier($mySearchCategory, $this->getEditorUserId());
        }
    }

    /**
     * Update My Search Custom
     */
    public function updateMySearchCustom(): void
    {
        $mySearchCustom = $this->createEntityFactory()->mySearchCustom();
        $mySearchCustom->LotItemCustFieldId = $this->getLotCustomFieldId();
        $mySearchCustom->MaxField = $this->getMaxValue();
        $mySearchCustom->MinField = $this->getMinValue();
        $mySearchCustom->MySearchId = $this->getMySearchId();
        $this->getMySearchCustomWriteRepository()->saveWithModifier($mySearchCustom, $this->getEditorUserId());
    }
}
