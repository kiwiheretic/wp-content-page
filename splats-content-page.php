<?php
/**
 * Plugin Name: Splat's Contents Page
 * Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
 * Description: To provide a contents page based on categories
 * Version: 0.5
 * Author: Splat
 * Author URI: http://blog.splats-vps.info/
 * License: GPL2
 */
 
 /*
DEMO List Posts (Wordpress Plugin)
Copyright (C) 2009 Paul McKnight
Contact me at http://www.reallyeffective.co.uk

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

//tell wordpress to register the demolistposts shortcode
add_shortcode("splats-contents", "contents_shortcode");

function contents_shortcode($atts) {
  //run function that actually does the work of the plugin
  extract( shortcode_atts( array(
          'categories' => '',
          'title' => '',
          'max_posts' => '20',
          ), $atts ) ); 
  $cat_array = explode(",", $categories);
  ob_start();

  echo "<div class='featured-cat-div'>\n";
  echo "<div class='title'>" . $title . "</div>";
  
  foreach ($cat_array as $slug) {
    echo "<div class='cat-section'>";
    $cat_obj = get_category_by_slug( $slug );
    if ($cat_obj !== false) {
        $cat_id = $cat_obj -> term_id;  
        $cat_name = get_cat_name ($cat_id);
        echo "<span class='cat-heading'>" . $cat_name . "</span><br/>\n";    
        $cat_desc = category_description( $cat_id );
        $cat_desc = str_replace (array( '<p>','</p>'), '', $cat_desc);
        $cat_desc = '<span class="cat_desc">' . $cat_desc . '</span>';
        echo $cat_desc . "<br/>\n";
        
        $args = array(
                'numberposts'     => $max_posts,
                'offset'          => 0,
                'category'        => $cat_id,
                'orderby'         => 'post_date',
                'order'           => 'DESC',
                'post_type'       => 'post',
                'post_status'     => 'publish',
                'suppress_filters' => true );

           $posts_array = get_posts( $args );
           $str = "<ul class=\"cat-posts\">" . PHP_EOL;

           foreach ($posts_array as $elmt) {
             $post_title = htmlentities($elmt->post_title);
             $id = $elmt->ID;
             $permalink = get_permalink( $id );
        $str1 = <<<EOT
             <li>
             <a href="$permalink">$post_title</a>
             </li>
             
EOT;
             $str = $str . $str1;

           }
           
           $str = $str . "</ul>";

           echo $str;        
    } else {
        echo "No such category slug $slug<br/>\n";
    }
    echo "</div>";  # end section
  }; // end foreach
  echo "</div>\n"; # end featured-cat-div
  
  echo "<br/>";

  $contents_output = ob_get_clean();

  //send back text to replace shortcode in post
  return $contents_output;
}

function contents_function() {
  //process plugin
  $demolp_output = "Hello World!";
  //send back text to calling function
  return $demolp_output;
}

 ?>
