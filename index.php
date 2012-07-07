<?php get_header (); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div <?php post_class(); ?>>

			<?php $title = (get_the_title()) ? get_the_title() : __('Untitled', 'monospace'); ?>
            <h2><a href="<?php the_permalink(); ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a></h2>
            <?php monospace_meta(); ?>

			<div class="entry">
				<?php the_content(); ?>
                <?php wp_link_pages(); ?>
			</div>

			<?php monospace_share (); ?>
			<?php comments_popup_link(); ?>
			<?php monospace_related (); ?>

			<?php comments_template(); ?>

		</div>

	<?php endwhile; ?>

		<?php monospace_navigation(); ?>

	<?php else : ?>

	<?php endif; ?>

<?php get_footer (); ?>
