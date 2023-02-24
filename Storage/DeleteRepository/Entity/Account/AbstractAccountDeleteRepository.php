<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\Account;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractAccountDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_ACCOUNT;
    protected string $alias = Db::A_ACCOUNT;

    /**
     * Filter by account.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out account.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by account.name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.name', $filterValue);
        return $this;
    }

    /**
     * Filter out account.name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.name', $skipValue);
        return $this;
    }

    /**
     * Filter by account.company_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCompanyName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.company_name', $filterValue);
        return $this;
    }

    /**
     * Filter out account.company_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCompanyName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.company_name', $skipValue);
        return $this;
    }

    /**
     * Filter by account.address
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAddress(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.address', $filterValue);
        return $this;
    }

    /**
     * Filter out account.address from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAddress(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.address', $skipValue);
        return $this;
    }

    /**
     * Filter by account.address_2
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAddress2(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.address_2', $filterValue);
        return $this;
    }

    /**
     * Filter out account.address_2 from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAddress2(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.address_2', $skipValue);
        return $this;
    }

    /**
     * Filter by account.city
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCity(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.city', $filterValue);
        return $this;
    }

    /**
     * Filter out account.city from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCity(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.city', $skipValue);
        return $this;
    }

    /**
     * Filter by account.state_province
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterStateProvince(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.state_province', $filterValue);
        return $this;
    }

    /**
     * Filter out account.state_province from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipStateProvince(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.state_province', $skipValue);
        return $this;
    }

    /**
     * Filter by account.county
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCounty(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.county', $filterValue);
        return $this;
    }

    /**
     * Filter out account.county from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCounty(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.county', $skipValue);
        return $this;
    }

    /**
     * Filter by account.zip
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterZip(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.zip', $filterValue);
        return $this;
    }

    /**
     * Filter out account.zip from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipZip(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.zip', $skipValue);
        return $this;
    }

    /**
     * Filter by account.country
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCountry(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.country', $filterValue);
        return $this;
    }

    /**
     * Filter out account.country from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCountry(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.country', $skipValue);
        return $this;
    }

    /**
     * Filter by account.phone
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPhone(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.phone', $filterValue);
        return $this;
    }

    /**
     * Filter out account.phone from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPhone(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.phone', $skipValue);
        return $this;
    }

    /**
     * Filter by account.email
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterEmail(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.email', $filterValue);
        return $this;
    }

    /**
     * Filter out account.email from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipEmail(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.email', $skipValue);
        return $this;
    }

    /**
     * Filter by account.site_url
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSiteUrl(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.site_url', $filterValue);
        return $this;
    }

    /**
     * Filter out account.site_url from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSiteUrl(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.site_url', $skipValue);
        return $this;
    }

    /**
     * Filter by account.notes
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterNotes(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.notes', $filterValue);
        return $this;
    }

    /**
     * Filter out account.notes from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipNotes(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.notes', $skipValue);
        return $this;
    }

    /**
     * Filter by account.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out account.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by account.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out account.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by account.modified_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out account.modified_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by account.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out account.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by account.main
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterMain(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.main', $filterValue);
        return $this;
    }

    /**
     * Filter out account.main from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipMain(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.main', $skipValue);
        return $this;
    }

    /**
     * Filter by account.active
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterActive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out account.active from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipActive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Filter by account.auction_inc_allowed
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAuctionIncAllowed(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_inc_allowed', $filterValue);
        return $this;
    }

    /**
     * Filter out account.auction_inc_allowed from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAuctionIncAllowed(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_inc_allowed', $skipValue);
        return $this;
    }

    /**
     * Filter by account.public_support_contact_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPublicSupportContactName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.public_support_contact_name', $filterValue);
        return $this;
    }

    /**
     * Filter out account.public_support_contact_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPublicSupportContactName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.public_support_contact_name', $skipValue);
        return $this;
    }

    /**
     * Filter by account.url_domain
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterUrlDomain(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.url_domain', $filterValue);
        return $this;
    }

    /**
     * Filter out account.url_domain from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipUrlDomain(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.url_domain', $skipValue);
        return $this;
    }

    /**
     * Filter by account.show_all
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterShowAll(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.show_all', $filterValue);
        return $this;
    }

    /**
     * Filter out account.show_all from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipShowAll(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.show_all', $skipValue);
        return $this;
    }

    /**
     * Filter by account.hybrid_auction_enabled
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterHybridAuctionEnabled(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hybrid_auction_enabled', $filterValue);
        return $this;
    }

    /**
     * Filter out account.hybrid_auction_enabled from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipHybridAuctionEnabled(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hybrid_auction_enabled', $skipValue);
        return $this;
    }

    /**
     * Filter by account.buy_now_select_quantity_enabled
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterBuyNowSelectQuantityEnabled(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.buy_now_select_quantity_enabled', $filterValue);
        return $this;
    }

    /**
     * Filter out account.buy_now_select_quantity_enabled from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipBuyNowSelectQuantityEnabled(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.buy_now_select_quantity_enabled', $skipValue);
        return $this;
    }

    /**
     * Filter by account.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out account.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
