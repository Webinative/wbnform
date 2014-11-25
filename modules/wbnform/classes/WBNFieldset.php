<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Description of WBNFieldset
 *
 * @author Udhaya Kumar
 */
class WBNFieldset extends WBNFormField {

    /** @var array To add form inputs */
    protected $inputs;

    /**
     * 
     * @param string $name
     */
    function __construct($name, $label) {
        parent::__construct($name, $label);
        
        $this->inputs = array();
    }

    protected function autovalidate() {
        
    }
    
    protected function process_rules() {
        
    }
    
    public function add_input($input) {
        array_push($this->inputs, $input);
        return $this;
    }

    public function html() {
        $el_id = "fieldset-". str_replace('_', '-', $this->name);
            
        $html = '<fieldset id="' . $el_id . '"> <legend>';
        $html .= $this->label . '</legend>';

        // add inputs to fieldset
        if ( sizeof($this->inputs ) !== 0) {

            foreach ($this->inputs as $input) {
                $html .= $input->html();
            }
        }

        $html .= '</fieldset>';

        return $html;
    }

}
