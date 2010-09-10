<?php
/**
 * Sub Class of LITOC_View
 * Provides a separation layer for rendering widget HTML.
 * The minimum amount of PHP possible should be used.
 * Any conditional expressions or other data processing should be pushed back to LITOC_Data_widgetData.
 * @author Martin Hurford <litoc@mindtripz.com>
 */
final class LITOC_View_widgetView extends LITOC_View
{
    /**
     * Render Widget HTML
     * @access public
     * @param object $widget_obj The Widget object
     * @param object $instance   Data to be inserted into HTML template
     * @param object $args       Display arguments including before_title, after_title, before_widget, and after_widget.
     */
    public function render($instance,$args = null,$widget_obj = null)
    {
        echo $args->before_widget; 
            echo $args->before_title . $instance->title . $args->after_title;
        echo $args->after_widget;
        foreach($instance->categories as $category):
            echo $args->before_widget;
                echo $args->before_title . $category->title . $args->after_title; ?>
                <ul>
                <?php foreach($category->posts as $post): ?>
                    <li>
                        <a href="<?php echo $post->permalink; ?>" rel="bookmark" title="Permanent Link to <?php echo $post->title; ?>"><?php echo $post->title; ?></a>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php echo $args->after_widget;
        endforeach;
    }
}