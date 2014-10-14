<?php

/**
 * Description of CheckboxGroup
 *
 * @author mageshravi
 */
class WBNCheckboxGroup extends WBNFormField {
    
    /** @var array */
    protected $boxes;
    
    /** @var boolean */
    protected $line_break;
    
    function __construct($name, $label, $line_break=FALSE) {
        parent::__construct($name, $label, NULL);
        $this->boxes = array();
        $this->line_break = $line_break;
    }
    
    public function add_box($label, $value, $is_selected=FALSE) {
        $this->boxes[] = array(
            'label' => $label,
            'value' => $value,
            'is_selected' => $is_selected
        );
        return $this;
    }
    
    /**
     * Overridden method: Fetching input value for checkbox group is different
     * Refer to the case 'post'
     * 
     * Sets the user input value to the current field
     * 
     * @param string $request_method
     */
    protected function process_input_value($request_method = 'post') {
        switch($request_method) {
            case 'post':
                $filtered_result = filter_input_array(INPUT_POST, array(
                    $this->name => array(
                        'flags' => FILTER_REQUIRE_ARRAY
                    )
                ));
                $this->input_value = $filtered_result[ $this->name ];
                break;
            case 'put':
                $_PUT = array();
                parse_str(file_get_contents('php://input'), $_PUT);
                $this->input_value = filter_var($_PUT[ $this->name ]);
                break;
            default:
                $this->input_value = filter_input(INPUT_GET, $this->name);
        }
    }
    
    public function autovalidate() {
        
    }

    /**
     * Get the HTML code
     * @return string
     */
    public function html() {

        $el_id = str_replace(' ', '-', $this->label);
        
        $html = '<div id="field-'. $el_id .'" class="form-group">';
        $html .= '<label class="control-label">'. $this->label .'</label>';
        
        // errors if any
        if($this->error) {
            $html = '<div id="field-'. $el_id .'" class="form-group has-error">';
            $html .= '<label class="control-label">'. $this->error .'</label>';
        }
        
        $html .= '<br>';
        
        foreach($this->boxes as $_box) {
            $html .= '<label class="wbn-checkbox-label">&nbsp;';
            $html .= '<input type="checkbox" name="'. $this->name .'[]" value="'. $_box['value'] .'" ';
            
            $is_selected = FALSE;
            if( strtolower($_SERVER['REQUEST_METHOD']) == 'get' )
                $is_selected = $_box['is_selected'];
            else if(is_array($this->input_value) AND in_array($_box['value'], $this->input_value))
                $is_selected = TRUE;
            
            if($is_selected)
                $html .= ' checked="checked" ';
            
            if($this->readonly)
                $html .= ' readonly="readonly" ';
            
            if($this->disabled)
                $html .= ' disabled="disabled" ';
            
            $html .= '> '. $_box['label'] .'</label> ';
            
            if($this->line_break)
                $html .= '<br>';
        }

        if($this->help_block)
            $html .= '<p class="help-block">'. $this->help_block .'</p>';

        $html .= '</div>';

        return $html;
    }
    
}
