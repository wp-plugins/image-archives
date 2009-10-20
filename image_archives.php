<?php
/*
 Plugin Name: Image Archives
 Plugin URI: http://if-music.be/2009/10/15/image-archives/
 Description: Show images that you searched in your database, and the images are linked to the permalink of posts that the images are attached to.
 Version: 0.11
 Author: coppola
 Author URI: http://if-music.be/
 */
 
/*  Copyright 2009 coppola (email : coppola@if-music.be)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class image_archives {
	
	var $v_term_id;
	var $v_order_by;
	var $v_order;
	var $v_str;
	var $v_img_size;
	
	
	function image_archives_query() {
		
		global $wpdb;
		
		$query =
			  "SELECT p1.ID as img_post_id, p1.post_parent as parent_id, $wpdb->posts.post_title as post_title, $wpdb->posts.post_date as post_date"
			. " FROM $wpdb->posts as p1"
			. " LEFT JOIN $wpdb->term_relationships ON ($wpdb->term_relationships.object_id = p1.post_parent)"
			. " LEFT JOIN $wpdb->postmeta ON (p1.ID = $wpdb->postmeta.post_id)"
			. " LEFT JOIN $wpdb->posts ON ($wpdb->posts.ID = p1.post_parent)"
			. " WHERE p1.post_mime_type LIKE 'image%'"
			. " AND p1.post_status = 'inherit'"
			. " AND $wpdb->postmeta.meta_key = '_wp_attached_file'"
			. " AND p1.post_title LIKE '". $wpdb->escape( $this->v_str ) ."'"
			. " AND $wpdb->term_relationships.term_taxonomy_id IN (". $wpdb->escape( $this->v_term_id ) .")"
			. " AND $wpdb->posts.post_status = 'publish'"
			. " ORDER BY ". $wpdb->escape( $this->v_order_by ) ." ". $wpdb->escape( $this->v_order );
		
		$query_array = $wpdb->get_results($query, ARRAY_A);
		// この結果、query_array[ img_post_id / parent_id / post_title / post_date ]

		
		if(is_array($query_array)) {
			return $query_array;
		} else {
			return false;
		}
	}
	
	function image_archives_output ( $atts, $content = null ) {
		
		extract( shortcode_atts( array(
			'term_id'	=>	'0',
			'order_by'	=>	'title',
			'order'		=>	'ASC',
			'str'		=>	'%_logo',
			'size'		=>	'medium',
			'design'	=>	'2',
			'item'		=>	'3',
		), $atts ) );
		
		
		//attsを代入
		$this->v_term_id	= $term_id;
		//order_by
		if ( $order_by == 'title' ) {
			$this->v_order_by	= 'post_title';
		} elseif( $order_by == 'date' ) {
			$this->v_order_by	= 'post_date';
		} else {
			return "shortcode atts error. order_by is required to be 'title' or 'date'.";
		}
		//order
		if ( ($order == 'ASC') || ($order == 'DESC') ) {
			$this->v_order = $order;
		} else {
			return "shortcode atts error. order is required to be 'ASC' or 'DESC'.";
		}
		//string
		$this->v_str = $str;
		//img_size
		if ( ($size == thumbnail) || ($size == medium) || ($size == large) || ($size == full) ) {
			$this->v_img_size = $size;
		} else {
			return "shortcode atts error. size is required to be 'thumbnail' or 'medium' or 'large' or 'full'.";
		}
		//design
		$design = intval( $design );
		//item
		$item = intval( $item );
		if ( $item < 1 ) {
			return "the number of ITEM is required to be larger than 0.";
		} elseif( $item > 100 ) {
			return "the number of ITEM is too big.";
		}
		
		
		//send query
		$arr = $this->image_archives_query();
		
		if( !$arr ) return 'Query Error. Your \"str\"(search strings) is wrong.';
		
		
		// OUTPUT
		$output = "<table class=\"image_archives\"><tbody>\n";
		
		if( $design == 1 ) { 
		
			for ($i=0; $arr[$i] !== NULL; $i++) {
				$img_src = wp_get_attachment_image_src( $arr[$i][img_post_id], $this->v_img_size );
			
				$output .= "  <tr>\n"
						  ."    <td class=\"image_archives\"><div class=\"image_archives\"><a class=\"image_archives\" href=\"". get_permalink($arr[$i][parent_id]) ."\"><img class=\"image_archives\" src=\"$img_src[0]\" alt=\"". $arr[$i][post_title] ."\" title=\"". $arr[$i][post_title] ."\"></a></td><td class=\"image_archives\"><a class=\"image_archives\" href=\"". get_permalink($arr[$i][parent_id]) ."><p class=\"image_archives\">". $arr[$i][post_title] ."</p></a></div></td>\n"
						  ."  </tr>\n";
			}
		
		} elseif ( $design == 2) {
			
			if ( $item == 1 ) {
				
				for ($i=0; $arr[$i] !== NULL; $i++) {
					$img_src = wp_get_attachment_image_src( $arr[$i][img_post_id], $this->v_img_size );
					
					$output .= "  <tr>\n";
					$output .= "    <td class=\"image_archives\"><div class=\"image_archives\"><a class=\"image_archives\" href=\"". get_permalink($arr[$i][parent_id]) ."\"><img class=\"image_archives\" src=\"$img_src[0]\" alt=\"". $arr[$i][post_title] ."\" title=\"". $arr[$i][post_title] ."\"></a><br /><a class=\"image_archives\" href=\"". get_permalink($arr[$i][parent_id]) ."\"><div class=\"image_archives\"><p class=\"image_archives\">". $arr[$i][post_title] ."</p></div></a></td>\n";
					$output .= "  </tr>\n";
				}
			
			} elseif ( $item > 1 ) {
				
				$output .= "  <tr>\n";
				
				for ($i=0; $arr[$i] !== NULL; $i++) {
					$img_src = wp_get_attachment_image_src( $arr[$i][img_post_id], $this->v_img_size );
				
					$output .= "    <td class=\"image_archives\"><div class=\"image_archives\"><a class=\"image_archives\" href=\"". get_permalink($arr[$i][parent_id]) ."\"><img class=\"image_archives\" src=\"$img_src[0]\" alt=\"". $arr[$i][post_title] ."\" title=\"". $arr[$i][post_title] ."\"></a><br /><a class=\"image_archives\" href=\"". get_permalink($arr[$i][parent_id]) ."\"><div class=\"image_archives\"><p class=\"image_archives\">". $arr[$i][post_title] ."</p></div></a></td>\n";
				
					if ( $i % $item == $item - 1 ) $output .= "  </tr>";
				}
			
				// $i はループ終了後 +1 されている
				$i = $i -1;
				if ( $i % $item != $item - 1 ) $output .= "  </tr>";
			}
			
		} else {
			return "shortcode error. \"design\" is required to be 1 or 2." ;
		}
		
		$output .= "</tbody></table>\n";
		return $output;
	}


}



/******************************************************************************
 * shortcode - [image_archives]
 *****************************************************************************/

$image_archives = new image_archives();
add_shortcode('image_archives', array ( $image_archives, 'image_archives_output'));


?>