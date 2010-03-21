<?php
/*
 Plugin Name: Image Archives
 Plugin URI: http://if-music.be/2009/11/10/image-archives/
 Description: Show images that you searched in your database, and the images are linked to the permalink of posts that the images are attached to.
 Version: 0.33
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
	var $v_column;
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
			'str'			=>	'%',
			'limit'			=>	'0,50',
			'size'			=>	'medium',
			'design'			=>	'2',
			'item'			=>	'9',
			'column'			=>	'3',
			'date_format'		=>	'Y-m-d',
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
		//column
		if ( $column < 1 ) {
			return "the number of 'column' is required to be larger than 0.";
		} elseif( $column > 100 ) {
			return "the number of 'column' is too big.";
		}
		$this->v_column = intval( $column );
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
			'item'			=>	'9',
			'column'		=>	'3',
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
		//column
		if ( $column < 1 ) {
			echo "the number of 'column' is required to be larger than 0.";
		} elseif( $column > 100 ) {
			echo "the number of 'column' is too big.";
		}
		$this->v_column = intval( $column );
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
	
	
	function image_archives_query( &$row_count = 0 ) {
		
		global $wpdb;
		
		$query =
			  "SELECT SQL_CALC_FOUND_ROWS p1.ID as img_post_id, p1.post_parent as parent_id, $wpdb->posts.post_title as post_title, $wpdb->posts.post_date as post_date"
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
		
		$row_count = $wpdb->num_rows;
		
		// get the number of row in without limit
		//$query_count = "SELECT FOUND_ROWS();";
		//$row_count = $wpdb->get_var($query_count);
		
		if(is_array($query_array)) {
			return $query_array;
		} else {
			return false;
		}
	}
	
	function image_archives_output () {
		
		//send query
		$arr = $this->image_archives_query( $count );
		
		if( !$arr ) return "Query Error? Searching your database was done, but any images were not found. Your 'str'(search strings) may be wrong or your input 'term_id' don't exist, or 'limit' may be wrong.";
		
		//echo "all count: $count";
		
		// OUTPUT
		
		if( $this->v_design == 1 ) { 
			
			$output = "<table class='img_arc'><tbody>\n";
			
			for ($i=0; $arr[$i] !== NULL; $i++) {
				$img_src = wp_get_attachment_image_src( $arr[$i][img_post_id], $this->v_img_size );
				
				$output .= "  <tr>\n"
				 		.  "    <td class='img_arc'>\n"
						.  "      <div class='img_arc_img'><a class='img_arc' href='". get_permalink($arr[$i][parent_id]) ."'><img class='img_arc' src='$img_src[0]' alt='". attribute_escape($arr[$i][post_title]) ."' title='". attribute_escape($arr[$i][post_title]) ."' /></a></div>\n"
						.  "    </td>\n"
						.  "    <td class='img_arc'>\n"
						.  "      <div class='img_arc_text'><p class='img_arc'><a class='img_arc' href='". get_permalink($arr[$i][parent_id]) ."'>". $arr[$i][post_title] ."</a></p>";
				if ( $this->v_date_show == 'on' ) $output .= "<p class='img_arc_date'>( ". date( "$this->v_date_format", strtotime($arr[$i][post_date]) ) ." )</p>" ;
				$output .= "      </div>\n"
						.  "    </td>\n"
						.  "  </tr>\n";
			}
			
			$output .= "</tbody></table>\n";
			
		} elseif ( $this->v_design == 2) {
			
			if ( $this->v_column == 1 ) {
				
				$output = "<table class='img_arc'><tbody>\n";
				
				for ($i=0; $arr[$i] !== NULL; $i++) {
					$img_src = wp_get_attachment_image_src( $arr[$i][img_post_id], $this->v_img_size );
					
					$output .= "  <tr>\n"
							.  "    <td class='img_arc'>\n"
							.  "      <div class='img_arc_img'><a class='img_arc' href='". get_permalink($arr[$i][parent_id]) ."'><img class='img_arc' src='$img_src[0]' alt='". attribute_escape($arr[$i][post_title]) ."' title='". attribute_escape($arr[$i][post_title]) ."' /></a></div>\n"
							.  "      <div class='img_arc_text'><p class='img_arc'><a class='img_arc' href='". get_permalink($arr[$i][parent_id]) ."'>". $arr[$i][post_title] ."</a></p>";
					if ( $this->v_date_show == 'on' ) $output .= "<p class='img_arc_date'>( ". date( "$this->v_date_format", strtotime($arr[$i][post_date]) ) ." )</p>" ;
					$output .= "      </div>\n"
							.  "    </td>\n"
							.  "  </tr>\n";
				}
				
				$output .= "</tbody></table>\n";
				
			} elseif ( $this->v_column > 1 ) {
				
				$output = "<table class='img_arc'><tbody>\n";
				
				for ($i=0; $arr[$i] !== NULL; $i++) {
					$img_src = wp_get_attachment_image_src( $arr[$i][img_post_id], $this->v_img_size );
					
					if ( $i % $this->v_column == 0 ) $output .= "  <tr>\n";
					$output .= "	<td class='img_arc'>\n"
							.  "		<div class='img_arc_img'><a class='img_arc' href='". get_permalink($arr[$i][parent_id]) ."'><img class='img_arc' src='$img_src[0]' alt='". attribute_escape($arr[$i][post_title]) ."' title='". attribute_escape($arr[$i][post_title]) ."' /></a></div>\n"
							.  "		<div class='img_arc_text'><p class='img_arc'><a class='img_arc' href='". get_permalink($arr[$i][parent_id]) ."'>". $arr[$i][post_title] ."</a></p>";
					if ( $this->v_date_show == 'on' ) $output .= "<p class='img_arc_date'>( ". date( "$this->v_date_format", strtotime($arr[$i][post_date]) ) ." )</p>" ;
					$output	.= "		</div>\n"
							.  "	</td>\n";
					
					if ( $i % $this->v_column == $this->v_column - 1 ) $output .= "  </tr>\n";
				}
				
				// $i はループ終了後 +1 されている
				$i = $i -1;
				if ( $i % $this->v_column != $this->v_column - 1 ) $output .= "  </tr>\n";
				
				$output .= "</tbody></table>\n";
			}
			
		} elseif ( $this->v_design == 3 ) {
			
			$output = "<div class='img_arc'>\n";
			
			for ($i=0; $arr[$i] !== NULL; $i++) {
				$img_src = wp_get_attachment_image_src( $arr[$i][img_post_id], $this->v_img_size );
				
				$output .= "	<p class='img_arc'><a class='img_arc' href='". get_permalink($arr[$i][parent_id]) ."'><img class='img_arc' src='$img_src[0]' alt='". attribute_escape($arr[$i][post_title]) ."' title='". attribute_escape($arr[$i][post_title]) ."' /></a>\n"
						.  "	 <a class='img_arc' href='". get_permalink($arr[$i][parent_id]) ."'>". $arr[$i][post_title] ."</a>";
				if ( $this->v_date_show == 'on' ) $output .= "  ( ". date( "$this->v_date_format", strtotime($arr[$i][post_date]) ) ." )" ;
				$output .= "</p>\n";
			}
			
			$output .= "</div>\n";
			
		} elseif ( $this->v_design == 4 ) {
			
			if( $this->v_column > 1)
			{
				$output = "<script type='text/javascript' src='". get_bloginfo('home') ."/wp-content/plugins/image-archives/jquery-1.4.2.min.js'></script>\n"
						. "<script type='text/javascript' src='". get_bloginfo('home') ."/wp-content/plugins/image-archives/jquery-ui-1.7.2.custom.min.js'></script>\n"
						. "<script type='text/javascript' src='". get_bloginfo('home') ."/wp-content/plugins/image-archives/image_archives_accordion.js'></script>\n"
						. "<div id='accordion'>";
				
				// calculate a number of pages.
				$page = $count / $this->v_item;
				if ( $page != (int) $page ) $page = (int) $page + 1;
				
				// $i is a global number of items.
				$i=0;
				
				// ページ毎に
				for( $p=1; $p <= $page ; $p++ ) {
					
					// accordionのタイトル
					$output .= "<h3><a href='#'>";
					
					// 最後のページの時で無い時
					if( $p < $page ) {
						$output .= "Section $p ( ". $this->v_item * $p ." / $count )</a></h3>\n";
					} elseif ( $p == $page ) {
					//最後のページの時
						$output .= "Section $p ( $count / $count )</a></h3>\n";
					}
					
					// accordion head
					$output .= "<div>\n";
					
					// テーブルを作成
					$output .= "<table class='img_arc'><tbody>\n";
					
					// $cr is a number of images per a page.
					for ( $cr = 0; ( $cr <= $this->v_item -1 ) && ( $arr[$i] !== NULL ) ; $cr++) {
						
						$img_src = wp_get_attachment_image_src( $arr[$i][img_post_id], $this->v_img_size );
						
						if ( $cr % $this->v_column == 0 ) $output .= "	<tr>\n";
						$output .= "	<td class='img_arc'>\n"
								.  "		<div class='img_arc_img'><a class='img_arc' href='". get_permalink($arr[$i][parent_id]) ."'><img class='img_arc' src='$img_src[0]' alt='". attribute_escape($arr[$i][post_title]) ."' title='". attribute_escape($arr[$i][post_title]) ."' /></a></div>\n"
								.  "		<div class='img_arc_text'><p class='img_arc'><a class='img_arc' href='". get_permalink($arr[$i][parent_id]) ."'>". $arr[$i][post_title] ."</a></p>";
						if ( $this->v_date_show == 'on' ) $output .= "<p class='img_arc_date'>( ". date( "$this->v_date_format", strtotime($arr[$i][post_date]) ) ." )</p>" ;
						$output	.= "		</div>\n"
								.  "	</td>\n";
						
						if ( $cr % $this->v_column == $this->v_column - 1 ) $output .= "  </tr>\n";
						
						$i++;
					}
								
					// 最後の行で、列数より横の画像数が小さい時 かつ 上のループを終了した時。
					// $cr はループ終了後 +1 されている
					$cr = $cr -1;
					if ( $cr % $this->v_column != $this->v_column - 1 ) $output .= "  </tr>\n";
					
					// テーブルの作成終了
					$output .= "</tbody></table>\n";
					
					// end of accordion head
					$output .= "</div>\n";
				} // ページの作成終了
				
				// <div id='accordion'> を閉じる
				$output .= "</div>\n";
			
			} else {
				return "in 'design = 4', 'column' is required to be larger than 1. ";
			}
		} elseif ( $this->v_design == 5 ) {
			
			if( $this->v_column > 1)
			{
				$output = "<script type='text/javascript' src='". get_bloginfo('home') ."/wp-content/plugins/image-archives/jquery-1.4.2.min.js'></script>\n"
						. "<script type='text/javascript' src='". get_bloginfo('home') ."/wp-content/plugins/image-archives/jquery-ui-1.7.2.custom.min.js'></script>\n"
						. "<script type='text/javascript' src='". get_bloginfo('home') ."/wp-content/plugins/image-archives/image_archives_tabs.js'></script>\n"
						. "<div id='tabs'>";
				
				// calculate a number of pages.
				$page = $count / $this->v_item;
				if ( $page != (int) $page ) $page = (int) $page + 1;
				
				// tabs の見出しをpageから作る
				$output .= "<ul>\n";
				for( $p=1; $p <= $page ; $p++ ) {
					
					// 最後のページの時で無い時
					if( $p < $page ) {
						$output .= "	<li><h3><a href='#tabs-$p'>Section $p ( ". $this->v_item * $p ." / $count )</a></h3></li>\n";
					} elseif ( $p == $page ) {
					//最後のページの時
						$output .= "	<li><h3><a href='#tabs-$p'>Section $p ( $count / $count )</a></h3></li>\n";
					}
					
				}
				$output .= "</ul>\n";
				// 見出し終了
				
				// $i is a global number of items.
				$i=0;
				
				//tab毎の内容を作成
				// ページ毎に
				for( $p=1; $p <= $page ; $p++ ) {
					
					$output .= "<div id='tabs-$p'>\n";
					
					// テーブルを作成
					$output .= "<table class='img_arc'><tbody>\n";
					
					// $cr is a calculated number of images per a page.
					for ( $cr = 0; ( $cr <= $this->v_item -1 ) && ( $arr[$i] !== NULL ) ; $cr++) {
						
						$img_src = wp_get_attachment_image_src( $arr[$i][img_post_id], $this->v_img_size );
						
						if ( $cr % $this->v_column == 0 ) $output .= "	<tr>\n";
						$output .= "	<td class='img_arc'>\n"
								.  "		<div class='img_arc_img'><a class='img_arc' href='". get_permalink($arr[$i][parent_id]) ."'><img class='img_arc' src='$img_src[0]' alt='". attribute_escape($arr[$i][post_title]) ."' title='". attribute_escape($arr[$i][post_title]) ."' /></a></div>\n"
								.  "		<div class='img_arc_text'><p class='img_arc'><a class='img_arc' href='". get_permalink($arr[$i][parent_id]) ."'>". $arr[$i][post_title] ."</a></p>";
						if ( $this->v_date_show == 'on' ) $output .= "<p class='img_arc_date'>( ". date( "$this->v_date_format", strtotime($arr[$i][post_date]) ) ." )</p>" ;
						$output	.= "		</div>\n"
								.  "	</td>\n";
						
						if ( $cr % $this->v_column == $this->v_column - 1 ) $output .= "  </tr>\n";
						
						$i++;
					}
								
					// 最後の行で、列数より横の画像数が小さい時 かつ 上のループを終了した時。
					// $cr はループ終了後 +1 されている
					$cr = $cr -1;
					if ( $cr % $this->v_column != $this->v_column - 1 ) $output .= "  </tr>\n";
					
					// テーブルの作成終了
					$output .= "</tbody></table>\n";
					
					// end of <div id='tabs-$p'>
					$output .= "</div>\n";
				} // tabsの作成終了
				
				// <div id='tabs'> を閉じる
				$output .= "</div>\n";
				
			} else {
				return "in 'design = 5', 'column' is required to be larger than 1.";
			}
			
		} else {
			return "'design' is required to be from 1 to 5.";
		}
		
		return $output;
	}

}


/******************************************************************************
 * grobal template tag - wp_image_archives()
 *****************************************************************************/

function wp_image_archives ( $args = '' ) {
	global $image_archives;
	echo $image_archives->image_archives_template_tag ( $args );
}



/******************************************************************************
 * shortcode - [image_archives]
 *****************************************************************************/

$image_archives = new image_archives();
add_shortcode( 'image_archives', array ( $image_archives, 'image_archives_shortcode' ));


?>