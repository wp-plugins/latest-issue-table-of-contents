<?php
/**
 * Sub Class of LITOC_Data
 * Provides a separation layer for pre-processing widget data.
 * Manipulate widget data into format compatible with widget view
 * @author Martin Hurford <litoc@mindtripz.com>
 */
final class LITOC_Data_widgetData extends LITOC_Data
{
    /**
     * Data from the widget form
     * @var array
     */
    private $instance;
    /**
     * Array of tag objects matching $this->instance['prefix']
     * @var array
     */
    private $tags        = array();
    /**
     * Tag object matching $this->instance['prefix'] and
     * associated with the current page type
     * @var object
     */
    private $current_tag;
    /**
     * Set the property current_tag
     * @access private
     * @param object $tag_obj tag object
     */
    private function set_current_tag($tag_obj)
    {
        $this->current_tag = (object) array(
            'name' => $tag_obj->name
           ,'slug' => $tag_obj->slug
        );
    }
    /**
     * Get all the tags that have published posts
     * that are 'like' the supplied string argument
     * @access private
     * @param  string $this->instance['prefix'] tag prefix/suffix
     * @return array                            tag objects
     */
    private function get_non_empty_tags_like_prefix()
    {
        return get_tags(array(
            'name__like' => $this->instance['prefix']
           ,'order'      => 'desc'
        ));
    }
    /**
     * Get all the tags that are related to the current post
     * @access private
     * @param  string $this_post_id post id
     * @return array                tag objects
     */
    private function get_all_tags_for_this_post($this_post_id)
    {
        return wp_get_post_tags(
            $this_post_id
           ,array(
                'fields'  => 'all'
               ,'orderby' => 'name'
               ,'order'   => 'desc'
            )
        );
    }
    /**
     * Match tag prefix to post tags
     * @access private
     * @param  string $post_tag_list Tag object array
     * @return object                Tag object, there should only be one
     */
    private function match_current_post_tags_to_prefix($post_tag_list)
    {
        return array_shift(
            array_filter($post_tag_list,array($this,'tag_matches_prefix'))
        );
    }
    /**
     * Callback defined as method to enable access to object property $this->instance
     * @access private
     * @param  object $v Tag object
     * @return bool      True when tag name matches the prefix else false
     */
    private function tag_matches_prefix($v)
    {
        return preg_match("/{$this->instance['prefix']}/", $v->name)
             ? true
             : false
        ;
    }
    /**
     * Get the tag associated with the current tag archive
     * Input is the object property $this->tags
     * @access private
     * @return object The tag object associated with the current tag archive
     */
    private function get_the_current_archive_tag()
    {
        function is_current_tag ($v)
        {
            return is_tag($v->slug)
                 ? true
                 : false;
        };
        return (object) array_shift(array_filter( $this->tags,  'is_current_tag' ));
    }
    /**
     * Update the current_tag property if the page type is single or tag archive.
     * Leave current_tag as is if page type is preview or none of the other types.
     * No user specified input required.
     * @access private
     */
    private function update_current_tag_by_page_type ()
    {
        // Check if post preview, do nothing. Post can be single and preview, used to bypass is_single
        if(is_preview()){
            return true;
        }
        // Check for single post page (is_single) and replace $this->current_tag with post tag if true
        elseif(is_single()){
            global $wp_query;
            $this->set_current_tag(
                $this->match_current_post_tags_to_prefix(
                        $this->get_all_tags_for_this_post($wp_query->post->ID)
                )
            );
        }
        // Check for tag archive (is_tag) and replace $this->current_tag if true
        elseif(is_tag()){
            $this->set_current_tag(
                $this->get_the_current_archive_tag()
            );
        }
        return true;
    }
    /**
     * Sort array of objects by an object property.
     * Array of post objects are sorted by 'menu_order' property.
     * With thanks to Will Saver http://www.php.net/manual/en/function.usort.php#93194
     * and aj at ajcates dot com http://www.php.net/manual/en/function.usort.php#93155
     * @access private
     * @param array  $oarray Array of post objects
     * @param string $prop   Property of post object to sort by
     */
    private function osort(&$oarray, $props)
    {
        usort(
            $oarray
          , create_function(
                '$a,$b'
               ,'if($a->' . $props . '== $b->' . $props .'){
                     return 0;
                 }
                 else {
                     return ($a->' . $props . '< $b->' . $props .') ? -1 : 1;
                 }'
            )
        );
    }
    /**
     * Manipulate widget data into the format required for your widget view
     * @access public
     * @param  string $instance The settings for the particular instance of the widget
     * @return object           Widget data in format compatible with the widget view
     */
    public function process($instance,$notRequired = null)
    {
        // Populate instance property
        $this->instance = $instance;
        // Populate tags property with all the tags with published posts that match the 'prefix'
        $this->tags = $this->get_non_empty_tags_like_prefix();
        // Set current_tag property to highest (sorted descending in previous step)
        $this->set_current_tag($this->tags[0]);
        // Modify current_tag property if page type is_single or is_tag
        $this->update_current_tag_by_page_type();
        // Manipulate data into structure required for widget view
        // Loop thru $this->instance elements (from widget form)
        foreach($this->instance as $k => $v){
            switch ($k) {
                // Use title is provided else use the tag name
                case 'title'  : $v
                                ? $newInstance[$k] = $v
                                : $newInstance[$k] = $this->current_tag->name;
                    break;
                // Process the rest of the elements
                default :
                    // Pattern to find all array keys suffixed with '_order'
                    $pattern = "/\_order/";
                    // If pattern is matched
                    if(preg_match($pattern, $k)){
                        // Extract category name from array key
                        $kNew = preg_replace($pattern, "", $k);

                        // Construct query arguments to get all posts for category and tag
                        $posts_args = array(
                                'category_name' => $kNew
                               ,'numberposts'   => -1
                               ,'tag'           => $this->current_tag->slug
                        );
                        // Get the post objects
                        $post_obj_array = get_posts($posts_args);
                        // If first post has a 'menu_order' then assume they all do
                        // Re-sort by 'menu_order' to work with plugins such as postMash
                        if($post_obj_array[0]->menu_order !== 0){
                            $this->osort($post_obj_array, 'menu_order');
                        }
                        // Loop thru posts and build array of category posts with permalink and title
                        foreach($post_obj_array as $post_obj){
                            $category_posts[] = (object) array(
                                'permalink' => get_permalink($post_obj->ID)
                               ,'title'     => get_the_title($post_obj->ID)
                            );
                        };
                        // Add array element to categories including category posts
                        if($instance[$kNew] === 'on' and count($category_posts) > 0){
                            $categories[$v] = (object) array(
                                 'title'  => get_category_by_slug($kNew)->name
                                ,'posts'  => $category_posts
                            );
                        }
                        // Initialise variable ready for next iteration
                        unset($category_posts);

                    }
                    break;
            };
        }
        // Sort the categories so they appear in the user selected order
        ksort($categories);
        // Add categories array to newInstance
        $newInstance['categories'] = $categories;
        // Convert newInstance to object
        return (object) $newInstance;
    }
}