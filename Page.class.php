<?php
/**
* filename: Page.class.php
* @package:phpbean
* @author :feifengxlq<feifengxlq@sohu.com><http://xlq521.blog.sohu.com/>
* @copyright :Copyright 2006 feifengxlq
* @license:version 1.1
* create:2006-5-31
* modify:2006-6-1
* description:超强分页类，四种分页模式，默认采用类似baidu,google的分页风格。
* 目前尚为测试版本，欢迎大家提取改进的建议。我的邮箱：feifengxlq@sohu.com
* example:
$total = 1000;
$onepage = 20;
$page=new Page($total,$onepage);
echo $pb_page->pageBar(1);
echo "<br>limit:".$page->limit();
*/

class Page
{
        /**
         * config 
         */
        private $page_name="pno";//page标签，用来控制url页。比如说xxx.php?PB_page=2中的PB_page
        private $perpage=10;//每页显示记录条数
        private $total=0;//总的记录数
        private $pagebarnum=10;//控制记录条的个数。
        
        private $totalpage=0;//总页数
        private $linkhead="";//url地址头
        private $current_pageno=1;//当前页
        /**
         * 显示符号设置
         *
         */
		 //电石法金克拉煽风点火加快速度发掘困了就睡打
        private $next_page='>';//下一页
        private $pre_page='<';//上一页
        private $first_page='First';//首页
        private $last_page='Last';//尾页
        private $pre_bar='<<';//上一分页条
        private $next_bar='>>';//下一分页条
        private $format_left='[';
        private $format_right=']';
        
        /**
         * constructor构造函数
         *
         * @param int $total
         * @param int $perpage
         */
        public function __construct($total,$perpage=10)
        {
                /*if((!is_int($total))||($total<0))
				{
				   echo "Invalid data：$total is not a positive integer!";
				   die();
				}
                if((!is_int($perpage))||($perpage<0))
				{
				  echo "Invalid data：$perpageis not a positive integer!";
				  die();
				}*/
                $this->set("total",$total);
                $this->set("perpage",$perpage);
                $this->set('totalpage',ceil($total/$perpage));
        }
        
        /**
         * 设定类中指定变量名的值，如果改变量不属于这个类，将throw一个exception
         *
         * @param string $var
         * @param string $value
         */
        public function set($var,$value)
        {
                if(in_array($var,get_object_vars($this)))
                   $this->$var=$value;
                else {
                   echo "Error in set():".$var." does not belong to PB_Page!";
                }
        }
        
        /**
         * get the default url获取指定的url地址
         *
         */
        public function get_linkhead()
        {
                $this->set_current_page();
                if(empty($_SERVER['QUERY_STRING']))
                     $this->linkhead=$_SERVER['REQUEST_URI']."?".$this->page_name."=";
                else{
                        if(isset($_GET[$this->page_name])){                                
                                $this->linkhead=str_replace($this->page_name.'='.$this->current_pageno,$this->page_name.'=',$_SERVER['REQUEST_URI']);
                        }else {
                                $this->linkhead=$_SERVER['REQUEST_URI'].'&'.$this->page_name.'=';
                        }
                }
     
        }
        
        /**
         * 为指定的页面返回地址值
         *
         * @param int $pageno
         * @return string $url
         */
        public function get_url($pageno=1)
        {
                if(empty($this->linkhead))$this->get_linkhead();
                return str_replace($this->page_name.'=',$this->page_name.'='.$pageno,$this->linkhead);
        }
        
        /**
         * 设置当前页面
         *
         */
        public function set_current_page($current_pageno=0)
        {
                if(empty($current_pageno)){
                        if(isset($_GET[$this->page_name])){
                                      $this->current_pageno=intval($_GET[$this->page_name]);
                        }
                }else{
                        $this->current_pageno=intval($current_pageno);
                }
        }
        
        public function set_format($str)
        {
                return $this->format_left.$str.$this->format_right;
        }
        
        /**
         * 获取显示"下一页"的代码
         *
         * @return string
         */
        public function next_page()
        {
                if($this->current_pageno<$this->totalpage){
                        return '<a href="'.$this->get_url($this->current_pageno+1).'">'.$this->next_page.'</a>';
                }
                return '';
        }
        
        /**
         * 获取显示“上一页”的代码
         *
         * @return string
         */
        public function pre_page()
        {
                if($this->current_pageno>1){
                        return '<a href="'.$this->get_url($this->current_pageno-1).'">'.$this->pre_page.'</a>';
                }
                return '';
        }
        
        /**
         * 获取显示“首页”的代码
         *
         * @return string
         */
        public function first_page()
        {
                return '<a href="'.$this->get_url(1).'">'.$this->first_page."</a>";
        }
        
