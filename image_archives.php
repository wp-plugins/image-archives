<?php
/*
 Plugin Name: Image Archives
 Plugin URI: 
 Description: Image Archives is a wordpress plugin that displays images from your published posts with a permalink back to the post that the image is connected to. It can also be used as a complete visual archive or gallery archive with several customizable settings.
 Version: 0.692
 Author: Nomeu
 Author URI: http://nomeu.net/
 */
 
/*  Copyright 2010- Nomeu (email : nomeu@nomeu.net)

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
	public $v_section_name;
	public $v_section_sort;
	public $v_section_result_number_show;
	
	
	// shortcode function
	// shortcode は return を用いても良いが、template tag では使えない。
	function ia_shortcode ( $atts, $content = null ) {
		
		extract( shortcode_atts( array(
			'first_image_mode'	=>	'off',
			'image_order_by'	=>	'date',
			'image_order'		=>	'ASC',
			'term_id'			=>	'1',
			'order_by'			=>	'title',
			'order'				=>	'ASC',
			'str'				=>	'%',
			'limit'				=>	'0,50',
			'size'				=>	'medium',
			'design'			=>	'2',
			'item'				=>	'9',
			'column'			=>	'3',
			'date_format'		=>	'Y-m-d',
			'date_show'			=>	'off',
			'title_show'		=>	'on',
			'cache'				=>	'on',
			'section_name'		=>	'Section',
			'section_sort'		=>	'number',
			'section_result_number_show'	=>	'on'
		), $atts ) );
		
		
		//Substitution
		
		//first_image_mode
			if( ($first_image_mode == 'on') || ($first_image_mode == 'off') ) $this->v_first_image_mode = $first_image_mode;
			else return "shortcode atts error. first_image_mode is required to be 'on' or 'off'.";
		//image_order_by
			if( $image_order_by == 'title' ) $this->v_image_order_by = 'p1.post_title';
			elseif( $image_order_by == 'date' ) $this->v_image_order_by = 'p1.post_date';
			else return "shortcode atts error. image_order_by is required to be 'title' or 'date'.";
		//image_order
			if( ($image_order == 'ASC') || ($image_order == 'DESC') ) $this->v_image_order = $image_order;
			else return "shortcode atts error. image_order is required to be 'ASC' or 'DESC'.";
		//term_id
			$this->v_term_id = $term_id;
		//order_by
			if( $order_by == 'title' ) $this->v_order_by = 'post_title';
			elseif( $order_by == 'date' ) $this->v_order_by = 'post_date';
			else return "shortcode atts error. order_by is required to be 'title' or 'date'.";
		//order
			if( ($order == 'ASC') || ($order == 'DESC') ) $this->v_order = $order;
			else return "shortcode atts error. order is required to be 'ASC' or 'DESC'.";
		//string
			$this->v_str = $str;
		//limit
			$this->v_limit = $limit;
		//img_size
			if( ($size == 'thumbnail') || ($size == 'medium') || ($size == 'large') || ($size == 'full') ) $this->v_img_size = $size;
			else return "shortcode atts error. size is required to be 'thumbnail' or 'medium' or 'large' or 'full'.";
		//design
			$this->v_design = intval( $design );
		//item
			$this->v_item = intval( $item );
		//column
			$column = intval( $column );
			if( $column < 1 ) return "the number of 'column' is required to be larger than 0.";
			elseif( $column > 100 ) return "the number of 'column' is too big.";
			$this->v_column = $column;
		//date format
			$this->v_date_format = $date_format;
		//date show
			if( ($date_show == 'on') || ($date_show == 'off') ) $this->v_date_show = $date_show;
			else return "date_show is required to be 'on' or 'off'.";
		//title show
			if( ($title_show == 'on') || ($title_show == 'off') ) $this->v_title_show = $title_show;
			else return "title_show is required to be 'on' or 'off'.";
		//cache
			if( ($cache == 'on') || ($cache == 'off') ) $this->v_cache = $cache;
			else return "cache is required to be 'on' or 'off'.";
		//section_name
			$this->v_section_name = htmlspecialchars($section_name);
		//section_sort
			if( ($section_sort == 'category') || ($section_sort == 'number') ) $this->v_section_sort = $section_sort;
			else return "section_sort is required to be 'category' or 'number'";
		//section_result_number_show
			if( ($section_result_number_show == 'on') || ($section_result_number_show == 'off') ) $this->v_section_result_number_show = $section_result_number_show;
			else return "section_result_number_show is required to be 'on' or 'off'";
		
		return $this->ia_core();
		
	}
	
	
	
	//template tag function
	// cannot use "return".
	function ia_template_tag ( $args = '' ) {
		
		$default = array(
			'first_image_mode'	=>	'off',
			'image_order_by'	=>	'date',
			'image_order'		=>	'ASC',
			'term_id'			=>	'1',
			'order_by'			=>	'title',
			'order'				=>	'ASC',
			'str'				=>	'%',
			'limit'				=>	'0,50',
			'size'				=>	'medium',
			'design'			=>	'2',
			'item'				=>	'9',
			'column'			=>	'3',
			'date_format'		=>	'Y-m-d',
			'date_show'			=>	'off',
			'title_show'		=>	'on',
			'cache'				=>	'on',
			'section_name'		=>	'Section',
			'section_sort'		=>	'number',
			'section_result_number_show'	=>	'on'
		);
		
		$args = wp_parse_args($args, $default);
		
		extract( $args, EXTR_SKIP );
		
		
		//Substitution
		
		//first_image_mode
			if( ($first_image_mode == 'on') || ($first_image_mode == 'off') ) $this->v_first_image_mode = $first_image_mode;
			else { echo "shortcode atts error. first_image_mode is required to be 'on' or 'off'."; return; }
		//image_order_by
			if( $image_order_by == 'title' ) $this->v_image_order_by = 'p1.post_title';
			elseif( $image_order_by == 'date' ) $this->v_image_order_by = 'p1.post_date';
			else { echo "shortcode atts error. image_order_by is required to be 'title' or 'date'."; return; }
		//image_order
			if( ($image_order == 'ASC') || ($image_order == 'DESC') ) $this->v_image_order = $image_order;
			else { echo "shortcode atts error. image_order is required to be 'ASC' or 'DESC'."; return; }
		//term_id
			$this->v_term_id = $term_id;
		//order_by
			if( $order_by == 'title' ) $this->v_order_by = 'post_title';
			elseif( $order_by == 'date' ) $this->v_order_by = 'post_date';
			else { echo "shortcode atts error. order_by is required to be 'title' or 'date'."; return; }
		//order
			if( ($order == 'ASC') || ($order == 'DESC') ) $this->v_order = $order;
			else { echo "shortcode atts error. order is required to be 'ASC' or 'DESC'."; return; }
		//string
			$this->v_str = $str;
		//limit
			$this->v_limit = $limit;
		//img_size
			if( ($size == 'thumbnail') || ($size == 'medium') || ($size == 'large') || ($size == 'full') ) $this->v_img_size = $size;
			else { echo "shortcode atts error. size is required to be 'thumbnail' or 'medium' or 'large' or 'full'."; return; }
		//design
			$this->v_design = intval( $design );
		//item
			$this->v_item = intval( $item );
		//column
			$column = intval( $column );
			if( $column < 1 ) { echo "the number of 'column' is required to be larger than 0."; return; }
			elseif( $column > 100 ) { echo "the number of 'column' is too big."; return; }
			$this->v_column = $column;
		//date format
			$this->v_date_format = $date_format;
		//date show
			if( ($date_show == 'on') || ($date_show == 'off') ) $this->v_date_show = $date_show;
			else { echo "date_show is required to be 'on' or 'off'."; return; }
		//title show
			if( ($title_show == 'on') || ($title_show == 'off') ) $this->v_title_show = $title_show;
			else { echo "title_show is required to be 'on' or 'off'."; return; }
		//cache
			if( ($cache == 'on') || ($cache == 'off') ) $this->v_cache = $cache;
			else { echo "cache is required to be 'on' or 'off'."; return; }
		//section_name
			$this->v_section_name = htmlspecialchars($section_name);
		//section_sort
			if( ($section_sort == 'category') || ($section_sort == 'number') ) $this->v_section_sort = $section_sort;
			else { echo "section_sort is required to be 'category' or 'number'"; return; }
		//section_result_number_show
			if( ($section_result_number_show == 'on') || ($section_result_number_show == 'off') ) $this->v_section_result_number_show = $section_result_number_show;
			else { echo "section_result_number_show is required to be 'on' or 'off'"; return; }
		//important
		echo $this->ia_core();
		
	}
	
	
	
	function ia_settings_write () {
		
		$file = WP_PLUGIN_DIR . '/image-archives/settings.ini';
		
		$str =	 "first_image_mode = \"".$this->v_first_image_mode	."\"\n"
				."image_order_by = \""	.$this->v_image_order_by	."\"\n"
				."image_order = \""		.$this->v_image_order		."\"\n"
				."term_id = \""			.$this->v_term_id			."\"\n"
				."order_by = \""		.$this->v_order_by			."\"\n"
				."order = \""			.$this->v_order				."\"\n"
				."str = \""				.$this->v_str				."\"\n"
				."limit = \""			.$this->v_limit				."\"\n"
				."img_size = \""		.$this->v_img_size			."\"\n"
				."item = \""			.$this->v_item				."\"\n"
				."column = \""			.$this->v_column			."\"\n"
				."design = \""			.$this->v_design			."\"\n"
				."date_format = \""		.$this->v_date_format		."\"\n"
				."date_show = \""		.$this->v_date_show			."\"\n"
				."title_show = \""		.$this->v_title_show		."\"\n"
				."cache = \""			.$this->v_cache				."\"\n"
				."section_name = \""	.$this->v_section_name		."\"\n"
				."section_sort = \""	.$this->v_section_sort		."\"\n"
				."section_result_number_show = \""	.$this->v_section_result_number_show	."\"\n";
		
		if( $fp = fopen( $file, 'w' ) ) {
			flock( $fp, LOCK_EX );
				fwrite( $fp, $str );
			flock( $fp, LOCK_UN );
			fclose($fp);
		} else {
			echo 'the settings file cannot be opened or be created.';
		}
		
	}
	
	
	
	function ia_settings_read () {
		
		$file = WP_PLUGIN_DIR . '/image-archives/settings.ini';
		
		if( file_exists($file) ) {
		
			$ini = parse_ini_file($file);
		
			$this->v_first_image_mode	= $ini["first_image_mode"];
			$this->v_image_order_by		= $ini["image_order_by"];
			$this->v_image_order		= $ini["image_order"];
			$this->v_term_id			= $ini["term_id"];
			$this->v_order_by			= $ini["order_by"];
			$this->v_order				= $ini["order"];
			$this->v_str				= $ini["str"];
			$this->v_limit				= $ini["limit"];
			$this->v_img_size			= $ini["img_size"];
			$this->v_item				= $ini["item"];
			$this->v_column				= $ini["column"];
			$this->v_design				= $ini["design"];
			$this->v_date_format		= $ini["date_format"];
			$this->v_date_show			= $ini["date_show"];
			$this->v_title_show			= $ini["title_show"];
			$this->v_cache				= $ini["cache"];
			$this->v_section_name		= $ini["section_name"];
			$this->v_section_sort		= $ini["section_sort"];
			$this->v_section_result_number_show		= $ini["section_result_number_show"];
			
			return true;
		
		} else {
			return false;
		}
		
	}
	
	
	
	function ia_query( &$row_count = 0 ) {
		
		global $wpdb;
		
		if( $this->v_first_image_mode == 'off' ) {
			
			$query	= "SELECT SQL_CALC_FOUND_ROWS DISTINCT p1.ID AS image_post_id, p1.post_parent AS parent_article_id, $wpdb->posts.post_title, $wpdb->posts.post_date"
					. " FROM $wpdb->posts AS p1"
					. " INNER JOIN $wpdb->term_relationships ON ( $wpdb->term_relationships.object_id = p1.post_parent )"
					. " INNER JOIN $wpdb->posts ON ( $wpdb->posts.ID = p1.post_parent )"
					. " INNER JOIN $wpdb->term_taxonomy ON ( $wpdb->term_taxonomy.term_taxonomy_id = $wpdb->term_relationships.term_taxonomy_id )"
					. " WHERE p1.post_mime_type LIKE 'image%'"
					. " AND p1.post_type = 'attachment'"
					. " AND p1.post_status = 'inherit'"
					. " AND $wpdb->posts.post_status = 'publish'";
			if( $this->v_term_id == "ALL" )
				$query .= " AND $wpdb->term_taxonomy.term_id IS NOT NULL";
			else
				$query .= " AND $wpdb->term_taxonomy.term_id IN (". $wpdb->escape( $this->v_term_id ) .")";
			
			$query	.= " AND p1.post_title LIKE '". $wpdb->escape( $this->v_str ) ."'"
					. " ORDER BY ". $wpdb->escape( $this->v_order_by ) ." ". $wpdb->escape( $this->v_order )
					. " LIMIT ". $wpdb->escape( $this->v_limit );
			
			$query_array = $wpdb->get_results($query, ARRAY_A);
			// query_array[ROW][ image_post_id / parent_article_id / post_title / post_date ]
			
		} elseif( $this->v_first_image_mode == 'on' ) {
			
			$query2	= "SELECT SQL_CALC_FOUND_ROWS * FROM (SELECT p1.ID AS image_post_id, p1.post_title AS image_post_title, p1.post_parent AS parent_article_id, $wpdb->posts.post_title, $wpdb->posts.post_date"
					. " FROM $wpdb->posts AS p1"
					. " INNER JOIN $wpdb->term_relationships ON ($wpdb->term_relationships.object_id = p1.post_parent)"
					. " INNER JOIN $wpdb->posts ON ($wpdb->posts.ID = p1.post_parent)"
					. " INNER JOIN $wpdb->term_taxonomy ON ( $wpdb->term_taxonomy.term_taxonomy_id = $wpdb->term_relationships.term_taxonomy_id )"
					. " WHERE p1.post_mime_type LIKE 'image%'"
					. " AND p1.post_type = 'attachment'"
					. " AND p1.post_status = 'inherit'"
					. " AND $wpdb->posts.post_status = 'publish'";
			if( $this->v_term_id == "ALL" )
				$query2 .= " AND $wpdb->term_taxonomy.term_id IS NOT NULL";
			else
				$query2 .= " AND $wpdb->term_taxonomy.term_id IN (". $wpdb->escape( $this->v_term_id ) .")";
			
			$query2 .= " AND p1.post_title LIKE '". $wpdb->escape( $this->v_str ) ."'"
					. " ORDER BY ". $wpdb->escape( $this->v_image_order_by ) ." ". $wpdb->escape( $this->v_image_order ) .") AS m1"
					. " GROUP BY parent_article_id"
					. " ORDER BY ". $wpdb->escape( $this->v_order_by ) ." ". $wpdb->escape( $this->v_order )
					. " LIMIT ". $wpdb->escape( $this->v_limit );
				
			$query2_array = $wpdb->get_results($query2, ARRAY_A);
			// query2_array[ROW][ image_post_id / parent_article_id / post_title / post_date ]
			
			//echo $query2;
			//var_dump($query2_array);
		}
		
		$row_count = $wpdb->num_rows;
		
		// get the number of rows without limit and without Wordpress's function.
		//$query_count = "SELECT FOUND_ROWS();";
		//$row_count = $wpdb->get_var($query_count);
		
		if( is_array($query_array) || is_array($query2_array) ) {
			if( $this->v_first_image_mode == 'off' ) return $query_array;
			if( $this->v_first_image_mode == 'on' ) return $query2_array;
		} else {
			return false;
		}
		
	}
	
	
	
	function ia_core () {
		
		if( $this->v_cache == 'on' ) {
			
			//$this->ia_settings_write();
			$this->ia_cache_file ( $c_dir, $c_file );
			
			if( file_exists($c_file) ) {
				
				$content = file_get_contents($c_file);
				return $content;
				
			} else {
				
				$this->ia_cache_create();
				$content = file_get_contents($c_file);
				return $content;
				
			}
			
		} else {
		
			return $this->ia_output();
		
		}
		
	}
	
	
	
	function ia_cache_file ( &$cache_dir, &$cache_file ) {
		
		$cache_dir = WP_PLUGIN_DIR . '/image-archives/cache';
		
		$str =	  $this->v_first_image_mode
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
				. $this->v_title_show
				. $this->v_cache
				. $this->v_section_name
				. $this->v_section_sort
				. $this->v_section_result_number_show;
		
		$md5 = md5($str);
		
		$cache_file = $cache_dir . '/ia-' . $md5;
		
	}
	
	
	
	function ia_cache_create () {
		
		$this->ia_cache_file ( $c_dir, $c_file );
		
		// create a cache dir
		if( !is_dir( $c_dir ) ) {
			if( !mkdir( $c_dir , 0755 ) ) echo 'failed to creat cache dir.';
		}
		
		if( $fp = fopen( $c_file, 'w' ) ) {
			flock( $fp, LOCK_EX );
				fwrite( $fp, $this->ia_output() );
			flock( $fp, LOCK_UN );
			fclose( $fp );
		} else {
			echo 'a cache file cannot be opened or be created.';
		}
		
	}
	
	
	
	// not in use
	function ia_cache_update () {
		
		/*	After reading "settings.ini", WordPress runs this "cache update" function.
		*	
		*	ia_cache_update() が add_action によって直接呼び出される時、
		*	$this->v_* に何も値が入っていない。Wordpress のデータベースに値を保存する手段もあるが
		*	それをしてしまうと Image Archives を同じページで二度呼べなくなる。この解決方法として
		*	settings.ini を作り、それを読み出す様にした。
		*	PHPをセーフモードで使っているとこのプラグインが使えないだろう。
		*/
		
		if( $this->ia_settings_read() == false ) return;
		
		$this->ia_cache_file ( $c_dir, $c_file );
		
		if( $this->v_cache == 'on' && file_exists($c_file) ) $this->ia_cache_create();
		
	}
	
	
	function ia_cache_delete () {
		
		$cache_dir = WP_PLUGIN_DIR . '/image-archives/cache';
		
		if ( $handle = @opendir($cache_dir) ) {
			while( false !== ($file_name = readdir($handle)) ) {
				if( preg_match("/^ia-/", $file_name) ) unlink($cache_dir ."/". $file_name);
            }
        	
        	closedir($handle);
        	return true;
        	
		} else {
			return false;
    	}
    	
	}
	
	
	
	function ia_output () {
		
		//send query
		if( ($this->v_design != 4) || ($this->v_design != 5) ) {
			$arr = $this->ia_query( $count );
			if( !$arr ) return "<div style='border:1px solid #888;padding: 5px;'>"
				."<h3>No images were found or Query error</h3>"
				."<p>Searching your database was done, but no images were found. Your 'str'(search strings) may be wrong or your input 'term_id' doesn't exist, or 'limit' may be wrong.</p>"
				."<p>"
					."first_image_mode=". $this->v_first_image_mode ."<br />"
					."image_order_by=". $this->v_image_order_by ."(On query)<br />"
					."image_order=". $this->v_image_order ."<br />"
					."term_id=". $this->v_term_id ."<br />"
					."order_by=". $this->v_order_by ."<br />"
					."order=". $this->v_order ."<br />"
					."str=". $this->v_str ."<br />"
					."limit=". $this->v_limit ."<br />"
					."img_size=". $this->v_img_size ."<br />"
					."item=". $this->v_item ."<br />"
					."column=". $this->v_column ."<br />"
					."design=". $this->v_design ."<br />"
					."date_format=". $this->v_date_format ."<br />"
					."date_show=". $this->v_date_show ."<br />"
					."title_show=". $this->v_title_show ."<br />"
					."cache=". $this->v_cache ."<br />"
					."section_name=". $this->v_section_name ."<br />"
					."section_sort=". $this->v_section_sort ."<br />"
					."section_result_number_show=". $this->v_section_result_number_show ."<br />"
				."</p>"
				."</div>";
		}
		
		// OUTPUT
		if( $this->v_design == 1 ) { 
			
			$output = "<table class='img_arc'><tbody>\n";
			
			for( $i=0; $arr[$i] != NULL; $i++ ) {
				$img_src = wp_get_attachment_image_src( $arr[$i][image_post_id], $this->v_img_size );
				
				$output .= "  <tr>\n"
					 	.  "    <td>\n"
						.  "      <div class='img_arc_img'><a href='". get_permalink($arr[$i][parent_article_id]) ."'><img src='$img_src[0]' alt='". attribute_escape($arr[$i][post_title]) ."' title='". attribute_escape($arr[$i][post_title]) ."' /></a></div>\n"
						.  "    </td>\n"
						.  "    <td>\n";
				if( $this->v_title_show == 'on' ) $output .= "      <div class='img_arc_text'><a href='". get_permalink($arr[$i][parent_article_id]) ."'>". $arr[$i][post_title] ."</a></div>\n";
				if( $this->v_date_show == 'on' )  $output .= "      <div class='img_arc_date'>( ". date( "$this->v_date_format", strtotime($arr[$i][post_date]) ) ." )</div>\n";
				$output .= "    </td>\n"
						.  "  </tr>\n";
			}
			
			$output .= "</tbody></table>\n";
			
		} elseif( $this->v_design == 2 ) {
			
			if( $this->v_column == 1 ) {
				
				$output = "<table class='img_arc'><tbody>\n";
				
				for( $i=0; $arr[$i] != NULL; $i++ ) {
					$img_src = wp_get_attachment_image_src( $arr[$i][image_post_id], $this->v_img_size );
					
					$output .= "  <tr>\n"
							.  "    <td>\n"
							.  "      <div class='img_arc_img'><a href='". get_permalink($arr[$i][parent_article_id]) ."'><img src='$img_src[0]' alt='". attribute_escape($arr[$i][post_title]) ."' title='". attribute_escape($arr[$i][post_title]) ."' /></a></div>\n";
					if( $this->v_title_show == 'on' ) $output .= "      <div class='img_arc_text'><a href='". get_permalink($arr[$i][parent_article_id]) ."'>". $arr[$i][post_title] ."</a></div>\n";
					if( $this->v_date_show == 'on' )  $output .= "      <div class='img_arc_date'>( ". date( "$this->v_date_format", strtotime($arr[$i][post_date]) ) ." )</div>\n";
					$output .= "    </td>\n"
							.  "  </tr>\n";
				}
				
				$output .= "</tbody></table>\n";
				
			} elseif( $this->v_column > 1 ) {
				
				$output = "<table class='img_arc'><tbody>\n";
				
				for( $i=0; $arr[$i] != NULL; $i++ ) {
					$img_src = wp_get_attachment_image_src( $arr[$i][image_post_id], $this->v_img_size );
					
					if( $i % $this->v_column == 0 ) $output .= "  <tr>\n";
					$output .= "    <td>\n"
							.  "      <div class='img_arc_img'><a href='". get_permalink($arr[$i][parent_article_id]) ."'><img src='$img_src[0]' alt='". attribute_escape($arr[$i][post_title]) ."' title='". attribute_escape($arr[$i][post_title]) ."' /></a></div>\n";
					if( $this->v_title_show == 'on' ) $output .= "      <div class='img_arc_text'><a href='". get_permalink($arr[$i][parent_article_id]) ."'>". $arr[$i][post_title] ."</a></div>\n";
					if( $this->v_date_show == 'on' )  $output .= "      <div class='img_arc_date'>( ". date( "$this->v_date_format", strtotime($arr[$i][post_date]) ) ." )</div>\n";
					$output	.= "    </td>\n";
					
					if( $i % $this->v_column == $this->v_column - 1 ) $output .= "  </tr>\n";
				}
				
				// $i はループ終了後 +1 されている
				$i = $i -1;
				if ( $i % $this->v_column != $this->v_column - 1 ) $output .= "  </tr>\n";
				
				$output .= "</tbody></table>\n";
			}
			
		} elseif( $this->v_design == 3 ) {
			
			$output = "<div class='img_arc'>\n";
			
			for( $i=0; $arr[$i] != NULL; $i++ ) {
				$img_src = wp_get_attachment_image_src( $arr[$i][image_post_id], $this->v_img_size );
				
				$output .= "  <div class='img_arc_img'><a href='". get_permalink($arr[$i][parent_article_id]) ."'><img src='$img_src[0]' alt='". attribute_escape($arr[$i][post_title]) ."' title='". attribute_escape($arr[$i][post_title]) ."' /></a>\n";
				if( $this->v_title_show == 'on' ) $output .= "    <div class='img_arc_text'><a href='". get_permalink($arr[$i][parent_article_id]) ."'>". $arr[$i][post_title] ."</a></div>\n";
				if( $this->v_date_show == 'on' )  $output .= "    <div class='img_arc_date'>  ( ". date( "$this->v_date_format", strtotime($arr[$i][post_date]) ) ." )\n";
				$output .= "  </div>\n";
			}
			
			$output .= "</div>\n";
			
		} elseif( $this->v_design == 4 ) {
			
			if( $this->v_section_sort == 'category' )
			{
				if( $this->v_column > 1) {
					$output = "<link type='text/css' href='". get_bloginfo('home') ."/wp-content/plugins/image-archives/css/jquery-ui-1.9.2.custom.min.css' rel='stylesheet' />\n"
							. "<div class='image_archives accordion'>\n";
					
					//$arr = $this->ia_query( $count );
					//if( !$arr ) return "Query Error. Searching your database was done, but any images were not found. Your 'str'(search strings) may be wrong or your input 'term_id' doesn't exist, or 'limit' may be wrong.";
					
					// categories を comma 切りから格納
					$cat_arr = explode (',', $this->v_term_id );
					// 最初の category だけを抽出。style="display: none; に使う。
					$cat_first = $cat_arr[0];
					
					foreach( $cat_arr as $cat_id ){
						
						$this->v_term_id = $cat_id;
						$arr = $this->ia_query( $count );
						
						// calculate a number of pages in this category.
						$page = $count / $this->v_item;
						if( $page != (int)$page ) $page = (int)$page + 1;
						
						// $i is the number of the whole images per a category.
						$i = 0;
						
						for( $current_page=1 ; $current_page <= $page; $current_page ++ )
						{
							$output .= "<h3><a href='#'>" . get_cat_name($cat_id) . " ( $current_page / $page )</a></h3>\n";
							
							// create a <div> for jQuery.
							// $img_num is a calculated number of images per a page.
							$output	.= " <div id='accordion-cat-$this->v_term_id-$current_page'";
								if( $cat_id != $cat_first ) $output .= " style='display: none;' ";
								elseif( ($cat_id == $cat_first) && ($current_page != 1)) $output .= " style='display: none;' ";
							$output .= ">\n";
							
							$output	.= "  <table class='img_arc'><tbody>\n";
							for( $img_num = 0; ( $img_num <= ($this->v_item - 1) ) && ( $arr[$i] != NULL ) ; $img_num ++ )
							{
								$img_src = wp_get_attachment_image_src( $arr[$i][image_post_id], $this->v_img_size );
								
								if( $img_num % $this->v_column == 0 ) // if it is a first image of a row,
								{	$output	.= "   <tr>\n";}
									
									$output	.= "    <td class='img_arc'>\n"
											.  "      <div class='img_arc_img'><a href='". get_permalink($arr[$i][parent_article_id]) ."'><img src='$img_src[0]' alt='". attribute_escape($arr[$i][post_title]) ."' title='". attribute_escape($arr[$i][post_title]) ."' /></a></div>\n";
								
								if( $this->v_title_show == 'on' )
								{	$output	.= "      <div class='img_arc_text'><a href='". get_permalink($arr[$i][parent_article_id]) ."'>". $arr[$i][post_title] ."</a></div>\n";}
								
								if( $this->v_date_show == 'on' )
								{	$output	.= "      <div class='img_arc_date'>( ". date( "$this->v_date_format", strtotime($arr[$i][post_date]) ) ." )</div>\n";}
									
									$output	.= "    </td>\n";
									
								if( $img_num % $this->v_column == ($this->v_column - 1) )
								{	$output	.= "   </tr>\n";}
								
								$i++;
							}
							
							// 上のループの最後の行で、(列数より横の画像数が小さい時) && (上のループを終了した時)
							// $img_num はループ終了後 +1 されている
							$img_num = $img_num -1;
							if( $img_num % $this->v_column != $this->v_column - 1 ) $output_c .= "   </tr>\n";
							
							// close the table.
							$output	.= " </tbody></table>\n";
						
							// close <div id='accordion-cat-'>
							$output	.= "</div>\n";
							
						}
						
					}
					// いろいろ作成終了
					
					// <div class='image_archives accordion'> を閉じる
					$output .= "</div>\n";
					
				} else return "in 'design = 4', 'column' is required to be larger than 1.";
				
			} elseif( $this->v_section_sort == 'number' )
			{
				if( $this->v_column > 1) {
					
					$arr = $this->ia_query( $count );
					if( !$arr ) return "Query Error. Searching your database was done, but any images were not found. Your 'str'(search strings) may be wrong or your input 'term_id' doesn't exist, or 'limit' may be wrong.";
					
					$output = "<link type='text/css' href='". get_bloginfo('home') ."/wp-content/plugins/image-archives/css/jquery-ui-1.9.2.custom.min.css' rel='stylesheet' />\n"
							. "<div class='image_archives accordion'>\n";
					
					// calculate a number of pages.
					$page = $count / $this->v_item;
					if( $page != (int) $page ) $page = (int) $page + 1;
					
					// $i is a global number of the whole items.
					$i=0;
					
					// ページ毎に
					for( $p=1; $p <= $page ; $p++ ) {
						
						// accordionのタイトル
						$output .= "<h3><a href='#'>";
						
						if( $p < $page ) {
							//最後のページの時で無い時
							$output .= "$this->v_section_name $p";
							
							if( $this->v_section_result_number_show == 'on' )
							{	$output .= " ( ". $this->v_item * $p ." / $count )";}
							
							$output .= "</a></h3>\n";
							
						} elseif ( $p == $page ) {
							//最後のページの時
							$output .= "$this->v_section_name $p";
							
							if( $this->v_section_result_number_show == 'on' )
							{	$output .= " ( $count / $count )";}
							
							$output .= "</a></h3>\n";
						}
						
						// accordion head
						$output .= "<div";
						if( $p != 1 ) $output .= " style='display: none;' ";
						$output .= ">\n";
						
						// テーブルを作成
						$output .= "<table class='img_arc'><tbody>\n";
						
						// $cr is the number of images per a page.
						for( $cr = 0; ( $cr <= $this->v_item -1 ) && ( $arr[$i] != NULL ) ; $cr++) {
							
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
						
						// end of accordion head
						$output .= "</div>\n";
						
					} // ページの作成終了
					
					// <div class='image_archives accordion'> を閉じる
					$output .= "</div>\n";
					
				} else return "in 'design = 4', 'column' is required to be larger than 1.";
			} else {
				return "in 'design = 4', section_sort is required to be 'category' or 'number'.";
			}
			
		} elseif( $this->v_design == 5 ) {
			
			if( $this->v_section_sort == 'category' )
			{
				if( $this->v_column > 1)
				{
					$output = "<link type='text/css' href='". get_bloginfo('home') ."/wp-content/plugins/image-archives/css/jquery-ui-1.9.2.custom.min.css' rel='stylesheet' />\n"
							. "<div class='image_archives tabs ui-tabs'>\n";
					
					//$arr = $this->ia_query( $count );
					//if( !$arr ) return "Query Error. Searching your database was done, but any images were not found. Your 'str'(search strings) may be wrong or your input 'term_id' doesn't exist, or 'limit' may be wrong.";
					
					// categories を comma 切りから格納
					$cat_arr = explode (',', $this->v_term_id );
					$cat_first = $cat_arr[0];
					
					foreach( $cat_arr as $cat_id ){
						
						$this->v_term_id = $cat_id;
						$arr = $this->ia_query( $count );
						
						// calculate a number of pages in this category.
						$page = $count / $this->v_item;
						if( $page != (int)$page ) $page = (int)$page + 1;
						
						/*-- create <li>-section for <ul> after this loop. --*/
						$section_title[][] = ''; $section_content[][] = '';
						// $i is the number of the whole images per a category.
						$i = 0;
						
						for( $current_page=1 ; $current_page <= $page; $current_page ++ )
						{
							$section_title[$this->v_term_id][$current_page] = "    <li><h3><a href='#tabs-cat-$this->v_term_id-$current_page'>" .get_cat_name($cat_id) . " ( $current_page / $page )</a></h3></li>\n";
							
							// create a <div> for jQuery.
							// $img_num is a calculated number of images per a page.
							$output_c	 = "<div id='tabs-cat-$this->v_term_id-$current_page'";
								if( $cat_id != $cat_first ) $output_c .= " class='ui-tabs-hide' ";
								elseif( ($cat_id == $cat_first) && ($current_page != 1)) $output_c .= " class='ui-tabs-hide' ";
							$output_c .= ">\n";
							
							$output_c	.= " <table class='img_arc'><tbody>\n";
							for( $img_num = 0; ( $img_num <= ($this->v_item - 1) ) && ( $arr[$i] != NULL ) ; $img_num ++ )
							{
								$img_src = wp_get_attachment_image_src( $arr[$i][image_post_id], $this->v_img_size );
								
								if( $img_num % $this->v_column == 0 ) // if it is a first image of a row,
								{	$output_c	.= "  <tr>\n";}
									
									$output_c	.= "    <td class='img_arc'>\n"
												.  "      <div class='img_arc_img'><a href='". get_permalink($arr[$i][parent_article_id]) ."'><img src='$img_src[0]' alt='". attribute_escape($arr[$i][post_title]) ."' title='". attribute_escape($arr[$i][post_title]) ."' /></a></div>\n";
								
								if( $this->v_title_show == 'on' )
								{	$output_c	.= "      <div class='img_arc_text'><a href='". get_permalink($arr[$i][parent_article_id]) ."'>". $arr[$i][post_title] ."</a></div>\n";}
								
								/*if( $this->v_article_show == 'on' )
								{	$output_c	.="";}*/
								
								if( $this->v_date_show == 'on' )
								{	$output_c	.= "      <div class='img_arc_date'>( ". date( "$this->v_date_format", strtotime($arr[$i][post_date]) ) ." )</div>\n";}
									
									$output_c	.= "    </td>\n";
									
								if( $img_num % $this->v_column == ($this->v_column - 1) )
								{	$output_c	.= "  </tr>\n";}
								
								$i++;
							}
							
							// 上のループの最後の行で、(列数より横の画像数が小さい時) && (上のループを終了した時)
							// $img_num はループ終了後 +1 されている
							$img_num = $img_num -1;
							if( $img_num % $this->v_column != $this->v_column - 1 ) $output_c .= "  </tr>\n";
							
							// close the table.
							$output_c	.= " </tbody></table>\n";
						
							// close <div id='tabs-'>
							$output_c	.= "</div>\n";
							
							$section_content[$this->v_term_id][$current_page] = $output_c;
						}
						
					}
					
					//test
					//print_r($section_title);
					
					// tabs の見出しを作る
					$output .= "  <ul>\n";
					
					//多次元配列の foreach. section_title[$this->v_term_id][$current_page]
					foreach( $section_title as $st_cat_id => $st_val1 ) {
						foreach( $st_val1 as $st_cp => $st_val2 ) {
							$output .= $st_val2;
						}
					}
					$output .= "  </ul>\n";
					// 見出し終了
					
					// section 毎の内容を作成
					foreach( $section_content as $sc_cat_id => $sc_val1 ) {
						foreach( $sc_val1 as $sc_cp => $sc_val2 ) {
							$output .= $sc_val2;
						}
					}
					
					// いろいろ作成終了
					
					// <div class='image_archives tabs'> を閉じる
					$output .= "</div>\n";
					
				} else {
					return "in 'design = 5', 'column' is required to be larger than 1.";
				}
				
			} elseif( $this->v_section_sort == 'number' )
			{
				if( $this->v_column > 1)
				{
					//send query.
					$arr = $this->ia_query( $count );
					if( !$arr ) return "Query Error. Searching your database was done, but any images were not found. Your 'str'(search strings) may be wrong or your input 'term_id' doesn't exist, or 'limit' may be wrong.";
					
					$output = "<link type='text/css' href='". get_bloginfo('home') ."/wp-content/plugins/image-archives/css/jquery-ui-1.9.2.custom.min.css' rel='stylesheet' />\n"
							. "<div class='image_archives tabs ui-tabs'>\n";
					
					// calculate a number of pages.
					$page = $count / $this->v_item;
					if( $page != (int) $page ) $page = (int)$page + 1;
					
					// tabs の見出しをpageから作る
					$output .= "<ul>\n";
					
					for( $p=1; $p <= $page ; $p++ ) {
						
						if( $p < $page ) {
							// 最後のページの時で無い時
							
							$output .= "  <li><h3><a href='#tabs-$p'>$this->v_section_name $p";
							
							if( $this->v_section_result_number_show == 'on' )
							{	$output .= " ( ". $this->v_item * $p ." / $count )";}
							
							$output .= "</a></h3></li>\n";
							
						} elseif( $p == $page ) {
							//最後のページの時
							
							$output .= "  <li><h3><a href='#tabs-$p'>$this->v_section_name $p";
							if( $this->v_section_result_number_show == 'on' )
							{	$output .= " ( $count / $count )";}
							
							$output .= "</a></h3></li>\n";
						}
						
					}
					
					$output .= "</ul>\n";
					// 見出し終了
					
					// $i is a global number of the whole items.
					$i=0;
					
					//tab毎の内容を作成
					// ページ毎に
					for( $p=1; $p <= $page ; $p++ ) {
						
						$output .= "<div id='tabs-$p'";
						if( $p != 1 ) $output .= " class='ui-tabs-hide' ";
						$output .= ">\n";
						
						// テーブルを作成
						$output .= "<table class='img_arc'><tbody>\n";
						
						// $cr is a calculated number of images per a page.
						for( $cr = 0; ( $cr <= $this->v_item -1 ) && ( $arr[$i] != NULL ) ; $cr++ ) {
							
							$img_src = wp_get_attachment_image_src( $arr[$i][image_post_id], $this->v_img_size );
							
							if( $cr % $this->v_column == 0 ) $output .= "  <tr>\n";
							$output .= "    <td class='img_arc'>\n"
									.  "      <div class='img_arc_img'><a href='". get_permalink($arr[$i][parent_article_id]) ."'><img src='$img_src[0]' alt='". attribute_escape($arr[$i][post_title]) ."' title='". attribute_escape($arr[$i][post_title]) ."' /></a></div>\n";
							if( $this->v_title_show == 'on' ) $output .= "      <div class='img_arc_text'><a href='". get_permalink($arr[$i][parent_article_id]) ."'>". $arr[$i][post_title] ."</a></div>\n";
							if( $this->v_date_show == 'on' )  $output .= "      <div class='img_arc_date'>( ". date( "$this->v_date_format", strtotime($arr[$i][post_date]) ) ." )</div>\n";
							$output	.= "    </td>\n";
							
							if( $cr % $this->v_column == $this->v_column - 1 ) $output .= "  </tr>\n";
							
							$i++;
						}
						
						// 最後の行で、列数より横の画像数が小さい時 かつ 上のループを終了した時。
						// $cr はループ終了後 +1 されている
						$cr = $cr -1;
						if( $cr % $this->v_column != $this->v_column - 1 ) $output .= "  </tr>\n";
						
						// テーブルの作成終了
						$output .= "</tbody></table>\n";
						
						// end of <div id='tabs-$p'>
						$output .= "</div>\n";
						
					} // tabsの作成終了
					
					// <div class='image_archives tabs'> を下で閉じる
					$output .= "</div>\n";
					
				} else {
					return "in 'design = 5', 'column' is required to be larger than 1.";
				}
			
			} else {
				return "'section_sort' is required to be 'category' or 'number'.";
			}
			
		} else {
			return "'design' is required to be from 1 to 5.";
		}
		
		return $output;
		
	}
	
	
	function ia_library() {
			wp_enqueue_script('jquery');
			wp_deregister_script('jquery-ui');
			wp_register_script('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js', array('jquery'));
			wp_enqueue_script('jquery-ui');
			
			wp_deregister_script('ia-jquery');
			wp_register_script('ia-jquery', get_bloginfo('home') ."/wp-content/plugins/image-archives/image_archives_jquery.js", array('jquery'));
			wp_enqueue_script('ia-jquery');
	}
	
	


}

$image_archives = new image_archives();


/******************************************************************************
 * grobal template tag - wp_image_archives()
 *****************************************************************************/

function wp_image_archives ( $args = '' ) {
	global $image_archives;
	echo $image_archives->ia_template_tag ( $args );
}


/******************************************************************************
 * shortcode - [image_archives]
 *****************************************************************************/

add_shortcode( 'image_archives', array( $image_archives, 'ia_shortcode' ) );



/******************************************************************************
 * add_action hook
 *****************************************************************************/

add_action( 'wp_insert_post', array( $image_archives, 'ia_cache_delete' ) );
add_action( 'wp_enqueue_scripts', array( $image_archives, 'ia_library' ) );


?>