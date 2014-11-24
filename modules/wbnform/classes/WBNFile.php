<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Description of WBNFile
 *
 * @author udhayakumar
 */
class WBNFile extends WBNFormField implements IwbnProcessRuleRequired, IwbnProcessImageFile {

    /** @var boolean turn on or off auto-suggestions */
    protected $autocomplete;
    protected $value;

    function __construct($name, $label, $placeholder = NULL, $value = NULL) {
        parent::__construct($name, $label, $placeholder);
        $this->value = $value;
    }

    protected function autovalidate() {
        
    }

    public function process_rule_required() {
        $file = $_FILES[$this->name];
        if( ! Upload::valid($file) OR ! Upload::not_empty($file) ) {
            $this->generate_error_message('required');
            return FALSE;
        }
    }
    
    public function process_rule_image_dimension($value) {
        if(! Upload::image($_FILES[$this->name], $value[0], $value[1],TRUE)) {
            $this->generate_error_message('image_dimension');
            return FALSE;
        }
    }
    
    public function process_rule_file_size($value) {
        if(! Upload::size($_FILES[$this->name], $value)) {
            $this->generate_error_message('file_size');
            return FALSE;
        }
    }
    
    public function process_rule_file_type ($value) {
        if(! Upload::type($_FILES[$this->name], $value)) {
            $this->generate_error_message('file_type');
            return FALSE;
        }
    }
    
    protected function process_rules() {
        
        // validate file required
        if (isset($this->_rules['required'])) {
            if ($this->process_rule_required() === FALSE)
                return;
        }
        
        // validate file type
        if (isset($this->_rules['file_type'])) {
            if ($this->process_rule_file_type($this->_rules['file_type']) === FALSE)
                return;
        }
        
        // validate file size
        if (isset($this->_rules['file_size'])) {
            if ($this->process_rule_file_size($this->_rules['file_size']) === FALSE)
                return;
        }
        
        // validate file size
        if (isset($this->_rules['image_dimension'])) {
            if ($this->process_rule_image_dimension($this->_rules['image_dimension']) === FALSE)
                return;
        }
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
        $html .= '<input type="file"  accept="image/*" id="' . $el_id . '"';

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
