<?php
/**
 * Plugin Name: Latest Issue Table of Contents 
 * Plugin URI: http://webdesign.mindtripz.com/wordpress-plugin-latest-issue-table-of-contents/
 * Description: A widget displaying a table of contents for the latest issue (tag prefix/suffix) or tag archive
 * Version: 1.0
 * Author: Martin Hurford
 * Author URI: http://www.mindtripz.com
 * License: GPL2
 *
 * Copyright 2010 Martin Hurford (email : litoc@mindtripz.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
require_once 'Data.class.php';
require_once 'View.class.php';

class LITOC_Widget extends WP_Widget
{
    /**
     * Widget Options
     */
    const WIDGET_ID   = 'latest-issue-table-of-contents';
    const WIDGET_NAME = 'Latest Issue Table of Contents';
    const WIDGET_DESC = 'A table of contents sidebar for your latest issue and tag archives';

    public function LITOC_Widget()
    {
	// WP_Widget constructor
    	$this->WP_Widget(self::WIDGET_ID, self::WIDGET_NAME, array('description' => self::WIDGET_DESC));
    }

    public function widget($args, $instance)
    {
        // make args an object so we can refer to the values via object notation
        $args     = (object) $args;
        // Get data object for form
        $dataObj = LITOC_Data::getData('widget');
        // Process data into format required for widget
        $newInstance = $dataObj->process($instance);
        // Instantiate a View Object
        $viewObj = LITOC_View::getView('widget');
        // Render view
        $viewObj->render($newInstance, $args, $this);
    }

    public function form($instance)
    {
        // Get data object for form
        $dataObj = LITOC_Data::getData('form');
        // Process data into format required for form
        $newInstance = $dataObj->process($instance);
        // Get view object for form
        $viewObj = LITOC_View::getView('form');
        // Render the widget on admin page
        $viewObj->render($newInstance, null, $this);
    }

    public function update($new_instance, $old_instance)
    {
        // Get data object for update
        $dataObj = LITOC_Data::getData('update');
        // Validate data and return instance
        return $dataObj->process($new_instance,$old_instance);
    }

}

// register LatestIssueTableOfContents widget
add_action('widgets_init', create_function('', 'return register_widget("LITOC_Widget");'));

// Replace LITOC shortcode with tag archive list
class LITOC_shortcode
{
    // The constructor, call init() function
    function __construct()
    {
        $this->init();
    }
    // Register the 'filter' function to be called on the_content
    function init()
    {
        add_filter('the_content', array(&$this,'filter'));
    }
    // Where we do the business
    function filter($content)
    {
        // Pattern to find shortcode
        $pattern = "/\[LITOC:(.*?):LITOC\]/";
        // Search for pattern in content
        preg_match($pattern, $content, $matches);
        // Short circuit function if not shortcode is found
        if(!isset($matches[1])){ return $content; }
        // Get tags that have a name like that provided in the shortcode
        $tags = get_tags(array(
            'name__like' => $matches[1]
           ,'order'      => 'desc'
        ));
        // Remove the latest tag - not required in a tag archive
        array_shift($tags);
        // Start the html build
        $litoc = '<ol class="litoc">'."\n";
        // Loop thru the tags
        foreach($tags as $tag){
            // Get the tag archive url
            $url    = get_term_link($tag, $tag->taxonomy);
            $litoc .= '<li><a href="'.$url.'">'.$tag->name.'</a></li>'."\n";
        }
        $litoc .= '</ol>'."\n";
        // Replace the shortcode with the tag archive list
        $newContent = preg_replace($pattern,$litoc,$content);
        // Return the new content
        return $newContent;
    }
}
// Create the LITOC_filter object
$litoc_shortcode = new LITOC_shortcode();
