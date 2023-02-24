<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\UserInfo;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use UserInfo;

/**
 * Abstract class AbstractUserInfoReadRepository
 * @method UserInfo[] loadEntities()
 * @method UserInfo|null loadEntity()
 */
abstract class AbstractUserInfoReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_USER_INFO;
    protected string $alias = Db::A_USER_INFO;

    /**
     * Filter by user_info.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by user_info.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_info.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_info.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_info.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_info.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_info.user_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterUserId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.user_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.user_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipUserId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.user_id', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.user_id
     * @return static
     */
    public function groupByUserId(): static
    {
        $this->group($this->alias . '.user_id');
        return $this;
    }

    /**
     * Order by user_info.user_id
     * @param bool $ascending
     * @return static
     */
    public function orderByUserId(bool $ascending = true): static
    {
        $this->order($this->alias . '.user_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_info.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_info.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_info.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_info.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_info.first_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFirstName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.first_name', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.first_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFirstName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.first_name', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.first_name
     * @return static
     */
    public function groupByFirstName(): static
    {
        $this->group($this->alias . '.first_name');
        return $this;
    }

    /**
     * Order by user_info.first_name
     * @param bool $ascending
     * @return static
     */
    public function orderByFirstName(bool $ascending = true): static
    {
        $this->order($this->alias . '.first_name', $ascending);
        return $this;
    }

    /**
     * Filter user_info.first_name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeFirstName(string $filterValue): static
    {
        $this->like($this->alias . '.first_name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_info.last_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLastName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.last_name', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.last_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLastName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.last_name', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.last_name
     * @return static
     */
    public function groupByLastName(): static
    {
        $this->group($this->alias . '.last_name');
        return $this;
    }

    /**
     * Order by user_info.last_name
     * @param bool $ascending
     * @return static
     */
    public function orderByLastName(bool $ascending = true): static
    {
        $this->order($this->alias . '.last_name', $ascending);
        return $this;
    }

    /**
     * Filter user_info.last_name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLastName(string $filterValue): static
    {
        $this->like($this->alias . '.last_name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_info.sales_tax
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterSalesTax(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.sales_tax', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.sales_tax from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipSalesTax(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.sales_tax', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.sales_tax
     * @return static
     */
    public function groupBySalesTax(): static
    {
        $this->group($this->alias . '.sales_tax');
        return $this;
    }

    /**
     * Order by user_info.sales_tax
     * @param bool $ascending
     * @return static
     */
    public function orderBySalesTax(bool $ascending = true): static
    {
        $this->order($this->alias . '.sales_tax', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_info.sales_tax
     * @param float $filterValue
     * @return static
     */
    public function filterSalesTaxGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_tax', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_info.sales_tax
     * @param float $filterValue
     * @return static
     */
    public function filterSalesTaxGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_tax', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_info.sales_tax
     * @param float $filterValue
     * @return static
     */
    public function filterSalesTaxLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_tax', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_info.sales_tax
     * @param float $filterValue
     * @return static
     */
    public function filterSalesTaxLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_tax', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_info.tax_application
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterTaxApplication(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_application', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.tax_application from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipTaxApplication(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_application', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.tax_application
     * @return static
     */
    public function groupByTaxApplication(): static
    {
        $this->group($this->alias . '.tax_application');
        return $this;
    }

    /**
     * Order by user_info.tax_application
     * @param bool $ascending
     * @return static
     */
    public function orderByTaxApplication(bool $ascending = true): static
    {
        $this->order($this->alias . '.tax_application', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_info.tax_application
     * @param int $filterValue
     * @return static
     */
    public function filterTaxApplicationGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_application', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_info.tax_application
     * @param int $filterValue
     * @return static
     */
    public function filterTaxApplicationGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_application', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_info.tax_application
     * @param int $filterValue
     * @return static
     */
    public function filterTaxApplicationLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_application', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_info.tax_application
     * @param int $filterValue
     * @return static
     */
    public function filterTaxApplicationLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_application', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_info.no_tax
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterNoTax(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.no_tax', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.no_tax from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipNoTax(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.no_tax', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.no_tax
     * @return static
     */
    public function groupByNoTax(): static
    {
        $this->group($this->alias . '.no_tax');
        return $this;
    }

    /**
     * Order by user_info.no_tax
     * @param bool $ascending
     * @return static
     */
    public function orderByNoTax(bool $ascending = true): static
    {
        $this->order($this->alias . '.no_tax', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_info.no_tax
     * @param bool $filterValue
     * @return static
     */
    public function filterNoTaxGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.no_tax', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_info.no_tax
     * @param bool $filterValue
     * @return static
     */
    public function filterNoTaxGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.no_tax', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_info.no_tax
     * @param bool $filterValue
     * @return static
     */
    public function filterNoTaxLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.no_tax', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_info.no_tax
     * @param bool $filterValue
     * @return static
     */
    public function filterNoTaxLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.no_tax', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_info.no_tax_bp
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterNoTaxBp(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.no_tax_bp', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.no_tax_bp from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipNoTaxBp(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.no_tax_bp', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.no_tax_bp
     * @return static
     */
    public function groupByNoTaxBp(): static
    {
        $this->group($this->alias . '.no_tax_bp');
        return $this;
    }

    /**
     * Order by user_info.no_tax_bp
     * @param bool $ascending
     * @return static
     */
    public function orderByNoTaxBp(bool $ascending = true): static
    {
        $this->order($this->alias . '.no_tax_bp', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_info.no_tax_bp
     * @param bool $filterValue
     * @return static
     */
    public function filterNoTaxBpGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.no_tax_bp', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_info.no_tax_bp
     * @param bool $filterValue
     * @return static
     */
    public function filterNoTaxBpGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.no_tax_bp', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_info.no_tax_bp
     * @param bool $filterValue
     * @return static
     */
    public function filterNoTaxBpLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.no_tax_bp', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_info.no_tax_bp
     * @param bool $filterValue
     * @return static
     */
    public function filterNoTaxBpLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.no_tax_bp', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_info.note
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterNote(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.note', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.note from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipNote(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.note', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.note
     * @return static
     */
    public function groupByNote(): static
    {
        $this->group($this->alias . '.note');
        return $this;
    }

    /**
     * Order by user_info.note
     * @param bool $ascending
     * @return static
     */
    public function orderByNote(bool $ascending = true): static
    {
        $this->order($this->alias . '.note', $ascending);
        return $this;
    }

    /**
     * Filter user_info.note by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeNote(string $filterValue): static
    {
        $this->like($this->alias . '.note', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_info.resume
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterResume(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.resume', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.resume from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipResume(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.resume', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.resume
     * @return static
     */
    public function groupByResume(): static
    {
        $this->group($this->alias . '.resume');
        return $this;
    }

    /**
     * Order by user_info.resume
     * @param bool $ascending
     * @return static
     */
    public function orderByResume(bool $ascending = true): static
    {
        $this->order($this->alias . '.resume', $ascending);
        return $this;
    }

    /**
     * Filter user_info.resume by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeResume(string $filterValue): static
    {
        $this->like($this->alias . '.resume', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_info.phone
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPhone(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.phone', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.phone from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPhone(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.phone', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.phone
     * @return static
     */
    public function groupByPhone(): static
    {
        $this->group($this->alias . '.phone');
        return $this;
    }

    /**
     * Order by user_info.phone
     * @param bool $ascending
     * @return static
     */
    public function orderByPhone(bool $ascending = true): static
    {
        $this->order($this->alias . '.phone', $ascending);
        return $this;
    }

    /**
     * Filter user_info.phone by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePhone(string $filterValue): static
    {
        $this->like($this->alias . '.phone', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_info.view_language
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterViewLanguage(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.view_language', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.view_language from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipViewLanguage(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.view_language', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.view_language
     * @return static
     */
    public function groupByViewLanguage(): static
    {
        $this->group($this->alias . '.view_language');
        return $this;
    }

    /**
     * Order by user_info.view_language
     * @param bool $ascending
     * @return static
     */
    public function orderByViewLanguage(bool $ascending = true): static
    {
        $this->order($this->alias . '.view_language', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_info.view_language
     * @param int $filterValue
     * @return static
     */
    public function filterViewLanguageGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.view_language', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_info.view_language
     * @param int $filterValue
     * @return static
     */
    public function filterViewLanguageGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.view_language', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_info.view_language
     * @param int $filterValue
     * @return static
     */
    public function filterViewLanguageLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.view_language', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_info.view_language
     * @param int $filterValue
     * @return static
     */
    public function filterViewLanguageLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.view_language', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_info.news_letter
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterNewsLetter(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.news_letter', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.news_letter from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipNewsLetter(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.news_letter', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.news_letter
     * @return static
     */
    public function groupByNewsLetter(): static
    {
        $this->group($this->alias . '.news_letter');
        return $this;
    }

    /**
     * Order by user_info.news_letter
     * @param bool $ascending
     * @return static
     */
    public function orderByNewsLetter(bool $ascending = true): static
    {
        $this->order($this->alias . '.news_letter', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_info.news_letter
     * @param bool $filterValue
     * @return static
     */
    public function filterNewsLetterGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.news_letter', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_info.news_letter
     * @param bool $filterValue
     * @return static
     */
    public function filterNewsLetterGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.news_letter', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_info.news_letter
     * @param bool $filterValue
     * @return static
     */
    public function filterNewsLetterLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.news_letter', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_info.news_letter
     * @param bool $filterValue
     * @return static
     */
    public function filterNewsLetterLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.news_letter', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_info.company_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCompanyName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.company_name', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.company_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCompanyName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.company_name', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.company_name
     * @return static
     */
    public function groupByCompanyName(): static
    {
        $this->group($this->alias . '.company_name');
        return $this;
    }

    /**
     * Order by user_info.company_name
     * @param bool $ascending
     * @return static
     */
    public function orderByCompanyName(bool $ascending = true): static
    {
        $this->order($this->alias . '.company_name', $ascending);
        return $this;
    }

    /**
     * Filter user_info.company_name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCompanyName(string $filterValue): static
    {
        $this->like($this->alias . '.company_name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_info.reg_auth_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterRegAuthDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.reg_auth_date', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.reg_auth_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipRegAuthDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.reg_auth_date', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.reg_auth_date
     * @return static
     */
    public function groupByRegAuthDate(): static
    {
        $this->group($this->alias . '.reg_auth_date');
        return $this;
    }

    /**
     * Order by user_info.reg_auth_date
     * @param bool $ascending
     * @return static
     */
    public function orderByRegAuthDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.reg_auth_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_info.reg_auth_date
     * @param string $filterValue
     * @return static
     */
    public function filterRegAuthDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.reg_auth_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_info.reg_auth_date
     * @param string $filterValue
     * @return static
     */
    public function filterRegAuthDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.reg_auth_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_info.reg_auth_date
     * @param string $filterValue
     * @return static
     */
    public function filterRegAuthDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.reg_auth_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_info.reg_auth_date
     * @param string $filterValue
     * @return static
     */
    public function filterRegAuthDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.reg_auth_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_info.phone_type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterPhoneType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.phone_type', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.phone_type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipPhoneType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.phone_type', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.phone_type
     * @return static
     */
    public function groupByPhoneType(): static
    {
        $this->group($this->alias . '.phone_type');
        return $this;
    }

    /**
     * Order by user_info.phone_type
     * @param bool $ascending
     * @return static
     */
    public function orderByPhoneType(bool $ascending = true): static
    {
        $this->order($this->alias . '.phone_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_info.phone_type
     * @param int $filterValue
     * @return static
     */
    public function filterPhoneTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.phone_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_info.phone_type
     * @param int $filterValue
     * @return static
     */
    public function filterPhoneTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.phone_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_info.phone_type
     * @param int $filterValue
     * @return static
     */
    public function filterPhoneTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.phone_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_info.phone_type
     * @param int $filterValue
     * @return static
     */
    public function filterPhoneTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.phone_type', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_info.identification
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterIdentification(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.identification', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.identification from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipIdentification(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.identification', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.identification
     * @return static
     */
    public function groupByIdentification(): static
    {
        $this->group($this->alias . '.identification');
        return $this;
    }

    /**
     * Order by user_info.identification
     * @param bool $ascending
     * @return static
     */
    public function orderByIdentification(bool $ascending = true): static
    {
        $this->order($this->alias . '.identification', $ascending);
        return $this;
    }

    /**
     * Filter user_info.identification by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeIdentification(string $filterValue): static
    {
        $this->like($this->alias . '.identification', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_info.identification_type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterIdentificationType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.identification_type', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.identification_type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipIdentificationType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.identification_type', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.identification_type
     * @return static
     */
    public function groupByIdentificationType(): static
    {
        $this->group($this->alias . '.identification_type');
        return $this;
    }

    /**
     * Order by user_info.identification_type
     * @param bool $ascending
     * @return static
     */
    public function orderByIdentificationType(bool $ascending = true): static
    {
        $this->order($this->alias . '.identification_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_info.identification_type
     * @param int $filterValue
     * @return static
     */
    public function filterIdentificationTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.identification_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_info.identification_type
     * @param int $filterValue
     * @return static
     */
    public function filterIdentificationTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.identification_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_info.identification_type
     * @param int $filterValue
     * @return static
     */
    public function filterIdentificationTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.identification_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_info.identification_type
     * @param int $filterValue
     * @return static
     */
    public function filterIdentificationTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.identification_type', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_info.location_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLocationId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.location_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.location_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLocationId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.location_id', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.location_id
     * @return static
     */
    public function groupByLocationId(): static
    {
        $this->group($this->alias . '.location_id');
        return $this;
    }

    /**
     * Order by user_info.location_id
     * @param bool $ascending
     * @return static
     */
    public function orderByLocationId(bool $ascending = true): static
    {
        $this->order($this->alias . '.location_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_info.location_id
     * @param int $filterValue
     * @return static
     */
    public function filterLocationIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.location_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_info.location_id
     * @param int $filterValue
     * @return static
     */
    public function filterLocationIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.location_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_info.location_id
     * @param int $filterValue
     * @return static
     */
    public function filterLocationIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.location_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_info.location_id
     * @param int $filterValue
     * @return static
     */
    public function filterLocationIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.location_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_info.send_text_alerts
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterSendTextAlerts(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.send_text_alerts', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.send_text_alerts from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipSendTextAlerts(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.send_text_alerts', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.send_text_alerts
     * @return static
     */
    public function groupBySendTextAlerts(): static
    {
        $this->group($this->alias . '.send_text_alerts');
        return $this;
    }

    /**
     * Order by user_info.send_text_alerts
     * @param bool $ascending
     * @return static
     */
    public function orderBySendTextAlerts(bool $ascending = true): static
    {
        $this->order($this->alias . '.send_text_alerts', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_info.send_text_alerts
     * @param bool $filterValue
     * @return static
     */
    public function filterSendTextAlertsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.send_text_alerts', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_info.send_text_alerts
     * @param bool $filterValue
     * @return static
     */
    public function filterSendTextAlertsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.send_text_alerts', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_info.send_text_alerts
     * @param bool $filterValue
     * @return static
     */
    public function filterSendTextAlertsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.send_text_alerts', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_info.send_text_alerts
     * @param bool $filterValue
     * @return static
     */
    public function filterSendTextAlertsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.send_text_alerts', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_info.referrer
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterReferrer(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.referrer', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.referrer from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipReferrer(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.referrer', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.referrer
     * @return static
     */
    public function groupByReferrer(): static
    {
        $this->group($this->alias . '.referrer');
        return $this;
    }

    /**
     * Order by user_info.referrer
     * @param bool $ascending
     * @return static
     */
    public function orderByReferrer(bool $ascending = true): static
    {
        $this->order($this->alias . '.referrer', $ascending);
        return $this;
    }

    /**
     * Filter user_info.referrer by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeReferrer(string $filterValue): static
    {
        $this->like($this->alias . '.referrer', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_info.referrer_host
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterReferrerHost(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.referrer_host', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.referrer_host from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipReferrerHost(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.referrer_host', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.referrer_host
     * @return static
     */
    public function groupByReferrerHost(): static
    {
        $this->group($this->alias . '.referrer_host');
        return $this;
    }

    /**
     * Order by user_info.referrer_host
     * @param bool $ascending
     * @return static
     */
    public function orderByReferrerHost(bool $ascending = true): static
    {
        $this->order($this->alias . '.referrer_host', $ascending);
        return $this;
    }

    /**
     * Filter user_info.referrer_host by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeReferrerHost(string $filterValue): static
    {
        $this->like($this->alias . '.referrer_host', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_info.reseller_cert_approved
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterResellerCertApproved(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.reseller_cert_approved', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.reseller_cert_approved from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipResellerCertApproved(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.reseller_cert_approved', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.reseller_cert_approved
     * @return static
     */
    public function groupByResellerCertApproved(): static
    {
        $this->group($this->alias . '.reseller_cert_approved');
        return $this;
    }

    /**
     * Order by user_info.reseller_cert_approved
     * @param bool $ascending
     * @return static
     */
    public function orderByResellerCertApproved(bool $ascending = true): static
    {
        $this->order($this->alias . '.reseller_cert_approved', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_info.reseller_cert_approved
     * @param bool $filterValue
     * @return static
     */
    public function filterResellerCertApprovedGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reseller_cert_approved', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_info.reseller_cert_approved
     * @param bool $filterValue
     * @return static
     */
    public function filterResellerCertApprovedGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reseller_cert_approved', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_info.reseller_cert_approved
     * @param bool $filterValue
     * @return static
     */
    public function filterResellerCertApprovedLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reseller_cert_approved', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_info.reseller_cert_approved
     * @param bool $filterValue
     * @return static
     */
    public function filterResellerCertApprovedLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reseller_cert_approved', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_info.reseller_cert_file
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterResellerCertFile(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.reseller_cert_file', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.reseller_cert_file from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipResellerCertFile(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.reseller_cert_file', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.reseller_cert_file
     * @return static
     */
    public function groupByResellerCertFile(): static
    {
        $this->group($this->alias . '.reseller_cert_file');
        return $this;
    }

    /**
     * Order by user_info.reseller_cert_file
     * @param bool $ascending
     * @return static
     */
    public function orderByResellerCertFile(bool $ascending = true): static
    {
        $this->order($this->alias . '.reseller_cert_file', $ascending);
        return $this;
    }

    /**
     * Filter user_info.reseller_cert_file by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeResellerCertFile(string $filterValue): static
    {
        $this->like($this->alias . '.reseller_cert_file', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_info.reseller_cert_expiration
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterResellerCertExpiration(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.reseller_cert_expiration', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.reseller_cert_expiration from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipResellerCertExpiration(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.reseller_cert_expiration', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.reseller_cert_expiration
     * @return static
     */
    public function groupByResellerCertExpiration(): static
    {
        $this->group($this->alias . '.reseller_cert_expiration');
        return $this;
    }

    /**
     * Order by user_info.reseller_cert_expiration
     * @param bool $ascending
     * @return static
     */
    public function orderByResellerCertExpiration(bool $ascending = true): static
    {
        $this->order($this->alias . '.reseller_cert_expiration', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_info.reseller_cert_expiration
     * @param string $filterValue
     * @return static
     */
    public function filterResellerCertExpirationGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.reseller_cert_expiration', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_info.reseller_cert_expiration
     * @param string $filterValue
     * @return static
     */
    public function filterResellerCertExpirationGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.reseller_cert_expiration', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_info.reseller_cert_expiration
     * @param string $filterValue
     * @return static
     */
    public function filterResellerCertExpirationLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.reseller_cert_expiration', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_info.reseller_cert_expiration
     * @param string $filterValue
     * @return static
     */
    public function filterResellerCertExpirationLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.reseller_cert_expiration', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_info.max_outstanding
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterMaxOutstanding(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.max_outstanding', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.max_outstanding from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipMaxOutstanding(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.max_outstanding', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.max_outstanding
     * @return static
     */
    public function groupByMaxOutstanding(): static
    {
        $this->group($this->alias . '.max_outstanding');
        return $this;
    }

    /**
     * Order by user_info.max_outstanding
     * @param bool $ascending
     * @return static
     */
    public function orderByMaxOutstanding(bool $ascending = true): static
    {
        $this->order($this->alias . '.max_outstanding', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_info.max_outstanding
     * @param float $filterValue
     * @return static
     */
    public function filterMaxOutstandingGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.max_outstanding', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_info.max_outstanding
     * @param float $filterValue
     * @return static
     */
    public function filterMaxOutstandingGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.max_outstanding', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_info.max_outstanding
     * @param float $filterValue
     * @return static
     */
    public function filterMaxOutstandingLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.max_outstanding', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_info.max_outstanding
     * @param float $filterValue
     * @return static
     */
    public function filterMaxOutstandingLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.max_outstanding', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_info.locale
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLocale(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.locale', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.locale from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLocale(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.locale', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.locale
     * @return static
     */
    public function groupByLocale(): static
    {
        $this->group($this->alias . '.locale');
        return $this;
    }

    /**
     * Order by user_info.locale
     * @param bool $ascending
     * @return static
     */
    public function orderByLocale(bool $ascending = true): static
    {
        $this->order($this->alias . '.locale', $ascending);
        return $this;
    }

    /**
     * Filter user_info.locale by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLocale(string $filterValue): static
    {
        $this->like($this->alias . '.locale', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_info.timezone_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterTimezoneId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.timezone_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.timezone_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipTimezoneId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.timezone_id', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.timezone_id
     * @return static
     */
    public function groupByTimezoneId(): static
    {
        $this->group($this->alias . '.timezone_id');
        return $this;
    }

    /**
     * Order by user_info.timezone_id
     * @param bool $ascending
     * @return static
     */
    public function orderByTimezoneId(bool $ascending = true): static
    {
        $this->order($this->alias . '.timezone_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_info.timezone_id
     * @param int $filterValue
     * @return static
     */
    public function filterTimezoneIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.timezone_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_info.timezone_id
     * @param int $filterValue
     * @return static
     */
    public function filterTimezoneIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.timezone_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_info.timezone_id
     * @param int $filterValue
     * @return static
     */
    public function filterTimezoneIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.timezone_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_info.timezone_id
     * @param int $filterValue
     * @return static
     */
    public function filterTimezoneIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.timezone_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_info.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by user_info.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_info.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_info.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_info.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_info.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_info.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by user_info.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_info.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_info.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_info.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_info.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_info.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by user_info.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_info.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_info.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_info.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_info.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_info.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by user_info.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_info.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_info.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_info.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_info.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_info.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by user_info.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by user_info.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_info.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_info.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_info.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_info.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
