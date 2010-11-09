<?php
/*
 Plugin Name: Image Archives
 Plugin URI: http://if-music.be/2009/11/10/image-archives/
 Description: Image Archives is a wordpress plugin that displays images from your published posts with a permalink back to the post that the image is connected to. It can also be used as a complete visual archive or gallery archive with several customizable settings.
 Version: 0.50
 Author: coppola
 Author URI: http://if-music.be/
 */
 
/*  Copyright 2010 coppola (email : coppola@if-music.be)

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
	
	public $v_first_image_mode;
	public $v_image_order_by;
	public $v_image_order;
	public $v_term_id;
	public $v_order_by;
	public $v_order;
	public $v_str;
	public $v_limit;
	public $v_img_size;
	public $v_item;
	public $v_column;
	public $v_design;
	public $v_date_format;
	public $v_date_show;
	public $v_title_show;
	public $v_cache;
	
	
	// shortcode function
	// shortcode は return を用いても良いが、template tag では使えない。
	function image_archives_shortcode ( $atts, $content = null ) {
		
		extract( shortcode_atts( array(
			'first_image_mode'	=>	'off',
			'image_order_by'	=>	'date',
			'image_order'		=>	'ASC',
			'term_id'		=>	'1',
			'order_by'		=>	'title',
			'order'			=>	'ASC',
			'str'			=>	'%',
			'limit'			=>	'0,50',
			'size'			=>	'medium',
			'design'		=>	'2',
			'item'			=>	'9',
			'column'		=>	'3',
			'date_format'		=>	'Y-m-d',
			'date_show'		=>	'off',
			'title_show'		=>	'on',
			'cache'			=>	'off'
		), $atts ) );
		
		
		//Substitution
		
		//first_image_mode
			if ( ($first_image_mode == 'on') || ($first_image_mode == 'off') ) $this->v_first_image_mode = $first_image_mode;
			else return "shortcode atts error. first_image_mode is required to be 'on' or 'off'.";
		//image_order_by
			if ($image_order_by == 'title') $this->v_image_order_by = 'p1.post_date';
			elseif ($image_order_by == 'date') $this->v_image_order_by = 'p1.post_date';
			else return "shortcode atts error. image_order_by is required to be 'title' or 'date'.";
		//image_order
			if ( ($image_order == 'ASC') || ($image_order == 'DESC') ) $this->v_image_order = $image_order;
			else return "shortcode atts error. image_order is required to be 'ASC' or 'DESC'.";
		//term_id
			$this->v_term_id = $term_id;
		//order_by
			if ($order_by == 'title') $this->v_order_by = 'post_title';
			elseif ($order_by == 'date') $this->v_order_by = 'post_date';
			else return "shortcode atts error. order_by is required to be 'title' or 'date'.";
		//order
			if ( ($order == 'ASC') || ($order == 'DESC') ) $this->v_order = $order;
			else return "shortcode atts error. order is required to be 'ASC' or 'DESC'.";
		//string
			$this->v_str = $str;
		//limit
			$this->v_limit = $limit;
		//img_size
			if ( ($size == 'thumbnail') || ($size == 'medium') || ($size == 'large') || ($size == 'full') ) $this->v_img_size = $size;
			else return "shortcode atts error. size is required to be 'thumbnail' or 'medium' or 'large' or 'full'.";
		//design
			$this->v_design = intval( $design );
		//item
			$this->v_item = intval( $item );
		//column
			$column = intval( $column );
			if ( $column < 1 ) return "the number of 'column' is required to be larger than 0.";
			elseif( $column > 100 ) return "the number of 'column' is too big.";
			$this->v_column = $column;
		//date format
			$this->v_date_format = $date_format;
		//date show
			if ( ($date_show == 'on') || ($date_show == 'off') ) $this->v_date_show = $date_show;
			else return "date_show is required to be 'on' or 'off'.";
		//title show
			if ( ($title_show == 'on') || ($title_show == 'off') ) $this->v_title_show = $title_show;
			else return "title_show is required to be 'on' or 'off'.";
		//cache
			if ( ($cache == 'on') || ($cache == 'off') ) $this->v_cache = $cache;
			else return "cache is required to be 'on' or 'off'.";
		
		return $this->image_archives_core();
	
	}
	
	
	
	//template tag function
	// cannot use "return".
	function image_archives_template_tag ( $args = '' ) {
		
		$default = array (
			'first_image_mode'	=>	'off',
			'image_order_by'	=>	'date',
			'image_order'		=>	'ASC',
			'term_id'		=>	'1',
			'order_by'		=>	'title',
			'order'			=>	'ASC',
			'str'			=>	'%',
			'limit'			=>	'0,50',
			'size'			=>	'medium',
			'design'		=>	'2',
			'item'			=>	'9',
			'column'		=>	'3',
			'date_format'		=>	'Y-m-d',
			'date_show'		=>	'off',
			'title_show'		=>	'on',
			'cache'			=>	'off'
		);
		
		$args = wp_parse_args($args, $default);
		
		extract( $args, EXTR_SKIP );
		
		
		//Substitution
		
		//first_image_mode
			if ( ($first_image_mode == 'on') || ($first_image_mode == 'off') ) $this->v_first_image_mode = $first_image_mode;
			else echo "shortcode atts error. first_image_mode is required to be 'on' or 'off'.";
		//image_order_by
			if ($image_order_by == 'title') $this->v_image_order_by = 'p1.post_date';
			elseif ($image_order_by == 'date') $this->v_image_order_by = 'p1.post_date';
			else echo "shortcode atts error. image_order_by is required to be 'title' or 'date'.";
		//image_order
			if ( ($image_order == 'ASC') || ($image_order == 'DESC') ) $this->v_image_order = $image_order;
			else echo "shortcode atts error. image_order is required to be 'ASC' or 'DESC'.";
		//term_id
			$this->v_term_id = $term_id;
		//order_by
			if ($order_by == 'title') $this->v_order_by = 'post_title';
			elseif ($order_by == 'date') $this->v_order_by = 'post_date';
			else echo "shortcode atts error. order_by is required to be 'title' or 'date'.";
		//order
			if ( ($order == 'ASC') || ($order == 'DESC') ) $this->v_order = $order;
			else echo "shortcode atts error. order is required to be 'ASC' or 'DESC'.";
		//string
			$this->v_str = $str;
		//limit
			$this->v_limit = $limit;
		//img_size
			if ( ($size == 'thumbnail') || ($size == 'medium') || ($size == 'large') || ($size == 'full') ) $this->v_img_size = $size;
			else echo "shortcode atts error. size is required to be 'thumbnail' or 'medium' or 'large' or 'full'.";
		//design
			$this->v_design = intval( $design );
		//item
			$this->v_item = intval( $item );
		//column
			$column = intval( $column );
			if ( $column < 1 ) echo "the number of 'column' is required to be larger than 0.";
			elseif( $column > 100 ) echo "the number of 'column' is too big.";
			$this->v_column = $column;
		//date format
			$this->v_date_format = $date_format;
		//date show
			if ( ($date_show == 'on') || ($date_show == 'off') ) $this->v_date_show = $date_show;
			else echo "date_show is required to be 'on' or 'off'.";
		//title show
			if ( ($title_show == 'on') || ($title_show == 'off') ) $this->v_title_show = $title_show;
			else echo "title_show is required to be 'on' or 'off'.";
		//cache
			if ( ($cache == 'on') || ($cache == 'off') ) $this->v_cache = $cache;
			else echo "cache is required to be 'on' or 'off'.";
		
		//important
		echo $this->image_archives_core();
	
	}
	
	
	
	function image_archives_settings_write () {
		
		$file = WP_PLUGIN_DIR . '/image-archives/settings.ini';
		
		$str =	 "first_image_mode = \"".$this->v_first_image_mode	."\"\n"
			."image_order_by = \""	.$this->v_image_order_by	."\"\n"
			."image_order = \""	.$this->v_image_order		."\"\n"
			."term_id = \""		.$this->v_term_id		."\"\n"
			."order_by = \""	.$this->v_order_by		."\"\n"
			."order = \""		.$this->v_order			."\"\n"
			."str = \""		.$this->v_str			."\"\n"
			."limit = \""		.$this->v_limit			."\"\n"
			."img_size = \""	.$this->v_img_size		."\"\n"
			."item = \""		.$this->v_item			."\"\n"
			."column = \""		.$this->v_column		."\"\n"
			."design = \""		.$this->v_design		."\"\n"
			."date_format = \""	.$this->v_date_format		."\"\n"
			."date_show = \""	.$this->v_date_show		."\"\n"
			."title_show = \""	.$this->v_title_show		."\"\n";
		
		if( $fp = fopen( $file, 'w' ) ) {
			flock( $fp, LOCK_EX );
				fwrite( $fp, $str );
			flock( $fp, LOCK_UN );
			fclose($fp);
		} else {
			echo 'the settings file cannot be opened or be created.';
		}
	
	}
	
	
	
	function image_archives_settings_read () {
		
		$file = WP_PLUGIN_DIR . '/image-archives/settings.ini';
		
		$ini = parse_ini_file($file);
		print_r($ini);
		
			$this->v_first_image_mode	= $ini["first_image_mode"];
			$this->v_image_order_by		= $ini["image_order_by"];
			$this->v_image_order		= $ini["image_order"];
			$this->v_term_id		= $ini["term_id"];
			$this->v_order_by		= $ini["order_by"];
			$this->v_order			= $ini["order"];
			$this->v_str			= $ini["str"];
			$this->v_limit			= $ini["limit"];
			$this->v_img_size		= $ini["img_size"];
			$this->v_item			= $ini["item"];
			$this->v_column			= $ini["column"];
			$this->v_design			= $ini["design"];
			$this->v_date_format		= $ini["date_format"];
			$this->v_date_show		= $ini["date_show"];
			$this->v_title_show		= $ini["title_show"];
		
	}
	
	
	
	function image_archives_query( &$row_count = 0 ) {
		
		global $wpdb;
		
		if ($this->v_first_image_mode == 'off') {
		
			$query	= "SELECT SQL_CALC_FOUND_ROWS DISTINCT p1.ID AS image_post_id, p1.post_parent AS parent_article_id, $wpdb->posts.post_title, $wpdb->posts.post_date"
				. " FROM $wpdb->posts AS p1"
				. " RIGHT JOIN $wpdb->term_relationships ON ($wpdb->term_relationships.object_id = p1.post_parent)"
				. " RIGHT JOIN $wpdb->posts ON ($wpdb->posts.ID = p1.post_parent)"
				. " WHERE p1.post_mime_type LIKE 'image%'"
				. " AND p1.post_type = 'attachment'"
				. " AND p1.post_status = 'inherit'"
				. " AND $wpdb->posts.post_status = 'publish'"
				. " AND $wpdb->term_relationships.term_taxonomy_id IN (". $wpdb->escape( $this->v_term_id ) .")"
				. " AND p1.post_title LIKE '". $wpdb->escape( $this->v_str ) ."'"
				. " ORDER BY ". $wpdb->escape( $this->v_order_by ) ." ". $wpdb->escape( $this->v_order )
				. " LIMIT ". $wpdb->escape( $this->v_limit );
			
			$query_array = $wpdb->get_results($query, ARRAY_A);
			// 　query_array[ROW][ image_post_id / parent_article_id / post_title / post_date ]
			
		} elseif ($this->v_first_image_mode == 'on') {
			
			$query2	= "SELECT SQL_CALC_FOUND_ROWS * FROM (SELECT p1.ID AS image_post_id, p1.post_title AS image_post_title, p1.post_parent AS parent_article_id, $wpdb->posts.post_title, $wpdb->posts.post_date"
				. " FROM $wpdb->posts AS p1"
				. " RIGHT JOIN $wpdb->term_relationships ON ($wpdb->term_relationships.object_id = p1.post_parent)"
				. " RIGHT JOIN $wpdb->posts ON ($wpdb->posts.ID = p1.post_parent)"
				. " WHERE p1.post_mime_type LIKE 'image%'"
				. " AND p1.post_type = 'attachment'"
				. " AND p1.post_status = 'inherit'"
				. " AND $wpdb->posts.post_status = 'publish'"
				. " AND $wpdb->term_relationships.term_taxonomy_id IN (". $wpdb->escape( $this->v_term_id ) .")"
				. " AND p1.post_title LIKE '". $wpdb->escape( $this->v_str ) ."'"
				. " ORDER BY ". $wpdb->escape( $this->v_image_order_by ) ." ". $wpdb->escape( $this->v_image_order ) .") AS m1"
				. " GROUP BY parent_article_id"
				. " ORDER BY ". $wpdb->escape( $this->v_order_by ) ." ". $wpdb->escape( $this->v_order )
				. " LIMIT ". $wpdb->escape( $this->v_limit );
				
			//echo $query2;
			$query2_array = $wpdb->get_results($query2, ARRAY_A);
			// query2_array[ROW][ image_post_id / parent_article_id / post_title / post_date ]
			
			//var_dump($query2_array);
		}
		
		$row_count = $wpdb->num_rows;
		
		// get the number of row without limit and without wp function.
		//$query_count = "SELECT FOUND_ROWS();";
		//$row_count = $wpdb->get_var($query_count);
		
		if( is_array($query_array) || is_array($query2_array) ) {
			if($this->v_first_image_mode == 'off') return $query_array;
			if($this->v_first_image_mode == 'on') return $query2_array;
		} else {
			return false;
		}
	}
	
	
	
	function image_archives_core () {
		
		if( $this->v_cache == 'on' ) {
			
			$this->image_archives_settings_write();
			$this->image_archives_cache_file ( $c_dir, $c_file );
			
			if( file_exists($c_file) ) {
				
				$content = file_get_contents($c_file);
				return $content;
				
			} else {
				
				$this->image_archives_cache_create();
				$content = file_get_contents($c_file);
				return $content;
				
			}
			
		} else {
		
			return $this->image_archives_output();
		
		}
	
	}
	
	
	
	function image_archives_cache_file ( &$cache_dir, &$cache_file ) {
		
		$cache_dir = WP_PLUGIN_DIR . '/image-archives/cache';
		
		$str = $this->v_first_image_mode
			. $this->v_image_order_by
			. $this->v_image_order
			. $this->v_term_id
			. $this->v_order_by
			. $this->v_order
			. $this->v_str
			. $this->v_limit
			. $this->v_img_size
			. $this->v_item
			. $this->v_column
			. $this->v_design
			. $this->v_date_format
			. $this->v_date_show
			. $this->v_title_show;
		
		//echo 'str='.$str;
		
		$md5 = md5($str);
		
		$cache_file = $cache_dir . '/ia-' . $md5;
		
	}
	
	
	
	function image_archives_cache_create () {
		
		$this->image_archives_cache_file ( $c_dir, $c_file );
		
		// create cache dir
		if( !is_dir( $c_dir ) ) {
			if( !mkdir( $c_dir , 0755 ) ) echo 'failed to creat cache dir.';
		}
		
		if( $fp = fopen( $c_file, 'w' ) ) {
			flock( $fp, LOCK_EX );
				fwrite( $fp, $this->image_archives_output() );
			flock( $fp, LOCK_UN );
			fclose($fp);
		} else {
			echo 'a cache file cannot be opened or be created.';
		}
	
	}
	
	
	
	function image_archives_cache_update () {
		
		$this->image_archives_settings_read();
		
		$this->image_archives_cache_file ( $c_dir, $c_file );
		
		if( file_exists($c_file) ) $this->image_archives_cache_create();
		
	}
	
	
	
	function image_archives_output () {
		
		//send query
		$arr = $this->image_archives_query( $count );
		
		if( !$arr ) return "Query Error. Searching your database was done, but any images were not found. Your 'str'(search strings) may be wrong or your input 'term_id' doesn't exist, or 'limit' may be wrong.";
		
		//echo "all count: $count";
		
		// OUTPUT
		
		if( $this->v_design == 1 ) { 
			
			$output = "<table class='img_arc'><tbody>\n";
			
			for ($i=0; $arr[$i] !== NULL; $i++) {
				$img_src = wp_get_attachment_image_src( $arr[$i][image_post_id], $this->v_img_size );
				
				$output .= "  <tr>\n"
				 	.  "    <td>\n"
					.  "      <div class='img_arc_img'><a href='". get_permalink($arr[$i][parent_article_id]) ."'><img src='$img_src[0]' alt='". attribute_escape($arr[$i][post_title]) ."' title='". attribute_escape($arr[$i][post_title]) ."' /></a></div>\n"
					.  "    </td>\n"
					.  "    <td>\n";
				if ( $this->v_title_show == 'on' ) $output .= "      <div class='img_arc_text'><a href='". get_permalink($arr[$i][parent_article_id]) ."'>". $arr[$i][post_title] ."</a></div>\n";
				if ( $this->v_date_show == 'on' )  $output .= "      <div class='img_arc_date'>( ". date( "$this->v_date_format", strtotime($arr[$i][post_date]) ) ." )</div>\n";
				$output .= "    </td>\n"
					.  "  </tr>\n";
			}
			
			$output .= "</tbody></table>\n";
			
		} elseif ( $this->v_design == 2) {
			
			if ( $this->v_column == 1 ) {
				
				$output = "<table class='img_arc'><tbody>\n";
				
				for ($i=0; $arr[$i] !== NULL; $i++) {
					$img_src = wp_get_attachment_image_src( $arr[$i][image_post_id], $this->v_img_size );
					
					$output .= "  <tr>\n"
						.  "    <td>\n"
						.  "      <div class='img_arc_img'><a href='". get_permalink($arr[$i][parent_article_id]) ."'><img src='$img_src[0]' alt='". attribute_escape($arr[$i][post_title]) ."' title='". attribute_escape($arr[$i][post_title]) ."' /></a></div>\n";
					if ( $this->v_title_show == 'on' ) $output .= "      <div class='img_arc_text'><a href='". get_permalink($arr[$i][parent_article_id]) ."'>". $arr[$i][post_title] ."</a></div>\n";
					if ( $this->v_date_show == 'on' )  $output .= "      <div class='img_arc_date'>( ". date( "$this->v_date_format", strtotime($arr[$i][post_date]) ) ." )</div>\n";
					$output .= "    </td>\n"
						.  "  </tr>\n";
				}
				
				$output .= "</tbody></table>\n";
				
			} elseif ( $this->v_column > 1 ) {
				
				$output = "<table class='img_arc'><tbody>\n";
				
				for ($i=0; $arr[$i] !== NULL; $i++) {
					$img_src = wp_get_attachment_image_src( $arr[$i][image_post_id], $this->v_img_size );
					
					if ( $i % $this->v_column == 0 ) $output .= "  <tr>\n";
					$output .= "    <td>\n"
						.  "      <div class='img_arc_img'><a href='". get_permalink($arr[$i][parent_article_id]) ."'><img src='$img_src[0]' alt='". attribute_escape($arr[$i][post_title]) ."' title='". attribute_escape($arr[$i][post_title]) ."' /></a></div>\n";
					if ( $this->v_title_show == 'on' ) $output .= "      <div class='img_arc_text'><a href='". get_permalink($arr[$i][parent_article_id]) ."'>". $arr[$i][post_title] ."</a></div>\n";
					if ( $this->v_date_show == 'on' )  $output .= "      <div class='img_arc_date'>( ". date( "$this->v_date_format", strtotime($arr[$i][post_date]) ) ." )</div>\n";
					$output	.= "    </td>\n";
					
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
				$img_src = wp_get_attachment_image_src( $arr[$i][image_post_id], $this->v_img_size );
				
				$output .= "  <div class='img_arc_img'><a href='". get_permalink($arr[$i][parent_article_id]) ."'><img src='$img_src[0]' alt='". attribute_escape($arr[$i][post_title]) ."' title='". attribute_escape($arr[$i][post_title]) ."' /></a>\n";
				if ( $this->v_title_show == 'on' ) $output .= "    <div class='img_arc_text'><a href='". get_permalink($arr[$i][parent_article_id]) ."'>". $arr[$i][post_title] ."</a></div>\n";
				if ( $this->v_date_show == 'on' )  $output .= "    <div class='img_arc_date'>  ( ". date( "$this->v_date_format", strtotime($arr[$i][post_date]) ) ." )\n";
				$output .= "  </div>\n";
			}
			
			$output .= "</div>\n";
			
		} elseif ( $this->v_design == 4 ) {
			
			if( $this->v_column > 1)
			{
				$output = "<script type='text/javascript' src='". get_bloginfo('home') ."/wp-content/plugins/image-archives/jquery-1.4.2.min.js'></script>\n"
					. "<script type='text/javascript' src='". get_bloginfo('home') ."/wp-content/plugins/image-archives/jquery-ui-1.8.6.custom.min.js'></script>\n"
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
					
					if( $p < $page ) {
						//最後のページの時で無い時
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
						
						$img_src = wp_get_attachment_image_src( $arr[$i][image_post_id], $this->v_img_size );
						
						if ( $cr % $this->v_column == 0 ) $output .= "  <tr>\n";
						$output .= "    <td>\n"
							.  "      <div class='img_arc_img'><a href='". get_permalink($arr[$i][parent_article_id]) ."'><img src='$img_src[0]' alt='". attribute_escape($arr[$i][post_title]) ."' title='". attribute_escape($arr[$i][post_title]) ."' /></a></div>\n";
						if ( $this->v_title_show == 'on' ) $output .= "      <div class='img_arc_text'><a href='". get_permalink($arr[$i][parent_article_id]) ."'>". $arr[$i][post_title] ."</a></div>\n";
						if ( $this->v_date_show == 'on' )  $output .= "      <div class='img_arc_date'>( ". date( "$this->v_date_format", strtotime($arr[$i][post_date]) ) ." )</div>\n";
						$output	.= "    </td>\n";
						
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
			
			} else return "in 'design = 4', 'column' is required to be larger than 1.";
			
		} elseif ( $this->v_design == 5 ) {
			
			if( $this->v_column > 1)
			{
				$output = "<script type='text/javascript' src='". get_bloginfo('home') ."/wp-content/plugins/image-archives/jquery-1.4.2.min.js'></script>\n"
					. "<script type='text/javascript' src='". get_bloginfo('home') ."/wp-content/plugins/image-archives/jquery-ui-1.8.6.custom.min.js'></script>\n"
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
						$output .= "  <li><h3><a href='#tabs-$p'>Section $p ( ". $this->v_item * $p ." / $count )</a></h3></li>\n";
					} elseif ( $p == $page ) {
					//最後のページの時
						$output .= "  <li><h3><a href='#tabs-$p'>Section $p ( $count / $count )</a></h3></li>\n";
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
						
						$img_src = wp_get_attachment_image_src( $arr[$i][image_post_id], $this->v_img_size );
						
						if ( $cr % $this->v_column == 0 ) $output .= "  <tr>\n";
						$output .= "    <td class='img_arc'>\n"
							.  "      <div class='img_arc_img'><a href='". get_permalink($arr[$i][parent_article_id]) ."'><img src='$img_src[0]' alt='". attribute_escape($arr[$i][post_title]) ."' title='". attribute_escape($arr[$i][post_title]) ."' /></a></div>\n";
						if ( $this->v_title_show == 'on' ) $output .= "      <div class='img_arc_text'><a href='". get_permalink($arr[$i][parent_article_id]) ."'>". $arr[$i][post_title] ."</a></div>\n";
						if ( $this->v_date_show == 'on' )  $output .= "      <div class='img_arc_date'>( ". date( "$this->v_date_format", strtotime($arr[$i][post_date]) ) ." )</div>\n";
						$output	.= "    </td>\n";
						
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


$image_archives = new image_archives();


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

add_shortcode( 'image_archives', array( $image_archives, 'image_archives_shortcode' ) );



/******************************************************************************
 * add_action hook
 *****************************************************************************/

add_action( 'wp_insert_post', array( $image_archives, 'image_archives_cache_update' ) );


?>