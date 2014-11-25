<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Form Field
 *
 * @author mageshravi
 */
abstract class WBNFormField {
    
    public $name;
    protected $label;
    protected $placeholder;
    protected $autofocus;
    protected $readonly;
    protected $disabled;
    protected $help_block;
    
    protected $generate_html = TRUE;
    
    protected $_rules;
    protected $_callbacks;
    protected $_messages;
    protected $error;
    protected $input_value;

    /**
     * 
     * @param string $name
     * @param string $label
     * @param string $placeholder
     */
    protected function __construct($name, $label=NULL, $placeholder=NULL) {
        $this->name = $name;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->error = NULL;
    }

    /**
     * 
     * @param boolean $value
     * @return \WBNTextbox
     */
    public function autofocus($value=NULL) {
        if(is_null($value))
            return $this->autofocus;
        elseif(is_bool($value))
            $this->autofocus = $value;
        
        return $this;
    }
    
    /**
     * 
     * @param boolean $value
     * @return \WBNTextbox
     */
    public function readonly($value=NULL) {
        if(is_null($value))
            return $this->readonly;
        elseif(is_bool($value))
            $this->readonly = $value;
        
        return $this;
    }
    
    /**
     * 
     * @param boolean $value
     * @return \WBNTextbox
     */
    public function disabled($value=NULL) {
        if(is_null($value))
            return $this->disabled;
        elseif(is_bool($value))
            $this->disabled = $value;
        
        return $this;
    }
    
    /**
     * 
     * @param string $message
     * @param boolean $html if set to false, the message will be escaped automatically
     * @return \WBNFormField
     */
    public function help_block($message=NULL, $html=FALSE) {
        if(is_null($message))
            return $this->help_block;
        elseif(is_string($message))
            $this->help_block = ($html == TRUE) ? $message : htmlspecialchars($message);
        
        return $this;
    }
    
    public function do_generate_html($value=NULL) {
        if(is_null($value))
            return $this->generate_html;
        elseif (is_bool($value)) {
            $this->generate_html = $value;
        }
        
        return $this;
    }
    
    public function add_rule($key, $value, $message='') {
        $this->_rules[ $key ] = $value;
        $this->_messages[ $key ] = $message;
        return $this;
    }

    /**
     * 
     * @param string $function function name
     * @param array $params Parameters to be passed to the callback function in an indexed array.
     * Use placeholder :input for current field's user input value.
     * If $params is set to NULL, the user input value will be passed automatically. 
     * @return \WBNFormField
     */
    public function add_callback($function, array $params=NULL) {
        $this->_callbacks[$function] = $params;
        return $this;
    }

    public function add_to_form(WBNForm &$form) {
        $form->add_field($this);
        $this->process_input_value($form->method);
    }

    /**
     * @return string returns the html code
     */
    abstract public function html();
    
    /**
     * 
     * @return string|null returns error message on failure. NULL otherwise.
     */
    abstract protected function autovalidate();

    public function validate() {
        $this->process_rules();
        if( !$this->error )
            $this->process_callbacks();
    }
    
    /**
     * Sets the user input value to the current field
     * @param string $request_method
     */
    protected function process_input_value($request_method = 'post') {
        switch($request_method) {
            case 'post':
                $this->input_value = filter_input(INPUT_POST, $this->name);
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
    
    abstract protected function process_rules();

    protected function generate_error_message( $rule, $value=NULL ) {

        $err_msg = str_replace(':label', $this->label, $this->_messages[ $rule ]);

        if( is_null($value) == FALSE )
            $err_msg = str_replace(':value', $value, $err_msg);

        $this->error = $err_msg;
    }
    
    private function process_callbacks() {

        if( $this->_callbacks == '' OR count($this->_callbacks) == 0 )
            return;

        foreach($this->_callbacks as $function => $params) {
            try {
                if(is_array($params)) {
                    // replace :input by the current field's input value
                    $this->add_input_value_to_params($this->input_value, $params);
                    
                    $this->error = call_user_func_array ($function, $params);
                } else {
                    $this->error = call_user_func($function, $this->input_value);
                }
            } catch (Exception $e) {
                Log::instance()->add(Log::DEBUG, $e->getMessage());
            }
        }
    }
    
    private function add_input_value_to_params($input_value, array &$params) {
        $index = array_search(':input', $params);
        if( $index !== FALSE ) {
            $params[ $index ] = $input_value;
        }
    }

    public function error($err_msg = NULL) {
        if(is_null($err_msg)) {
            // GET
            return $this->error;
        } elseif(is_string($err_msg) ) {
            // SET
            $this->error = $err_msg;
        }
    }
    
}