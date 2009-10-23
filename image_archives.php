<?php
/*
 Plugin Name: Image Archives
 Plugin URI: http://if-music.be/2009/10/15/image-archives/
 Description: Show images that you searched in your database, and the images are linked to the permalink of posts that the images are attached to.
 Version: 0.20
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
	var $v_limit;
	var $v_img_size;
	var $v_item;
	var $v_design;
	var $v_date_format;
	var $v_date_show;
	
	
	// shortcode function
	// shortcode は return を用いても良いが、template tag では使えない。
	function image_archives_shortcode ( $atts, $content = null ) {
		
		extract( shortcode_atts( array(
			'term_id'		=>	'1',
			'order_by'		=>	'title',
			'order'			=>	'ASC',
			'str'			=>	'%_logo',
			'limit'			=>	'0,50',
			'size'			=>	'medium',
			'design'		=>	'2',
			'item'			=>	'3',
			'date_format'	=>	'Y-m-d',
			'date_show'		=>	'off',
		), $atts ) );
		
		
		//Substitution
		
		//term_id
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
		//limit
		$this->v_limit = $limit;
		//img_size
		if ( ($size == thumbnail) || ($size == medium) || ($size == large) || ($size == full) ) {
			$this->v_img_size = $size;
		} else {
			return "shortcode atts error. size is required to be 'thumbnail' or 'medium' or 'large' or 'full'.";
		}
		//design
		$this->v_design = intval( $design );
		//item
		$this->v_item = intval( $item );
		if ( $item < 1 ) {
			return "the number of 'item' is required to be larger than 0.";
		} elseif( $item > 100 ) {
			return "the number of 'item' is too big.";
		}
		//date format
		$this->v_date_format = $date_format;
		//date show
		if ( ($date_show == on) || ( $date_show == off) ) {
			$this->v_date_show = $date_show;
		} else {
			return "date_show is required to be 'on' or 'off'.";
		}
		
		return $this->image_archives_output();
		
	}
	
	
	//template tag function
	// cannot use "return".
	function image_archives_template_tag ( $args = '' ) {
		
		$default = array (
			'term_id'		=>	'1',
			'order_by'		=>	'title',
			'order'			=>	'ASC',
			'str'			=>	'%_logo',
			'limit'			=>	'0,50',
			'size'			=>	'medium',
			'design'		=>	'2',
			'item'			=>	'3',
			'date_format'	=>	'Y-m-d',
			'date_show'		=>	'off',
		);
		
		$args = wp_parse_args($args, $default);
		
		extract( $args, EXTR_SKIP );
		
		
		//Substitution
		
		//term_id
		$this->v_term_id	= $term_id;
		//order_by
		if ( $order_by == 'title' ) {
			$this->v_order_by	= 'post_title';
		} elseif( $order_by == 'date' ) {
			$this->v_order_by	= 'post_date';
		} else {
			echo "shortcode atts error. order_by is required to be 'title' or 'date'.";
		}
		//order
		if ( ($order == 'ASC') || ($order == 'DESC') ) {
			$this->v_order = $order;
		} else {
			echo "shortcode atts error. order is required to be 'ASC' or 'DESC'.";
		}
		//string
		$this->v_str = $str;
		//limit
		$this->v_limit = $limit;
		//img_size
		if ( ($size == thumbnail) || ($size == medium) || ($size == large) || ($size == full) ) {
			$this->v_img_size = $size;
		} else {
			echo "shortcode atts error. size is required to be 'thumbnail' or 'medium' or 'large' or 'full'.";
		}
		//design
		$this->v_design = intval( $design );
		//item
		$this->v_item = intval( $item );
		if ( $item < 1 ) {
			echo "the number of 'item' is required to be larger than 0.";
		} elseif( $item > 100 ) {
			echo "the number of 'item' is too big.";
		}
		//date format
		$this->v_date_format = $date_format;
		//date show
		if ( ($date_show == 'on') || ( $date_show == 'off') ) {
			$this->v_date_show = $date_show;
		} else {
			echo "date_show is required to be 'on' or 'off'.";
		}
		
		echo $this->image_archives_output();
	}
	
	
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
			. " ORDER BY ". $wpdb->escape( $this->v_order_by ) ." ". $wpdb->escape( $this->v_order )
			. " LIMIT ". $wpdb->escape( $this->v_limit );
		
		$query_array = $wpdb->get_results($query, ARRAY_A);
		// この結果、query_array[ img_post_id / parent_id / post_title / post_date ]
		
		
		if(is_array($query_array)) {
			return $query_array;
		} else {
			return false;
		}
	}
	
	function image_archives_output () {
		
		//send query
		$arr = $this->image_archives_query();
		
		if( !$arr ) return "Query Error. Your \"str\"(search strings) may be wrong or your input \"term_id\" don't exsit, or \"limit\" may be wrong.";
		
		
		// OUTPUT
		
		if( $this->v_design == 1 ) { 
			
			$output = "<table class=\"img_arc\"><tbody>\n";
			
			for ($i=0; $arr[$i] !== NULL; $i++) {
				$img_src = wp_get_attachment_image_src( $arr[$i][img_post_id], $this->v_img_size );
				
				$output .= "  <tr>\n"
				 		.  "    <td class=\"img_arc\">\n"
						.  "      <div class=\"img_arc_img\"><a class=\"img_arc\" href=\"". get_permalink($arr[$i][parent_id]) ."\"><img class=\"img_arc\" src=\"$img_src[0]\" alt=\"". $arr[$i][post_title] ."\" title=\"". $arr[$i][post_title] ."\" /></a></div>\n"
						.  "    </td>\n"
						.  "    <td class=\"img_arc\">\n"
						.  "      <div class=\"img_arc_text\"><a class=\"img_arc\" href=\"". get_permalink($arr[$i][parent_id]) ."\"><p class=\"img_arc\">". $arr[$i][post_title] ."</p></a>";
				if ( $this->v_date_show == 'on' ) $output .= "<p class=\"img_src_date\">( ". date( "$this->v_date_format", strtotime($arr[$i][post_date]) ) ." )</p>" ;
				$output .= "      </div>\n"
						.  "    </td>\n"
						.  "  </tr>\n";
			}
			
			$output .= "</tbody></table>\n";
			
		} elseif ( $this->v_design == 2) {
			
			if ( $this->v_item == 1 ) {
				
				$output = "<table class=\"img_arc\"><tbody>\n";
				
				for ($i=0; $arr[$i] !== NULL; $i++) {
					$img_src = wp_get_attachment_image_src( $arr[$i][img_post_id], $this->v_img_size );
					
					$output .= "  <tr>\n"
							.  "    <td class=\"img_arc\">\n"
							.  "      <div class=\"img_arc_img\"><a class=\"img_arc\" href=\"". get_permalink($arr[$i][parent_id]) ."\"><img class=\"img_arc\" src=\"$img_src[0]\" alt=\"". $arr[$i][post_title] ."\" title=\"". $arr[$i][post_title] ."\" /></a></div>\n"
							.  "      <div class=\"img_arc_text\"><a class=\"img_arc\" href=\"". get_permalink($arr[$i][parent_id]) ."\"><p class=\"img_arc\">". $arr[$i][post_title] ."</p></a>";
					if ( $this->v_date_show == 'on' ) $output .= "<p class=\"img_src_date\">( ". date( "$this->v_date_format", strtotime($arr[$i][post_date]) ) ." )</p>" ;
					$output .= "      </div>\n"
							.  "    </td>\n"
							.  "  </tr>\n";
				}
				
				$output .= "</tbody></table>\n";
				
			} elseif ( $this->v_item > 1 ) {
				
				$output = "<table class=\"img_arc\"><tbody>\n";
				
				for ($i=0; $arr[$i] !== NULL; $i++) {
					$img_src = wp_get_attachment_image_src( $arr[$i][img_post_id], $this->v_img_size );
					
					if ( $i % $this->v_item == 0 ) $output .= "  <tr>\n";
					$output .= "    <td class=\"img_arc\">\n"
							.  "      <div class=\"img_arc_img\"><a class=\"img_arc\" href=\"". get_permalink($arr[$i][parent_id]) ."\"><img class=\"img_arc\" src=\"$img_src[0]\" alt=\"". $arr[$i][post_title] ."\" title=\"". $arr[$i][post_title] ."\" /></a></div>\n"
							.  "      <div class=\"img_arc_text\"><a class=\"img_arc\" href=\"". get_permalink($arr[$i][parent_id]) ."\"><p class=\"img_arc\">". $arr[$i][post_title] ."</p></a>";
					if ( $this->v_date_show == 'on' ) $output .= "<p class=\"img_src_date\">( ". date( "$this->v_date_format", strtotime($arr[$i][post_date]) ) ." )</p>" ;
					$output	.= "      </div>\n"
							.  "    </td>\n";
					
					if ( $i % $this->item == $this->item - 1 ) $output .= "  </tr>\n";
				}
				
				// $i はループ終了後 +1 されている
				$i = $i -1;
				if ( $i % $this->item != $this->item - 1 ) $output .= "  </tr>\n";
				
				$output .= "</tbody></table>\n";
			}
			
		} elseif ( $this->v_design == 3 ) {
			
			$output = "<div class=\"img_arc\">\n";
			
			for ($i=0; $arr[$i] !== NULL; $i++) {
				$img_src = wp_get_attachment_image_src( $arr[$i][img_post_id], $this->v_img_size );
				
				$output .= "  <p class=\"img_arc\"><a class=\"img_arc\" href=\"". get_permalink($arr[$i][parent_id]) ."\"><img class=\"img_arc\" src=\"$img_src[0]\" alt=\"". $arr[$i][post_title] ."\" title=\"". $arr[$i][post_title] ."\" /></a>\n"
						.  "  <a class=\"img_arc\" href=\"". get_permalink($arr[$i][parent_id]) ."\">". $arr[$i][post_title] ."</a>";
				if ( $this->v_date_show == 'on' ) $output .= "  ( ". date( "$this->v_date_format", strtotime($arr[$i][post_date]) ) ." )" ;
				$output .= "</p>\n";
			}
			
			$output .= "</div>\n";
			
		} else {
			echo "\"design\" is required to be from 1 to 3." ;
		}
		
		return $output;
	}

}


/******************************************************************************
 * grobal template tag - wp_image_archives()
 *****************************************************************************/

function wp_image_archives( $args = '' ) {
	global $image_archives;
	echo $image_archives->image_archives_template_tag ( $args );
}



/******************************************************************************
 * shortcode - [image_archives]
 *****************************************************************************/

$image_archives = new image_archives();
add_shortcode( 'image_archives', array ( $image_archives, 'image_archives_shortcode' ));


?>