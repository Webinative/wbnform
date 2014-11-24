<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Description of WBNEmail
 *
 * @author mageshravi
 */
class WBNEmail extends WBNFormField implements IwbnProcessRuleRequired{
    
    /** @var boolean turn on or off auto-suggestions */
    protected $autosuggest;
    
    protected $value;
    
    protected $err_msg = NULL;
    
    function __construct($name, $label, $placeholder=NULL, $value=NULL) {
        parent::__construct($name, $label, $placeholder);
        $this->value = $value;
    }
    
    protected function process_rules() {
        // autovalidate 
        $this->error = $this->autovalidate();
        
        // validate required
        if (isset($this->_rules['required'])) {
            if ($this->process_rule_required() === FALSE)
                return;
        }
    }

    public function process_rule_required() {
        if ($this->input_value == NULL) {
            $this->generate_error_message('required');
            return FALSE;
        }

        if (trim($this->input_value) == '') {
            $this->generate_error_message('required');
            return FALSE;
        }
    }

    /**
     * 
     * @return string|null returns error message on failure or NULL
     */
    protected function autovalidate() {
        // if input value is NULL or "" return
        if (is_null($this->input_value) || $this->input_value == "")
            return;
        
        $filterResult = filter_var($this->input_value, FILTER_VALIDATE_EMAIL);
        
        // Set error message
        if (is_null($this->err_msg))
            $this->err_msg = $this->label. " should have valid e-mail address";
        
        if($filterResult == FALSE)
            return $this->err_msg;
        else
            return NULL;
    }
    
    /**
     * To set custom error message
     * @param string $msg
     * @return \WBNEmail
     */
    public function error_message($msg) {
        $this->err_msg = str_replace(':label', $this->label, $msg);
        
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
