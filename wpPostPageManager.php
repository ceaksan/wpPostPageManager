<?php

/*
Plugin Name: wpPostPageManager
Plugin URI: https://github.com/ceaksan/wpPostPageManager
Description: Enables to add post type links (posts, pages, custom post types etc) and to change the titles in admin main menu.
Version: 1.0
Author: Ceyhun Enki Aksan
Author URI: http://ceaksan.com/en/wppostpagemanager/
*/

/*
Copyright (C) 2015 Ceyhun Aksan, ceaksan.com (wassup AT ceaksan DOT com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


add_action('admin_menu', 'cea_wpPostPageManager');
function cea_wpPostPageManager(){
/* get default post types
 ***********************************************************************************/
	$getPostTypes[0] = array(
		'post' => 'Post',
		'page' => 'Page',
	);

/* get custom post types
 ***********************************************************************************/
	$args = array(
	   'public'   => true,
	   '_builtin' => false
	);
/* merge default and custom post types
 ***********************************************************************************/
	$getPostTypes[1] = get_post_types($args);
	$getPostTypes = array_merge($getPostTypes[0], $getPostTypes[1]);

/* control
 ***********************************************************************************/
	if(!empty($getPostTypes)){
		global $submenu;
		// print_r($submenu);
		if((array) $getPostTypes !== $getPostTypes) (array) $getPostTypes; else $getPostTypes;

		foreach($getPostTypes as $getRecentType => $key){
			$getCountType = wp_count_posts($getRecentType);
			$getDraftCount = $getCountType->draft;
			$getFutureCount = $getCountType->future;

/* customize main menu
 ***********************************************************************************/
			if($getRecentType == 'post'):
				$submenu['edit.php'][5][2] = 'edit.php?post_type='.$getRecentType.'&amp;post_status=publish';
				$submenu['edit.php'][5][0] = 'Published '.$key.'(s)';
				add_posts_page(__('Drafts'), __("Drafts ($getDraftCount)"), 'read', 'edit.php?post_status=draft&post_type=post');
				add_posts_page(__('Scheduled'), __("Scheduled ($getFutureCount)"), 'read', 'edit.php?post_status=future&post_type='.$getRecentType);
			elseif($getRecentType == 'page'):
				$submenu['edit.php?post_type='.esc_attr($getRecentType)][5][2] = 'edit.php?post_type='.$getRecentType.'&post_status=publish';
				$submenu['edit.php?post_type='.esc_attr($getRecentType)][5][0] = 'Published '.ucfirst($key).'(s)';
				add_pages_page(__('Drafts'), __("Drafts ($getDraftCount)"), 'read', 'edit.php?post_status=draft&post_type='.$getRecentType);
				add_pages_page(__('Scheduled'), __("Scheduled ($getFutureCount)"), 'read', 'edit.php?post_status=future&post_type='.$getRecentType);
			else:
				$submenu['edit.php?post_type='.esc_attr($getRecentType)][5][2] = 'edit.php?post_type='.$getRecentType.'&post_status=publish';
				$submenu['edit.php?post_type='.esc_attr($getRecentType)][5][0] = 'Published '.ucfirst($key).'(s)';
				add_submenu_page('edit.php?post_type='.$getRecentType, __('Drafts'), __("Drafts ($getDraftCount)"), 'read', 'edit.php?post_status=draft&post_type='.$getRecentType);
				add_submenu_page('edit.php?post_type='.$getRecentType,__('Scheduled'), __("Scheduled ($getFutureCount)"), 'read', 'edit.php?post_status=future&post_type='.$getRecentType);
			endif;
		}
	} else { return; }
}
