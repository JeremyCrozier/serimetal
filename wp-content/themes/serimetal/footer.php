        </main>  
        <footer id="footer">
            <div class="inside">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo" aria-label="<?php bloginfo( 'name' ); ?>" title="Accueil">
                    <img src="/wp-content/uploads/icons/logo-serimetal-blue-white.svg" alt="<?php bloginfo( 'name' ); ?>" width="220" height="32" class="logo-desktop">
                </a>

                <div class="footer-content">
                    <nav class="footer-nav" aria-label="<?php esc_attr_e( 'Pôles', 'Serimetal' ); ?>">
                        <?php $poles_menu = wp_get_nav_menu_object( 'poles' ); ?>
                        <?php if ( $poles_menu ) : ?>
                            <p class="footer-nav__title heading-small"><?php echo esc_html( $poles_menu->name ); ?></p>
                        <?php endif; ?>
                        <?php
                        wp_nav_menu(
                            array(
                                'menu'        => 'poles',
                                'container'   => '',
                                'menu_class'  => 'menu menu-poles',
                                'fallback_cb' => false,
                            )
                        );
                        ?>
                    </nav>

                    <nav class="footer-nav" aria-label="<?php esc_attr_e( 'Navigation', 'Serimetal' ); ?>">
                        <?php $navigation_menu = wp_get_nav_menu_object( 'navigation' ); ?>
                        <?php if ( $navigation_menu ) : ?>
                            <p class="footer-nav__title heading-small"><?php echo esc_html( $navigation_menu->name ); ?></p>
                        <?php endif; ?>
                        <?php
                        wp_nav_menu(
                            array(
                                'menu'        => 'navigation',
                                'container'   => '',
                                'menu_class'  => 'menu menu-navigation',
                                'fallback_cb' => false,
                            )
                        );
                        ?>
                    </nav>

                    <div class="footer-contact">
                        <p>
                            116 Impasse Pasteur<br>
                            69780 Toussieu<br>
                            FRANCE
                        </p>
                        <?php
                        wp_nav_menu(
                            array(
                                'menu'        => 'reseaux-sociaux',
                                'container'   => '',
                                'menu_class'  => 'menu menu-social',
                                'fallback_cb' => false,
                            )
                        );
                        ?>
                    </div>
                </div>
            </div>

            <p class="footer-tagline">Concevoir. Fabriquer. Assembler</p>
        </footer>

        <?php wp_footer(); ?>
    </body>
</html>
