<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Description of WBNPassword
 *
 * @author udhayakumar
 */
class WBNPassword extends WBNFormField implements IwbnProcessRuleRequired {
    
    function __construct($name, $label, $placeholder=NULL) {
        parent::__construct($name, $label, $placeholder);
    }
    
    protected function process_rules() {
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
    
    protected function autovalidate() {
        
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
        $html .= '<input type="password" id="'. $el_id .'" class="form-control" '
                .'placeholder="'. $this->placeholder .'" ';
        
        if($this->autofocus)
            $html .= ' autofocus ';
        
        if($this->readonly)
            $html .= ' readonly="readonly" ';
        
        if($this->disabled)
            $html .= ' disabled="disabled" ';
        
        $html .= 'name="'. $this->name .'" > ';
        
        if($this->help_block)
            $html .= '<p class="help-block">'. $this->help_block .'</p>';
        
        $html .= '</div>';

        return $html;
    }
}