<?php
/**
 * SAM-5853: Project path resolver
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           26/2/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Path;

use Sam\Core\Application\Ui\Ui;
use Sam\Core\Service\Dummy\DummyServiceTrait;

/**
 * Class PathProvider
 * @package
 */
class DummyPathResolver extends PathResolver
{
    use DummyServiceTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Should be constructed with system root absolute path
     * @param string|null $sysRoot
     * @return PathResolver
     */
    public function construct(?string $sysRoot = null): PathResolver
    {
        return $this;
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function sysRoot(bool $isCustom = false): string
    {
        return '/' . __FUNCTION__;
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function app(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function bin(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function cache(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function classes(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function configuration(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function daemon(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function dataClass(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function dataClassGenerated(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param string $filename
     * @param bool $isCustom
     * @return string
     */
    public function dataClassGeneratedFile(string $filename, bool $isCustom = false): string
    {
        return __FUNCTION__ . '/' . $filename;
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function docRoot(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function docRootReseller(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function font(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function functionalTest(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @return string
     */
    public function functionalTestTemporary(): string
    {
        return $this->withSysRoot(__FUNCTION__);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function includes(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function inlineHelp(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function libs(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function log(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function logReport(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function logRun(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function session(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function qcodoLib(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function qcodoCore(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function qcodoDevToolsCli(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function qcodoJsAssets(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function qcodoCssAssets(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function qcodoImageAssets(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function qcodoPhpAssets(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function textImages(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function resources(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function temporary(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function translation(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function translationMaster(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function dynamicLanguage(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function customFieldTranslationMaster(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function unitTest(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @return string
     */
    public function unitTestTemporary(): string
    {
        return $this->withSysRoot(__FUNCTION__);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function upload(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function uploadLotItemImage(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function uploadLotImageBucket(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function uploadAuctionImage(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function uploadSetting(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function sound(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }


    /**
     * @param bool $isCustom
     * @return string
     */
    public function uploadLotCustomFieldFile(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function uploadAuctionCustomFieldFile(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function uploadUserCustomFieldFile(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function uploadLotCategoryImage(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function uploadReseller(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    // -----

    /**
     * @param Ui $ui
     * @param bool $isCustom
     * @return string
     */
    public function controller(Ui $ui, bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom) . '/' . $ui->value();
    }

    /**
     * @param Ui $ui
     * @param bool $isCustom
     * @return string
     */
    public function layout(Ui $ui, bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom) . '/' . $ui->value();
    }

    /**
     * @param Ui $ui
     * @param bool $isCustom
     * @return string
     */
    public function draft(Ui $ui, bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom) . '/' . $ui->value();
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function draftResponsive(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param string $filename
     * @param bool $isCustom
     * @return string
     */
    public function draftResponsiveFile(string $filename, bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function draftAdmin(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param string $filename
     * @param bool $isCustom
     * @return string
     */
    public function draftAdminFile(string $filename, bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param Ui $ui
     * @param bool $isCustom
     * @return string
     */
    public function viewScript(Ui $ui, bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * set the image prefix for this account
     * SAM-6695: Image link prefix detection do not provide default value
     * @param int|null $accountId
     * @param array $optionals
     * @return string
     */
    public function imagePrefix(?int $accountId = null, array $optionals = []): string
    {
        return $this->withSysRoot(__FUNCTION__) . '/' . $accountId;
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function baseImage(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function defaultAuctionImages(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function defaultLotImages(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function defaultAccountImages(bool $isCustom = false): string
    {
        return $this->withSysRoot(__FUNCTION__, $isCustom);
    }

    protected function withSysRoot(string $functionName, bool $isCustom = false): string
    {
        return $this->sysRoot() . '/' . $functionName . ($isCustom ? '/1' : '');
    }
}
