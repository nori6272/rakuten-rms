<?php
/**
 * Rabuten Rms class
 *
 * @author wareon <wareon@qq.com>
 * @date 2020/1/10 12:27
 * @since rakuten rms 1.0
 */

namespace Wareon\RakutenRms;

use Illuminate\Config\Repository;
use Wareon\RakutenRms\Models\Item;

class RakutenRms
{
    use \Wareon\RakutenRms\Func\Item;

    public $replaceApi = '';

    public $serviceSecret = "";
    public $licenseKey = "";
    public $logFile = '';//output log file

    public $proxy = false;
    public $curloptProxy = '';
    public $curloptProxyPort = '';
    public $curloptProxyUserpwd = '';

    /**
     * @var Repository
     */
    protected $config;

    /**
     * Packagetest constructor.
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config->get('rakuten-rms');
        $this->replaceApi = $this->config['replace_api'];
        $this->serviceSecret = $this->config['service_secret'];
        $this->licenseKey = $this->config['license_key'];
        $this->logFile = $this->config['log_file'];
        $this->proxy = $this->config['proxy'];
        $this->curloptProxy = $this->config['curlopt_proxy'];
        $this->curloptProxyPort = $this->config['curlopt_proxy_port'];
        $this->curloptProxyUserpwd = $this->config['curlopt_proxy_userpwd'];
    }

    public function dealUrl($uri)
    {
        if (!empty($this->replaceApi)) {
            return $this->replaceApi . urlencode($uri);
        } else {
            return ApiDefine::HOST . $uri;
        }
    }

    public function xml2arr($xmlStr)
    {
        $xml = simplexml_load_string($xmlStr);
        $jsonStr = json_encode($xml);
        return json_decode($jsonStr, true);
    }

    public function strToUtf8($str)
    {
        $encode = mb_detect_encoding($str, array("ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5', 'EUC-JP'));
        if ($encode == 'UTF-8') {
            return $str;
        } else {
            return mb_convert_encoding($str, 'UTF-8', $encode);
        }
    }

    public function utf8ToEuc_jp($str)
    {
        return mb_convert_encoding($str, 'EUC-JP', 'UTF-8');
    }

    public function authHeader()
    {
        $cryptStr = 'ESA ' . base64_encode($this->serviceSecret . ':' . $this->licenseKey);
        return $cryptStr;
    }

    public function curl($url, $post = false, $params = [])
    {
        $headerArray = array(
            "Content-type: text/xml; charset=utf-8",
            "Accept:application/xml",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36"
        );
        $headerArray[] = 'Authorization:' . $this->authHeader();
        $ch = curl_init();
        if ($post === false && !empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, $post);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);

        if ($this->proxy) {
            curl_setopt($ch, CURLOPT_PROXY, $this->curloptProxy);
            curl_setopt($ch, CURLOPT_PROXYPORT, $this->curloptProxyPort);
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->curloptProxyUserpwd);
        }

        $output = curl_exec($ch);
        curl_close($ch);
        if (!empty($this->logFile)) $this->log($output, $this->logFile);
        return $output;
    }

    public function log($output, $file = 'output.log')
    {
        try {
            file_put_contents(storage_path($file), $output);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
