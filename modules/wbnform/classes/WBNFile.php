<?php

/**
 * Description of WBNFile
 *
 * @author udhayakumar
 */
class WBNFile extends WBNFormField {

    /** @var boolean turn on or off auto-suggestions */
    protected $autocomplete;
    protected $value;

    function __construct($name, $label, $placeholder = NULL, $value = NULL) {
        parent::__construct($name, $label, $placeholder);
        $this->value = $value;
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
