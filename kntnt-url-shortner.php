<?php

/**
 * Plugin main file.
 *
 * @wordpress-plugin
 * Plugin Name: Kntnt's URL Shortner plugin
 * Plugin URI:  https://www.kntnt.com/
 * Description: Redirects https://www.example.com/1234 to to the post with id 1234. 
 * Version:     1.0.0
 * Author:      Thomas Barregren
 * Author URI:  https://www.kntnt.com/
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */


defined('WPINC') || die;

new Kntnt_Url_Shortner('kntnt-url-shortner');

class Kntnt_Url_Shortner {

  // Plugin's namespace.
  private $ns;
  
  private $pattern = '(\d+)$';
  
  private $replace = 'index.php/?p=$1';

  // Start here. :-)
  public function __construct($ns) {

    // Register activation function.
    register_activation_hook(__FILE__, function() {
      $this->add_rewrite_rule();
      flush_rewrite_rules();
    });

    // Register deactivation function.
    register_deactivation_hook(__FILE__, function() {
      $this->del_rewrite_rule();
      flush_rewrite_rules();
    });

    // Add rewrite rules in case WordPress or another plugin flushes rules.
    add_action('init', [$this, 'add_rewrite_rule']);

  }

  public function add_rewrite_rule() {
    global $wp_rewrite;
    $wp_rewrite->non_wp_rules[$this->pattern] = $this->replace;
  }

  public function del_rewrite_rule() {
    global $wp_rewrite;
    unset($wp_rewrite->non_wp_rules[$this->pattern]);
  }

}
