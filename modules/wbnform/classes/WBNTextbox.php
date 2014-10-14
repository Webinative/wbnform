<?php


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
    
    function __construct($name, $label, $placeholder=NULL, $value=NULL) {
        parent::__construct($name, $label, $placeholder);
        $this->value = $value;
    }
    
    /**
     * 
     * @param string $value
     * @return \WBNTextbox
     */
    public function addon_prefix($value=NULL) {
        if(NULL == $value)
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
    public function addon_suffix($value=NULL) {
        if(NULL == $value)
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
    public function autocomplete($value=NULL) {
        if(NULL === $value)
            return $this->autocomplete;
        else if(is_bool($value))
            $this->autocomplete = $value;
        
        return $this;
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
        
        // input-group-addon (bootstrap component)
        if($this->addon_prefix OR $this->addon_suffix)
            $html .= '<div class="input-group">';
        
        if($this->addon_prefix)
            $html .= '<span class="input-group-addon">'. $this->addon_prefix .'</span>';
        
        // input element
        $html .= '<input type="text" id="'. $el_id .'" class="form-control" '
                .'placeholder="'. $this->placeholder .'" ';
        
        if($this->autocomplete === FALSE)
            $html .= ' autocomplete="off" ';
        
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
        
        if($this->addon_suffix)
            $html .= '<span class="input-group-addon">'. $this->addon_suffix .'</span>';

        if($this->addon_prefix OR $this->addon_suffix)
            $html .= '</div>';
        
        if($this->help_block)
            $html .= '<p class="help-block">'. $this->help_block .'</p>';
        
        $html .= '</div>';

        return $html;
    }
}
