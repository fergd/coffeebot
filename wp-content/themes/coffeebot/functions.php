<?php
// Add a favicon
function bot_favicon() {
  echo '<link rel="shortcut icon" href="' . get_bloginfo('stylesheet_directory') . '../favicon.ico"/>';
}
add_action('wp_head', 'bot_favicon');

// Add support for thumbnails
add_theme_support('post-thumbnails');
set_post_thumbnail_size(540, 300, true);
add_image_size('homepage-thumbnail', 300, 200, true);

// output a list of top-level pages
function bot_footer_pagelinks() {
  echo '<ul id="simplepages">';
  wp_list_pages('depth=1&sort_column=menu_order&title_li=');
  echo '</ul>';
}

// custom homepage loop
function bot_indexloop() {
  query_posts("posts_per_page=4");
  $counter = 1;
  if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div id="post-<?php the_ID() ?>" class="<?php thematic_post_class() ?>">
      <?php thematic_postheader();
      if ($counter == 1 && has_post_thumbnail() && !is_paged()) {
        the_post_thumbnail('homepage-thumbnail');
      } ?>
      <div class="entry-content">
        <?php the_excerpt(); ?>
        <a href="<?php the_permalink(); ?>" class="more"><?php echo more_text() ?></a>
        <?php $counter++; ?>
      </div>
    </div><!-- .post -->
  <?php endwhile; else: ?>
    <h2>Eek</h2>
    <p>There are no posts to show!</p>
  <?php endif;
  wp_reset_query();
}

?>