<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    
    <div class="blog-post">
        <!-- Display the Title as a link to the Post's permalink. -->
        <<?php echo $args["h"] ? $args["h"] : "h3"?> class="blog-post-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></<?php echo $args["h"] ? $args["h"] : "h3"?>>
        <!-- Display the date (November 16th, 2009 format) and a link to other posts by this posts author. -->
        <div class="blog-post-date"><?php the_time('F jS, Y'); ?> by <?php the_author_posts_link(); ?></div>
        <!-- Display the Post's content in a div box. -->
        <div class="blog-post-content">
            <?php the_content(); ?>
        </div>
        <!-- Display a comma separated list of the Post's Categories. -->
        <p class="blog-post-actions"><?php _e( 'Posted in' ); ?> <?php the_category( ', ' ); ?></p>
        <?php if ( !isset($args["h"]) ) : ?><a class="blog-post-actions" href="<?php comments_link(); ?>"><?php printf( _nx( '%1$s comment', '%1$s comments', get_comments_number(), "comments number" ), number_format_i18n( get_comments_number() ) );?></a><?php endif; ?>
    </div> <!-- closes the first div box -->
        
<!-- Stop The Loop (but note the "else:" - see next line). -->
<?php endwhile; ?><div class="blog-pagination"><?php posts_nav_link(); ?></div><?php else : ?>

    <!-- The very first "if" tested to see if there were any Posts to -->
    <!-- display.  This "else" part tells what do if there weren't any. -->
    <p><?php esc_html_e( 'Sorry, no posts matched your criteria.' ); ?></p>

<!-- REALLY stop The Loop. -->
<?php endif; ?>
