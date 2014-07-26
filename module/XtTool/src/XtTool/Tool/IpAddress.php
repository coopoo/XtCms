<?php
/**
 * @Author:    coopoo
 * @Copy Right:    2014
 * @Date:    2014-3-19
 * @QQ:    259522
 * @Time:    下午2:06:09
 * @Encodeing:    UTF-8
 * @FileName IpAddress.php
 * @Language PHP
 * @Description
 */
namespace XtTool\Tool;

class IpAddress
{
    /*
     * @获取客户端ip
     */
    public static function getIp()
    {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } else
            if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
                $ip = getenv("HTTP_X_FORWARDED_FOR");
            } else
                if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
                    $ip = getenv("REMOTE_ADDR");
                } else
                    if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
                        $ip = $_SERVER['REMOTE_ADDR'];
                    } else {
                        $ip = "unknown";
                    }
        return $ip;
    }

    /*
     * @获取 IP 地理位置 @淘宝IP接口 @Return: array
     */
    public static function getIpCity($ip)
    {
        $url = "http://ip.taobao.com/service/getIpInfo.php?ip=" . $ip;
        $ipobj = json_decode(file_get_contents($url));
        if ((string)$ipobj->code == '1') {
            return false;
        }
        $data = (array)$ipobj->data;
        return $data;
    }

    /*
     * @采用纯真数据库
     */
    public static function convertIp($ip)
    {
        $ip1num = 0;
        $ip2num = 0;
        $ipAddr1 = "";
        $ipAddr2 = "";
        $dat_path = XT_APPLICATION_PATH . '/public/ip/qqwry.dat';
        // 匹配IP数据
        if (!preg_match('/^((?:(?:25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d)))\.){3}(?:25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d))))$/', $ip)) {
            return 'IP Address Error';
        }
        if (!$fd = @fopen($dat_path, 'rb')) {
            return 'IP date file not exists or access denied';
        }
        $ip = explode('.', $ip);
        $ipNum = $ip[0] * 16777216 + $ip[1] * 65536 + $ip[2] * 256 + $ip[3];
        $DataBegin = fread($fd, 4);
        $DataEnd = fread($fd, 4);
        $ipbegin = implode('', unpack('L', $DataBegin));
        if ($ipbegin < 0)
            $ipbegin += pow(2, 32);
        $ipend = implode('', unpack('L', $DataEnd));
        if ($ipend < 0)
            $ipend += pow(2, 32);
        $ipAllNum = ($ipend - $ipbegin) / 7 + 1;
        $BeginNum = 0;
        $EndNum = $ipAllNum;
        while ($ip1num > $ipNum || $ip2num < $ipNum) {
            $Middle = intval(($EndNum + $BeginNum) / 2);
            fseek($fd, $ipbegin + 7 * $Middle);
            $ipData1 = fread($fd, 4);
            if (strlen($ipData1) < 4) {
                fclose($fd);
                return 'System Error';
            }
            $ip1num = implode('', unpack('L', $ipData1));
            if ($ip1num < 0)
                $ip1num += pow(2, 32);

            if ($ip1num > $ipNum) {
                $EndNum = $Middle;
                continue;
            }
            $DataSeek = fread($fd, 3);
            if (strlen($DataSeek) < 3) {
                fclose($fd);
                return 'System Error';
            }
            $DataSeek = implode('', unpack('L', $DataSeek . chr(0)));
            fseek($fd, $DataSeek);
            $ipData2 = fread($fd, 4);
            if (strlen($ipData2) < 4) {
                fclose($fd);
                return 'System Error';
            }
            $ip2num = implode('', unpack('L', $ipData2));
            if ($ip2num < 0)
                $ip2num += pow(2, 32);
            if ($ip2num < $ipNum) {
                if ($Middle == $BeginNum) {
                    fclose($fd);
                    return 'Unknown';
                }
                $BeginNum = $Middle;
            }
        }
        $ipFlag = fread($fd, 1);
        if ($ipFlag == chr(1)) {
            $ipSeek = fread($fd, 3);
            if (strlen($ipSeek) < 3) {
                fclose($fd);
                return 'System Error';
            }
            $ipSeek = implode('', unpack('L', $ipSeek . chr(0)));
            fseek($fd, $ipSeek);
            $ipFlag = fread($fd, 1);
        }
        if ($ipFlag == chr(2)) {
            $AddrSeek = fread($fd, 3);
            if (strlen($AddrSeek) < 3) {
                fclose($fd);
                return 'System Error';
            }
            $ipFlag = fread($fd, 1);
            if ($ipFlag == chr(2)) {
                $AddrSeek2 = fread($fd, 3);
                if (strlen($AddrSeek2) < 3) {
                    fclose($fd);
                    return 'System Error';
                }
                $AddrSeek2 = implode('', unpack('L', $AddrSeek2 . chr(0)));
                fseek($fd, $AddrSeek2);
            } else {
                fseek($fd, -1, SEEK_CUR);
            }
            while (($char = fread($fd, 1)) != chr(0))
                $ipAddr2 .= $char;
            $AddrSeek = implode('', unpack('L', $AddrSeek . chr(0)));
            fseek($fd, $AddrSeek);
            while (($char = fread($fd, 1)) != chr(0))
                $ipAddr1 .= $char;
        } else {
            fseek($fd, -1, SEEK_CUR);
            while (($char = fread($fd, 1)) != chr(0))
                $ipAddr1 .= $char;
            $ipFlag = fread($fd, 1);
            if ($ipFlag == chr(2)) {
                $AddrSeek2 = fread($fd, 3);
                if (strlen($AddrSeek2) < 3) {
                    fclose($fd);
                    return 'System Error';
                }
                $AddrSeek2 = implode('', unpack('L', $AddrSeek2 . chr(0)));
                fseek($fd, $AddrSeek2);
            } else {
                fseek($fd, -1, SEEK_CUR);
            }
            while (($char = fread($fd, 1)) != chr(0)) {
                $ipAddr2 .= $char;
            }
        }
        fclose($fd);
        if (preg_match('/http/i', $ipAddr2)) {
            $ipAddr2 = '';
        }
        $ipaddr = "$ipAddr1 $ipAddr2";
        $ipaddr = preg_replace('/CZ88.NET/is', '', $ipaddr);
        $ipaddr = preg_replace('/^s*/is', '', $ipaddr);
        $ipaddr = preg_replace('/s*$/is', '', $ipaddr);
        if (preg_match('/http/i', $ipaddr) || $ipaddr == '') {
            $ipaddr = 'Unknown';
        }
        return iconv("GB2312", "UTF-8//IGNORE", $ipaddr);
    }

    /*
     * @通过域名查询对应IP
     */
    protected static function hostToIpItem($hostname, $www)
    {
        // 判断是否为空
        if (empty($hostname)) {
            return false;
        }
        // 判断是否为域名格式
        if (false == self::checkDomain(strtolower(trim($hostname)))) {
            return false;
        }
        // 判断是否自动加减WWW
        if ($www) {
            if (preg_match('/^www\./', $hostname)) {
                $otherName = substr($hostname, 4);
            } else {
                $otherName = 'www.' . $hostname;
            }
            return array(
                $hostname => gethostbynamel($hostname),
                $otherName => gethostbynamel($otherName)
            );
        } else {
            return array(
                $hostname => gethostbynamel($hostname)
            );
        }
    }

    /**
     * @判断是否是域名
     */
    protected static function checkDomain($domain)
    {
        $pattern = '/^([a-zA-Z0-9]+([-]?[a-zA-Z0-9]+)?\.)?[a-zA-Z0-9]+([-]?[a-zA-Z0-9]+)?(\.[a-zA-Z]{1,4}){1,2}$/is';
        if (preg_match($pattern, $domain)) {
            return true;
        }
        return false;
    }

    /*
     * @通过域名查询对应IP @$address=true 显示IP所在地理位置
     */
    public static function hostToIp($hostname, $www = true, $address = false)
    {
        if (empty($hostname)) {
            return false;
        }
        $host = array();
        if (is_string($hostname)) {
            if (strpos($hostname, ';')) {
                $hostname = explode(';', $hostname);
                foreach ($hostname as $val) {
                    $host[] = self::hostToIpItem($val, $www);
                    unset($val);
                }
            } else {
                $host[] = self::hostToIpItem($hostname, $www);
            }
        } elseif (is_array($hostname)) {
            foreach ($hostname as $val) {
                $host[] = self::hostToIpItem($val, $www);
                unset($val);
            }
        }
        unset($hostname);
        $newhost = array();
        if (!empty($host)) {
            foreach ($host as $hostItem) {
                if (is_array($hostItem) && !empty($hostItem)) {
                    foreach ($hostItem as $k => $v) {
                        $newhost[$k] = $v;
                    }
                    unset($hostItem);
                }
            }
            unset($host);
        }

        if ($address) {
            foreach ($newhost as $key => $val) {
                if ($val == false) {
                    $newhost[$key] = 'error!';
                } elseif (is_array($val)) {
                    foreach ($val as $k => $valItem) {
                        $val[$k] = $valItem . ' >> ' . self::convertIp($valItem);
                    }
                    $newhost[$key] = $val;
                }
            }
        }

        $result = array();
        foreach ($newhost as $k => $v) {
            $result[] = array(
                'host' => $k,
                'address' => is_array($v) ? implode(';', $v) : 'error'
            );
        }
        unset($newhost);
        return $result;
    }
}
