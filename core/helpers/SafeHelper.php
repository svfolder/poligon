<?php


namespace core\helpers;


use yii\helpers\Html;
use yii\httpclient\Exception;

class SafeHelper
{

    /**
     * Соль
     * @var mixed|string
     */
    protected $_salt;

    /**
     * Хранилище модели
     * @var SafeHelper
     */
    static private $_model = null;

    /**
     * SafeHelper constructor.
     * @param $config
     */
    function __construct($config)
    {

        $this->_salt = \Yii::$app->params['cookieValidationKey'];

    }

    /**
     * Create singleton
     * @param array $config
     * @return SafeHelper|null
     */
    public static function model($config = [])
    {

        if (!(self::$_model instanceof SafeHelper)) {
            self::$_model = new SafeHelper($config);
        }
        return self::$_model;

    }

    /**
     * @param $param
     * @return string
     */
    public function packParam($param)
    {
        return md5($this->_salt . $param);
    }

    /**
     * @param $param
     * @return string
     */
    public function packParamBinary($param)
    {
        return md5($this->_salt . base64_encode($param));
    }

    /**
     * @param $value
     * @param string $delimiter
     * @return mixed|string
     */
    public function shift($value, $delimiter = ':')
    {
        $array = explode($delimiter, $value);
        return array_shift($array);
    }

    /**
     * @return mixed|string
     * @throws Exception
     */
    public function shiftCID()
    {

        $cid = \Yii::$app->request->post('cid');

        if (is_array($cid) && sizeof($cid) > 0) {
            if ($this->explodeParamTest($cid[0])) {
                return $this->shift($cid[0]);
            } else {
                throw new Exception("INVALID ID");
            }
        } else {
            throw new Exception("INVALID CID");
        }

    }

    /**
     * @param $value
     * @return bool
     */
    public function explodeParamTest($value)
    {

        if (strpos($value, ':') !== false) {

            $explode = explode(':', $value);
            $hash_ck = md5($this->_salt . $explode[0]);

            if ($explode[1] == $hash_ck) {
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }

    }

    /**
     * @param $value
     * @return string
     */
    public function cidHiddenParam($value)
    {

        $value = urlencode($value) . ":{$this->packParam($value)}";
        return HTMl::hiddenInput('cid[]', $value, ['id' => 'cid']);

    }

    /**
     * @param $field_name
     * @param $value
     * @return string
     */
    public function hiddenParamBinary($field_name, $value)
    {

        $retval = HTMl::hiddenInput("bin_{$field_name}_ck", $this->packParamBinary($value), ['id' => "bin_{$field_name}_ck"]);
        $retval .= HTMl::hiddenInput("bin_{$field_name}", base64_encode($value), ['id' => "bin_{$field_name}"]);

        return $retval;
    }

    /**
     * @param $field_name
     * @param $value
     * @return string
     */
    public function hiddenParam($field_name, $value)
    {

        $retval = HTMl::hiddenInput($field_name . '_ck', $this->packParam($value), ['id' => $field_name . '_ck']);
        $retval .= HTMl::hiddenInput($field_name, urlencode($value), ['id' => $field_name]);

        return $retval;
    }

    /**
     * @param $field_name
     * @param $value
     * @return string
     */
    public function urlParam($field_name, $value)
    {

        $retval = "{$field_name}_ck=" . $this->packParam($value);
        $retval .= "&{$field_name}=" . urlencode($value);

        return $retval;
    }

    /**
     * @param $field_name
     * @return bool|string
     */
    public function decodePostParam($field_name)
    {

        $param = urldecode(\Yii::$app->request->post($field_name));
        $param_ck = \Yii::$app->request->post($field_name . '_ck');

        $hash_ck = md5($this->_salt . $param);

        if ($param_ck == $hash_ck) {
            return $param;
        } else {
            return false;
        }

    }

    /**
     * @param $field_name
     * @return bool|string
     */
    public function decodeGetParam($field_name)
    {

        $param = urldecode(\Yii::$app->request->get($field_name));
        $param_ck = \Yii::$app->request->get($field_name . '_ck');

        $hash_ck = md5($this->_salt . $param);

        if ($param_ck == $hash_ck) {
            return $param;
        } else {
            return false;
        }

    }

    /**
     * @param $field_name
     * @return bool|false|string
     */
    public function decodeParamBinary($field_name)
    {

        $param = \Yii::$app->request->post("bin_{$field_name}");
        $param_ck = \Yii::$app->request->post("bin_{$field_name}_ck");

        $hash_ck = md5($this->_salt . $param);

        if ($param_ck == $hash_ck) {
            return base64_decode($param);
        } else {
            return false;
        }

    }

    /**
     * @param $string
     * @return string
     */
    function strToHex($string)
    {
        $hex = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $hex .= dechex(ord($string[$i]));
        }
        return $hex;
    }

    /**
     * @param $hex
     * @return string
     */
    function hexToStr($hex)
    {
        $string = '';
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $string;
    }

}