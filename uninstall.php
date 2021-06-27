<?php

/**
 * Trigger this file on Plugin uninstall
 * 
 * @package My tests
 */

 if( !define('WP_UNINSTALL_PLUGIN')) {
     die;
 }

 // Clear DB data stored data

 $books = ge_posts(array(
         'post_type' => 'book',
         'numberposts' => -1
));

foreach( $books as $book ) {
    wp_delete_post( $book->id, false );
    
}