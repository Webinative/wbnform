<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Description of WBNHiddenInput
 *
 * @author Udhaya Kumar
 */
class WBNHiddenInput extends WBNFormField {

    protected $value;
    
    function __construct($name, $value = NULL) {
        parent::__construct($name, NULL, NULL);
        $this->value = $value;
    }

    protected function autovalidate() {
        
    }
    
    protected function process_rules() {
        
    }
    
    public function html() {

        $el_id = str_replace('_', '-', $this->name);

        // check for submitted value
        $value = filter_input(INPUT_POST, $this->name);
        if ($value)
            $this->value = $value;

        $html = '<input type="hidden" id="' . $el_id . '"';

        $html .= ' name="' . $this->name . '" value="' . $this->value . '"/>';

        return $html;
    }

}
