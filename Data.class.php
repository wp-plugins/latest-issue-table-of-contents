<?php
require_once 'Data/widgetData.class.php';
require_once 'Data/formData.class.php';
require_once 'Data/updateData.class.php';
/**
 * Defines the interface for LITOC_Data subclasses.
 * Implements factory method to generate the subclass objects
 * @author Martin Hurford <litoc@mindtripz.com>
 */
abstract class LITOC_Data
{
    /**
     * Concrete factory method returning LITOC_Data objects of subclass type $type
     * @static
     * @access public
     * @param  string $type          The type of data object required
     * @return LITOC_Data_formData   The formData object
     * @return LITOC_Data_widgetData The widgetData object
     * @return LITOC_Data_updateData The updateData object
     */
    static public function getData($type)
    {
        switch ($type){
            case 'form':
                return new LITOC_Data_formData();
                break;
            case 'widget':
                return new LITOC_Data_widgetData();
                break;
            case 'update':
                return new LITOC_Data_updateData();
                break;
            default :
                throw new Exception("Requested subclass '$type' is not implemented");
        }
    }

    /**
     * Abstract method forces implementation in subclass
     * Manipulates data into pre-defined format
     * @access public
     * @abstract
     * @param $new_instance   The settings for the particular instance of the widget
     * @param $old_instance   The previous settings for the particular instance of the widget
     */
    abstract public function process($new_instance,$old_instance = null);

}
