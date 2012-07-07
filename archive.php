<?php get_header (); ?>

	<h1><?php monospace_title (); ?></h1>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div class="post">
			<?php $title = (get_the_title()) ? get_the_title() : __('Untitled', 'monospace'); ?>
            <h2><a href="<?php the_permalink(); ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a></h2>
			<?php monospace_meta (); ?>
			<?php edit_post_link (__('Edit', 'monospaced')); ?>
		</div>

	<?php endwhile; ?>

		<?php monospace_navigation (); ?>

	<?php else : ?>

	<?php endif; ?>

<?php get_footer (); ?>
