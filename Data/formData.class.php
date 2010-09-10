<?php
/**
 * Sub Class of LITOC_Data
 * Provides a separation layer for pre-processing form data.
 * Mainpulate form data in format compatible with form view.
 * @author Martin Hurford <litoc@mindtripz.com>
 */
final class LITOC_Data_formData extends LITOC_Data
{
    /**
     * Manipulate widget data into the format required for your widget view
     * @param  array  $instance    The settings for the particular instance of the widget
     * @param  null   $notRequired Not required for form
     * @return object $newInstance Data in format compatible with form view
     */
    public function process($instance,$notRequired = null)
    {
        // Copy values
        $newInstance['title']  = $instance['title'];
        $newInstance['prefix'] = $instance['prefix'];
        $newInstance['error']  = $instance['error'];
        // Build data structure for form
        foreach(get_categories('hide_empty=0') as $category){
            $newInstance['categories'][] = (object) array(
                'name'    => $category->name
               ,'slug'    => $category->slug
               ,'checked' => $instance[$category->slug]
                             ? 'checked="checked"'
                             : null
               ,'order'   => $instance[$category->slug.'_order']
                             ? $instance[$category->slug.'_order']
                             : null
            );
        }
        // return data as an object
        return (object) $newInstance;
    }
}