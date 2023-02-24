<?php
/**
 * SAM-5752: Rtb connected user list builder
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 24, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\User\Connected;

use Sam\Application\Url\Build\Config\User\AdminUserEditUrlConfig;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Constants;
use Sam\Rtb\User\Connected\Load\DataLoaderCreateTrait;
use Sam\Rtb\User\Connected\Load\RtbConnectedUserDto;
use Sam\Rtb\User\Connected\Render\RendererCreateTrait;

/**
 * Class RtbConnectedUserListBuilder
 * @package Sam\Rtb\User\Connected
 */
class RtbConnectedUserListBuilder extends CustomizableClass
{
    use BidderNumPaddingAwareTrait;
    use DataLoaderCreateTrait;
    use RendererCreateTrait;
    use UrlBuilderAwareTrait;

    private const CLASS_REGISTERED = 'registered';
    private const CLASS_UNREGISTERED = 'unregistered';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $auctionId
     * @return array [
     *   'Count' - connected user count.
     *   'List' - <br /> separated list of connected user is rendered in squad block at right bottom of clerk console.
     *   'Bidders' - array of data for "to bidder" selection drop-down of message sending.
     * ]
     */
    public function build(int $auctionId = null): array
    {
        $bidders = [
            '' => [
                'lbl' => 'All',
                'rel' => '',
                'bidno' => ''
            ]
        ];
        if (!$auctionId) {
            return [
                'Count' => 0,
                'List' => '',
                'Bidders' => $bidders
            ];
        }

        $links = [];
        $userDtos = $this->createDataLoader()->load($auctionId);
        foreach ($userDtos as $dto) {
            $bidderNo = $this->makeBidderNo($dto);
            $links[] = $this->renderBidderLink($dto, $bidderNo);
            $bidders[$dto->userId] = $this->buildBidderInfo($dto, $bidderNo);
        }

        return [
            'Count' => count($userDtos),
            'List' => implode('<br />', $links),
            'Bidders' => $bidders
        ];
    }

    /**
     * @param RtbConnectedUserDto $dto
     * @param string $bidderNo
     * @return string
     */
    protected function renderBidderLink(RtbConnectedUserDto $dto, string $bidderNo): string
    {
        $url = $this->getUrlBuilder()->build(
            AdminUserEditUrlConfig::new()->forWeb($dto->userId)
        );
        $renderLinkAttributes = [
            'uid' => $dto->userId,
            'bidno' => $bidderNo,
            'class' => $this->isBidderRegistered($dto) ? self::CLASS_REGISTERED : self::CLASS_UNREGISTERED
        ];
        $title = ee($this->makeUserLineByDto($dto, $bidderNo));
        $output = $this->createRenderer()->makeLink($url, $title, $renderLinkAttributes);
        return $output;
    }

    /**
     * @param RtbConnectedUserDto $dto
     * @param string $bidderNo
     * @return string
     */
    protected function makeUserLineByDto(RtbConnectedUserDto $dto, string $bidderNo): string
    {
        return $this->createRenderer()->makeUserLine(
            $bidderNo,
            $dto->firstName,
            $dto->lastName,
            $dto->username,
            $dto->companyName
        );
    }

    /**
     * @param RtbConnectedUserDto $dto
     * @param string $bidderNo
     * @return array
     */
    protected function buildBidderInfo(RtbConnectedUserDto $dto, string $bidderNo): array
    {
        return [
            'lbl' => $this->makeUserLineByDto($dto, $bidderNo),
            'rel' => sprintf('@%s(%s)', $dto->username, $bidderNo),
            'bidno' => $bidderNo,
        ];
    }

    /**
     * @param RtbConnectedUserDto $dto
     * @return bool
     */
    protected function isBidderRegistered(RtbConnectedUserDto $dto): bool
    {
        return (bool)$dto->bidderNum;
    }

    /**
     * @param RtbConnectedUserDto $dto
     * @return string
     */
    protected function makeBidderNo(RtbConnectedUserDto $dto): string
    {
        if ($this->isBidderRegistered($dto)) {
            return $this->getBidderNumberPadding()->clear($dto->bidderNum);
        }
        return Constants\Rtb::CUL_ABSENT_BIDDER_NO;
    }
}
