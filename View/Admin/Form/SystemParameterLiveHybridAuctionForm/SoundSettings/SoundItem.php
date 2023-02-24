<?php
/**
 * SAM-10008: Move sections' logic to separate Panel classes at Manage settings system parameters live/hybrid auction page (/admin/manage-system-parameter/live-hybrid-auction)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           01-07, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SystemParameterLiveHybridAuctionForm\SoundSettings;

use Qform_FileAsset;
use Sam\Application\Url\Build\Config\Asset\SoundUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class SoundItem
 * @package Sam\View\Admin\Form\SystemParameterLiveHybridAuctionForm\SoundSettings
 */
class SoundItem extends CustomizableClass
{
    use UrlBuilderAwareTrait;

    protected int $type;
    protected string $title;
    protected string $soundUrl;
    protected Qform_FileAsset $control;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(int $type, string $title, Qform_FileAsset $control, int $systemAccountId): static
    {
        $this->type = $type;
        $this->title = $title;
        $this->control = $control;
        $this->soundUrl = $this->buildSoundUrl($this->type, $systemAccountId);

        return $this;
    }

    /** @return int */
    public function getType(): int
    {
        return $this->type;
    }

    /** @return string */
    public function getTitle(): string
    {
        return $this->title;
    }

    /** @return string */
    public function getSoundUrl(): string
    {
        return $this->soundUrl;
    }

    /** @return Qform_FileAsset */
    public function getControl(): Qform_FileAsset
    {
        return $this->control;
    }

    protected function buildSoundUrl(int $type, int $systemAccountId): string
    {
        return $this->getUrlBuilder()->build(SoundUrlConfig::new()->forWeb($type, $systemAccountId));
    }
}
