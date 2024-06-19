<?php
add_action( 'wp_enqueue_scripts', 'astra_child_film_portfolio_theme_enqueue_styles' );
function astra_child_film_portfolio_theme_enqueue_styles() {
    // Enqueue the parent theme's stylesheet with the proper text domain
    wp_enqueue_style( 'astra-child-film-portfolio-theme-parent-style', get_parent_theme_file_uri( 'style.css' ), array(), '1.0.0' );

    // Enqueue the child theme's stylesheet with its own text domain
    wp_enqueue_style( 'astra-child-film-portfolio-theme-style', get_stylesheet_uri(), array( 'astra-child-film-portfolio-theme-parent-style' ), '1.0.0' );
}

function custom_blog_grid_shortcode($atts) {
    // Query for the latest posts
    $args = array(
        'posts_per_page' => -1, // Retrieve all posts
    );
    $query = new WP_Query($args);

    // Initialize output variable
    $output = '<div class="custom-blog-grid">';

    // Loop through posts
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            // Get the featured image URL
            $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
            $categories = get_the_category();
            $category_names = [];
            foreach ($categories as $category) {
                $category_names[] = $category->name;
            }
            $category_list = implode(', ', $category_names);

            // Add each post to the grid
            $output .= '<div class="grid-item" style="background-image: url(' . esc_url($featured_img_url) . ');">';
            $output .= '<div class="post-details">';
            $output .= '<h2>' . get_the_title() . '</h2>';
            $output .= '<p class="post-categories">' . $category_list . '</p>';
            $output .= '</div>'; // .post-details
            $output .= '</div>'; // .grid-item
        }
        wp_reset_postdata();
    } else {
        $output .= '<p>No posts found</p>';
    }

    $output .= '</div>'; // .custom-blog-grid

    return $output;
}
add_shortcode('custom_blog_grid', 'custom_blog_grid_shortcode');
