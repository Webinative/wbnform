<?php

/**
 * Description of WBNEmail
 *
 * @author mageshravi
 */
class WBNEmail extends WBNFormField {
    
    /** @var boolean turn on or off auto-suggestions */
    protected $autosuggest;
    
    protected $value;
    
    function __construct($name, $label, $placeholder=NULL, $value=NULL) {
        parent::__construct($name, $label, $placeholder);
        $this->value = $value;
    }
    
    /**
     * 
     * @return string|null returns error message on failure or NULL
     */
    protected function autovalidate() {
        $filterResult = filter_var($this->input_value, FILTER_VALIDATE_EMAIL);

        if($filterResult == FALSE)
            return "Not a valid e-mail address!";
        else
            return NULL;
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
        $html .= '<input type="email" id="'. $el_id .'" class="form-control" '
                .'placeholder="'. $this->placeholder .'" ';
        
        if($this->autosuggest === FALSE)
            $html .= ' autosuggest="off" ';
        
        if($this->autofocus)
            $html .= ' autofocus ';
        
        if($this->readonly)
            $html .= ' readonly="readonly" ';
        
        if($this->disabled)
            $html .= ' disabled="disabled" ';

        // check for submitted value
        $value = filter_input(INPUT_POST, $this->name);
        if($value)
            $this->value = $value;
        
        $html .= 'name="'. $this->name .'" value="'. $this->value .'"> ';
        
        if($this->help_block)
            $html .= '<p class="help-block">'. $this->help_block .'</p>';
        
        $html .= '</div>';

        return $html;
    }
    
}
