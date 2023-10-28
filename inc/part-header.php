<div class="container">

    <div class="row m-0">
        <div class="col-sm-4 col-lg-3 py-3 velocity-contact">
            <span class="dashicons dashicons-backup"></span><strong class="text-color-theme">Jam Kerja</strong><br>
            <span class="text-secondary"><?php echo velocitytheme_option('jam_kerja','');?></span>
        </div>
        <div class="col-sm-4 col-lg-3 py-3 velocity-contact">
            <span class="dashicons dashicons-email-alt"></span><strong class="text-color-theme">Email</strong><br>
            <a class="text-secondary" href="mailto:<?php echo velocitytheme_option('contact_email','');?>"><?php echo velocitytheme_option('contact_email','');?></a>
        </div>
        <div class="col-sm-4 col-lg-3 py-3 velocity-contact">
            <span class="dashicons dashicons-phone"></span><strong class="text-color-theme">Telepon</strong><br>
            <a class="text-secondary" href="tel:<?php echo velocitytheme_option('contact_telepon','');?>"><?php echo velocitytheme_option('contact_telepon','');?></a>
        </div>
        <div class="col-sm-12 col-lg-3 py-3 bg-theme text-white position-relative velocity-contact">        
            <a class="wa-link" href="https://wa.me/<?php echo velocitytheme_option('contact_wa','');?>"></a>
            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="text-white align-middle bi bi-whatsapp" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/></svg>
            <div class="fw-bold mt-sm-1 mt-md-0">HUBUNGI KAMI <span class="d-md-block">VIA WHATSAPP</span></div>
        </div>
    </div>


    <nav id="velocity-navbar" class="navbar navbar-expand-md d-block navbar-light py-3 border-top" aria-labelledby="main-nav-label">

        <h2 id="main-nav-label" class="screen-reader-text">
            <?php esc_html_e('Main Navigation', 'justg'); ?>
        </h2>

        <div class="row align-items-center m-0">
            <div class="col-9 col-sm-8 col-md-2 px-sm-0 text-start mb-3 mb-sm-0">
                <?php if (has_custom_logo()) {
                    echo get_custom_logo();
                } ?>
            </div>
            <div class="col-3 col-sm-4 col-md-10 pe-sm-0">

                <div class="offcanvas offcanvas-start" tabindex="-1" id="navbarNavOffcanvas">

                    <div class="offcanvas-header justify-content-end">
                        <button type="button" class="btn-close btn-close-dark text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div><!-- .offcancas-header -->

                    <!-- The WordPress Menu goes here -->
                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location'  => 'primary',
                            'container_class' => 'offcanvas-body',
                            'container_id'    => '',
                            'menu_class'      => 'navbar-nav navbar-light justify-content-end flex-md-wrap flex-grow-1',
                            'fallback_cb'     => '',
                            'menu_id'         => 'primary-menu',
                            'depth'           => 4,
                            'walker'          => new justg_WP_Bootstrap_Navwalker(),
                        )
                    ); ?>

                </div><!-- .offcanvas -->

                <div class="menu-header d-md-none position-relative text-end" data-bs-theme="dark">
                    <button class="navbar-toggler bg-theme text-white text-end" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarNavOffcanvas" aria-controls="navbarNavOffcanvas" aria-expanded="false" aria-label="<?php esc_attr_e('Toggle navigation', 'justg'); ?>">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
            </div>
        </div>

    </nav><!-- .site-navigation -->

</div>


<?php if (!is_front_page()) {
    echo '<div class="bg-light">';
        echo '<div class="container py-5">';
            echo velocity_title();
        echo '</div>';
    echo '</div>';
    echo '<div class="bg-white border-bottom mb-5">';
        echo '<div class="container py-3">';
            if(function_exists('aioseo_breadcrumbs')){
                aioseo_breadcrumbs();
            } else {
                echo justg_breadcrumb();
            }
        echo '</div>';
    echo '</div>';
} ?>
