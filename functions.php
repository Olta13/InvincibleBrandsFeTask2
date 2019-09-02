<?php
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );

function enqueue_parent_styles() {
 wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}

/**
 * Function that adds two custom billing fields birthdate and gender
 */
function add_birthdate_gender_fields($billing_fields) {

    $billing_fields['billing_birth_date'] = array(
        'type'        => 'date',
        'label'       => __('Birth date'),
        'class'       => array(''),
        'priority'    => 25,
        'required'    => true,
        'clear'       => true,
    );

    $billing_fields['billing_gender'] = array(
        'type'        => 'select',
        'class'       => array(''),
        'label'       => __('Gender'),
        'required'    => true,
        'priority'    => 26,
        'options'     => array(
           '' => __('Select gender'),
           'F' => __('Female'),
           'M' => __('Male')
       ),
        'default' => ''
    );

    return $billing_fields;
}
add_filter( 'woocommerce_billing_fields', 'add_birthdate_gender_fields', 20, 1 );


/**
 * Function that checks if customer age is above 18 years old 
 */
function check_birth_date() {
    if( isset($_POST['billing_birth_date']) && ! empty($_POST['billing_birth_date']) ){
        $age = date_diff(date_create($_POST['billing_birth_date']), date_create('now'))->y;

        if( $age < 18 ){
            wc_add_notice( __( "You need at least to be 18 years old, to be able to checkout." ), "error" );
        }
    }
}
add_action('woocommerce_checkout_process', 'check_birth_date');
