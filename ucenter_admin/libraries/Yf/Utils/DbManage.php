<?php

class Yf_Utils_DBManage
{
	var $db; // 数据库连接
	var $msg;

	/**
	 * 初始化
	 *
	 * @param string $db
	 */
	function __construct($db)
	{
		$this->db = $db;
		set_time_limit(0);//无时间限制
	}

	/**
	 * 将sql导入到数据库（普通导入）
	 *
	 * @param string $sqlfile
	 * @return boolean
	 */
	public function import($sqlfile, $db_prefix='yf_', $db_prefix_base='yf_')
	{
		// sql文件包含的sql语句数组
		$sqls = array();
		$f    = fopen($sqlfile, "rb");

		// 创建表缓冲变量
		$create_table = '';

		while (!feof($f))
		{
			// 读取每一行sql
			$line = fgets($f);

			if (substr($line, 0, 2) == '/*' || substr($line, 0, 2) == '--' || $line == '')
			{
				continue;
			}

			$create_table .= $line;
			if (substr(trim($line), -1, 1) == ';')
			{
				$create_table = str_replace($db_prefix_base, $db_prefix, $create_table);
				//执行sql语句创建表
				$flag = $this->exec($create_table);

				echo str_repeat(" ", 4096);  //以确保到达output_buffering值

				echo '.';
				ob_flush();
				flush();

				if (!$flag)
				{
					return false;
				}

				// 清空当前，准备下一个表的创建
				unset($create_table);
				$create_table = '';
			}

			unset($line);
		}

		fclose($f);
		
		return true;
	}

	//插入单条sql语句
	private function exec($sql)
	{
		if (false === $this->db->exec(trim($sql)))
		{
			$this->msg .= $this->db->getErrorCode();
			return false;
		}

		unset($sql);

		return true;
	}
}
