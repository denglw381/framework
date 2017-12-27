<?php
/**
 +------------------------------------------------------------------------------
 * 分页显示类
 +------------------------------------------------------------------------------
 * @category   ORG
 * @package  ORG
 * @subpackage  Util
 * @author    liu21st <liu21st@gmail.com>
 * @version   $Id$
 +------------------------------------------------------------------------------
 */
class SpPage
{//类定义开始

    /**
     +----------------------------------------------------------
     * 分页起始行数
     +----------------------------------------------------------
     * @var integer
     * @access protected
     +----------------------------------------------------------
     */
    protected $firstRow ;

    /**
     +----------------------------------------------------------
     * 列表每页显示行数
     +----------------------------------------------------------
     * @var integer
     * @access protected
     +----------------------------------------------------------
     */
    protected $listRows ;

    /**
     +----------------------------------------------------------
     * 页数跳转时要带的参数
     +----------------------------------------------------------
     * @var integer
     * @access protected
     +----------------------------------------------------------
     */
    protected $parameter  ;

    /**
     +----------------------------------------------------------
     * 分页总页面数
     +----------------------------------------------------------
     * @var integer
     * @access protected
     +----------------------------------------------------------
     */
    protected $totalPages  ;

    /**
     +----------------------------------------------------------
     * 总行数
     +----------------------------------------------------------
     * @var integer
     * @access protected
     +----------------------------------------------------------
     */
    protected $totalRows  ;

    /**
     +----------------------------------------------------------
     * 当前页数
     +----------------------------------------------------------
     * @var integer
     * @access protected
     +----------------------------------------------------------
     */
    protected $nowPage    ;

    /**
     +----------------------------------------------------------
     * 分页的栏的总页数
     +----------------------------------------------------------
     * @var integer
     * @access protected
     +----------------------------------------------------------
     */
    protected $coolPages   ;

    /**
     +----------------------------------------------------------
     * 分页栏每页显示的页数
     +----------------------------------------------------------
     * @var integer
     * @access protected
     +----------------------------------------------------------
     */
    protected $rollPage   ;

    /**
     +----------------------------------------------------------
     * 分页记录名称
     +----------------------------------------------------------
     * @var integer
     * @access protected
     +----------------------------------------------------------
     */

    // 分页显示定制
    protected $config   =   array('header'=>'条记录','prev'=>'上一页','next'=>'下一页','first'=>'第一页','last'=>'最后一页');

    protected $url = "";

    /**
     +----------------------------------------------------------
     * 架构函数
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $totalRows  总的记录数
     * @param array $firstRow  起始记录位置
     * @param array $listRows  每页显示记录数
     * @param array $parameter  分页跳转的参数
     +----------------------------------------------------------
     */
    public function __construct($totalRows,$listRows='',$parameter='')
    {
        $this->totalRows = $totalRows;
        $this->parameter = $parameter;
        $this->rollPage = 5;
        $this->listRows = !empty($listRows)?$listRows:20;
        $this->totalPages = ceil($this->totalRows/$this->listRows);     //总页数
        $this->coolPages  = ceil($this->totalPages/$this->rollPage);
        $this->nowPage  = !empty($_GET[spConfig('var_page')])&&($_GET[spConfig('var_page')] >0)?$_GET[spConfig('var_page')]:1;

        if( (!empty($this->totalPages) && $this->nowPage>$this->totalPages) || $_GET[spConfig('var_page')]=='last' ) {
            $this->nowPage = $this->totalPages;
        }

        $this->firstRow = $this->listRows*($this->nowPage-1);
    }


