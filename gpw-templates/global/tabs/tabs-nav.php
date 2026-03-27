<?php
/**
 * @author Hieu 'Jin' Phan Trung
 * * Template for displaying the navigation of tabs
 *
 * Initializes tab navigation variables from provided arguments.
 *
 * @var array  $navItems     List of navigation items for the tabs.
 * ! - 'id' (string): The unique identifier for the tab item.
 * ! - 'title' (string): The visible title for the tab item.
 * @var bool   $hasAos       Whether to enable AOS (Animate On Scroll) animations. Defaults to false.
 * @var int    $activeIndex  Index of the currently active tab. Defaults to 0.
 * @var string $orientation  Orientation of the tabs ('horizontal' or 'vertical'). Defaults to 'horizontal'.
 * @var string $style       Style of the tabs ('underline' or 'pills'). Defaults to 'underline'.
 */
$navItems = $args['nav_items'] ?? [];
$hasAos = $args['has_aos'] ?? false;
$activeIndex = $args['active_index'] ?? 0;
$orientation = $args['orientation'] ?? 'horizontal';
$style = $args['style'] ?? 'default';
$validStyles = ['default', 'underline', 'pills', 'dot-after'];
if (empty($navItems)) {
  return;
}
$classes = ['tabs__nav'];
$classes[] = in_array( $style, $validStyles ) ? "tabs__nav--$style" : "tabs__nav--" . reset($validStyles);
?>
<nav class="<?= esc_attr(trim( implode(' ', $classes ))) ?>" role="navigation" aria-label="Tabs navigation" <?php if ($hasAos) echo 'data-aos="fade-up"'; ?>>
  <ul class="tabs__nav-menu" role="tablist" aria-orientation="<?= esc_attr($orientation) ?>">
    <?php foreach ($navItems as $idx => $navItem): ?>
      <li class="tabs__nav-item" role="tab" aria-controls="panel-<?= esc_attr($navItem['id']) ?>"
        id="tab-<?= esc_attr($navItem['id']) ?>" tabindex="<?= esc_attr($idx == $activeIndex ? '0' : '-1') ?>"
        aria-selected="<?= esc_attr($idx == $activeIndex ? 'true' : 'false') ?>"><?= esc_html($navItem['title']) ?>
      </li>
    <?php endforeach ?>
  </ul>
</nav>
<?php
// ! Clean up variables
unset($navItems, $hasAos, $orientation, $idx, $navItem, $classes, $style, $validStyles, $activeIndex);