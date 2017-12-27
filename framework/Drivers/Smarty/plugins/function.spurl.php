<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsFunction
 */
function smarty_function_spurl($params, $template)
{
	$retval = '';
	if($params['r'] != ''){
		foreach($params as $k=>$v){
			if($k == 'r')
				$r = $v;	
			else{
				$args[$k]=$v;
			}
		}	
		$retval = spUrl_2($r,$args);
	}	
	return $retval;
}

?>