        /**
         * 获取显示“尾页”的代码
         *
         * @return string
         */
        public function last_page()
        {
                return '<a href="'.$this->get_url($this->totalpage).'">'.$this->last_page.'</a>';
        }
        
        public function nowbar()
        {
                $begin=$this->current_pageno-ceil($this->pagebarnum/2);
                $begin=($begin>=1)?$begin:1;
                $return='';
                for($i=$begin;$i<$begin+$this->pagebarnum;$i++)
                {
                        if($i<=$this->totalpage){
                                if($i!=$this->current_pageno)
                                    $return.=$this->set_format('<a href="'.$this->get_url($i).'">'.$i.'</a>');
                                else 
                                    $return.=$this->set_format($i);
                        }else{
                                break;
                        }
                }
                unset($begin);
                return $return;
        }
        
        /**
         * 获取显示“上一分页条”的代码
         *
         * @return string
         */
        public function pre_bar()
        {
                if($this->current_pageno>ceil($this->pagebarnum/2)){
                        $pageno=$this->current_pageno-$this->pagebarnum;
                        if($pageno<=0)$pageno=1;
                        return $this->set_format('<a href="'.$this->get_url($pageno).'">'.$this->pre_bar."</a>");
                }
                return $this->set_format('<a href="'.$this->get_url(1).'">'.$this->pre_bar."</a>");
        }
        
        /**
         * 获取显示“下一分页条”的代码
         *
         * @return string
         */
        public function next_bar()
        {
                if($this->current_pageno<$this->totalpage-ceil($this->pagebarnum/2)){
                        $pageno=$this->current_pageno+$this->pagebarnum;
                        if($pageno>$this->totalpage)$pageno=$this->totalpage;
                        return $this->set_format('<a href="'.$this->get_url($pageno).'">'.$this->next_bar."</a>");
                }
                return $this->set_format('<a href="'.$this->get_url($this->totalpage).'">'.$this->next_bar."</a>");
        }
        
        /**
         * 获取显示跳转按钮的代码
         *
         * @return string
         */
        public function select()
        {
                $return='<select name="PB_Page_Select" onchange="window.location.href=\''.$this->linkhead.'\'+this.options[this.selectedIndex].value">';
                for($i=1;$i<=$this->totalpage;$i++)
                {
                        if($i==$this->current_pageno){
                                $return.='<option value="'.$i.'" selected>'.$i.'</option>';
                        }else{
                                $return.='<option value="'.$i.'">'.$i.'</option>';
                        }
                }
                $return.='</select>';
                return $return;
        }
        
        /**
         * 获取mysql 语句中limit需要的值
         *
         * @return string
         */
        public function limit()
        {
                 //这2行代码是算当前页 是我从pagebar那个函数中移动过来的
        	     $this->set_current_page();
                 $this->get_linkhead(); 
        	   if(empty($this->current_pageno))$this->set_current_page();
                return ($this->current_pageno-1)*$this->perpage.",".$this->perpage;
        }
        
        /**
         * 控制分页显示风格（你可以增加相应的风格）
         *
         * @param int $mode
         * @return string
         */
        public function pageBar($mode=1)
        {
                /*$this->set_current_page();
                $this->get_linkhead();*/
                switch ($mode)
                {
                        case '1':
                                $this->next_page='下一页';
                                $this->pre_page='上一页';
                                return $this->pre_page().$this->nowbar().$this->next_page().'第'.$this->select().'页';
                                break;
                        case '2':
                                $this->next_page='下一页';
                                $this->pre_page='上一页';
                                $this->first_page='首页';
                                $this->last_page='尾页';
                                return $this->first_page().$this->pre_page().'[第'.$this->current_pageno.'页]'.$this->next_page().$this->last_page().'第'.$this->select().'页';
                                break;
                        case '3':
                                $this->next_page='下一页';
                                $this->pre_page='上一页';
                                $this->first_page='首页';
                                $this->last_page='尾页';
                                return $this->first_page().$this->pre_page().$this->next_page().$this->last_page();
                                break;
                        case '4':
                                return $this->pre_bar().$this->pre_page().$this->nowbar().$this->next_page().$this->next_bar();
                                break;
                        case '5':
                                $this->next_page='下一页';
                                $this->pre_page='上一页';
                                $this->first_page='首页';
                                $this->last_page='尾页';
                                $strpage="共有{$this->total}条，每页显示{$this->perpage}条。";
                                return $strpage.$this->first_page().$this->pre_page().'[第'.$this->current_pageno.'页]'.$this->next_page().$this->last_page().'第'.$this->select().'页';
                                break;
                      
                }
                
        }
        
}

?>