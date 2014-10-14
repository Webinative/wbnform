<?php

/**
 * Description of WBNSelectMenu
 *
 * @author mageshravi
 */
class WBNSelectMenu extends WBNFormField {
    
    protected $options;

    function __construct($name, $label) {
        parent::__construct($name, $label, NULL);
        $this->options = array();
    }

    /**
     * 
     * @return string|null returns error message on failure. NULL otherwise.
     */
    protected function autovalidate() {

    }
    
    /**
     * 
     * @param string $label
     * @param string $value
     * @param boolean $is_selected
     * @return \WBNSelectMenu
     */
    public function add_option($label, $value, $is_selected=FALSE) {
        $this->options[] = array(
            'label' => $label,
            'value' => $value,
            'is_selected' => $is_selected
        );
        return $this;
    }

    public function html() {
        $el_id = str_replace('_', '-', $this->name);

        $html = '<div id="field-'. $el_id .'" class="form-group">';
        
        // label
        $html .= '<label for="'. $el_id . '" class="control-label">'. $this->label .'</label>';
        
        // errors if any
        if($this->error) {
            $html = '<div id="field-'. $el_id .'" class="form-group has-error">';
            $html .= '<label for="'. $el_id . '" class="control-label">'. $this->error .'</label>';
        }
        
        // input element
        $html .= '<select type="text" id="'. $el_id .'" class="form-control" ';
        
        if($this->readonly)
            $html .= ' readonly="readonly" ';

        if($this->disabled)
            $html .= ' disabled="disabled" ';
        
        $html .= ' name="'. $this->name .'" > ';
        
        // default option
        $html .= '<option value=""> Select </option>';
        
        foreach ($this->options as $_option) {
            $html .= '<option value="'. $_option['value'] .'"';
            
            $is_selected = FALSE;
            if(strtolower($_SERVER['REQUEST_METHOD']) == 'get' AND $_option['is_selected']) {
                $is_selected = TRUE;
            } elseif($this->input_value == $_option['value'])
                $is_selected = TRUE;
            
            if( $is_selected )
                $html .= ' selected="selected" ';
            
            $html .= '>'. $_option['label'] .'</option>';
        }
        
        $html .= '</select>';
        
        if($this->help_block)
            $html .= '<p class="help-block">'. $this->help_block .'</p>';

        $html .= '</div>';

        return $html;
    }

}
