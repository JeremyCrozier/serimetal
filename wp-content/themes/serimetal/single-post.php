<?php get_header(); ?>

<?php
if ( function_exists('yoast_breadcrumb') ) {
    yoast_breadcrumb('<nav id="yoast-breadcrumbs" aria-label="Breadcrumb">','</nav>');
}
?>

<article id="post-single">
    <header id="hero-post" class="wp-block-group is-layout-constrained">
        <div class="wp-block-group columns">
            <div class="wp-block-image column is-layout-constrained">
                <?php echo get_the_post_thumbnail(get_the_ID(), 'featured-post'); ?>
            </div>
            <div class="wp-block-group column has-slate-50-background-color has-background heading-wrapper">
                <div class="label">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                            <path opacity="0.2" d="M12.25 3.0625V10.9375C12.25 11.0535 12.2039 11.1648 12.1219 11.2469C12.0398 11.3289 11.9285 11.375 11.8125 11.375H2.1875C2.07147 11.375 1.96019 11.3289 1.87814 11.2469C1.79609 11.1648 1.75 11.0535 1.75 10.9375V3.0625C1.75 2.94647 1.79609 2.83519 1.87814 2.75314C1.96019 2.67109 2.07147 2.625 2.1875 2.625H11.8125C11.9285 2.625 12.0398 2.67109 12.1219 2.75314C12.2039 2.83519 12.25 2.94647 12.25 3.0625Z" fill="#FF6900"/>
                            <path d="M11.8125 2.1875H2.1875C1.95544 2.1875 1.73288 2.27969 1.56878 2.44378C1.40469 2.60788 1.3125 2.83044 1.3125 3.0625V10.9375C1.3125 11.1696 1.40469 11.3921 1.56878 11.5562C1.73288 11.7203 1.95544 11.8125 2.1875 11.8125H11.8125C12.0446 11.8125 12.2671 11.7203 12.4312 11.5562C12.5953 11.3921 12.6875 11.1696 12.6875 10.9375V3.0625C12.6875 2.83044 12.5953 2.60788 12.4312 2.44378C12.2671 2.27969 12.0446 2.1875 11.8125 2.1875ZM11.8125 10.9375H2.1875V3.0625H11.8125V10.9375ZM10.0625 5.25C10.0625 5.36603 10.0164 5.47731 9.93436 5.55936C9.85231 5.64141 9.74103 5.6875 9.625 5.6875H4.375C4.25897 5.6875 4.14769 5.64141 4.06564 5.55936C3.98359 5.47731 3.9375 5.36603 3.9375 5.25C3.9375 5.13397 3.98359 5.02269 4.06564 4.94064C4.14769 4.85859 4.25897 4.8125 4.375 4.8125H9.625C9.74103 4.8125 9.85231 4.85859 9.93436 4.94064C10.0164 5.02269 10.0625 5.13397 10.0625 5.25ZM10.0625 7C10.0625 7.11603 10.0164 7.22731 9.93436 7.30936C9.85231 7.39141 9.74103 7.4375 9.625 7.4375H4.375C4.25897 7.4375 4.14769 7.39141 4.06564 7.30936C3.98359 7.22731 3.9375 7.11603 3.9375 7C3.9375 6.88397 3.98359 6.77269 4.06564 6.69064C4.14769 6.60859 4.25897 6.5625 4.375 6.5625H9.625C9.74103 6.5625 9.85231 6.60859 9.93436 6.69064C10.0164 6.77269 10.0625 6.88397 10.0625 7ZM10.0625 8.75C10.0625 8.86603 10.0164 8.97731 9.93436 9.05936C9.85231 9.14141 9.74103 9.1875 9.625 9.1875H4.375C4.25897 9.1875 4.14769 9.14141 4.06564 9.05936C3.98359 8.97731 3.9375 8.86603 3.9375 8.75C3.9375 8.63397 3.98359 8.52269 4.06564 8.44064C4.14769 8.35859 4.25897 8.3125 4.375 8.3125H9.625C9.74103 8.3125 9.85231 8.35859 9.93436 8.44064C10.0164 8.52269 10.0625 8.63397 10.0625 8.75Z" fill="#FF6900"/>
                        </svg>
                    </span>
                    <span class="caption">
                        <?php _e('Blog', 'Serimetal'); ?>
                    </span>
                </div>
                <h1 class="wp-block-heading"><?php echo get_the_title(); ?></h1>
                <?php if ($reading_time = get_post_meta(get_the_ID(), '_yoast_wpseo_estimated-reading-time-minutes', true)) : ?>
                    <div class="reading-time">
                        <?php
                        printf(
                            /* translators: %s is reading time in minutes */
                            esc_html__('%s minutes de lecture', 'Serimetal'),
                            esc_html($reading_time)
                        );
                        ?>
                    </div>
                <?php endif; ?>
                <div class="published-date">
                    <?php
                    printf(
                        /* translators: 1: post date, 2: author name */
                        esc_html__('Écrit le %1$s par %2$s', 'Serimetal'),
                        '<time datetime="' . esc_attr(get_the_date('c')) . '">' . esc_html(get_the_date()) . '</time>',
                        '<strong>' . esc_html(get_the_author_meta('display_name')) . '</strong>'
                    );
                    ?>
                </div>
                <?php get_template_part('parts/social', 'share'); ?>
            </div>
        </div>
    </header>

    <section id="content" class="wp-block-group is-layout-constrained">
        <div class="wp-block-columns is-layout-flex is-layout-constrained wp-block-columns-is-layout-flex content-container">
            <div class="wp-block-column is-layout-flow wp-block-column-is-layout-flow content-wrapper">
                <?php echo the_content(); ?>

                <div class="wp-block-group has-slate-50-background-color has-background post-author">
                    <?php
                    $author_id = get_the_author_meta('ID');
                    $author_avatar = get_avatar($author_id, 128, '', get_the_author_meta('display_name'));
                    $author_bio = get_the_author_meta('description');
                    $author_post = get_user_meta($author_id, 'post', true);
                    ?>
                    <div class="author-avatar">
                        <?php if ($author_avatar) : ?>
                            <?php echo $author_avatar; ?>
                        <?php endif; ?>
                        <div class="wp-block-heading is-style-h4 author-name">
                            <?php if ($author_post) : ?>
                                <?php echo get_the_author_meta('display_name') . ', ' . $author_post; ?>
                            <?php else: ?>
                                <?php echo get_the_author_meta('display_name'); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="author-info">
                        <div class="wp-block-heading is-style-h4 author-name">
                            <?php if ($author_post) : ?>
                                <?php echo get_the_author_meta('display_name') . ', ' . $author_post; ?>
                            <?php else: ?>
                                <?php echo get_the_author_meta('display_name'); ?>
                            <?php endif; ?>
                        </div>
                        <?php if ($author_bio) : ?>
                            <p><?php echo wp_kses_post($author_bio); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <?php get_template_part('parts/social', 'share'); ?>
            </div>

            <?php
            // Related posts
            $args = array(
                'post_type' => 'post',
                'posts_per_page' => 2,
                'post__not_in' => array(get_the_ID()),
                'orderby' => 'date',
                'order' => 'DESC'
            );
            $query = new WP_Query($args);

            if ($query->have_posts()) : ?>
                <aside class="wp-block-column is-layout-flow wp-block-column-is-layout-flow" style="flex-basis:30%;">
                    <div class="wp-block-group is-layout-constrained has-blue-100-background-color has-background articles-related">
                        <h2 class="wp-block-heading is-style-h3"><?php _e('Nos derniers articles', 'Serimetal'); ?></h2>
                        <ul class="posts-loop">
                            <?php 
                                while ($query->have_posts()) : $query->the_post();
                                    get_template_part('parts/card', 'post', array('is_related' => true));
                                endwhile;
                                wp_reset_postdata(); 
                            ?>
                        </ul>
                    </div>
                </aside>
            <?php endif; ?>
        </div>
    </section>
</article>



<?php get_footer(); ?>