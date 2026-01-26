<?php
$blockClasses = [];

// Global block variables
$id = $block['id'];
$customID = get_field('block_id');
$blockBG = get_field('block_background');
$paddingOptions = get_field('padding_options');
$marginOptions = get_field('margin_options');

// Get and clean the block name (e.g., "acf/hero" → "hero")
$blockName = str_replace('acf/', '', $block['name']);

// Set block ID (custom if available)
$blockID = $customID ? $customID : $id;

// Handle padding options
if ($paddingOptions) {
	$paddingTop = 'pt-' . $paddingOptions['padding_top'];
	$paddingBottom = 'pb-' . $paddingOptions['padding_bottom'];
	array_push($blockClasses, $paddingTop, $paddingBottom);
}

// Handle margin options
if ($marginOptions) {
	$marginTop = 'mt-' . $marginOptions['margin_top'];
	$marginBottom = 'mb-' . $marginOptions['margin_bottom'];
	array_push($blockClasses, $marginTop, $marginBottom);
}

// Handle background color or class
if ($blockBG) {
	array_push($blockClasses, 'bg-' . $blockBG);
}

// Add base class for the block (e.g., "block-hero")
array_unshift($blockClasses, 'block-' . $blockName);
?>

<section 
	id="<?= esc_attr($blockID); ?>" 
	data-block="<?= esc_attr($blockName); ?>" 
	class="<?= esc_attr(implode(' ', $blockClasses)); ?>"
>
	<!-- Block content goes here -->
</section>
