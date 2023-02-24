<?php
/**
 * @var FileEditPanel $_CONTROL
 */

use Sam\CustomField\Base\Qform\Component\FileEditPanel;

$style = 'border:1px solid #cccccc;padding:5px;margin:2px;';
if ($_CONTROL->getWidth() !== '') {
    $style .= 'width:' . $_CONTROL->getWidth() . ';';
}
if ($_CONTROL->getHeight() !== '') {
    $style .= 'height:' . $_CONTROL->getHeight() . ';';
}
if ($_CONTROL->getDisplay() !== '') {
    $style .= 'display:' . $_CONTROL->getDisplay() . ';';
}
?>
<div style="<?php
echo $style; ?>">
    <div style="margin-bottom:5px;">
        <?php
        $_CONTROL->btnDelete->Render(); ?>
    </div>
    <div>
        <div class="fleft" style="width:156px;">
            <?php
            echo $_CONTROL->renderFileName(); ?>
            <?php
            echo $_CONTROL->renderTmpFileName(); ?>
        </div>
        <div class="fleft">
            <?php
            $_CONTROL->flaFile->RenderWithError(); ?>
        </div>
        <div class="clear"></div>
        <?php
        echo $_CONTROL->getDownloadLink(); ?>
    </div>
    <div class="clear"></div>
</div>
