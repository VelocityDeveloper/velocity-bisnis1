<?php

/**
 * Template Name: Home Template
 *
 * Template for displaying a page just with the header and footer area and a "naked" content area in between.
 * Good for landingpages and other types of pages where you want to add a lot of custom markup.
 *
 * @package justg
 */

get_header();

$home_banner =  velocitytheme_option('home_banner', '');
$home_banner_title =  velocitytheme_option('home_banner_title', '');
$home_banner_subtitle =  velocitytheme_option('home_banner_subtitle', '');

if($home_banner){
    echo '<div class="position-relative overflow-hidden">';
        echo '<img class="w-100" src="'.$home_banner.'" />';
        echo '<div class="d-flex align-items-center position-absolute top-0 start-0 w-100 h-100">';
            echo '<div class="col-12 text-center fw-bold text-center text-uppercase text-white lh-sm">';
                if($home_banner_title){
                    echo '<div class="velocity-banner-title">'.$home_banner_title.'</div>';
                } if($home_banner_subtitle){
                    echo '<div class="velocity-banner-subtitle mt-1">'.$home_banner_subtitle.'</div>';
                }
            echo '</div>';
        echo '</div>';
    echo '</div>';
}
?>


<div class="wrapper" id="page-wrapper">

    <div class="<?php echo esc_attr($container); ?>" id="content">

            <main class="site-main" id="main" role="main">
                
		    <?php
            echo '<div class="row my-5 py-5">';
            $homesmall_json = get_theme_mod('homesmall_repeater', '[]');
            $homesmall = json_decode($homesmall_json, true);
            if (is_array($homesmall) && !empty($homesmall)) {
                foreach ($homesmall as $item) {
                    $img_id = isset($item['id']) ? absint($item['id']) : 0;
                    $sb_title = isset($item['title']) ? $item['title'] : '';
                    $src = $img_id ? wp_get_attachment_image_url($img_id,'full') : '';
                    if($src){
                        $urlresize = (function_exists('aq_resize') ? aq_resize( $src, 380, 380, true, true, true ) : $src);
                        if(!$urlresize){ $urlresize = $src; }
                        echo '<div class="col-12 col-sm mb-3 mb-sm-0">';
                            echo '<div class="position-relative overflow-hidden">';
                                echo '<img class="w-100" src="'.$urlresize.'" />';
                                if($sb_title){
                                    echo '<div class="lh-sm sb-title p-3 position-absolute bottom-0 start-0 w-100 text-white fw-bold text-uppercase">';
                                        echo $sb_title;
                                    echo '</div>';
                                }
                            echo '</div>';
                        echo '</div>';
                    }
                }
            } else {
                for($x = 1; $x <= 3; $x++){
                    $banner = velocitytheme_option('small_banner'.$x, '');
                    $sb_title = velocitytheme_option('sb_title'.$x, '');
                    if($banner){
                        $urlresize = (function_exists('aq_resize') ? aq_resize( $banner, 380, 380, true, true, true ) : $banner);
                        if(!$urlresize){ $urlresize = $banner; }
                        echo '<div class="col-12 col-sm mb-3 mb-sm-0">';
                            echo '<div class="position-relative overflow-hidden">';
                                echo '<img class="w-100" src="'.$urlresize.'" />';
                                if($sb_title){
                                    echo '<div class="lh-sm sb-title p-3 position-absolute bottom-0 start-0 w-100 text-white fw-bold text-uppercase">';
                                        echo $sb_title;
                                    echo '</div>';
                                }
                            echo '</div>';
                        echo '</div>';
                    }
                }
            }
            echo '</div>';
            ?>
                
		    <?php
            echo '<div class="row my-5 pt-2">';
            $hs_title = velocitytheme_option('hs_title', '');
            if($hs_title){
                echo '<h2 class="fs-2 col-12 text-center mb-4">';
                    echo velocity_first_word($hs_title);
                echo '</h2>';
            }
            $services_json = velocitytheme_option('services_repeater', '[]');
            $services = json_decode($services_json, true);
            if (is_array($services) && !empty($services)) {
                foreach ($services as $item) {
                    $bi = isset($item['bi']) ? $item['bi'] : '';
                    $service = isset($item['content']) ? $item['content'] : '';
                    if($bi || $service){
                        echo '<div class="col-md-4 col-12 mb-4 velocity-service">';
                            echo '<div class="h-100 row border m-0 p-3 bg-white">';
                                if($bi){
                                    echo '<div class="col-2 col-sm-3 p-0 col-lg-2 me-lg-2">';
                                        echo '<span class="border text-center p-2">'.velocity_bootstrap_icon_svg($bi, 24).'</span>';
                                    echo '</div>';
                                }
                                echo '<div class="col p-0 text-muted">';
                                    echo $service;
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    }
                }
            }
            echo '</div>';

            echo '<div class="my-5 pt-2">';
                $client_title = velocitytheme_option('client_title', '');
                if($client_title){
                    echo '<h2 class="fs-2 text-center mb-4">';
                        echo velocity_first_word($client_title);
                    echo '</h2>';
                }
                echo '<div class="velocity-home-clients">';
                    $client_json = velocitytheme_option('client_repeater', '[]');
                    $clients = json_decode($client_json, true);
                    if (is_array($clients) && !empty($clients)) {
                        foreach ($clients as $item) {
                            $img_id = isset($item['id']) ? absint($item['id']) : 0;
                            if($img_id){
                                echo '<div class="p-2">';
                                    echo '<img class="d-inline-block" src="'.wp_get_attachment_image_url($img_id,'').'" />';
                                echo '</div>';
                            }
                        }
                    }
                echo '</div>';
            echo '</div>';?>

            </main><!-- #main -->

    </div><!-- #content -->

</div><!-- #page-wrapper -->

<?php
get_footer();
