<?php
/**
 * Smarty plugin to format text blocks
 *
 * @package Smarty
 * @subpackage PluginsBlock
 */

function smarty_block_data($params, $content, $template, &$repeat)
{
	extract($params);
	if($row < 1) return '';
	if(!isset($sql)) return ;//检查是否设置了sql语句
	//注册一个smarty变量来记录结果条数
	if(method_exists($smarty,'get_template_vars'))  
		$_bindex = $smarty->get_template_vars('_dengIndex');  
	else  
		$_dengIndex=$template->getVariable("_dengIndex")->value;//注册开始
	if(!$_dengIndex){
		$_dengIndex=array();
	}
	if(empty($_dengIndex['row'])){
			$_dengIndex['row']=1;
	}else{
			$_dengIndex['row']++;
	}
	if($_dengIndex['row']>$row || $_dengIndex['row']>100){
		unset($_dengIndex['row']);		
		$template->assign('_dengIndex',$_dengIndex); //注册完毕
		return $content;
	}
	$template->assign('_dengIndex',$_dengIndex); //注册完毕
	
	#在对象$smarty上注册一个数组以供block使用
	$blocksdata=$template->properties['_blocksdata'];
	if(!isset($blocksdata))
		$template->properties['_blocksdata']= array();	
		
	#获得一个本区块的专属数据存储空间
	$dataindex = md5(__FUNCTION__ . md5(serialize($params)));
	$dataindex = substr($dataindex,0,16);
	#将使用$template->blocksdata[$dataindex]来存储
	#填充数据
	if(!$template->properties['_blocksdata'][$dataindex] && $_dengIndex['row']==1)
	{
		#************************************************************************
		#主要数据填充区
		$model=spClass('spModel');
		$result=$model->findAllSql($sql);
		#如果没有数据，直接返回null,不必再执行了
		if(!$result)
		{
			$repeat = false;
			return '';
		} 
		$template->properties['_blocksdata'][$dataindex]=$result;
		#填充区完成
		#************************************************************************
	}

	#取一条数据出栈，并把它指派给$assign，重复执行开关置位1
	$item=false;
	if($item = array_pop($template->properties['_blocksdata'][$dataindex]))
	{
		$template->assign('row',$item);
		$repeat = true;
	}
	#如果已经到达最后，重置数组指针，重复执行开关置位0
	if(!$item)
	{
		unset($template->properties['_blocksdata'][$dataindex]);
		$repeat = false;
		if($row)
		{
			unset($_dengIndex['row']);
			$template->assign('_dengIndex',$_dengIndex);
		}
	}
	return $content;
}

?>

