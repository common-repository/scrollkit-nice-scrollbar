<?php
/**
 * Plugin Name: ScrollKit - Nice Scrollbar
 * Plugin URI:  http://www.modinatheme.com/plugins/scrollkit-nice-scrollbar/
 * Description: Awesome Scrollbar WordPress Plugin. Change Scrollbar Color with Plugin Option Panel.
 * Version:     1.0
 * Author:      ModinaTheme
 * Author URI:  https://profiles.wordpress.org/modinatheme/
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: modniatheme
 */


    defined( 'ABSPATH' ) or die( 'Not Found!' );


    // include files
    add_action('admin_enqueue_scripts', 'scrollkit_scrollbar_script');
    add_action('wp_enqueue_scripts', 'scrollkit_scrollbar_script');


    function scrollkit_scrollbar_script() {

        wp_enqueue_style( 'wp-color-picker' );

        wp_register_script('nicescroll-js', plugins_url( 'assets/js/nicescroll.min.js', __FILE__ ), 'jquery');
        wp_register_script('jscolor-js', plugins_url( 'assets/js/jscolor.js', __FILE__ ), array( 'jquery', 'wp-color-picker' ) );


    	wp_enqueue_script('nicescroll-js');
        wp_enqueue_script('jscolor-js');

    }


    function scrollkit_framework() {
        add_menu_page( 
            __( 'Scrollkit - Options Panel', 'modniatheme' ),
            __( 'Scroll Kit', 'modniatheme' ),
            'edit_posts',
            'scrollkit-setting.php',
            'scrollkit_options',
            'dashicons-controls-pause'
        );
    }
    add_action( 'admin_menu', 'scrollkit_framework' );



    function scrollkit_options(){ ?>
        <div class="wrap">
        <h1>ScrollKit - Options Panel</h1>
          <form method="post" action="options.php">
            <?php 
                settings_fields('scrollkit_option_fields'); 

                do_settings_sections('scrollkit_option_fields'); 

                submit_button( 'Save Settings' ); 
            ?>

            <hr>
                <p><strong>Pro Version</strong> Comming Soon with Multi Options. Stay with ScrollKit</p>
            <hr>
          </form>
        </div>
    <?php }


add_action( 'admin_init', 'scrollkit_section_fields' );

function scrollkit_section_fields() {
    add_settings_section( 'scrollkit_color_pick', 'Color Picker', false, 'scrollkit_option_fields' );
}


add_action('admin_init', 'scrollkit_fields_setup');

function scrollkit_fields_setup() {
    add_settings_field( 'scrollkit_bar_color', 'Choice Bar Color', 'scrollkit_bar_color_field', 'scrollkit_option_fields', 'scrollkit_color_pick' );
}

function scrollkit_bar_color_field( $input ) {
    $options = get_option( 'scrollkit_option_fields' );
     
    echo '<input name="scrollkit_option_fields[scrollkit_bar_color]" class="jscolor" id="scrollkit_bar_color" type="text"  value="' . $options[ 'scrollkit_bar_color' ] . '" />'; 
     
}




register_setting(
    'scrollkit_option_fields',
    'scrollkit_option_fields',
    'scrollkit_validate_input'
);


function scrollkit_validate_input( $input ) {
 
    // Create our array for storing the validated options
    $output = array();
     
    // Loop through each of the incoming options
    foreach( $input as $key => $value ) {
         
        // Check to see if the current option has a value. If so, process it.
        if( isset( $input[$key] ) ) {
         
            // Strip all HTML and PHP tags and properly handle quoted strings
            $output[$key] = strip_tags( stripslashes( $input[ $key ] ) );
             
        } // end if
         
    } // end foreach
     
    // Return the array processing any additional functions filtered by this action
    return apply_filters( 'scrollkit_validate_input', $output, $input );
 
}


function scrollkit_active() {

     $scrollkit = get_option('scrollkit_option_fields');
     $color =  $scrollkit['scrollkit_bar_color'];

 ?>
    <script>
        (function($){
            $(window).on("load",function(){
                $("body").niceScroll({
                    zindex: 999999,
                    cursorcolor: "#<?php echo !empty( $color ) ? $color : '1abc9c'; ?>", // change cursor color in hex
                    cursoropacitymin: 0, // change opacity when cursor is inactive (scrollabar "hidden" state), range from 1 to 0
                    cursoropacitymax: 1, // change opacity when cursor is active (scrollabar "visible" state), range from 1 to 0
                    cursorwidth: "10px", // cursor width in pixel (you can also write "5px")
                    cursorminheight: 30, // set the minimum cursor height (pixel)
                    cursormaxheight: 50, // set the minimum cursor height (pixel)
                    cursorborder: "2px solid transparent", // css definition for cursor border
                    cursorborderradius: "5px", // border radius in pixel for cursor
                    scrollspeed: 40, // scrolling speed
                    mousescrollstep: 40, // scrolling speed with mouse wheel (pixel)
                    usetransition: true,
                    mousescrollstep: 9 * 3,
                });
            });
        })(jQuery);
    </script>

<?php 
}

add_action( 'wp_head', 'scrollkit_active' );