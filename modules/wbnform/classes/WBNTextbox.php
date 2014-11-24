<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Description of Textbox
 *
 * @author mageshravi
 */
class WBNTextbox extends WBNFormField {

    /** @var boolean turn on or off auto-suggestions */
    protected $autocomplete;

    /** @var string input-group-addon value */
    protected $addon_prefix;

    /** @var string input-group-addon value */
    protected $addon_suffix;
    protected $value;

    function __construct($name, $label, $placeholder = NULL, $value = NULL) {
        parent::__construct($name, $label, $placeholder);
        $this->value = $value;
    }

    /**
     * 
     * @param string $value
     * @return \WBNTextbox
     */
    public function addon_prefix($value = NULL) {
        if (NULL == $value)
            return $this->addon_prefix;
        else
            $this->addon_prefix = $value;

        return $this;
    }

    /**
     * 
     * @param string $value
     * @return \WBNTextbox
     */
    public function addon_suffix($value = NULL) {
        if (NULL == $value)
            return $this->addon_suffix;
        else
            $this->addon_suffix = $value;

        return $this;
    }

    /**
     * 
     * @param boolean $value
     * @return \WBNTextbox
     */
    public function autocomplete($value = NULL) {
        if (NULL === $value)
            return $this->autocomplete;
        else if (is_bool($value))
            $this->autocomplete = $value;

        return $this;
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
        
        // validate regex
        if (isset($this->_rules['regex'])) {
            if ($this->process_rule_regex($this->_rules['regex']) === FALSE)
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

    private function process_rule_filter($filters) {

        if (is_array($filters) == FALSE)
            $filters = array($filters);

        foreach ($filters as $filter) {
            if (filter_var($this->input_value, $filter) != TRUE) {
                $this->generate_error_message('filter');
                return FALSE;
            }
        }
    }

    private function process_rule_regex($regex_string) {
        $arr_options = array(
            "options" => array(
                "regexp" => $regex_string
            )
        );

        if (filter_var($this->input_value, FILTER_VALIDATE_REGEXP, $arr_options) === FALSE) {
            $this->generate_error_message('regex');
            return FALSE;
        }
    }

    protected function autovalidate() {
        
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

        // input-group-addon (bootstrap component)
        if ($this->addon_prefix OR $this->addon_suffix)
            $html .= '<div class="input-group">';

        if ($this->addon_prefix)
            $html .= '<span class="input-group-addon">' . $this->addon_prefix . '</span>';

        // input element
        $html .= '<input type="text" id="' . $el_id . '" class="form-control" '
                . 'placeholder="' . $this->placeholder . '" ';

        if ($this->autocomplete === FALSE)
            $html .= ' autocomplete="off" ';

        if ($this->autofocus)
            $html .= ' autofocus ';

        if ($this->readonly)
            $html .= ' readonly="readonly" ';

        if ($this->disabled)
            $html .= ' disabled="disabled" ';

        // check for submitted value
        $value = filter_input(INPUT_POST, $this->name);
        if (!is_null($value))
            $this->value = $value;

        $html .= 'name="' . $this->name . '" value="' . $this->value . '"> ';

        if ($this->addon_suffix)
            $html .= '<span class="input-group-addon">' . $this->addon_suffix . '</span>';

        if ($this->addon_prefix OR $this->addon_suffix)
            $html .= '</div>';

        if ($this->help_block)
            $html .= '<p class="help-block">' . $this->help_block . '</p>';

        $html .= '</div>';

        return $html;
    }

}
