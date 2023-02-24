<?php

namespace Sam\Lot\Video\Render;

use Sam\Application\Url\UrlAdvisorAwareTrait;
use Sam\Core\Constants\Responsive\LotDetailsBaseConstants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParser;
use Sam\CustomField\Lot\Load\LotCustomDataLoaderCreateTrait;
use Sam\File\FilePathHelperAwareTrait;

/**
 * Class LotVideoRenderer
 */
class LotVideoRenderer extends CustomizableClass
{
    use FilePathHelperAwareTrait;
    use LotCustomDataLoaderCreateTrait;
    use UrlAdvisorAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Build url to video at youtube
     * @param string $urlPath
     * @param array $params
     * @return string
     */
    public function buildUrl(string $urlPath, array $params = []): string
    {
        $youtubeId = $this->extractYoutubeIdFromUrlPath($urlPath);
        if (!$youtubeId) {
            return '';
        }

        $url = $this->assembleYoutubeUrl($youtubeId, $params);
        return $url;
    }

    /**
     * Render html tag with embedded video at youtube
     * @param int $lotCustomDataId
     * @param string $divId
     * @param string $divStyle
     * @return string
     */
    public function renderTag(int $lotCustomDataId, string $divId = '', string $divStyle = ''): string
    {
        $lotVideo = $this->createLotCustomDataLoader()->loadById($lotCustomDataId);
        if (!$lotVideo) {
            return '';
        }

        $urlPath = $this->buildUrl($lotVideo->Text);
        $embedUrlPath = $this->getFilePathHelper()->appendUrlPathWithMTime($urlPath);

        if ($divId) {
            $divId = "id=\"{$divId}\"";
        }
        if ($divStyle) {
            $divStyle = "style=\"{$divStyle}\"";
        }

        $youtubeWrapClass = LotDetailsBaseConstants::CLASS_BLK_YOUTUBE_WRAP;
        $html = <<<HTML
<div class="{$youtubeWrapClass}" {$divId} {$divStyle}>
    <iframe src="{$embedUrlPath}" class="yt-embed-frame" allowfullscreen sandbox="allow-scripts  allow-same-origin"></iframe>
</div>
HTML;
        return $html;
    }

    /**
     * @param string $urlPath
     * @param int $size
     * @return string
     */
    public function buildThumbnailUrl(string $urlPath, int $size = 1): string
    {
        $youtubeId = $this->extractYoutubeIdFromUrlPath($urlPath);
        $thumbnailUrl = 'http://img.youtube.com/vi/' . $youtubeId . '/' . ($size === 1 ? '1' : '0') . '.jpg';
        return $thumbnailUrl;
    }

    /**
     * @param string $youtubeId
     * @param array $params
     * @return string
     */
    protected function assembleYoutubeUrl(string $youtubeId, array $params): string
    {
        $scheme = $this->getUrlAdvisor()->detectScheme();
        $url = $scheme . '://www.youtube.com/embed/' . $youtubeId;
        $url = UrlParser::new()->replaceParams($url, $params);
        return $url;
    }

    /**
     * @param string $urlPath
     * @return string|null
     */
    protected function extractYoutubeIdFromUrlPath(string $urlPath): ?string
    {
        $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
        $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch)|(?:shorts))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';

        $youtubeId = null;
        if (preg_match($longUrlRegex, $urlPath, $matches)) {
            $youtubeId = $matches[count($matches) - 1];
        }

        if (preg_match($shortUrlRegex, $urlPath, $matches)) {
            $youtubeId = $matches[count($matches) - 1];
        }

        return $youtubeId;
    }
}
