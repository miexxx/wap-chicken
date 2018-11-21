<?php

use EasyWeChat\Factory;
use App\Models\Article;

function mlog($txtname,$data){
    $now = date("Y-m-d H:i:s",time());
    file_put_contents($txtname.".txt",var_export($now,1)."\r\n",FILE_APPEND);
    file_put_contents($txtname.".txt",var_export($data,1)."\r\n",FILE_APPEND);
    file_put_contents($txtname.".txt","================================"."\r\n",FILE_APPEND);
}

function removeImgAttr($content){
    $content = preg_replace(
        array(
            '/(<img [^<>]*?)width=.+?[\'|\"]/',
            '/(<img.*?)((height)=[\'"]+[0-9|%]+[\'"]+)/',
        ) , array('$1 width="100%" ', '$1 height="100%"') , $content);
    return $content;
}

function getWeekDate($first_time,$time_set,$time){
    $time_set = explode(',',$time_set);
    $now = date('N',strtotime($first_time));
    $begin = date('Y-m-d',strtotime("-".($now+1)." day",strtotime($first_time)));
    $flag = 0;
    $week_set = [];
    for($i = 0;$i <$time ;$i++){

        foreach($time_set as $k=>$v){
            $day = date('Y-m-d',strtotime("+".($v+$i*7)." day",strtotime($begin)));
            $week_set[] = $day;
            if($i==0 && strtotime($day)<strtotime($first_time)){
                $flag++;
            }

        }
    }

    for($i =0 ;$i<$flag;$i++){
        array_shift($week_set);
        $day = date('Y-m-d',strtotime("+".($time_set[$i]+$time*7)." day",strtotime($begin)));
        $week_set[] = $day;
    }
    if(strtotime($first_time)<strtotime($week_set[0])){
        array_unshift($week_set,$first_time);
        array_pop($week_set);
    }
    return $week_set;
}

function getMonthDate($first_time,$time_set,$time){
    $time_set = explode(',',$time_set);
    $now = date('d',strtotime($first_time));
    $begin = date('Y-m-d',strtotime("-".($now-1)." day",strtotime($first_time)));
    $flag = 0;
    $month_set = [];
    for($i = 0;$i <$time ;$i++){

        foreach($time_set as $k=>$v){
            $day = date('Y-m-d',strtotime('+'.($v-1).' day',strtotime("+".$i." month",strtotime($begin))));
            $month_set[] = $day;
            if($i==0 && strtotime($day)<strtotime($first_time)){
                $flag++;
            }

        }
    }

    for($i =0 ;$i<$flag;$i++){
        array_shift($month_set);
        $day = date('Y-m-d',strtotime('+'.($time_set[$i]-1).' day',strtotime("+".$time." month",strtotime($begin))));
        $month_set[] = $day;
    }
    if(strtotime($first_time)<strtotime($month_set[0])){
        array_unshift($month_set,$first_time);
        array_pop($month_set);
    }
    return $month_set;
}