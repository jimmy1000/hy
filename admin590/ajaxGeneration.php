<?php

include 'global.php';
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/31
 * Time: 19:29
 */
error_reporting(E_ALL^E_NOTICE^E_WARNING);

$configArr = include_once "core/pay_config.php";

// api 文件夹路径
$API_PATH = '../api/';

//生成文件夹
if($_POST['act'] == 'check_name'){
	//接口商名中文名
	$api_name = isset($_POST['api_name']) ? trim($_POST['api_name']) : '';
	if(!$api_name){
		returnJson([['err' => 1, 'msg' => '请输入有效的支付商名']]);
	}

	//pinyin插件    中文转拼音
	include_once 'core/pinyin-master/src/Pinyin.php';
	$pinyinObj = new Pinyin();

	$pinyin = implode('', $pinyinObj->convert($api_name));
	$dirArr = scandir($API_PATH);
	if(in_array($pinyin . 'pay', $dirArr)){
		$pinyin = implode('', $pinyinObj->convert($api_name, PINYIN_ASCII));
		if(in_array($pinyin . 'pay', $dirArr)){
			returnJson(['err' => 1, 'msg' => $pinyin . ' 文件夹已存在, 请在支付商后面加上数字']);
		}
	}

	//api文件夹名
	$api_dir_name = $pinyin . 'pay';
	//创建文件夹
	mkdir($API_PATH . $api_dir_name, 0777);

	returnJson(['err' => 0, 'msg' => '成功', 'api_name' => $api_name, 'api_pinyin' => $api_dir_name]);

}

//输入值
function input_value($value){
	$value = ltrim($value);
	$value = stripslashes(rtrim($value));

	switch($value[0]){
		case '$':
			break;
		case ':':
			$value = substr($value, 1);
			break;
		default:
			if(strpos('function', $value) !== 0){
				if(!is_numeric($value)){
					$value = "'" . $value . "'";
				}
			}
	}

	return $value;
}

//生成模板文件
if($_POST['act'] == 'generation'){
	// template 路径
	$TMPLATE_PATH = 'code_tmplate/';

	$API_DIR_NAME = $_POST['api_dir_path'];

	// 关闭顶层的输出缓冲区内容
	ob_end_clean();

	//循环生成代码
	$make_code = function($tmplate_file) use($_POST, $API_PATH, $API_DIR_NAME, $TMPLATE_PATH, &$make_code){
		// 加载变量
		extract($_POST);

		// 获取模板文件夹下的所有文件        要替换的文件    替换为自动获取
		$tmplate_fileArr = scandir($tmplate_file);

		foreach($tmplate_fileArr as $tmplate_filename){
			if($tmplate_filename == '' ||  $tmplate_filename == '.' || $tmplate_filename == '..'){
				continue;
			}
			
			if(is_dir($tmplate_file . $tmplate_filename)){
				//创建文件夹
				mkdir($API_PATH . $API_DIR_NAME . '/' . $tmplate_filename, 0777);
				//递归
				$make_code($tmplate_file . $tmplate_filename . '/');
				continue;
			}

			//初始化
			$view = '';

			// 开始一个新的缓冲区
			ob_start();

			// 加载视图view
			require "$tmplate_file$tmplate_filename";

			// 获得缓冲区的内容
			$view = ob_get_contents();

			//php文件 加入php文件头
			$path_info = pathinfo($tmplate_filename);
			if($path_info['extension'] == 'php'){
				$view = '<?php' . PHP_EOL . PHP_EOL . $view;
			}

			// 关闭缓冲区
			ob_end_clean();

			//输出文件
			$tmplate_filename =   str_replace($TMPLATE_PATH, '/' , $tmplate_file . $tmplate_filename);
			file_put_contents($API_PATH . $API_DIR_NAME . $tmplate_filename, $view);

//		ob_implicit_flush(0);
		}
	};
	
	//匿名函数实现递归
	$make_code($TMPLATE_PATH);

	// 开始新的缓冲区,给后面的程序用*/
	ob_start();

	returnJson(['err' => 0, 'msg' => '生成成功']);
}


returnJson(['err' => 1, 'msg' => '操作错误']);







