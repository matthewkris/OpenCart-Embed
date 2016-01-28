<?php

/*
 * Plugin Name: OpenCart Embed
 * Description: Embed OpenCart products in WordPress posts or pages
 * Version: 1.0
 * Author: Matthew Kris
 * Author URI: http://github.com
 * License:     GPL2

  OpenCart Embed is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 2 of the License, or
  any later version.

  OpenCart Embed is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with OpenCart Embed. If not, see <http://www.gnu.org/licenses/>.
 */

if (!function_exists('embed_products_register_shortcode')) {

    /**
     * Setup our shortcode
     */
    function embed_products_register_shortcode() {
        add_shortcode('opencart-embed', 'embed_products');
    }

}

if (!function_exists('add_product_style')) {

    /**
     * Add our style sheet
     */
    function add_product_style() {
        wp_enqueue_style('opencart-embed-style', plugins_url('css/style.css', __FILE__));
    }

}

if (!function_exists('embed_products')) {

    /**
     * Embed our products into post or page
     * @param string $atts
     * @return string
     */
    function embed_products($atts) {

        global $wpdb;

        $a = shortcode_atts(array(
            'id' => '1'
                ), $atts);

        // check to see if we passed one product ID or multiple
        $product_ids = array();
        if (strpos($a['id'], ',') !== false) {
            $product_ids = explode(',', $a['id']);
        } else {
            $product_ids[] = $a['id'];
        }

        // loop through products and gather data
        $products = array();
        foreach ($product_ids as $product_id) {
            
            $query = "SELECT p.product_id, p.image, p.price, pd.name, u.keyword AS url FROM product p
                    LEFT JOIN product_description pd ON pd.product_id = p.product_id 
                    LEFT JOIN url_alias u ON u.query = 'product_id=" . $product_id . "'
                    WHERE p.product_id = " . $product_id;
            $results = $wpdb->get_row($query);
            if ($results) {
                //print_r($results);
                $products[] = $results;
            } else {
                // do nothing for now
            }
        }

        echo '<!--<pre>';
        print_r($products);
        echo '</pre>-->';
        
        // create our output from our template
        $output = '<div class="oc-product-wrapper">';
        if (sizeof($products) > 0) {
            foreach ($products as $product) {
                ob_start();
                include plugin_dir_path(__FILE__) . 'includes/template.php';
                $output .= ob_get_contents();
                ob_end_clean();
            }
        }
        $output .= '</div>';
        return $output;
    }

}

// [opencart-embed id="1,2,3,4"]
add_action('init', 'embed_products_register_shortcode');
add_action('wp_head', 'add_product_style');
