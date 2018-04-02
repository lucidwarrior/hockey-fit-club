<?php if ( 'on' == et_get_option( 'divi_back_to_top', 'false' ) ) : ?>

	<span class="et_pb_scroll_top et-pb-icon"></span>

<?php endif;

if ( ! is_page_template( 'page-template-blank.php' ) ) : ?>

			<footer id="main-footer">
				<?php get_sidebar( 'footer' ); ?>

                <?php
                if ( has_nav_menu( 'footer-menu' ) ) : ?>

                    <div id="et-footer-nav">
                        <div class="container">
                            <?php
                                wp_nav_menu( array(
                                    'theme_location' => 'footer-menu',
                                    'depth'          => '1',
                                    'menu_class'     => 'bottom-nav',
                                    'container'      => '',
                                    'fallback_cb'    => '',
                                ) );
                            ?>
                        </div>
                    </div> <!-- #et-footer-nav -->

                <?php endif; ?>

                <div id="footer-bottom">
                    <div class="container clearfix">
                        <?php
                            if ( false !== et_get_option( 'show_footer_social_icons', true ) ) {
                                get_template_part( 'includes/social_icons', 'footer' );
                            }
                        ?>

                                <p id="footer-info"><?php
                            if ( '' !== et_get_option( 'footer_copr_text' ) && false !== et_get_option( 'footer_copr_text' ) ) {
                                echo et_get_option( 'footer_copr_text' );
                            } else {
                                        echo '<a href="https://hockeyfitclub.com">Hockey Fit Club</a> - Copyright 2017 | Designed by <a href="https://lucidwisdom.com">Lucid Wisdom Digital</a>';
                                   }
                        ?></p>
                    </div>	<!-- .container -->
                </div><!-- #footer-bottom -->
                    
			</footer> <!-- #main-footer -->

<?php endif; // ! is_page_template( 'page-template-blank.php' ) ?>

<?php wp_footer(); ?>
<?php //phpinfo();?>
