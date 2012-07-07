
		</div> <!-- #container -->

		<?php get_sidebar(); ?>

		<div id="footer">

            <?php dynamic_sidebar ('footer'); ?>

            <?php /*
                Thanks for using this theme. If you like it, I would really
                appreciate if you could leave these credits here.
            */ ?>

            <div id="credits">
                <span><a href="<?php echo MONOSPACE_CREDITS_URL; ?>">Monospace WordPress Theme</a> <?php _e('by', 'monospace'); ?> Vinicius Massuchetto</span>
            </div>

		</div>

        <?php wp_footer(); ?>
	</div> <!-- #wrap -->
</body>
</html>
