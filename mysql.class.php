<?php
/**
 * @CopyRight  
 * @WebSite    TuringPHP
 * @Author     ZY
 * @Brief      2013-01-25
 **/
/* 此页面为被引入文件，在引入此页的的公共页面顶一个常量 IN_PHP，非run.php页面不可以引入。*/
if (! defined ( 'IN_PHP' )) 
{//防止他人引入
	exit ( '黑客攻击' );
}
/*
		$querynum         执行sql次数
		$link
		$charset
	*/
class db_mysql {
		//var == public

	private $querynum = 0; //mysql_query(); 此函数执行的次数
	private $link;
	private $charset;
	/*
		$dbhost           数据库IP地址
		$dbuser           数据库用户名称
		$dbpw             数据库密码
		$dbcharset        数据库编码格式  注意： utf-8格式编码在数据库中要写成utf8格式 
		 $halt            提示信息
     */
	function __construct($dbhost, $dbuser, $dbpw, $dbname = '', $dbcharset = 'utf8', $halt = TRUE) {
		
		// 
		$this->connect ( $dbhost, $dbuser, $dbpw, $dbname, $dbcharset, $halt );
	
	}
	
	function connect($dbhost, $dbuser, $dbpw, $dbname = '', $dbcharset = 'utf8', $halt = TRUE) {
		/*
         *  打开链接通道
         **/
		
		if (! $this->link = @mysql_connect ( $dbhost, $dbuser, $dbpw )) {
			if ($halt) {
				$this->halt ( 'Can not connect to MySQL server' );
			}
			//$halt && $this->halt('Can not connect to MySQL server');
		//$this->halt('Can not connect to MySQL server');
		}
		
		/*
         *  设置编码格式
         **/
		if ($dbcharset) {
			@mysql_query ( "set names " . $dbcharset, $this->link );
		}
		
		/*
         *  选择数据库
         **/
		if ($dbname) {
			@mysql_select_db ( $dbname, $this->link );
		}
	}
	
	/**
	 * @return unknown
	 */
	public function getQuerynum() {
		return $this->querynum;
	}	
	
	// 选择数据库   
	function select_db($dbname) {
		return mysql_select_db ( $dbname, $this->link );
	}
	
	/* 
     *
     * 参数： 
     *      1.结果集
     *      2.系统常量
     *      mysql_fetch_array(结果集,系统常量);
     * 系统常量 ：
     *       MYSQL_ASSOC - 关联数组   mysql_fetch_assoc();
     *       MYSQL_NUM   - 数字数组   mysql_fetch_row(); 索引数组
     *       MYSQL_BOTH  - 默认 - 同时产生关联和数字数组   效率不高
     *
     **/
	function fetch_array($query, $result_type = MYSQL_ASSOC) {
		return mysql_fetch_array ( $query, $result_type );
	}
	
	// 返回结果集 
	function query($sql) {
		
		if (! ($query = mysql_query ( $sql, $this->link ))) {
			
			$this->halt ( 'MySQL Query Error', $sql );
		
		}
//        $query = mysql_query ( $sql, $this->link );
		$this->querynum++;
		

		return $query;
	}
	
	/*
     *  参数:无
     *
     *  作用：取回上一次MYSQL操作影响的行数
     *
     **/
	function affected_rows() {
		
		return mysql_affected_rows ( $this->link );
	}
	
	/*
     * 参数：
     *       结果集
     *
     * 作用：返回关联数组
     *
     *
     **/
	function fetch_assoc($query) {
		
		return mysql_fetch_assoc ( $query );
	}
	
	/*
     *  作用：返回错误信息
     *
     **/
	
	function error() {
		return (($this->link) ? mysql_error ( $this->link ) : mysql_error ());
	}
	
	/*
     * 作用：返回错误号
     *
     **/
	function errno() {
		return intval ( ($this->link) ? mysql_errno ( $this->link ) : mysql_errno () );
	}
	
	/*
     * 参数：
     *      结果集
     * 作用：
     *      返回结果集中信息的行数 即有多少条数据
     *
     *
     **/
	function num_rows($query) {
		//取得结果集中行的数目
		$query = mysql_num_rows ( $query );
		return $query;
	}
	
	/*
     * 参数：
     *      结果集
     * 作用：
     *      返回结果集中字段的数量
     *
     *
     **/
	function num_fields($query) {
		
		return mysql_num_fields ( $query );
	
	}
	
	/*
     * 参数：
     *      结果集
     * 作用：
     *      释放内存
     *
     *
     **/
	function free_result($query) {
		
		return mysql_free_result ( $query );
	
	}
	
	/*
     * 参数：无
     *
     * 作用：
     *      取得上一次inset into 表名 操作产生的ID号
     *      mysql_insert_id($this->link))
     *
     *
     **/
	function insert_id() {
		
		return ($id = mysql_insert_id ( $this->link )) >= 0 ? $id : $this->result ( $this->query ( "SELECT last_insert_id()" ), 0 );
	}
	
	/*
     * 参数：
     *      结果集
     *
     * 作用：
     *      返回索引数组
     *
     **/
	function fetch_row($query) {
		
		$query = mysql_fetch_row ( $query );
		return $query;
	
	}
	
