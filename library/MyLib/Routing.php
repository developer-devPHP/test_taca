<?php
class MyLib_Routing extends Zend_Controller_Router_Route_Regex
{
    protected $_defaultReqs = '\w+';
    protected $_paramStrFormat    = "name[value]";
    protected $_paramStrValueGlue = '~';
    //protected $_paramStrFormat    = "name-value-";
    //protected $_paramStrValueGlue = "!";
    protected $_regex = null;
    protected $_route = null;
    protected $_insides = array();
    protected $_quotes = array();
    protected $_defaults = array();
    protected $_values = array();
    protected $_map = array();
    protected $_reqs = array();

    /**
     * @param Zend_Config $config Configuration object
    */
    public static function getInstance(Zend_Config $config)
    {
        $defaults = ($config->defaults instanceof Zend_Config) ? $config->defaults->toArray() : array();
        $reqs = ($config->reqs instanceof Zend_Config) ? $config->reqs->toArray() : array();
        return new self($config->route, $defaults, $reqs);
    }

    public function __construct($route, $defaults = array(), $reqs = array())
    {
        $a = '(\[.*\])';
        $b = '(\{.*\})';
        $pattern = "#(.*)(" . $a . "|" . $b . ")#Ui";
        $this->_quotes  = array();
        $this->_insides = array();
        $map = array();
        $regex = '';
        if (preg_match_all($pattern, $route, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $i => $match) {
                $inside = substr($match[2], 1, strlen($match[2])-2);
                $key = preg_replace('[\W]', '', $inside);
                if (!empty($reqs[$key])) {
                    $reg = $reqs[$key];
                } elseif ('paramstr' == $key) {
                    $reg = '.*';
                } else {
                    $reg = $this->_defaultReqs;
                }
                switch ($match[2][0]) {
                    case '[':
                        $end = '';
                        break;
                    case '{':
                        $end = '{0,1}';
                        break;
                }
                $regex .= preg_quote($match[1], '#') . '(' . str_replace($key, $reg, preg_quote($inside, '#')) . ')' . $end;
                $map[$key] = $i+1;
                $this->_quotes[$key] = $match[2][0];
                $this->_insides[$key] = $inside;
            }
        }
        preg_match('#[^\]\}]*$#i', $route, $lostMatch);
        $regex .= preg_quote($lostMatch[0], '#');

        $this->_route    = $route;
        $this->_regex    = $regex;
        $this->_defaults = (array) $defaults;
        $this->_map      = $map;
        $this->_reqs     = $reqs;
    }

    /**
     * Matches a user submitted path with a previously defined route.
     * Assigns and returns an array of defaults on a successful match.
     *
     * @param  string $path Path used to match against this routing map
     * @return array|false  An array of assigned values or a false on a mismatch
     */
    public function match($path, $partial = false)
    {
        $path = trim(urldecode($path), '/');
        $regex = '#^' . $this->_regex . '$#Ui';
        $res = preg_match($regex, $path, $values);
        if ($res === 0) {
            return false;
        }
        foreach ($values as $i => $value) {
            if (!is_int($i) || $i === 0 || empty($value)) {
                unset($values[$i]);
            }
        }
        $values   = $this->_getMappedValues($values);

        foreach ($this->_insides as $name => $ins) {
            if ($name !== $ins && isset($values[$name])) {
                $pattern = '#^' . str_replace($name, '(.*)', preg_quote($ins)) . '$#i';
                if (preg_match($pattern, $values[$name], $matches)) {
                    $values[$name] = $matches[1];
                }
            }
        }

        if (!empty($values['paramstr'])) {
            $values += $this->explodeParamStr($values['paramstr']);
        }
        $this->_values = $values;

        $defaults = $this->_getMappedValues($this->_defaults, false, true);
        $result   = $values + $defaults;
        return $result;
    }

    public function explodeParamStr($paramStr)
    {
        $values = array();
        $pattern = str_replace(array('name',   'value'),
                array('(\w+)', '(.*)'),
                preg_quote($this->_paramStrFormat, '#'));

        $pattern = '#(' . $pattern . ')#Uui';
        if (preg_match_all($pattern, $paramStr, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                if (!empty($match[3]))
                    $value = explode($this->_paramStrValueGlue, $match[3]);
                if (count($value) > 1) {
                    $values[$match[2]] = $value;
                } else {
                    $values[$match[2]] = $match[3];
                }
            }
        }
        return $values;
    }

    public function implodeParamStr(array $params)
    {
        $paramStr = '';
        foreach($params as $name => $value) {
            if (!in_array($name, array('controller', 'module', 'paramstr', 'action')) && !array_key_exists($name, $this->_map)) {

                if (is_array($value)) {
                    $value = implode($this->_paramStrValueGlue, $value);
                }
                $paramStr .= str_replace(array('name', 'value'),
                        array($name,  $value),
                        $this->_paramStrFormat);
            }
        }
        if (empty($paramStr)) {
            $paramStr = null;
        }
        return $paramStr;
    }

    /**
     * Assembles a URL path defined by this route
     *
     * @param  array $data An array of name (or index) and value pairs used as parameters
     * @return string Route path with user submitted parameters
     */
    public function assemble($data = array(), $reset = false, $encode = false, $partial = false)
    {
        $replaces = array();
        $searches = array();

        $paramStr = $this->implodeParamStr($data);

        $data = $data + $this->_values;

        if (empty($paramStr)) {
            unset($data['paramstr']);
        } else {
            $data['paramstr'] = $paramStr;
        }

        foreach($this->_map as $key => $i) {
            $inside = $this->_insides[$key];
            switch ($this->_quotes[$key]) {
                case '[':
                    $searches[$i] = '[' . $inside . ']';
                    break;
                case '{':
                    $searches[$i] = '{' . $inside . '}';
                    break;
                default:
                    break;
            }
            if (!empty($data[$key])) {
                $replaces[$i] = str_ireplace($key, $data[$key], $inside);
            } else {
                $replaces[$i] = '';
            }
        }
        $url = str_replace($searches, $replaces, $this->_route);
        return $url;
    }
}