<?php

/**
 * Description of Form
 *
 * @author mageshravi
 */
class WBNForm {
    
    /** @var string $method */
    public $method;
    
    /** @var string $action */
    public $action;
    
    /** @var string $name */
    public $name=NULL;
    
    private $_fields;
    
    /** @var string Submit button label */
    public $submit_label;

    function __construct() {
        $this->action = '';
        $this->method = 'get';
        $this->submit_label = 'Submit';
    }
    
    /**
     * Add field to form
     * @param WBNFormField $field
     */
    public function add_field(WBNFormField $field) {
        $this->_fields[ $field->name ] = $field;
    }
    
    /**
     * Get form field by name
     * @param string $field_name
     * @return WBNFormField if found, else NULL
     */
    public function get_field($field_name) {
        if(isset($this->_fields[ $field_name ]))
            return $this->_fields[ $field_name ];
    }

    public function html() {
        
        $html = '<form enctype="multipart/form-data" role="form" id="'.$this->name.'" action="'. $this->action .'" method="'. $this->method .'">';
        
        foreach($this->_fields as $field) { /* @var $field Field */
            $html .= $field->html();
        }

        $html .= '<button type="submit" class="btn btn-primary">'. $this->submit_label .'</button>';

        $html .= '</form>';
        
        echo $html;
    }

    public function validate() {

        $method = strtolower( $_SERVER['REQUEST_METHOD'] );

        $_postData = filter_input_array(INPUT_POST);
        if($method != $this->method OR empty($_postData)) {
            Log::instance()->add(Log::DEBUG, 'Invalid form submission!');
            return false;
        }
    
        $error_free = true;
        foreach($this->_fields as $field) { /* @var $field WBNFormField */
            $field->validate( $this->method );

            if($error_free === true)
                $error_free = is_null($field->error());
        }

        return $error_free;
    }
    
    /**
     * Returns associative array 'field' => 'error_message'
     * @return array
     */
    public function errors(array $_errors=NULL) {
        
        if( is_null($_errors)) {
            // GET
            $_errors = array();
            foreach($this->_fields as $field) { /* @var $field WBNFormField */
                $field_error = $field->error();

                if( !is_null($field_error))
                    $_errors[ $field->name ] = $field_error;
            }
            return $_errors;
        } else {
            // SET
            foreach ($_errors as $field_name => $err_msg) {
                $field = $this->_fields[ $field_name ] ;
                $field->error($err_msg);
            }
        }
    }
    
}
