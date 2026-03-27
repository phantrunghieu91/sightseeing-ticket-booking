<?php
/**
 * @author Hieu "Jin" Phan Trung
 * * Template for accordion element
 */
$items = $args['items'] ?? [];
$hasIcon = $args['has_icon'] ?? false;

if( empty($items) ) {
  return;
}
?>
<div class="accordion">
  <?php foreach( $items as $idx => $item ) : ?>

    <div class="accordion__item">
      <button class="accordion__button" aria-expanded="<?= $idx == 0 ? 'true' : 'false' ?>" aria-controls="panel-<?= $idx ?>">
        <span class="accordion__button-text"><?= esc_html( $item['title'] ) ?></span>
        <?php if( $hasIcon ) : ?>
          <span class="accordion__button-icon material-symbols-outlined"><?= $idx == 0 ? 'remove' : 'add' ?></span>
        <?php endif; ?>
      </button>
      <div class="accordion__panel" id="panel-<?= $idx ?>" aria-hidden="<?= $idx == 0 ? 'false' : 'true' ?>">
        <?= wp_kses_post( $item['content'] ) ?>
      </div>
    </div>

  <?php endforeach; ?>
</div>

<?php
// ! Clean up variables
unset( $items, $hasIcon );