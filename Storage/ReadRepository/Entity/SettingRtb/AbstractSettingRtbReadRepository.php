<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingRtb;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use SettingRtb;

/**
 * Abstract class AbstractSettingRtbReadRepository
 * @method SettingRtb[] loadEntities()
 * @method SettingRtb|null loadEntity()
 */
abstract class AbstractSettingRtbReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_SETTING_RTB;
    protected string $alias = Db::A_SETTING_RTB;

    /**
     * Filter by setting_rtb.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by setting_rtb.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by setting_rtb.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.switch_frame_seconds
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterSwitchFrameSeconds(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.switch_frame_seconds', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.switch_frame_seconds from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipSwitchFrameSeconds(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.switch_frame_seconds', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.switch_frame_seconds
     * @return static
     */
    public function groupBySwitchFrameSeconds(): static
    {
        $this->group($this->alias . '.switch_frame_seconds');
        return $this;
    }

    /**
     * Order by setting_rtb.switch_frame_seconds
     * @param bool $ascending
     * @return static
     */
    public function orderBySwitchFrameSeconds(bool $ascending = true): static
    {
        $this->order($this->alias . '.switch_frame_seconds', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.switch_frame_seconds
     * @param int $filterValue
     * @return static
     */
    public function filterSwitchFrameSecondsGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.switch_frame_seconds', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.switch_frame_seconds
     * @param int $filterValue
     * @return static
     */
    public function filterSwitchFrameSecondsGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.switch_frame_seconds', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.switch_frame_seconds
     * @param int $filterValue
     * @return static
     */
    public function filterSwitchFrameSecondsLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.switch_frame_seconds', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.switch_frame_seconds
     * @param int $filterValue
     * @return static
     */
    public function filterSwitchFrameSecondsLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.switch_frame_seconds', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.slideshow_projector_only
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterSlideshowProjectorOnly(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.slideshow_projector_only', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.slideshow_projector_only from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipSlideshowProjectorOnly(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.slideshow_projector_only', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.slideshow_projector_only
     * @return static
     */
    public function groupBySlideshowProjectorOnly(): static
    {
        $this->group($this->alias . '.slideshow_projector_only');
        return $this;
    }

    /**
     * Order by setting_rtb.slideshow_projector_only
     * @param bool $ascending
     * @return static
     */
    public function orderBySlideshowProjectorOnly(bool $ascending = true): static
    {
        $this->order($this->alias . '.slideshow_projector_only', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.slideshow_projector_only
     * @param bool $filterValue
     * @return static
     */
    public function filterSlideshowProjectorOnlyGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.slideshow_projector_only', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.slideshow_projector_only
     * @param bool $filterValue
     * @return static
     */
    public function filterSlideshowProjectorOnlyGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.slideshow_projector_only', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.slideshow_projector_only
     * @param bool $filterValue
     * @return static
     */
    public function filterSlideshowProjectorOnlyLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.slideshow_projector_only', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.slideshow_projector_only
     * @param bool $filterValue
     * @return static
     */
    public function filterSlideshowProjectorOnlyLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.slideshow_projector_only', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.default_image_preview
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterDefaultImagePreview(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.default_image_preview', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.default_image_preview from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipDefaultImagePreview(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.default_image_preview', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.default_image_preview
     * @return static
     */
    public function groupByDefaultImagePreview(): static
    {
        $this->group($this->alias . '.default_image_preview');
        return $this;
    }

    /**
     * Order by setting_rtb.default_image_preview
     * @param bool $ascending
     * @return static
     */
    public function orderByDefaultImagePreview(bool $ascending = true): static
    {
        $this->order($this->alias . '.default_image_preview', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.default_image_preview
     * @param int $filterValue
     * @return static
     */
    public function filterDefaultImagePreviewGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.default_image_preview', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.default_image_preview
     * @param int $filterValue
     * @return static
     */
    public function filterDefaultImagePreviewGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.default_image_preview', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.default_image_preview
     * @param int $filterValue
     * @return static
     */
    public function filterDefaultImagePreviewLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.default_image_preview', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.default_image_preview
     * @param int $filterValue
     * @return static
     */
    public function filterDefaultImagePreviewLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.default_image_preview', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.live_bidding_countdown
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLiveBiddingCountdown(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.live_bidding_countdown', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.live_bidding_countdown from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLiveBiddingCountdown(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.live_bidding_countdown', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.live_bidding_countdown
     * @return static
     */
    public function groupByLiveBiddingCountdown(): static
    {
        $this->group($this->alias . '.live_bidding_countdown');
        return $this;
    }

    /**
     * Order by setting_rtb.live_bidding_countdown
     * @param bool $ascending
     * @return static
     */
    public function orderByLiveBiddingCountdown(bool $ascending = true): static
    {
        $this->order($this->alias . '.live_bidding_countdown', $ascending);
        return $this;
    }

    /**
     * Filter setting_rtb.live_bidding_countdown by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLiveBiddingCountdown(string $filterValue): static
    {
        $this->like($this->alias . '.live_bidding_countdown', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_rtb.show_port_notice
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterShowPortNotice(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.show_port_notice', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.show_port_notice from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipShowPortNotice(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.show_port_notice', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.show_port_notice
     * @return static
     */
    public function groupByShowPortNotice(): static
    {
        $this->group($this->alias . '.show_port_notice');
        return $this;
    }

    /**
     * Order by setting_rtb.show_port_notice
     * @param bool $ascending
     * @return static
     */
    public function orderByShowPortNotice(bool $ascending = true): static
    {
        $this->order($this->alias . '.show_port_notice', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.show_port_notice
     * @param bool $filterValue
     * @return static
     */
    public function filterShowPortNoticeGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_port_notice', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.show_port_notice
     * @param bool $filterValue
     * @return static
     */
    public function filterShowPortNoticeGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_port_notice', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.show_port_notice
     * @param bool $filterValue
     * @return static
     */
    public function filterShowPortNoticeLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_port_notice', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.show_port_notice
     * @param bool $filterValue
     * @return static
     */
    public function filterShowPortNoticeLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_port_notice', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.place_bid_sound
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPlaceBidSound(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.place_bid_sound', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.place_bid_sound from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPlaceBidSound(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.place_bid_sound', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.place_bid_sound
     * @return static
     */
    public function groupByPlaceBidSound(): static
    {
        $this->group($this->alias . '.place_bid_sound');
        return $this;
    }

    /**
     * Order by setting_rtb.place_bid_sound
     * @param bool $ascending
     * @return static
     */
    public function orderByPlaceBidSound(bool $ascending = true): static
    {
        $this->order($this->alias . '.place_bid_sound', $ascending);
        return $this;
    }

    /**
     * Filter setting_rtb.place_bid_sound by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePlaceBidSound(string $filterValue): static
    {
        $this->like($this->alias . '.place_bid_sound', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_rtb.bid_accepted_sound
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBidAcceptedSound(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bid_accepted_sound', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.bid_accepted_sound from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBidAcceptedSound(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bid_accepted_sound', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.bid_accepted_sound
     * @return static
     */
    public function groupByBidAcceptedSound(): static
    {
        $this->group($this->alias . '.bid_accepted_sound');
        return $this;
    }

    /**
     * Order by setting_rtb.bid_accepted_sound
     * @param bool $ascending
     * @return static
     */
    public function orderByBidAcceptedSound(bool $ascending = true): static
    {
        $this->order($this->alias . '.bid_accepted_sound', $ascending);
        return $this;
    }

    /**
     * Filter setting_rtb.bid_accepted_sound by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeBidAcceptedSound(string $filterValue): static
    {
        $this->like($this->alias . '.bid_accepted_sound', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_rtb.out_bid_sound
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterOutBidSound(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.out_bid_sound', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.out_bid_sound from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipOutBidSound(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.out_bid_sound', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.out_bid_sound
     * @return static
     */
    public function groupByOutBidSound(): static
    {
        $this->group($this->alias . '.out_bid_sound');
        return $this;
    }

    /**
     * Order by setting_rtb.out_bid_sound
     * @param bool $ascending
     * @return static
     */
    public function orderByOutBidSound(bool $ascending = true): static
    {
        $this->order($this->alias . '.out_bid_sound', $ascending);
        return $this;
    }

    /**
     * Filter setting_rtb.out_bid_sound by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeOutBidSound(string $filterValue): static
    {
        $this->like($this->alias . '.out_bid_sound', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_rtb.sold_not_won_sound
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSoldNotWonSound(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.sold_not_won_sound', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.sold_not_won_sound from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSoldNotWonSound(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.sold_not_won_sound', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.sold_not_won_sound
     * @return static
     */
    public function groupBySoldNotWonSound(): static
    {
        $this->group($this->alias . '.sold_not_won_sound');
        return $this;
    }

    /**
     * Order by setting_rtb.sold_not_won_sound
     * @param bool $ascending
     * @return static
     */
    public function orderBySoldNotWonSound(bool $ascending = true): static
    {
        $this->order($this->alias . '.sold_not_won_sound', $ascending);
        return $this;
    }

    /**
     * Filter setting_rtb.sold_not_won_sound by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSoldNotWonSound(string $filterValue): static
    {
        $this->like($this->alias . '.sold_not_won_sound', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_rtb.sold_won_sound
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSoldWonSound(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.sold_won_sound', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.sold_won_sound from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSoldWonSound(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.sold_won_sound', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.sold_won_sound
     * @return static
     */
    public function groupBySoldWonSound(): static
    {
        $this->group($this->alias . '.sold_won_sound');
        return $this;
    }

    /**
     * Order by setting_rtb.sold_won_sound
     * @param bool $ascending
     * @return static
     */
    public function orderBySoldWonSound(bool $ascending = true): static
    {
        $this->order($this->alias . '.sold_won_sound', $ascending);
        return $this;
    }

    /**
     * Filter setting_rtb.sold_won_sound by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSoldWonSound(string $filterValue): static
    {
        $this->like($this->alias . '.sold_won_sound', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_rtb.passed_sound
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPassedSound(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.passed_sound', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.passed_sound from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPassedSound(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.passed_sound', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.passed_sound
     * @return static
     */
    public function groupByPassedSound(): static
    {
        $this->group($this->alias . '.passed_sound');
        return $this;
    }

    /**
     * Order by setting_rtb.passed_sound
     * @param bool $ascending
     * @return static
     */
    public function orderByPassedSound(bool $ascending = true): static
    {
        $this->order($this->alias . '.passed_sound', $ascending);
        return $this;
    }

    /**
     * Filter setting_rtb.passed_sound by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePassedSound(string $filterValue): static
    {
        $this->like($this->alias . '.passed_sound', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_rtb.fair_warning_sound
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFairWarningSound(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.fair_warning_sound', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.fair_warning_sound from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFairWarningSound(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.fair_warning_sound', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.fair_warning_sound
     * @return static
     */
    public function groupByFairWarningSound(): static
    {
        $this->group($this->alias . '.fair_warning_sound');
        return $this;
    }

    /**
     * Order by setting_rtb.fair_warning_sound
     * @param bool $ascending
     * @return static
     */
    public function orderByFairWarningSound(bool $ascending = true): static
    {
        $this->order($this->alias . '.fair_warning_sound', $ascending);
        return $this;
    }

    /**
     * Filter setting_rtb.fair_warning_sound by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeFairWarningSound(string $filterValue): static
    {
        $this->like($this->alias . '.fair_warning_sound', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_rtb.online_bid_incoming_on_admin_sound
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterOnlineBidIncomingOnAdminSound(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.online_bid_incoming_on_admin_sound', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.online_bid_incoming_on_admin_sound from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipOnlineBidIncomingOnAdminSound(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.online_bid_incoming_on_admin_sound', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.online_bid_incoming_on_admin_sound
     * @return static
     */
    public function groupByOnlineBidIncomingOnAdminSound(): static
    {
        $this->group($this->alias . '.online_bid_incoming_on_admin_sound');
        return $this;
    }

    /**
     * Order by setting_rtb.online_bid_incoming_on_admin_sound
     * @param bool $ascending
     * @return static
     */
    public function orderByOnlineBidIncomingOnAdminSound(bool $ascending = true): static
    {
        $this->order($this->alias . '.online_bid_incoming_on_admin_sound', $ascending);
        return $this;
    }

    /**
     * Filter setting_rtb.online_bid_incoming_on_admin_sound by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeOnlineBidIncomingOnAdminSound(string $filterValue): static
    {
        $this->like($this->alias . '.online_bid_incoming_on_admin_sound', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_rtb.bid_sound
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBidSound(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bid_sound', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.bid_sound from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBidSound(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bid_sound', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.bid_sound
     * @return static
     */
    public function groupByBidSound(): static
    {
        $this->group($this->alias . '.bid_sound');
        return $this;
    }

    /**
     * Order by setting_rtb.bid_sound
     * @param bool $ascending
     * @return static
     */
    public function orderByBidSound(bool $ascending = true): static
    {
        $this->order($this->alias . '.bid_sound', $ascending);
        return $this;
    }

    /**
     * Filter setting_rtb.bid_sound by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeBidSound(string $filterValue): static
    {
        $this->like($this->alias . '.bid_sound', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_rtb.onlinebid_button_info
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterOnlinebidButtonInfo(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.onlinebid_button_info', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.onlinebid_button_info from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipOnlinebidButtonInfo(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.onlinebid_button_info', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.onlinebid_button_info
     * @return static
     */
    public function groupByOnlinebidButtonInfo(): static
    {
        $this->group($this->alias . '.onlinebid_button_info');
        return $this;
    }

    /**
     * Order by setting_rtb.onlinebid_button_info
     * @param bool $ascending
     * @return static
     */
    public function orderByOnlinebidButtonInfo(bool $ascending = true): static
    {
        $this->order($this->alias . '.onlinebid_button_info', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.onlinebid_button_info
     * @param int $filterValue
     * @return static
     */
    public function filterOnlinebidButtonInfoGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.onlinebid_button_info', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.onlinebid_button_info
     * @param int $filterValue
     * @return static
     */
    public function filterOnlinebidButtonInfoGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.onlinebid_button_info', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.onlinebid_button_info
     * @param int $filterValue
     * @return static
     */
    public function filterOnlinebidButtonInfoLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.onlinebid_button_info', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.onlinebid_button_info
     * @param int $filterValue
     * @return static
     */
    public function filterOnlinebidButtonInfoLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.onlinebid_button_info', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.live_chat
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterLiveChat(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.live_chat', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.live_chat from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipLiveChat(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.live_chat', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.live_chat
     * @return static
     */
    public function groupByLiveChat(): static
    {
        $this->group($this->alias . '.live_chat');
        return $this;
    }

    /**
     * Order by setting_rtb.live_chat
     * @param bool $ascending
     * @return static
     */
    public function orderByLiveChat(bool $ascending = true): static
    {
        $this->order($this->alias . '.live_chat', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.live_chat
     * @param bool $filterValue
     * @return static
     */
    public function filterLiveChatGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.live_chat', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.live_chat
     * @param bool $filterValue
     * @return static
     */
    public function filterLiveChatGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.live_chat', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.live_chat
     * @param bool $filterValue
     * @return static
     */
    public function filterLiveChatLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.live_chat', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.live_chat
     * @param bool $filterValue
     * @return static
     */
    public function filterLiveChatLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.live_chat', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.live_chat_view_all
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterLiveChatViewAll(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.live_chat_view_all', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.live_chat_view_all from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipLiveChatViewAll(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.live_chat_view_all', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.live_chat_view_all
     * @return static
     */
    public function groupByLiveChatViewAll(): static
    {
        $this->group($this->alias . '.live_chat_view_all');
        return $this;
    }

    /**
     * Order by setting_rtb.live_chat_view_all
     * @param bool $ascending
     * @return static
     */
    public function orderByLiveChatViewAll(bool $ascending = true): static
    {
        $this->order($this->alias . '.live_chat_view_all', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.live_chat_view_all
     * @param bool $filterValue
     * @return static
     */
    public function filterLiveChatViewAllGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.live_chat_view_all', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.live_chat_view_all
     * @param bool $filterValue
     * @return static
     */
    public function filterLiveChatViewAllGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.live_chat_view_all', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.live_chat_view_all
     * @param bool $filterValue
     * @return static
     */
    public function filterLiveChatViewAllLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.live_chat_view_all', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.live_chat_view_all
     * @param bool $filterValue
     * @return static
     */
    public function filterLiveChatViewAllLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.live_chat_view_all', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.clear_message_center
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterClearMessageCenter(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.clear_message_center', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.clear_message_center from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipClearMessageCenter(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.clear_message_center', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.clear_message_center
     * @return static
     */
    public function groupByClearMessageCenter(): static
    {
        $this->group($this->alias . '.clear_message_center');
        return $this;
    }

    /**
     * Order by setting_rtb.clear_message_center
     * @param bool $ascending
     * @return static
     */
    public function orderByClearMessageCenter(bool $ascending = true): static
    {
        $this->order($this->alias . '.clear_message_center', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.clear_message_center
     * @param bool $filterValue
     * @return static
     */
    public function filterClearMessageCenterGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.clear_message_center', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.clear_message_center
     * @param bool $filterValue
     * @return static
     */
    public function filterClearMessageCenterGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.clear_message_center', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.clear_message_center
     * @param bool $filterValue
     * @return static
     */
    public function filterClearMessageCenterLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.clear_message_center', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.clear_message_center
     * @param bool $filterValue
     * @return static
     */
    public function filterClearMessageCenterLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.clear_message_center', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.twenty_messages_max
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterTwentyMessagesMax(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.twenty_messages_max', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.twenty_messages_max from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipTwentyMessagesMax(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.twenty_messages_max', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.twenty_messages_max
     * @return static
     */
    public function groupByTwentyMessagesMax(): static
    {
        $this->group($this->alias . '.twenty_messages_max');
        return $this;
    }

    /**
     * Order by setting_rtb.twenty_messages_max
     * @param bool $ascending
     * @return static
     */
    public function orderByTwentyMessagesMax(bool $ascending = true): static
    {
        $this->order($this->alias . '.twenty_messages_max', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.twenty_messages_max
     * @param bool $filterValue
     * @return static
     */
    public function filterTwentyMessagesMaxGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.twenty_messages_max', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.twenty_messages_max
     * @param bool $filterValue
     * @return static
     */
    public function filterTwentyMessagesMaxGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.twenty_messages_max', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.twenty_messages_max
     * @param bool $filterValue
     * @return static
     */
    public function filterTwentyMessagesMaxLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.twenty_messages_max', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.twenty_messages_max
     * @param bool $filterValue
     * @return static
     */
    public function filterTwentyMessagesMaxLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.twenty_messages_max', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.clear_message_center_log
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterClearMessageCenterLog(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.clear_message_center_log', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.clear_message_center_log from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipClearMessageCenterLog(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.clear_message_center_log', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.clear_message_center_log
     * @return static
     */
    public function groupByClearMessageCenterLog(): static
    {
        $this->group($this->alias . '.clear_message_center_log');
        return $this;
    }

    /**
     * Order by setting_rtb.clear_message_center_log
     * @param bool $ascending
     * @return static
     */
    public function orderByClearMessageCenterLog(bool $ascending = true): static
    {
        $this->order($this->alias . '.clear_message_center_log', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.clear_message_center_log
     * @param bool $filterValue
     * @return static
     */
    public function filterClearMessageCenterLogGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.clear_message_center_log', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.clear_message_center_log
     * @param bool $filterValue
     * @return static
     */
    public function filterClearMessageCenterLogGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.clear_message_center_log', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.clear_message_center_log
     * @param bool $filterValue
     * @return static
     */
    public function filterClearMessageCenterLogLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.clear_message_center_log', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.clear_message_center_log
     * @param bool $filterValue
     * @return static
     */
    public function filterClearMessageCenterLogLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.clear_message_center_log', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.multi_currency
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterMultiCurrency(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.multi_currency', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.multi_currency from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipMultiCurrency(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.multi_currency', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.multi_currency
     * @return static
     */
    public function groupByMultiCurrency(): static
    {
        $this->group($this->alias . '.multi_currency');
        return $this;
    }

    /**
     * Order by setting_rtb.multi_currency
     * @param bool $ascending
     * @return static
     */
    public function orderByMultiCurrency(bool $ascending = true): static
    {
        $this->order($this->alias . '.multi_currency', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.multi_currency
     * @param bool $filterValue
     * @return static
     */
    public function filterMultiCurrencyGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.multi_currency', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.multi_currency
     * @param bool $filterValue
     * @return static
     */
    public function filterMultiCurrencyGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.multi_currency', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.multi_currency
     * @param bool $filterValue
     * @return static
     */
    public function filterMultiCurrencyLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.multi_currency', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.multi_currency
     * @param bool $filterValue
     * @return static
     */
    public function filterMultiCurrencyLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.multi_currency', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.delay_sold_item
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterDelaySoldItem(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.delay_sold_item', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.delay_sold_item from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipDelaySoldItem(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.delay_sold_item', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.delay_sold_item
     * @return static
     */
    public function groupByDelaySoldItem(): static
    {
        $this->group($this->alias . '.delay_sold_item');
        return $this;
    }

    /**
     * Order by setting_rtb.delay_sold_item
     * @param bool $ascending
     * @return static
     */
    public function orderByDelaySoldItem(bool $ascending = true): static
    {
        $this->order($this->alias . '.delay_sold_item', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.delay_sold_item
     * @param int $filterValue
     * @return static
     */
    public function filterDelaySoldItemGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.delay_sold_item', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.delay_sold_item
     * @param int $filterValue
     * @return static
     */
    public function filterDelaySoldItemGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.delay_sold_item', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.delay_sold_item
     * @param int $filterValue
     * @return static
     */
    public function filterDelaySoldItemLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.delay_sold_item', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.delay_sold_item
     * @param int $filterValue
     * @return static
     */
    public function filterDelaySoldItemLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.delay_sold_item', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.delay_block_sell
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterDelayBlockSell(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.delay_block_sell', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.delay_block_sell from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipDelayBlockSell(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.delay_block_sell', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.delay_block_sell
     * @return static
     */
    public function groupByDelayBlockSell(): static
    {
        $this->group($this->alias . '.delay_block_sell');
        return $this;
    }

    /**
     * Order by setting_rtb.delay_block_sell
     * @param bool $ascending
     * @return static
     */
    public function orderByDelayBlockSell(bool $ascending = true): static
    {
        $this->order($this->alias . '.delay_block_sell', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.delay_block_sell
     * @param int $filterValue
     * @return static
     */
    public function filterDelayBlockSellGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.delay_block_sell', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.delay_block_sell
     * @param int $filterValue
     * @return static
     */
    public function filterDelayBlockSellGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.delay_block_sell', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.delay_block_sell
     * @param int $filterValue
     * @return static
     */
    public function filterDelayBlockSellLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.delay_block_sell', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.delay_block_sell
     * @param int $filterValue
     * @return static
     */
    public function filterDelayBlockSellLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.delay_block_sell', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.delay_after_bid_accepted
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterDelayAfterBidAccepted(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.delay_after_bid_accepted', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.delay_after_bid_accepted from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipDelayAfterBidAccepted(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.delay_after_bid_accepted', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.delay_after_bid_accepted
     * @return static
     */
    public function groupByDelayAfterBidAccepted(): static
    {
        $this->group($this->alias . '.delay_after_bid_accepted');
        return $this;
    }

    /**
     * Order by setting_rtb.delay_after_bid_accepted
     * @param bool $ascending
     * @return static
     */
    public function orderByDelayAfterBidAccepted(bool $ascending = true): static
    {
        $this->order($this->alias . '.delay_after_bid_accepted', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.delay_after_bid_accepted
     * @param int $filterValue
     * @return static
     */
    public function filterDelayAfterBidAcceptedGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.delay_after_bid_accepted', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.delay_after_bid_accepted
     * @param int $filterValue
     * @return static
     */
    public function filterDelayAfterBidAcceptedGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.delay_after_bid_accepted', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.delay_after_bid_accepted
     * @param int $filterValue
     * @return static
     */
    public function filterDelayAfterBidAcceptedLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.delay_after_bid_accepted', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.delay_after_bid_accepted
     * @param int $filterValue
     * @return static
     */
    public function filterDelayAfterBidAcceptedLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.delay_after_bid_accepted', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.enable_consignor_company_clerking
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterEnableConsignorCompanyClerking(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.enable_consignor_company_clerking', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.enable_consignor_company_clerking from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipEnableConsignorCompanyClerking(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.enable_consignor_company_clerking', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.enable_consignor_company_clerking
     * @return static
     */
    public function groupByEnableConsignorCompanyClerking(): static
    {
        $this->group($this->alias . '.enable_consignor_company_clerking');
        return $this;
    }

    /**
     * Order by setting_rtb.enable_consignor_company_clerking
     * @param bool $ascending
     * @return static
     */
    public function orderByEnableConsignorCompanyClerking(bool $ascending = true): static
    {
        $this->order($this->alias . '.enable_consignor_company_clerking', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.enable_consignor_company_clerking
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableConsignorCompanyClerkingGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_consignor_company_clerking', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.enable_consignor_company_clerking
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableConsignorCompanyClerkingGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_consignor_company_clerking', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.enable_consignor_company_clerking
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableConsignorCompanyClerkingLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_consignor_company_clerking', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.enable_consignor_company_clerking
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableConsignorCompanyClerkingLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_consignor_company_clerking', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.floor_bidders_from_dropdown
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterFloorBiddersFromDropdown(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.floor_bidders_from_dropdown', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.floor_bidders_from_dropdown from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipFloorBiddersFromDropdown(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.floor_bidders_from_dropdown', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.floor_bidders_from_dropdown
     * @return static
     */
    public function groupByFloorBiddersFromDropdown(): static
    {
        $this->group($this->alias . '.floor_bidders_from_dropdown');
        return $this;
    }

    /**
     * Order by setting_rtb.floor_bidders_from_dropdown
     * @param bool $ascending
     * @return static
     */
    public function orderByFloorBiddersFromDropdown(bool $ascending = true): static
    {
        $this->order($this->alias . '.floor_bidders_from_dropdown', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.floor_bidders_from_dropdown
     * @param bool $filterValue
     * @return static
     */
    public function filterFloorBiddersFromDropdownGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.floor_bidders_from_dropdown', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.floor_bidders_from_dropdown
     * @param bool $filterValue
     * @return static
     */
    public function filterFloorBiddersFromDropdownGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.floor_bidders_from_dropdown', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.floor_bidders_from_dropdown
     * @param bool $filterValue
     * @return static
     */
    public function filterFloorBiddersFromDropdownLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.floor_bidders_from_dropdown', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.floor_bidders_from_dropdown
     * @param bool $filterValue
     * @return static
     */
    public function filterFloorBiddersFromDropdownLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.floor_bidders_from_dropdown', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.buy_now_restriction
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBuyNowRestriction(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.buy_now_restriction', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.buy_now_restriction from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBuyNowRestriction(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.buy_now_restriction', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.buy_now_restriction
     * @return static
     */
    public function groupByBuyNowRestriction(): static
    {
        $this->group($this->alias . '.buy_now_restriction');
        return $this;
    }

    /**
     * Order by setting_rtb.buy_now_restriction
     * @param bool $ascending
     * @return static
     */
    public function orderByBuyNowRestriction(bool $ascending = true): static
    {
        $this->order($this->alias . '.buy_now_restriction', $ascending);
        return $this;
    }

    /**
     * Filter setting_rtb.buy_now_restriction by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeBuyNowRestriction(string $filterValue): static
    {
        $this->like($this->alias . '.buy_now_restriction', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_rtb.suggested_starting_bid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterSuggestedStartingBid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.suggested_starting_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.suggested_starting_bid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipSuggestedStartingBid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.suggested_starting_bid', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.suggested_starting_bid
     * @return static
     */
    public function groupBySuggestedStartingBid(): static
    {
        $this->group($this->alias . '.suggested_starting_bid');
        return $this;
    }

    /**
     * Order by setting_rtb.suggested_starting_bid
     * @param bool $ascending
     * @return static
     */
    public function orderBySuggestedStartingBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.suggested_starting_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.suggested_starting_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterSuggestedStartingBidGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.suggested_starting_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.suggested_starting_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterSuggestedStartingBidGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.suggested_starting_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.suggested_starting_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterSuggestedStartingBidLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.suggested_starting_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.suggested_starting_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterSuggestedStartingBidLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.suggested_starting_bid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.auto_create_floor_bidder_record
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAutoCreateFloorBidderRecord(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auto_create_floor_bidder_record', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.auto_create_floor_bidder_record from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAutoCreateFloorBidderRecord(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auto_create_floor_bidder_record', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.auto_create_floor_bidder_record
     * @return static
     */
    public function groupByAutoCreateFloorBidderRecord(): static
    {
        $this->group($this->alias . '.auto_create_floor_bidder_record');
        return $this;
    }

    /**
     * Order by setting_rtb.auto_create_floor_bidder_record
     * @param bool $ascending
     * @return static
     */
    public function orderByAutoCreateFloorBidderRecord(bool $ascending = true): static
    {
        $this->order($this->alias . '.auto_create_floor_bidder_record', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.auto_create_floor_bidder_record
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoCreateFloorBidderRecordGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_create_floor_bidder_record', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.auto_create_floor_bidder_record
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoCreateFloorBidderRecordGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_create_floor_bidder_record', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.auto_create_floor_bidder_record
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoCreateFloorBidderRecordLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_create_floor_bidder_record', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.auto_create_floor_bidder_record
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoCreateFloorBidderRecordLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_create_floor_bidder_record', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.allow_bidding_during_start_gap_hybrid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAllowBiddingDuringStartGapHybrid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.allow_bidding_during_start_gap_hybrid', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.allow_bidding_during_start_gap_hybrid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAllowBiddingDuringStartGapHybrid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.allow_bidding_during_start_gap_hybrid', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.allow_bidding_during_start_gap_hybrid
     * @return static
     */
    public function groupByAllowBiddingDuringStartGapHybrid(): static
    {
        $this->group($this->alias . '.allow_bidding_during_start_gap_hybrid');
        return $this;
    }

    /**
     * Order by setting_rtb.allow_bidding_during_start_gap_hybrid
     * @param bool $ascending
     * @return static
     */
    public function orderByAllowBiddingDuringStartGapHybrid(bool $ascending = true): static
    {
        $this->order($this->alias . '.allow_bidding_during_start_gap_hybrid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.allow_bidding_during_start_gap_hybrid
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowBiddingDuringStartGapHybridGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_bidding_during_start_gap_hybrid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.allow_bidding_during_start_gap_hybrid
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowBiddingDuringStartGapHybridGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_bidding_during_start_gap_hybrid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.allow_bidding_during_start_gap_hybrid
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowBiddingDuringStartGapHybridLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_bidding_during_start_gap_hybrid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.allow_bidding_during_start_gap_hybrid
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowBiddingDuringStartGapHybridLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_bidding_during_start_gap_hybrid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.reset_timer_on_undo_hybrid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterResetTimerOnUndoHybrid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.reset_timer_on_undo_hybrid', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.reset_timer_on_undo_hybrid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipResetTimerOnUndoHybrid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.reset_timer_on_undo_hybrid', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.reset_timer_on_undo_hybrid
     * @return static
     */
    public function groupByResetTimerOnUndoHybrid(): static
    {
        $this->group($this->alias . '.reset_timer_on_undo_hybrid');
        return $this;
    }

    /**
     * Order by setting_rtb.reset_timer_on_undo_hybrid
     * @param bool $ascending
     * @return static
     */
    public function orderByResetTimerOnUndoHybrid(bool $ascending = true): static
    {
        $this->order($this->alias . '.reset_timer_on_undo_hybrid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.reset_timer_on_undo_hybrid
     * @param bool $filterValue
     * @return static
     */
    public function filterResetTimerOnUndoHybridGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reset_timer_on_undo_hybrid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.reset_timer_on_undo_hybrid
     * @param bool $filterValue
     * @return static
     */
    public function filterResetTimerOnUndoHybridGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reset_timer_on_undo_hybrid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.reset_timer_on_undo_hybrid
     * @param bool $filterValue
     * @return static
     */
    public function filterResetTimerOnUndoHybridLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reset_timer_on_undo_hybrid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.reset_timer_on_undo_hybrid
     * @param bool $filterValue
     * @return static
     */
    public function filterResetTimerOnUndoHybridLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reset_timer_on_undo_hybrid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.extend_time_hybrid
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterExtendTimeHybrid(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.extend_time_hybrid', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.extend_time_hybrid from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipExtendTimeHybrid(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.extend_time_hybrid', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.extend_time_hybrid
     * @return static
     */
    public function groupByExtendTimeHybrid(): static
    {
        $this->group($this->alias . '.extend_time_hybrid');
        return $this;
    }

    /**
     * Order by setting_rtb.extend_time_hybrid
     * @param bool $ascending
     * @return static
     */
    public function orderByExtendTimeHybrid(bool $ascending = true): static
    {
        $this->order($this->alias . '.extend_time_hybrid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.extend_time_hybrid
     * @param int $filterValue
     * @return static
     */
    public function filterExtendTimeHybridGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.extend_time_hybrid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.extend_time_hybrid
     * @param int $filterValue
     * @return static
     */
    public function filterExtendTimeHybridGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.extend_time_hybrid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.extend_time_hybrid
     * @param int $filterValue
     * @return static
     */
    public function filterExtendTimeHybridLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.extend_time_hybrid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.extend_time_hybrid
     * @param int $filterValue
     * @return static
     */
    public function filterExtendTimeHybridLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.extend_time_hybrid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.pending_action_timeout_hybrid
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterPendingActionTimeoutHybrid(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.pending_action_timeout_hybrid', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.pending_action_timeout_hybrid from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipPendingActionTimeoutHybrid(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.pending_action_timeout_hybrid', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.pending_action_timeout_hybrid
     * @return static
     */
    public function groupByPendingActionTimeoutHybrid(): static
    {
        $this->group($this->alias . '.pending_action_timeout_hybrid');
        return $this;
    }

    /**
     * Order by setting_rtb.pending_action_timeout_hybrid
     * @param bool $ascending
     * @return static
     */
    public function orderByPendingActionTimeoutHybrid(bool $ascending = true): static
    {
        $this->order($this->alias . '.pending_action_timeout_hybrid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.pending_action_timeout_hybrid
     * @param int $filterValue
     * @return static
     */
    public function filterPendingActionTimeoutHybridGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pending_action_timeout_hybrid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.pending_action_timeout_hybrid
     * @param int $filterValue
     * @return static
     */
    public function filterPendingActionTimeoutHybridGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pending_action_timeout_hybrid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.pending_action_timeout_hybrid
     * @param int $filterValue
     * @return static
     */
    public function filterPendingActionTimeoutHybridLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pending_action_timeout_hybrid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.pending_action_timeout_hybrid
     * @param int $filterValue
     * @return static
     */
    public function filterPendingActionTimeoutHybridLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pending_action_timeout_hybrid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.fair_warnings_hybrid
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFairWarningsHybrid(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.fair_warnings_hybrid', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.fair_warnings_hybrid from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFairWarningsHybrid(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.fair_warnings_hybrid', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.fair_warnings_hybrid
     * @return static
     */
    public function groupByFairWarningsHybrid(): static
    {
        $this->group($this->alias . '.fair_warnings_hybrid');
        return $this;
    }

    /**
     * Order by setting_rtb.fair_warnings_hybrid
     * @param bool $ascending
     * @return static
     */
    public function orderByFairWarningsHybrid(bool $ascending = true): static
    {
        $this->order($this->alias . '.fair_warnings_hybrid', $ascending);
        return $this;
    }

    /**
     * Filter setting_rtb.fair_warnings_hybrid by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeFairWarningsHybrid(string $filterValue): static
    {
        $this->like($this->alias . '.fair_warnings_hybrid', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_rtb.lot_start_gap_time_hybrid
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterLotStartGapTimeHybrid(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_start_gap_time_hybrid', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.lot_start_gap_time_hybrid from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipLotStartGapTimeHybrid(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_start_gap_time_hybrid', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.lot_start_gap_time_hybrid
     * @return static
     */
    public function groupByLotStartGapTimeHybrid(): static
    {
        $this->group($this->alias . '.lot_start_gap_time_hybrid');
        return $this;
    }

    /**
     * Order by setting_rtb.lot_start_gap_time_hybrid
     * @param bool $ascending
     * @return static
     */
    public function orderByLotStartGapTimeHybrid(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_start_gap_time_hybrid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.lot_start_gap_time_hybrid
     * @param int $filterValue
     * @return static
     */
    public function filterLotStartGapTimeHybridGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_start_gap_time_hybrid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.lot_start_gap_time_hybrid
     * @param int $filterValue
     * @return static
     */
    public function filterLotStartGapTimeHybridGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_start_gap_time_hybrid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.lot_start_gap_time_hybrid
     * @param int $filterValue
     * @return static
     */
    public function filterLotStartGapTimeHybridLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_start_gap_time_hybrid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.lot_start_gap_time_hybrid
     * @param int $filterValue
     * @return static
     */
    public function filterLotStartGapTimeHybridLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_start_gap_time_hybrid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by setting_rtb.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by setting_rtb.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by setting_rtb.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by setting_rtb.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_rtb.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by setting_rtb.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by setting_rtb.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_rtb.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_rtb.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_rtb.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_rtb.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
