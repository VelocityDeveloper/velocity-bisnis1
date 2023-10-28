<?php

/**
 * Post rendering content according to caller of get_template_part
 *
 * @package justg
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;
?>

<article <?php post_class('mb-5'); ?> id="post-<?php the_ID(); ?>">

    <?php if ( has_post_thumbnail() ) { ?>
        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
            <?php the_post_thumbnail('full', array( 'class' => 'w-100 mb-3')); ?>
        </a>
    <?php } ?>

    <header class="entry-header border-bottom pb-3">
        <?php if ( 'post' == get_post_type() ) : ?>
            <div class="entry-meta text-color-theme">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16"><path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/></svg>
                <?php echo get_the_date(); ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="ms-3 bi bi-person" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/></svg>
                <?php echo get_the_author(); ?>
            </div>
        <?php endif; ?>
    </header>
        
    <h3 class="py-3 fs-4">
        <a class="text-dark" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
    </h3>

    <div class="entry-content">
        <?php $content = get_the_content();
        $trimmed_content = wp_trim_words( $content, 30 );
        echo $trimmed_content; ?>
    </div>

    <div class="pt-3">
        <a class="btn btn-sm rounded-0 btn py-2 px-4 bg-theme text-white" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">READ MORE</a>
    </div>

</article>
