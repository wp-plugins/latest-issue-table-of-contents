<?php
require_once 'View/widgetView.class.php';
require_once 'View/formView.class.php';
/**
 * Defines the interface for LITOC_View subclasses.
 * Implements factory method to generate the subclass objects
 * @author Martin Hurford <litoc@mindtripz.com>
 */
abstract class LITOC_View
{ 
    /**
     * Concrete factory method returning LITOC_View objects of subclass type $type
     * @static
     * @access public
     * @param  string $type          The type of view object required
     * @return LITOC_View_formView   The formView object
     * @return LITOC_View_widgetView The widgetView object
     */
    static public function getView($type)
    {
        switch ($type){
            case 'form':
                return new LITOC_View_formView();
                break;
            case 'widget':
                return new LITOC_View_widgetView();
                break;
            default :
                throw new Exception("Requested subclass '$type' is not implemented");
        }
    }
    /**
     * Abstract method forces implementation in subclass
     * Renders data in HTML template
     * @access public
     * @abstract
     * @param $instance   Data from widget options
     * @param $args       Display arguments including before_title, after_title, before_widget, and after_widget.
     * @param $widget_obj The Widget Object
     */
    abstract public function render($instance,$args = null,$widget_obj = null);
}