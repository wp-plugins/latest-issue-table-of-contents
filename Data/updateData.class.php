<?php
/**
 * Sub Class of LITOC_Data
 * Provides a separation layer for validating user entered form data.
 * @author Martin Hurford <litoc@mindtripz.com>
 */
final class LITOC_Data_updateData extends LITOC_Data
{
    /**
     * Validate data entered into widget form
     * @param  array  $new_instance The settings for the particular instance of the widget
     * @param  array  $old_instance The previous settings for the particular instance of the widget
     * @return object $instance     Validated data
     */
    function  process($new_instance,$old_instance = null) {
        // Remove any previous error message
        unset($new_instance['error']);
        unset($old_instance['error']);
        // Order array used to detect duplicate order values
        $order = array();
        // Process user input, force numerics into integers and strip
        foreach(array_filter($new_instance) as $key => $value){
            // Strip tags on all values
            $value = strip_tags($value);
            // Is key an 'order' attribute
            if(preg_match("/\_order/", $key)){
                // Is value numeric (it should be)
                if(is_numeric($value)){
                    // Force integer
                    $intValue = (int) $value;
                    // Does value exist in order array (denotes duplicate value)
                    if(array_key_exists($intValue,$order)){
                        // Duplicate sort order value, set message and return
                        $old_instance['error'] = "Error: Sort Order Duplicate [$intValue]";
                        return $old_instance;
                    }
                    else {
                        // All is good, create instance entry and order entry
                        $instance[$key] = $intValue;
                        $order[$intValue] = 0;
                    }
                }
                else {
                    // Non numeric value, set message and return
                    $old_instance['error'] = "Error: Sort Order '$value' is not numeric";
                    return $old_instance;
                }
            }
            // is key 'prefix'
            elseif($key === 'prefix'){
                // Get all the tags like prefix
                $tags = get_tags(array(
                    'name__like' => $new_instance['prefix']
                   ,'order'      => 'desc'
                ));
                // If tag count is zero no matching tags were found
                if(count($tags) == 0){
                    // set message and return
                    $old_instance['error'] = 'Error: Tag Prefix does not exist!';
                    return $old_instance;
                }
                else {
                    // All is good, add key/value to instance
                    $instance[$key] = $value;
                }
            }
            else {
                // All is good, add key/value to instance
                $instance[$key] = $value;
            }
        }
        // Add 'title' back to instance
        if(!array_key_exists('title', $instance)){
            $instance['title'] = '';
        }
        // Save validated input
        return $instance;

    }

}