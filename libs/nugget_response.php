<?php
class NuggetResponse extends Object {
    static $status_codes = array(
        100 => 'Continue', 
        101 => 'Switching Protocols',
        200 => 'OK', 
        201 => 'Created', 
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out');

    // http status code. should be an integer.
    public $code = 200;
    // associative array of response headers
    public $headers = array();
    // hyper media type
    public $content_type = 'text/plain';
    // the model. Can be a string, array, anything
    public $model;
    
    protected $renderCallback;

    function  __construct(NuggetController $nugget) {
        parent::__construct();
        $this->renderCallback = function($model) {
            if (is_object($model) || is_array($model)) {
                echo print_r($model, true);
            } else {
                echo $model;
            }
        };
    }

    final public function set_header($key, $value) {
        $key = strtolower($key);
        $this->headers[$key] = $value;
    }

    final public function get_header($key) {
        $key = strtolower($key);
        return array_key_exists($key, $this->headers)
                ? $this->headers[$key]
                : null;
    }

    // sets the headers, http response code, etc
    protected function beforeRender() {
        $status = "HTTP/1.1 $this->code";

        if (isset(self::$status_codes[$this->code])) {
            $status .= ' ' . self::$status_codes[$this->code];
        }

        // to do: send status or whatever depending on the webserver
        header($status);

        $this->set_header('Content-Type', $this->content_type);
        foreach ($this->headers as $key => $value) {
            $this->set_header($key, $value);
        }
    }

    public final function render() {
        $this->beforeRender();
        $callback = $this->renderCallback;
        $callback($this->model);
    }
}
?>
