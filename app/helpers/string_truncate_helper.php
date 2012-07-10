<?php
    /**
     * (smarty function)
     * truncate string to some length
     * @param  $string
     * @param int $length - number of signs in string
     * @param string $etc
     * @param string $charset
     * @param bool $break_words
     * @param bool $middle
     * @return mixed|string
     */
    function smarty_modifier_mb_truncate(
                $string,
                $length = 80,
                $etc = '...',
                $wrap = false,
                $charset='UTF-8',
                $break_words = false,
                $middle = false) {

        if ($length == 0) return '';
        $result_string = "";

        if (iconv_strlen($string,$charset) > $length) {
            $length -= min($length, strlen($etc));
            if (!$break_words && !$middle) {
                $string = preg_replace('/\s+?(\S+)?$/', '',
                                 mb_substr($string, 0, $length+1, $charset));
            }
            if(!$middle) {
                $result_string = mb_substr($string, 0, $length, $charset) . $etc;
            } else {
                $result_string = mb_substr($string, 0, $length/2, $charset) .
                                         $etc .
                                         mb_substr($string, -$length/2, $charset);
            }
        } else {
            $result_string = $string;
        }
        if ($wrap) {
            return opacity_wrapper($result_string);
        }else
            return $result_string;
    }
    /**
     * градиент 5-ти последних символов
     * @param  $string
     * @return string
     */
    function opacity_wrapper ($string){
        $string = trim(strip_tags($string));
        $tmp_letter = array();
        if (strlen ($string) > 10) {
            $ArrayLetter = preg_split('//u',$string,-1,PREG_SPLIT_NO_EMPTY);
            $ArrayLetter = array_reverse($ArrayLetter);
            for  ($i = 0 ; $i<sizeof($ArrayLetter); $i++){

                if ($i < 5){
                    $tmp_letter[$i] = opacity_manager($ArrayLetter[$i],$i);
                }else{
                    $tmp_letter[$i] = $ArrayLetter[$i];
                }
            }
            $tmp_letter = array_reverse($tmp_letter);
            $result_string = "";
            foreach ($tmp_letter as $item)
                $result_string .= $item;
            return $result_string;
        }else{
            return $string;
        }
    }
    /**
     * локальная обертка для каждого из символов
     * @param  $element - елемент, который будет оборачиваться
     * @param  $opacity_k - коефициент
     * @return string
     */
    function opacity_manager ($element, $opacity_k){
        if  ((($opacity_k+1)*2) == 10){
            $opacity_style = "style='opacity:0.9;filter:alpha(opacity=".((($opacity_k+1)*20)-10).");'";
        }else{
            $opacity_style = "style='opacity:0.".(($opacity_k+1)*2).";filter:alpha(opacity=".(($opacity_k+1)*20).");'";
        }

        $result_string = "<span ".$opacity_style." >$element</span>";
        return $result_string;
    }