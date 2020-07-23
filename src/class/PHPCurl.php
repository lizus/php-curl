<?php
namespace Lizus\PHPCurl;

/*
a simple curl class for get or post data
can use set_options to custom curl options
*/

class PHPCurl
{
  protected $options=[
    CURLOPT_RETURNTRANSFER => true,         // return web page
    CURLOPT_HEADER         => false,        // don't return headers
    CURLOPT_FOLLOWLOCATION => true,         // follow redirects
    CURLOPT_ENCODING       => "",           // handle all encodings
    CURLOPT_USERAGENT => 'PHPCurl',         // user agent
    CURLOPT_AUTOREFERER    => true,         // set referer on redirect
    CURLOPT_CONNECTTIMEOUT => 30,          // timeout on connect
    CURLOPT_TIMEOUT        => 30,          // timeout on response
    CURLOPT_MAXREDIRS      => 5,           // stop after 10 redirects
    CURLOPT_POST => 0,                      // not sending post
    CURLOPT_SSL_VERIFYHOST => 0,            // don't verify ssl
    CURLOPT_SSL_VERIFYPEER => false,        //
  ];

  function __construct($args=[]) {
    if (\defined('PHPCURL_UA')) $this->options[CURLOPT_USERAGENT]=PHPCURL_UA;//custom UA, can be overwrite by set_options
    if (\defined('PHPCURL_COOKIE')) $this->options[CURLOPT_COOKIE]=PHPCURL_COOKIE;//custom Cookie, can be overwrite by set_options
    if (\is_array($args)) $this->set_options($args);
  }

  /**
   * apply curl and get result
   * @return array [
   * err      curl error number
   * errmsg   curl error message
   * info     curl information
   * data     curl return data, if the http_code not equal 200 then it will return empty string
   * ]
   */
  protected function apply(){
    $ch      = \curl_init();
    \curl_setopt_array($ch,$this->options);
    $data    = \curl_exec($ch);
    $err     = \curl_errno($ch);
    $errmsg  = \curl_error($ch);
    $info  = \curl_getinfo($ch);
    \curl_close($ch);
    if (!isset($info['http_code']) || $info['http_code'] != 200) {
      $data='';
    }
    return [
      'err'=>$err,
      'errmsg'=>$errmsg,
      'info'=>$info,
      'data'=>$data,
    ];
  }

  /**
   * set curl options
   * @param array $args curl options, it can overwrite all defaults
   */
  public function set_options($args=[]){
    if (\is_array($args) && \count($args)>0) {
      $this->options=\array_merge($this->options,$args);
    }
  }

  /**
   * get
   * @param  string $url url
   * @return array     result data
   */
  public function get($url){
    $this->options[CURLOPT_URL]=$url;
    return $this->apply();
  }

  /**
   * post
   * @param  string $url url
   * @param  array $data  post data, use array (key => value)
   * @return array     result data
   */
  public function post($url,$data){
    $this->options[CURLOPT_URL]=$url;
    $this->options[CURLOPT_POST]=1;
    $this->options[CURLOPT_POSTFIELDS]=$data;
    return $this->apply();
  }
}
