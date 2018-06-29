<?php
/**
 * Created by PhpStorm.
 * User: dong
 * Date: 2018/6/12
 * Time: 15:33
 */

namespace api\traits;


trait FormatResult
{
    /**
     * result
     * 黄东 kmdgs@qq.com
     * 2018/6/12 15:35
     *
     * @param $data
     * @return array
     */
    public static function result($data){
          return ['name'=>'success','message'=>'','code'=>0,'status'=>200,'type'=>'','data'=>$data];
     }
}