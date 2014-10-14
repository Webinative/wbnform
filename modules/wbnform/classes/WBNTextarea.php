<?php

/**
 * Description of WBNTextarea
 *
 * @author mageshravi
 */
class WBNTextarea extends WBNFormField {
    
    protected $value;
        
    function __construct($name, $label, $placeholder, $value=NULL) {
        parent::__construct($name, $label, $placeholder);
        $this->value = $value;
    }
    
    protected function autovalidate() {
        
    }
    
    public function html() {
        
        $el_id = str_replace('_', '-', $this->name);

        $html = '<div id="field-'. $el_id .'" class="form-group">';
        // label
        $html .= '<label for="'. $el_id . '" class="control-label">'. $this->label .'</label>';

        // errors, if any
        if($this->error) {
            $html = '<div id="field-'. $el_id .'" class="form-group has-error">';
            $html .= '<label for="'. $el_id . '" class="control-label">'. $this->error .'</label>';            
        }
        
        // check for submitted value
        $value = filter_input(INPUT_POST, $this->name);
        if($value)
            $this->value = $value;
        
        // input element
        $html .= '<textarea id="'. $el_id .'" class="form-control" '
                .'placeholder="'. $this->placeholder .'" ';
        
        if($this->autofocus)
            $html .= ' autofocus ';
        
        if($this->readonly)
            $html .= ' readonly="readonly" ';
        
        if($this->disabled)
            $html .= ' disabled="disabled" ';
        
        $html .= ' name="'. $this->name .'">'. $this->value .'</textarea>';
        
        if($this->help_block)
            $html .= '<p class="help-block">'. $this->help_block .'</p>';

        $html .= '</div>';

        return $html;
    }
}
