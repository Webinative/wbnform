<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Description of WBNPhone
 *
 * @author Udhaya Kumar
 */
class WBNNumber extends WBNFormField implements IwbnProcessRuleRequired, IwbnProcessMinLength, IwbnProcessMaxLength {

    /** @var boolean turn on or off auto-suggestions */
    protected $autosuggest;
    
    protected $value;
    
    protected $err_msg = NULL;
    
    function __construct($name, $label, $placeholder = NULL, $value = NULL) {
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

    /**
     * 
     * @return string|null returns error message on failure or NULL
     */
    protected function autovalidate() {
        // if input value is NULL or "" return
        if (is_null($this->input_value) || $this->input_value == "")
            return;
        
        $filterResult = filter_var($this->input_value, FILTER_VALIDATE_INT);
        
        // Set error message
        if (is_null($this->err_msg))
            $this->err_msg = $this->label . " should have a valid number";
        
        if ($filterResult == FALSE)
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

        $html = '<div id="field-' . $el_id . '" class="form-group">';
        // label
        $html .= '<label for="' . $el_id . '" class="control-label">' . $this->label . '</label>';

        // errors if any
        if ($this->error) {
            $html = '<div id="field-' . $el_id . '" class="form-group has-error">';
            $html .= '<label for="' . $el_id . '" class="control-label">' . $this->error . '</label>';
        }

        // input element
        $html .= '<input type="text" id="' . $el_id . '" class="form-control" '
                . 'placeholder="' . $this->placeholder . '" ';

        if ($this->autosuggest === FALSE)
            $html .= ' autosuggest="off" ';

        if ($this->autofocus)
            $html .= ' autofocus ';

        if ($this->readonly)
            $html .= ' readonly="readonly" ';

        if ($this->disabled)
            $html .= ' disabled="disabled" ';

        // check for submitted value
        $value = filter_input(INPUT_POST, $this->name);
        if ($value)
            $this->value = $value;

        $html .= 'name="' . $this->name . '" value="' . $this->value . '"> ';

        if ($this->help_block)
            $html .= '<p class="help-block">' . $this->help_block . '</p>';

        $html .= '</div>';

        return $html;
    }
}
