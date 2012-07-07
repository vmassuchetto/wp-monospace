<?php

    define ('MONOSPACE_CREDITS_URL', 'http://vinicius.soylocoporti.org.br/monospace-wordpress-theme/');

    if (function_exists('add_theme_support')) {
        add_theme_support('automatic-feed-links');
        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(100, 100, 1);
    } else {
        add_action('wp_head', 'monospace_head');
    }

    function monospace_head () {
        ?>
		<link rel="alternate" type="application/rss+xml" title="<?php _e('Posts Feed', 'monospace'); ?> &raquo; <?php bloginfo('name'); ?>" href="<?php bloginfo('rss2_url'); ?>" />
        <link rel="alternate" type="application/atom+xml" title="<?php _e('Posts Feed', 'monospace'); ?> &raquo; <?php bloginfo('name'); ?>" href="<?php bloginfo('atom_url'); ?>" />
        <link rel="alternate" type="application/rss+xml" title="<?php _e('Comments Feed', 'monospace'); ?> &raquo; <?php bloginfo('name'); ?>" href="<?php bloginfo('atom_url'); ?>" />
        <?php
    }

    load_theme_textdomain ('monospace', get_template_directory().'/lang');

	$args = array(
		'name' => __('Sidebar', 'monospaced'),
		'id'   => 'sidebar'
	);
	register_sidebar($args);

    $args = array(
		'name' => __('Footer', 'monospaced'),
		'id'   => 'footer'
	);
	register_sidebar($args);

    function monospace_meta () {
        global $post;
        ?>
        <div class="meta">

            <?php if (function_exists('the_post_thumbnail')) : ?>
                <?php if (has_post_thumbnail()) : ?>
                    <span class="thumbnail"><?php the_post_thumbnail(); ?></span>
                <?php endif; ?>
            <?php endif; ?>

            <span class="authordate">
                <?php _e ('Written by', 'monospace'); ?>&nbsp;<b><?php the_author(); ?></b>&nbsp;<?php _e('on', 'monospace'); ?>&nbsp;<?php the_date(); ?>
            </span>
            <span class="categories"><?php _e('Categories', 'monospace'); ?>:&nbsp;<?php the_category(', '); ?></span>
            <span class="tags"><?php the_tags(__('Tags:&nbsp;', 'monospace'), ', '); ?></span>
            <?php edit_post_link (__('Edit', 'monospace')); ?>
        </div>
        <?php
    }

	function monospace_title ($mode = false) {
		global $page, $paged;

        $title = wp_title('', false);

        if (is_category())
            echo __('Category').' | ';
        elseif (is_tag())
            echo __('Tag').' | ';
        elseif (is_archive())
            echo __('Archives').' | ';
        elseif (is_search())
            $title = preg_replace ('/^\ \ \|\ \ /', '', $title);

        echo $title;

        if ($mode == 'head') {
            if (!is_home())
                echo ' | ';
            bloginfo( 'name' );
		    if ($site_description = get_bloginfo( 'description', 'display' ))
			    echo " | $site_description";
        }

		if ( $paged >= 2 || $page >= 2 )
			echo ' | ' . sprintf( __( 'Page %s', 'monospace' ), max( $paged, $page ) );
	}

	function monospace_navigation () {
		?>

		<div class="nav">

			<?php if (is_single()) : ?>

				<?php previous_post_link (); ?>&nbsp;&mdash;&nbsp;<?php next_post_link (); ?>

			<?php elseif (function_exists('wp_pagenavi')) : ?>

				<?php wp_pagenavi(); ?>

			<?php else : ?>

				<?php posts_nav_link(); ?>

			<?php endif; ?>

		</div>

		<?php
	}

    /* TODO: Make every entry this optional */
	function monospace_share () {
        global $post;
		?>
		<div class="meta">
			<span class="share"><?php _e('Share on', 'monospace'); ?>&nbsp;
                <?php
                  $url = urlencode(get_permalink($post->ID));
                  $title = urlencode(get_the_title());
                ?>
				<a href="<?php echo esc_url('http://twitter.com/home?status=' . $title . '%20' . $url); ?>" target="_blank">Twitter</a>&nbsp;
				<a href="<?php echo esc_url('http://facebook.com/sharer.php?t=' . $title . '&amp;u=' . $url); ?>" target="_blank">Facebook</a>&nbsp;
				<a href="<?php echo esc_url('http://google.com/reader/link?title=' . $title . '&amp;url=' . $url);?>" target="_blank">Google Reader</a>&nbsp;
                <a href="<?php echo esc_url('http://ping.fm/ref/?conduit&method=status&title=' . $title . '&link=' . $url); ?>" target="_blank">Ping.fm</a>
            </span>
		</div>
		<?php
	}

	function monospace_related () {
		global $wpdb, $post,$table_prefix;
		?>

		<?php

		if(!$post->ID){return;}

		$now = current_time('mysql', 1);

		$tags = wp_get_post_tags($post->ID);
		$tagcount = count($tags);
        $taglist = '';
        if ($tagcount)
		    $taglist = "'" . $tags[0]->term_id. "'";

		if ($tagcount > 1) {
			for ($i = 1; $i < $tagcount; $i++) {
				$taglist = $taglist . ", '" . $tags[$i]->term_id . "'";
			}
		}

        if ($taglist)
            $taglist = "AND (t_t.term_id IN ($taglist))";

		$q = "
            SELECT p.ID, p.post_title, p.post_content,p.post_excerpt,
                p.post_date, p.comment_count, count(t_r.object_id) as cnt
            FROM $wpdb->term_taxonomy t_t, $wpdb->term_relationships t_r,
                $wpdb->posts p
            WHERE 1
                AND t_t.taxonomy ='post_tag'
                AND t_t.term_taxonomy_id = t_r.term_taxonomy_id
                AND t_r.object_id  = p.ID
                AND p.ID != $post->ID
                AND p.post_status = 'publish'
                AND p.post_date_gmt < '$now'
                $taglist
            GROUP BY t_r.object_id
            ORDER BY
                cnt DESC,
                p.post_date_gmt DESC
            LIMIT 5;
        ";

		$related_posts = $wpdb->get_results($q);

		if (!$related_posts) {
			$q = "
                SELECT ID, post_title, post_content, post_excerpt,
                    post_date,comment_count
                FROM $wpdb->posts
                WHERE 1
                    AND post_status = 'publish'
                    AND post_type = 'post'
                    AND ID != $post->ID
                ORDER BY RAND()
                LIMIT 5
            ";
			$related_posts = $wpdb->get_results($q);
		}

		$i = 0;
        $output = '';
		foreach ($related_posts as $related_post ){
			$datestr = strtotime($related_post->post_date);
			$dm = date('d/m', $datestr);
			$y = substr(date('Y', $datestr),2,2);
			$output .= '
				<li>
					<a href="'.get_permalink($related_post->ID).'" title="'.wptexturize($related_post->post_title).'">'.wptexturize($related_post->post_title).'</a>
				</li>
			';

		}

		if (!$output)
			return false;
		else
			echo '<div class="related"><h3>'.__('Related Posts', 'monospace').'</h3><ul>'.$output.'</ul></div>';

	}

    add_filter('comment_class','comment_add_microid');
    function comment_add_microid($classes) {

        $c_email=get_comment_author_email();
        $c_url=get_comment_author_url();

        if (!empty($c_email) && !empty($c_url)) {
            $microid = 'microid-mailto+http:sha1:' . sha1(sha1('mailto:'.$c_email).sha1($c_url));
            $classes[] = $microid;
        }

        return $classes;

    }

?>
