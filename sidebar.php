<div id="sidebar">
	<h1><a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></h1>
	<span class="description"><?php bloginfo('description'); ?></span>
	<span class="feed"><a href="<?php bloginfo('rss2_url'); ?>">RSS</a> | <a href="<?php bloginfo('atom_url'); ?>">Atom</a></span>
	<?php dynamic_sidebar('sidebar'); ?>
</div>