	/*
     *  参数： 
     *       结果集
     *
     *  作用：
     *       以对象的形式返回数据信息
     *
     *
     **/
	function fetch_fields($query) {
		
		return mysql_fetch_field ( $query );
	
	}
	
	/*
     *  作用：
     *       关闭链接通道
     *
     *
     **/
	function close() {
		
		return mysql_close ( $this->link );
	}
	
	/*
     *  参数：
     *      SQL语句 select 类型
     *  作用：
     *      返回一条数据信息  一维数组
     *
     *
     **/
	function getone($SQL) {
		$query = $this->query ( $SQL );
		$rs = mysql_fetch_array ( $query, MYSQL_ASSOC );
		return $rs;
	}
	
	/*
     *  ★★★★★
     *  参数：
     *      1.SQL语句
     *      2.是否开启缓存
     *
     *  作用：
     *      返回SQL查询的二维关联数组
     *      注意：SQL语句一定是什么类型？
     *             select 查询类型
     *             
     *             $db->query();
     *
     **/
	function getall($sql) {
		$res = $this->query ( $sql );
		if ($res !== false) {
			$arr = array ();
			while ( ($row = mysql_fetch_assoc ( $res )) == true ) {
				$arr [] = $row;
			}
		} else {
			return false;
		}
		return $arr;
	}
	
	/*
     *  ★★★★★
     *  参数：
     *      1.$table          表名 
	 *	    2.$array          字段 - 关联数组
     *	作用:
     *	    向表中插入数据
     *
     *		$db->insert('tb_news',array('newstitle'=>'姚明2号','newscontent'=>'姚明'));
     */
	function insert($table, $array) {
		
		//定义变量为数组
		$fields = $values = array ();
		
		// $array中是字段和插入的信息，所以$array不可以为空
		if (empty ( $array )) {
			return false;
		}
		
		//定义变量为数组
		//$sql_array=array();
		

		// $array 中含有 表字段信息 键  和 需要的数据 值
		foreach ( $array as $key => $val ) {
			
			$fields [] = $key;
			
			// 判断是否为数字类型，数字类型在插入数据表的时候不需要加''
			if (is_numeric ( $val )) {
				
				$values [] = $val;
			
			} else {
				// 字符串
				$values [] = '\'' . $this->encode ( $val ) . '\'';
			
			}
		
		}
		
		// insert into 表表名(字段1，字段3) values(值1，值2)
		// 将字段 拼接
		$sql = 'INSERT INTO ' . $table . ' (' . implode ( ',', $fields ) . ') VALUES ';
		
		// $sql.=
		// $sql=$sql.
		$sql .= '(' . implode ( ',', $values ) . ');';
		
		if ($this->query ( $sql )) {
			
			return true;
		
		} else {
			
			return false;
		
		}
	}
	/*
     * 参数：
	 *	    1.$table          表名
	 *	    2.$array          字段的关联数组  字段作为键 => 修改的值
     *	    3.$where          where条件 
     * 作用：
     *      更新数据表
     */
	function update($table, $array, $where = null) {
		
		//$fields = array();
		$values = array ();
		
		foreach ( $array as $key => $val ) {
			
			if (! is_numeric ( $val )) {
				
				$val = "'" . $this->encode ( $val ) . "'";
			
			}
			
			$values [] = $key . '=' . $val;
		}
		
		//(empty($where) ?  ';' : ' WHERE '.$where.';') 三目运算符 相当于if else
		$sql = 'UPDATE ' . $table . ' SET ' . implode ( ',', $values ) . (empty ( $where ) ? ';' : ' WHERE ' . $where . ';');
		if ($this->query ( $sql )) {
			return true;
		} else {
			return false;
		}
	}
	/*
     *  参数：
     *       字符串
     *  作用：
     *       将字符串中的特殊字符加上转义字符 \
     *
     *       run.php 页面中关闭了系统自动加转义字符
     *       
     *       addslashes( 字符串 )  将字符串特殊字符加上转移字符 \ '
     *
     **/
	function encode($s) {
		return addslashes ( $s );
	}
	
	/*
     *  参数：
     *       SQL语句 select count(*) from 表名
     *  作用：
     *       返回结果集中行数
     *
     *
     **/
	function fetch_count($sql) {
		
		$rs = $this->fetch_row ( $this->query ( $sql ) );
		
		return $rs [0];
	
	}
	
	/*
     *  作用：
     *       错误信息展示
     *
     **/
	function halt($message = '', $sql = '') {
		$dberror = $this->error ();
		$dberrno = $this->errno ();
		echo "<meta http-equiv='Content-Type' content='text/html;charset=utf-8' /><div style=\"position:absolute;font-size:11px;font-family:verdana,arial;background:#EBEBEB;padding:0.5em;\"><b>MySQL Error</b><br><b>Message</b>: $message<br><b>SQL</b>: $sql<br><b>Error</b>: $dberror<br><b>Errno.</b>: $dberrno<br></div>";
		exit ();
	}
}


?>
