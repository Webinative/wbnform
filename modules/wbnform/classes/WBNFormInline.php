<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Description of WBNFormInline
 *
 * @author Udhaya Kumar
 */
class WBNFormInline extends WBNFormField{
    
    /** @var array To add form inputs*/
    protected $inputs;    
    
    
    function __construct($name) {
        parent::__construct($name);
        
        $this->inputs = array();
    }
    
    protected function process_rules() {
        
    }
    
    protected function autovalidate() {
        
    }

    public function add_input($input) {
        array_push($this->inputs, $input);
        return $this;
    }
    
    public function html() {
        $el_id = str_replace('_', '-', $this->name);
        
        $html = '<div class="form-inline" style="margin-bottom: 15px;" id="'. $el_id .'">';
        
        // Add inputs
        if ( sizeof($this->inputs) !== 0 ) {
            
            foreach ( $this->inputs as $input ) {
                
                // applying style to div if its has no error
                $div = str_replace('class="form-group"','class="form-group" style="margin-right: 15px;"',$input->html());
                
                // check it has error and replace style
                if (strpos($input->html(),'has-error'))
                    $div = str_replace('class="form-group has-error"','class="form-group has-error" style="margin-right: 15px;"',$input->html());
                
                // applying style to label 
                $div = str_replace('class="control-label"','class="control-label" style="margin-bottom: 5px; display:block;"',$div);

                $html .= $div;
            }
        }
        
        $html .= '</div>';
        
        return $html;
    }

}