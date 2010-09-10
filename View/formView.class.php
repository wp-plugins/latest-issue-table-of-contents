<?php
/**
 * Sub Class of LITOC_View
 * Provides a separation layer for rendering form HTML.
 * The minimum amount of PHP possible should be used.
 * Any conditional expressions or other data processing should be pushed back to LITOC_Data_formData.
 * @author Martin Hurford <litoc@mindtripz.com>
 */
final class LITOC_View_formView extends LITOC_View
{
    /**
     * Render Form HTML
     * @access public
     * @param object $widget_obj The widget object
     * @param object $instance   Data to be inserted into HTML template
     * @param object $args       Not used for form
     */
    function render($instance,$args = null,$widget_obj = null)
    {
        ?>
        <label for="title">Title:</label><br />
        <input style="width: 100%;" type="text" name="<?php echo $widget_obj->get_field_name('title'); ?>" id="<?php echo $widget_obj->get_field_id('title'); ?>" value="<?php echo $instance->title; ?>" />
        <br />
        <label for="prefix">Tag Prefix/Suffix:</label><br />
        <input style="width: 100%;" type="text" name="<?php echo $widget_obj->get_field_name('prefix'); ?>" id="<?php echo $widget_obj->get_field_id('prefix'); ?>" value="<?php echo $instance->prefix; ?>" />
        <br /><br />
        <table style="width: 100%;">
            <tr>
                <th></th>
                <th style="text-align: left;">Category</th>
                <th style="text-align: left;">Order</th>
            </tr>
            <?php foreach($instance->categories as $category): ?>
            <tr>
                <td>
                    <input type="checkbox" <?php echo $category->checked; ?>
                           name="<?php echo $widget_obj->get_field_name($category->slug); ?>"
                           id="<?php echo $widget_obj->get_field_id($category->slug); ?>"
                    />
                </td>
                <td>
                    <label for="<?php echo $widget_obj->get_field_name($category->slug); ?>"><?php echo $category->name; ?></label>
                </td>
                <td>
                    <input type="text"
                           name="<?php echo $widget_obj->get_field_name($category->slug.'_order'); ?>"
                           id="<?php echo $widget_obj->get_field_id($category->slug.'_order'); ?>"
                           size="1"
                           maxlength="2"
                           value="<?php echo $category->order; ?>"
                    />
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <br />
        <p style="color: #FF0000; font-weight: bold;"><?php echo $instance->error; ?></p>
        <?php
    }
}