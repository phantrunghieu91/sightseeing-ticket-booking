<?php
/**
 * @author Hieu 'Jin' Phan Trung
 * * Template for displaying the content of tabs
 *
 * @var array $panels - Array of tab panels.
 * * Each panel is represented as an associative array with the following keys:
 * ! - 'id' (string): The unique identifier for the tab panel.
 * ! - 'content' (string): The HTML content to display within the tab panel.
 * 
 * @var boolean $hasAos - Whether the tab has AOS (Animate On Scroll) enabled
 * @var int $activeIndex - Index of the currently active tab. Defaults to 0.
 */
$panels = $args[ 'panels' ] ?? [];
if( empty( $panels ) ) {
  return;
}
$hasAos = $args[ 'has_aos' ] ?? false;
$activeIndex = $args[ 'active_index' ] ?? 0;
?>
<div class="tabs__content" <?php if( $hasAos ) echo 'data-aos="fade-up"' ?> >
  <?php foreach( $panels as $idx => $panel ): 
    $hidden = $idx == $activeIndex ? 'false' : 'true';
    ?>
    <div class="tabs__panel" role="tabpanel" 
      id="panel-<?= esc_attr( $panel[ 'id' ] ) ?>"
      aria-labelledby="tab-<?= esc_attr( $panel[ 'id' ] ) ?>"
      aria-hidden="<?= esc_attr( $hidden ) ?>"
    ><?= wp_kses_post( $panel[ 'content' ] ) ?></div>
  <?php endforeach ?>
</div>
<?php
// ! Clean up variables
unset( $panels, $hasAos, $idx, $panel, $activeIndex );