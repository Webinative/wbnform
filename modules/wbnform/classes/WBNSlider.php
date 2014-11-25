<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Description of WBNSlider
 *
 * @author Udhaya Kumar
 */
class WBNSlider extends WBNFormField {

    protected $value;

    function __construct($name, $label, $value = NULL) {
        parent::__construct($name, $label);
        $this->value = $value;
    }

    protected function autovalidate() {
        if(!is_null($this->value))
            $this->value = filter_var ($this->value, FILTER_SANITIZE_NUMBER_INT);
    }
    
    protected function process_rules() {
        // autovalidate 
        $this->error = $this->autovalidate();
    }

    public function html() {
        $el_id = str_replace('_', '-', $this->name);

        $html = '<div id="field-' . $el_id . '" class="form-group">';

        // label
        $html .= '<label for="' . $el_id . '" class="control-label">' . $this->label . '</label>';

        // check for submitted value
        $value = filter_input(INPUT_POST, $this->name);
        if ( !is_null($value) )
            $this->value = $value;
        
        // input element
        $html .= '<input type="text" id="' . $el_id . '" name="' . $this->name
                . '" value="' . $this->value . '" readonly'
                . ' style="border:0; color:#f6931f; font-weight:bold; padding-left:5px" >';

        $html .= '<div id="slider-'.$el_id.'"></div></div>';

        return $html;
    }
}