    /**
     *重新设置分页显示字符 
     */
    public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name]    =   $value;
        }
    }

   
    /**
     *分页显示程序 
     */
    public function show($isArray=false){
		if(0 == $this -> totalRows) return;
	    $this -> getUrl();
		$linkPage = $this -> getLinkPage();
    	if($this->totalPages <= 1) return '';
        if($isArray) {
            $upRow   = $this->nowPage-1;
            $downRow = $this->nowPage+1;
            $pageArray['totalRows'] =   $this->totalRows;
			if($upRow > 0)
            $pageArray['upPage']    =   $this->url.'&'.spConfig('var_page')."=$upRow";
			if($downRow < $this -> totalPages)
            $pageArray['downPage']  =   $this->url.'&'.spConfig('var_page')."=$downRow";
            $pageArray['totalPages']=   $this->totalPages;
            $pageArray['firstPage'] =   $this->url.'&'.spConfig('var_page')."=1";
            $pageArray['endPage']   =   $this->url.'&'.spConfig('var_page')."=".$this -> totalPages;
            $pageArray['linkPages'] =   $linkPage;
            $pageArray['nowPage'] =   $this->nowPage;
            return $pageArray;
        }
		$upPage   = $this -> getUpPage();
		$downPage = $this -> getDownPage();
		$pageStr  = $upPage.$linkPage.$downPage;
		return $pageStr;
	}

    /**
     *获取网页地址 
     */
    private function  getUrl(){
        $url	=	$_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?").(strpos($_SERVER['REQUEST_URI'],$this->parameter)===false?$this->parameter:'');
		$url	=	eregi_replace("&p=[0-9]+",'',$url);
		$this -> url = $url;
	}

    /**
     *获取上一页 
     */
    private function getUpPage(){
		$upRow   = $this->nowPage-1;
		if ($upRow > 0){
			$upPage = "<li><a href='".$this -> url."&".spConfig('var_page')."=$upRow' class='prev'><span>".$this->config['prev']." </span><b></b></a></li>";
		}else{
			$upPage = '';
		}
        return $upPage;
	}

   
    /**
     *获取下一页 
     */
    private function getDownPage(){
    	$downRow = $this->nowPage+1;
		if ($downRow <= $this->totalPages){
			$downPage="<li><a href='".$this -> url."&".spConfig('var_page')."=$downRow' class='next'><span>".$this->config['next']."</span><b></b></a></li>";
		}else{
			$downPage='';
		}
        return $downPage;
	}

    /**
     *获取中部显示样式1 2 3 4 5 ... 10 或 1 ... 3 4 5 6 7 ... 10 或 1 ... 6 7 8 9 10 
     */
	private function getLinkPage(){
		$frontEnd = $this -> rollFrontEnd(); 
		$linkPage = "";
		// 1 ... 部分
		if($frontEnd["front"] > 1){
			$linkPage .= "<li><a href='".$this -> url."&".spConfig('var_page')."=1'><span>1</span></a></li>";
			if(1+$this->rollPage<$this->totalPages) $linkPage .= "<li><a href='javascript:;'>...</a></li>";
		}
		// 3 4 5 6 7
        for($page=$frontEnd["front"];$page<=$frontEnd["end"];$page++){
            if($page!=$this->nowPage){
                if($page<=$this->totalPages){
                    $linkPage .= "<li><a href='".$this -> url."&".spConfig('var_page')."=$page'><span>".$page." </span></a></li>";
                }else{
                    break;
                }
            }else{
                if($this->totalPages != 1){
                    $linkPage .= " <li><a class='current'><span>".$page." </span></a></li>";
                }
            }
        }
		// ... 10 部分
		if($frontEnd["end"] < $this -> totalPages){
			if(1+$this->rollPage<$this->totalPages) $linkPage .= "<li><a href='javascript:;'>...</a></li>";
			$linkPage .= "<li><a href='".$this -> url."&".spConfig('var_page')."=".$this -> totalPages."'><span>".$this -> totalPages."</span></a></li>";
		}
		return $linkPage;
	}

    /**
     * 获取 3 4 5 6 7 的3 和 7  
     */
	private function rollFrontEnd(){
		$return = array("front"=>0,"end"=>0);
		$middleRow = floor($this -> rollPage/2);
		$return["front"] = $this -> nowPage - $middleRow;
		if($return["front"] <= $middleRow){
			$return["front"] = 1;
			$return["end"]   = $this -> rollPage;
			return $return;
		}
		$return["end"]   = $this -> nowPage + $middleRow;
		if($return["end"] >= $this -> totalPages -1  ){
			$return["end"]= $this -> totalPages ;
			$return["front"] = $this -> totalPages - $this -> rollPage + 1 ;
			return $return;
		}
		return $return;
	}
}//类定义结束
?>
