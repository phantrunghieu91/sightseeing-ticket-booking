<?php
/**
 * @author Hieu "JIN" Phan Trung
 * * Template: GPW Button
 */
if (empty($args) || !is_array($args)) {
  return;
}
$validTags = ['a', 'button'];
$buttonTag = isset($args['tag']) && in_array($args['tag'], $validTags) ? $args['tag'] : 'a';
$buttonType = $buttonTag === 'button' && isset($args['type']) ? $args['type'] : 'button';
$buttonPosition = isset($args['position']) ? $args['position'] : '';
$buttonLabel = $args['label'] ?? '';
$buttonUrl = isset($args['url']) && $args['url'] != '' ? esc_url($args['url']) : 'javascript:void(0)';
$buttonStyle = $args['style'] ?? '';
$buttonTarget = $args['target'] ?? '_self';
$buttonIconCode = $args['icon_code'] ?? '';
$buttonIconPosition = $args['icon_position'] ?? 'right'; // left | right
if (!$buttonLabel) {
  return;
}
$buttonClasses = isset($args['class']) && !empty($args['class']) ? [$args['class']] : [];
$buttonClasses[] = 'gpw-button';
$buttonClasses[] = match ($buttonPosition) {
  'center' => 'gpw-button--center',
  'right' => 'gpw-button--right',
  default => '',
};
match ($buttonStyle) {
  'primary' => $buttonClasses[] = 'gpw-button__primary',
  'secondary' => $buttonClasses[] = 'gpw-button__secondary',
  'white-primary' => $buttonClasses[] = 'gpw-button__white-primary',
  'outline' => $buttonClasses[] = 'gpw-button__outlined',
  default => $buttonClasses[] = '',
};

$renderButtonText = function ($isHover = false) use ($buttonLabel, $buttonIconCode, $buttonIconPosition) {
  ob_start();
  ?>
  <div class="gpw-button__text-wrapper<?= $isHover ? ' gpw-button__text-wrapper--hover' : '' ?>">
    <?php if ($buttonIconCode && $buttonIconPosition === 'left'): ?>
      <span
        class="gpw-button__icon gpw-button__icon--left material-symbols-outlined"><?= esc_html($buttonIconCode) ?></span>
    <?php endif; ?>
    <span class="gpw-button__text"><?= esc_html($buttonLabel) ?></span>
    <?php if ($buttonIconCode && $buttonIconPosition === 'right'): ?>
      <span
        class="gpw-button__icon gpw-button__icon--right material-symbols-outlined"><?= esc_html($buttonIconCode) ?></span>
    <?php endif; ?>
  </div>
  <?php
  return ob_get_clean();
}
  ?>
<<?= esc_html($buttonTag) ?>
  class="<?= esc_attr(implode(' ', $buttonClasses)) ?>"
  <?= $buttonTag === 'a' ? "href='{$buttonUrl}' target='{$buttonTarget}'" : "type='{$buttonType}'" ?>
  >
  <?php if ($buttonIconCode && $buttonIconPosition === 'left'): ?>
    <span class="gpw-button__icon gpw-button__icon--left material-symbols-outlined"><?= esc_html($buttonIconCode) ?></span>
  <?php endif; ?>
  
  <span class="gpw-button__text"><?= esc_html($buttonLabel) ?></span>

  <?php if ($buttonIconCode && $buttonIconPosition === 'right'): ?>
    <span class="gpw-button__icon gpw-button__icon--right material-symbols-outlined"><?= esc_html($buttonIconCode) ?></span>
  <?php endif; ?>
</<?= esc_html($buttonTag) ?>>

<?php
// ! Clean up variables
unset($buttonTag, $buttonLabel, $buttonUrl, $buttonStyle, $buttonTarget, $buttonIconCode, $buttonIconPosition, $buttonClasses, $buttonType, $renderButtonText);