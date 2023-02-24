<?php // @formatter:off ?>
<div <?php echo $this->left ? 'id="tab-sub-menu"' : 'class="tab-menu"'; ?> <?php echo $this->customClass ? 'class="' . $this->customClass . '"' : ''; ?>>
    <ul <?php echo $this->left ? 'class="open-sub-menu"' : ''; ?>>
<?php
foreach ($this->menu as $parameters) {
    $id = $this->showItemId ? 'id = "' . str_replace(' ', '-', strtolower($parameters['label'])) . '"' : '';
    $title = isset($parameters['title']) ? 'title="' . $parameters['title'] . '"' : '';
    $subUrls = $parameters['subUrls'] ?? [];
    $selected = false;
    foreach (array_merge($subUrls, [$parameters['url']]) as $action) {
        if (str_contains($this->selectedItem, $action)) {
            $selected = true;
            break;
        }
    }
?>
        <li <?php echo $id . ($selected ? 'class="selected"' : ''); ?>>
            <a href="<?php echo $parameters['url']; ?>" <?php echo $title; ?>>
                <?php echo $parameters['label'] ?? ''; ?>
            </a>
        </li>
<?php
}
?>
    </ul>
</div>
