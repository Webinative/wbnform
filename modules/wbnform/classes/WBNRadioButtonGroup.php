<?php

/**
 * @author mageshravi
 */
class WBNRadioButtonGroup extends WBNFormField {
    
    /** @var array */
    protected $buttons;
    
    /** @var boolean Line breaks between radio buttons */
    protected $line_breaks;

    function __construct($name, $label, $line_breaks=FALSE) {
        parent::__construct($name, $label, NULL);
        $this->buttons = array();
        $this->line_breaks = $line_breaks;
    }

    public function add_button($label, $value, $is_checked=FALSE) {
        $this->buttons[] = array(
            'label' => $label,
            'value' => $value,
            'is_checked' => $is_checked,
        );
        return $this;
    }
    
    public function autovalidate() {
        
    }

    public function html() {

        $el_id = str_replace(' ', '-', $this->label);
        
        $html = '<div id="field-'. $el_id .'" class="form-group">';
        $html .= '<label class="control-label">'. $this->label .'</label>';
        
        // errors if any
        if($this->error) {
            $html = '<div id="field-'. $el_id .'" class="form-group has-error">';
            $html .= '<label class="control-label">'. $this->error ."</label>";
        }
        
        $html .= '<br>';

        foreach($this->buttons as $_button) {
            $html .= '<label class="wbn-radiobutton-label">&nbsp;';
            $html .= '<input type="radio" name="'. $this->name .'" value="'. $_button['value'] .'" ';
            
            $is_checked = FALSE;
            if(strtolower($_SERVER['REQUEST_METHOD']) == 'get' AND $_button['is_checked'])
                $is_checked = TRUE;
            elseif($this->input_value == $_button['value'])
                $is_checked = TRUE;
            
            if($is_checked)
                $html .= ' checked="checked" ';
            
            if($this->readonly)
                $html .= ' readonly="readonly" ';
            
            if($this->disabled)
                $html .= ' disabled="disabled" ';
            
            $html .= '> '. $_button['label'] .'</label> ';
            
            if($this->line_breaks)
                $html .= '<br>';
        }
        
        if($this->help_block)
            $html .= '<p class="help-block">'. $this->help_block .'</p>';
        
        $html .= '</div>';

        return $html;
    }
    
}