<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Description of WBNTextarea
 *
 * @author mageshravi
 */
class WBNTextarea extends WBNFormField {
    
    protected $value;
    
    /** @var boolean disable textarea resize */
    protected $resize;
    
    function __construct($name, $label, $placeholder, $value=NULL) {
        parent::__construct($name, $label, $placeholder);
        $this->value = $value;
    }
    
    protected function process_rules() {
        // validate required
        if (isset($this->_rules['required'])) {
            if ($this->process_rule_required() === FALSE)
                return;
        }
        
        // validate min length
        if (isset($this->_rules['min_length'])) {
            if ($this->process_rule_min_length($this->_rules['min_length']) === FALSE)
                return;
        }
        
        // validate max length
        if (isset($this->_rules['max_length'])) {
            if ($this->process_rule_max_length($this->_rules['max_length']) === FALSE)
                return;
        }
        
        // validate filter
        if (isset($this->_rules['filter'])) {
            if ($this->process_rule_filter($this->_rules['filter']) === FALSE)
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
    
    public function process_rule_max_length($value) {
        if (strlen($this->input_value) > $value) {
            $this->generate_error_message('max_length', $value);
            return FALSE;
        }
    }

    public function process_rule_min_length($value) {
        if (strlen($this->input_value) < $value) {
            $this->generate_error_message('min_length', $value);
            return FALSE;
        }
    }
    
    private function process_rule_filter( $filters ) {
        
        if(is_array($filters) == FALSE)
            $filters = array($filters);

        foreach($filters as $filter) {
            if( filter_var($this->input_value, $filter) != TRUE ) {
                $this->generate_error_message ('filter');
                return FALSE;
            }
        }
    }
    
    protected function autovalidate() {
        
    }
    
    /**
     * 
     * @param boolean $value
     * @return \WBNTextbox
     */
    public function resize ($value=NULL) {
        if(NULL === $value)
            return $this->resize;
        else if (is_bool($value))
            $this->resize = $value;

        return $this;
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
        
        if($this->resize === FALSE)
            $html .= ' style="resize:none" ';
        
        $html .= ' name="'. $this->name .'">'. $this->value .'</textarea>';
        
        if($this->help_block)
            $html .= '<p class="help-block">'. $this->help_block .'</p>';

        $html .= '</div>';

        return $html;
    }
}
