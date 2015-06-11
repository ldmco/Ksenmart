<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class KSFunctions {

    public static function GenAlias($str, $type = '') {
        $converter = array(
            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'д' => 'd',
            'е' => 'e',
            'ё' => 'e',
            'ж' => 'zh',
            'з' => 'z',
            'и' => 'i',
            'й' => 'y',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ф' => 'f',
            'х' => 'h',
            'ц' => 'c',
            'ч' => 'ch',
            'ш' => 'sh',
            'щ' => 'sch',
            'ь' => '\'',
            'ы' => 'y',
            'ъ' => '\'',
            'э' => 'e',
            'ю' => 'yu',
            'я' => 'ya',

            'А' => 'A',
            'Б' => 'B',
            'В' => 'V',
            'Г' => 'G',
            'Д' => 'D',
            'Е' => 'E',
            'Ё' => 'E',
            'Ж' => 'Zh',
            'З' => 'Z',
            'И' => 'I',
            'Й' => 'Y',
            'К' => 'K',
            'Л' => 'L',
            'М' => 'M',
            'Н' => 'N',
            'О' => 'O',
            'П' => 'P',
            'Р' => 'R',
            'С' => 'S',
            'Т' => 'T',
            'У' => 'U',
            'Ф' => 'F',
            'Х' => 'H',
            'Ц' => 'C',
            'Ч' => 'Ch',
            'Ш' => 'Sh',
            'Щ' => 'Sch',
            'Ь' => '\'',
            'Ы' => 'Y',
            'Ъ' => '\'',
            'Э' => 'E',
            'Ю' => 'Yu',
            'Я' => 'Ya',
            );
        $str = strtr($str, $converter);
        $str = strtolower($str);
        $str = preg_replace('~[^-a-z0-9_]+~u', '.', $str);
        $str = trim($str, "-");
        $str = str_replace(array(
            '.',
            '-',
            '+',
            '=',
            '/'), array(
            '_',
            '_',
            '_',
            '_',
            '_'), $str);
        $bad_words = array(
            '',
            'price_less',
            'price_more',
            'order_type',
            'order_dir');
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('config')->from('#__ksen_seo_config')->where('type="url"');
        $db->setQuery($query);
        $configs = $db->loadObjectList();
        foreach($configs as $config) {
            $config = json_decode($config->config);
            foreach($config as $c)
                if($c->user == 1) $bad_words[] = $c->title;
        }
        if(in_array($str, $bad_words)) $str .= time();
        $tables = array(
            '#__ksenmart_products',
            '#__ksenmart_categories',
            '#__ksenmart_manufacturers',
            '#__ksenmart_countries',
            '#__ksenmart_properties',
            '#__ksenmart_property_values');
        foreach($tables as $table) {
            $query = $db->getQuery(true);
            $query->select('count(*)')->from($table)->where('alias="' . $str . '"');
            $db->setQuery($query);
            $count = $db->loadResult();
            if($count > 0) $str .= time();
        }
        return $str;
    }

    public static function checkAlias($alias, $id = 0) {
        $alias = str_replace(array(
            '.',
            '-',
            '+',
            '=',
            '/'), array(
            '_',
            '_',
            '_',
            '_',
            '_'), $alias);
        $bad_words = array(
            'price_less',
            'price_more',
            'order_type',
            'order_dir');
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('config')->from('#__ksen_seo_config')->where('type="url"');
        $db->setQuery($query);
        $configs = $db->loadObjectList();
        foreach($configs as $config) {
            $config = json_decode($config->config);
            foreach($config as $c)
                if($c->user == 1) $bad_words[] = $c->title;
        }
        if(in_array($alias, $bad_words)) return '';
        $tables = array(
            '#__ksenmart_products',
            '#__ksenmart_categories',
            '#__ksenmart_manufacturers',
            '#__ksenmart_countries',
            '#__ksenmart_properties',
            '#__ksenmart_property_values');
        foreach($tables as $table) {
            $query = $db->getQuery(true);
            $query->select('count(*)')->from($table)->where('alias="' . $alias . '"');
            if((int)$id != 0) $query->where('id!=' . (int)$id);
            $db->setQuery($query);
            $count = $db->loadResult();
            if($count > 0) return '';
        }
        return $alias;
    }

    public static function filterArray($var) {
        return ($var != 0);
    }
	
    public static function filterStrArray($var) {
        return ($var != '');
    }	

    public static function formatDate($date) {
        $str_date = '';
        $date = explode(' ', $date);
        $date = explode('-', $date[0]);
        $mon = '';
        switch($date[1]) {
            case '01':
                $mon = 'января';
                break;
            case '02':
                $mon = 'февраля';
                break;
            case '03':
                $mon = 'марта';
                break;
            case '04':
                $mon = 'апреля';
                break;
            case '05':
                $mon = 'мая';
                break;
            case '06':
                $mon = 'июня';
                break;
            case '07':
                $mon = 'июля';
                break;
            case '08':
                $mon = 'августа';
                break;
            case '09':
                $mon = 'сентября';
                break;
            case '10':
                $mon = 'октября';
                break;
            case '11':
                $mon = 'ноября';
                break;
            case '12':
                $mon = 'декабря';
                break;
        }
        $str_date = $date[2] . ' ' . $mon . ' ' . $date[0];
        return $str_date;
    }

    public static function getAddToCartLink() {
        $params = JComponentHelper::getParams('com_ksenmart');
        $Itemid = KSSystem::getShopItemid();
        if($params->get('order_process', 0) == 1) {
            $session = JFactory::getSession();
            $order_id = $session->get('shop_order_id', 0);
            if($order_id == 0) $add_link_cart = JRoute::_('index.php?option=com_ksenmart&view=order&Itemid=' . $Itemid);
            else  $add_link_cart = JRoute::_('index.php?option=com_ksenmart&view=cart&Itemid=' . $Itemid);
        } else  $add_link_cart = JRoute::_('index.php?option=com_ksenmart&view=cart&Itemid=' . $Itemid);
        return $add_link_cart;
    }

    public static function getCouponDiscount($price, $coupon_id) {
        $db = JFactory::getDBO();
        $diff = 0;
        $price_with_discount = 0;
        $query = "select * from #__ksenmart_discounts where id='$coupon_id'";
        $db->setQuery($query);
        $discount = $db->loadObject();
        if(count($discount) > 0) {
            if($discount->discount_type == 1) $price_with_discount = $price - $discount->discount;
            else  $price_with_discount = round($price * (100 - $discount->discount) / 100, 2);
            $diff = $price - $price_with_discount;
        }
        return $diff;
    }

    public static function number2string($n, $rod) //перевести число $n в строку. Число обязательно должно быть 0 < $n < 1000. $rod указывает на род суффикса (0 - женский, 1 - мужской; например, "рубль" - 1, "тысяча" - 0).
        {
        $n = round($n % 1000);
        $a = floor($n / 100) * 100;
        $b = floor(($n - $a) / 10) * 10;
        $c = $n % 10;
        if($b == 10) {
            $b = $b + $c;
            $c = 0;
        }
        $s = "";
        switch($a) { //сотни
            case 100:
                $s = "сто";
                break;
            case 200:
                $s = "двести";
                break;
            case 300:
                $s = "триста";
                break;
            case 400:
                $s = "четыреста";
                break;
            case 500:
                $s = "пятьсот";
                break;
            case 600:
                $s = "шестьсот";
                break;
            case 700:
                $s = "семьсот";
                break;
            case 800:
                $s = "восемьсот";
                break;
            case 900:
                $s = "девятьсот";
                break;
        }
        $s .= ($s && $b) ? " " : '';
        switch($b) { //десятки
            case 10:
                $s .= "десять";
                break;
            case 11:
                $s .= "одиннадцать";
                break;
            case 12:
                $s .= "двенадцать";
                break;
            case 13:
                $s .= "тринадцать";
                break;
            case 14:
                $s .= "четырнадцать";
                break;
            case 15:
                $s .= "пятнадцать";
                break;
            case 16:
                $s .= "шестнадцать";
                break;
            case 17:
                $s .= "семнадцать";
                break;
            case 18:
                $s .= "восемнадцать";
                break;
            case 19:
                $s .= "девятнадцать";
                break;
            case 20:
                $s .= "двадцать";
                break;
            case 30:
                $s .= "тридцать";
                break;
            case 40:
                $s .= "сорок";
                break;
            case 50:
                $s .= "пятьдесят";
                break;
            case 60:
                $s .= "шестьдесят";
                break;
            case 70:
                $s .= "семьдесят";
                break;
            case 80:
                $s .= "восемьдесят";
                break;
            case 90:
                $s .= "девяносто";
                break;
        }
        $s .= ($s && $c) ? " " : '';
        switch($c) { //единицы
            case 1:
                switch($rod) {
                    case 0:
                        $s .= "одна";
                        break; //ж.р. И.п.
                    case 1:
                        $s .= "один";
                        break; //м.р. И.п.
                    case 2:
                        $s .= "одну";
                        break; //ж.р. Р.п.
                    case 3:
                        $s .= "один";
                        break; //м.р. Р.п.
                }
                break;
            case 2:
                switch($rod) {
                    case 0:
                        $s .= "две";
                        break; //ж.р. И.п.
                    case 1:
                        $s .= "два";
                        break; //м.р. И.п.
                    case 2:
                        $s .= "две";
                        break; //ж.р. Р.п.
                    case 3:
                        $s .= "два";
                        break; //м.р. Р.п.
                }
                break;
            case 3:
                $s .= "три";
                break;
            case 4:
                $s .= "четыре";
                break;
            case 5:
                $s .= "пять";
                break;
            case 6:
                $s .= "шесть";
                break;
            case 7:
                $s .= "семь";
                break;
            case 8:
                $s .= "восемь";
                break;
            case 9:
                $s .= "девять";
                break;
        }
        return $s;
    }

    public static function stringView($amount) {
        //разделить сумма на разряды: единицы, тысячи, миллионы, миллиарды (больше миллиардов не проверять :) )
        $n = round($amount, 2);
        $billions = floor($n / 1000000000);
        $millions = floor(($n - $billions * 1000000000) / 1000000);
        $grands = floor(($n - $billions * 1000000000 - $millions * 1000000) / 1000);
        $roubles = floor(($n - $billions * 1000000000 - $millions * 1000000 - $grands * 1000)); //$n % 1000;


        //копейки
        $kop = round($n * 100 - round(floor($n) * 100));
        //var_dump(array($n,$billions,$millions,$grands,$roubles,$kop));
        if($kop < 10) $kop = "0" . (string )$kop;

        $s = "";
        if($billions > 0) {
            $t = "ов";
            $temp = $billions % 10;
            if(floor(($billions % 100) / 10) != 1) {
                if($temp == 1) $t = "";
                else
                    if($temp >= 2 && $temp <= 4) $t = "а";
            }
            $s .= KSFunctions::number2string($billions, 1) . " миллиард{$t} ";
        }
        if($millions > 0) {
            $t = "ов";
            $temp = $millions % 10;
            if(floor(($millions % 100) / 10) != 1) {
                if($temp == 1) $t = "";
                else
                    if($temp >= 2 && $temp <= 4) $t = "а";
            }
            $s .= KSFunctions::number2string($millions, 1) . " миллион{$t} ";
        }
        if($grands > 0) {
            $t = "";
            $temp = $grands % 10;
            if(floor(($grands % 100) / 10) != 1) {
                if($temp == 1) $t = "а";
                else
                    if($temp >= 2 && $temp <= 4) $t = "и";
            }
            $s .= KSFunctions::number2string($grands, 0) . " тысяч{$t} ";
        }
        if($roubles > 0) {
            $rub = "ей";
            $temp = $roubles % 10;
            if(floor(($roubles % 100) / 10) != 1) {
                if($temp == 1) $rub = "ь";
                else
                    if($temp >= 2 && $temp <= 4) $rub = "я";
            }
            $s .= KSFunctions::number2string($roubles, 1) . " рубл{$rub} ";
        }
        {
            $kp = "ек";
            $temp = $kop % 10;
            if(floor(($kop % 100) / 10) != 1) {
                if($temp == 1) $kp = "йка";
                else
                    if($temp >= 2 && $temp <= 4) $kp = "йки";
            }

            $s .= "{$kop} копе{$kp}";
        }
        /*
        //теперь сделать первую букву заглавной
        if ($roubles>0 || $grands>0 || $millions>0 || $billions>0)
        {
        $cnt=0; while(substr($s, $cnt, 1)==" ") $cnt++;
        $s = substr($s, $cnt, 1);
        $s[$cnt] = chr( ord($s[$cnt])- 32 );
        }
        */
        return $s;
    }

    public static function getStandartDate($date) {
        return date('d.m.Y', strtotime($date));
    }

}
