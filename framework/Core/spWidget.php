<?php
#模块
abstract class spWidget{

	abstract public function render($data, $tpl);

	#渲染文件
	protected function renderFile($templateFile = '', $var = '', $charset = 'utf-8') {
		if (!file_exists( $templateFile )) {
			// 自动定位模板文件
			$name = substr ( get_class ( $this ), 0, - 6 );
			$filename = empty ( $templateFile ) ? $name : $templateFile;
			$templateFile = WIDGET_PATH . '/template/' . $name . '/' . $filename . spConfig ( 'view.auto_display_suffix' );
			if (! file_exists ( $templateFile ))
				 spError( '模板'. $templateFile . '不存在，请进行检查!' );
		}   
		$tpl = spClass('spView'); 
		foreach($var as $k=>$v){
			$tpl->engine->assign($k, $v);
		}
		$content = $tpl->engine->fetch ($templateFile);
		return $content;
	}   
}
