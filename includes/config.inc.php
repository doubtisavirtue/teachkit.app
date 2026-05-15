<?php

// Config
date_default_timezone_set( 'America/Los_Angeles' );

// DATABASE
include( '/home/skeptoidcom/connection.php' );

// Make sure they have their 'u' cookie; uniquely identifies a listener, kinda sorta
if ( !isset( $_COOKIE[ 'u' ] ) ) {
    $str = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $i = 0;
    $u = '';
    while ( $i < 20 ) {
        $u .= substr( $str, rand( 0, 35 ), 1 );
        $i++;
    }
    setcookie( 'u', $u, time() + 3600 * 24 * 365, '/' );
}

// SANITIZE FUNCTION
// Example usage: $good = sanitize($bad);
// $_POST = sanitize($_POST);
// Throws an error if you don't have a database connection!!
function cleanInput( $input ) {
    $search = array(
        '@<script[^>]*?>.*?</script>@si', // Strip out javascript
        '@<[\/\!]*?[^<>]*?>@si', // Strip out HTML tags
        '@<style[^>]*?>.*?</style>@siU', // Strip style tags properly
        '@<![\s\S]*?--[ \t\n\r]*>@' // Strip multi-line comments
    );
    $output = preg_replace( $search, '', $input );
    return $output;
}

function sanitize( $input ) {
    if ( is_array( $input ) ) {
        foreach ( $input as $var => $val ) {
            $output[ $var ] = sanitize( $val );
        }
    } else {
        if ( get_magic_quotes_gpc() ) {
            $input = stripslashes( $input );
        }
        $input = cleanInput( $input );
        $output = mysql_real_escape_string( $input );
    }
    return $output;
}


?>