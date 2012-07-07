<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
	<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" />
	<title><?php monospace_title('head'); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_enqueue_script ('jquery'); ?>
	<?php wp_enqueue_script ('monospaced', get_stylesheet_directory_uri().'/scripts.js'); ?>
	<?php if (is_singular()) wp_enqueue_script('comment-reply'); ?>
	<?php wp_head(); ?>
</head>

<body <?php if (function_exists('body_class')) body_class(); ?>>

    <?php if (!isset($content_width)) $content_width = 560; ?>

	<div id="wrap" style="width:<?php echo $content_width + 290; ?>px">

		<div id="container" style="width:<?php echo $content_width; ?>px">
