<?php
namespace Modules\iribfinance\Forms;
use core\CoreClasses\services\FormDesign;
use core\CoreClasses\services\MessageType;
use core\CoreClasses\services\baseHTMLElement;
use core\CoreClasses\html\ListTable;
use core\CoreClasses\html\UList;
use core\CoreClasses\html\FormLabel;
use core\CoreClasses\html\UListElement;
use core\CoreClasses\html\Div;
use core\CoreClasses\html\link;
use core\CoreClasses\html\Lable;
use core\CoreClasses\html\TextBox;
use core\CoreClasses\html\DatePicker;
use core\CoreClasses\html\DataComboBox;
use core\CoreClasses\html\SweetButton;
use core\CoreClasses\html\Button;
use core\CoreClasses\html\CheckBox;
use core\CoreClasses\html\RadioBox;
use core\CoreClasses\html\SweetFrom;
use core\CoreClasses\html\ComboBox;
use core\CoreClasses\html\FileUploadBox;
use Modules\common\PublicClasses\AppRooter;
use Modules\common\PublicClasses\UrlParameter;
use core\CoreClasses\SweetDate;
/**
*@author Hadi AmirNahavandi
*@creationDate 1396-11-27 - 2018-02-16 01:43
*@lastUpdate 1396-11-27 - 2018-02-16 01:43
*@SweetFrameworkHelperVersion 2.004
*@SweetFrameworkVersion 2.004
*/
class workunitlist_Design extends FormDesign {
	private $Data;
	/**
	 * @param mixed $Data
	 */
	public function setData($Data)
	{
		$this->Data = $Data;
	}
	private $adminMode=true;
    public function getAdminMode()
    {
        return $this->adminMode;
    }
        /**
     * @param bool $adminMode
     */
    public function setAdminMode($adminMode)
    {
        $this->adminMode = $adminMode;
    }
	/** @var textbox */
	private $title;
	/**
	 * @return textbox
	 */
	public function getTitle()
	{
		return $this->title;
	}
	/** @var textbox */
	private $minutes;
	/**
	 * @return textbox
	 */
	public function getMinutes()
	{
		return $this->minutes;
	}
	/** @var combobox */
	private $sortby;
	/**
	 * @return combobox
	 */
	public function getSortby()
	{
		return $this->sortby;
	}
	/** @var combobox */
	private $isdesc;
	/**
	 * @return combobox
	 */
	public function getIsdesc()
	{
		return $this->isdesc;
	}
	/** @var SweetButton */
	private $search;
	public function getBodyHTML($command=null)
	{
		$this->FillItems();
		$Page=new Div();
		$Page->setClass("sweet_formtitle");
		$Page->setId("iribfinance_workunitlist");
		$Page->addElement($this->getPageTitlePart("فهرست " . $this->Data['workunit']->getTableTitle() . " ها"));
		$LTable1=new Div();
		$LTable1->setClass("searchtable");
		$LTable1->addElement($this->getFieldRowCode($this->title,$this->getFieldCaption('title'),null,'',null));
		$LTable1->addElement($this->getFieldRowCode($this->minutes,$this->getFieldCaption('minutes'),null,'',null));
		$LTable1->addElement($this->getFieldRowCode($this->sortby,$this->getFieldCaption('sortby'),null,'',null));
		$LTable1->addElement($this->getFieldRowCode($this->isdesc,$this->getFieldCaption('isdesc'),null,'',null));
		$LTable1->addElement($this->getSingleFieldRowCode($this->search));
		$Page->addElement($LTable1);
		if($this->getMessage()!="")
			$Page->addElement($this->getMessagePart());
		$Div1=new Div();
		$Div1->setClass("list");
		for($i=0;$i<count($this->Data['data']);$i++){
		$innerDiv[$i]=new Div();
		$innerDiv[$i]->setClass("listitem");
			$url=new AppRooter('iribfinance','workunit');
			$url->addParameter(new UrlParameter('id',$this->Data['data'][$i]->getID()));
			$Title=$this->Data['data'][$i]->getTitleField();
			if($this->Data['data'][$i]->getTitleField()=="")
				$Title='-- بدون عنوان --';
			$lbTit[$i]=new Lable($Title);
			$liTit[$i]=new link($url->getAbsoluteURL(),$lbTit[$i]);
			$innerDiv[$i]->addElement($liTit[$i]);
			$Div1->addElement($innerDiv[$i]);
		}
		$Page->addElement($Div1);
		$Page->addElement($this->getPaginationPart($this->Data['pagecount'],"iribfinance","workunitlist"));
		$PageLink=new AppRooter('iribfinance','workunitlist');
		$form=new SweetFrom($PageLink->getAbsoluteURL(), "GET", $Page);
		$form->setClass('form-horizontal');
		return $form->getHTML();
	}
	public function getJSON()
	{
		parent::getJSON();
		if (key_exists("data", $this->Data)){
			$AllCount1 = count($this->Data['data']);
			$Result=array();
			for($i=0;$i<$AllCount1;$i++){
				$Result[$i]=$this->Data['data'][$i]->GetArray();
			}
			return json_encode($Result);
		}
		return json_encode(array());
	}
	public function FillItems()
	{
		if (key_exists("workunit", $this->Data)){

			/******** title ********/
			$this->title->setValue($this->Data['workunit']->getTitle());
			$this->setFieldCaption('title',$this->Data['workunit']->getFieldInfo('title')->getTitle());

			/******** minutes ********/
			$this->minutes->setValue($this->Data['workunit']->getMinutes());
			$this->setFieldCaption('minutes',$this->Data['workunit']->getFieldInfo('minutes')->getTitle());

			/******** sortby ********/

			/******** isdesc ********/

			/******** search ********/
		}
			$this->isdesc->addOption('0','صعودی');
			$this->isdesc->addOption('1','نزولی');

		/******** title ********/
		$this->sortby->addOption($this->Data['workunit']->getTableFieldID('title'),$this->getFieldCaption('title'));
		if(isset($_GET['title']))
			$this->title->setValue($_GET['title']);

		/******** minutes ********/
		$this->sortby->addOption($this->Data['workunit']->getTableFieldID('minutes'),$this->getFieldCaption('minutes'));
		if(isset($_GET['minutes']))
			$this->minutes->setValue($_GET['minutes']);

		/******** sortby ********/
		if(isset($_GET['sortby']))
			$this->sortby->setSelectedValue($_GET['sortby']);

		/******** isdesc ********/
		if(isset($_GET['isdesc']))
			$this->isdesc->setSelectedValue($_GET['isdesc']);

		/******** search ********/
	}
	public function __construct()
	{
		parent::__construct();

		/******* title *******/
		$this->title= new textbox("title");
		$this->title->setClass("form-control");

		/******* minutes *******/
		$this->minutes= new textbox("minutes");
		$this->minutes->setClass("form-control");

		/******* sortby *******/
		$this->sortby= new combobox("sortby");
		$this->sortby->setClass("form-control");

		/******* isdesc *******/
		$this->isdesc= new combobox("isdesc");
		$this->isdesc->setClass("form-control");

		/******* search *******/
		$this->search= new SweetButton(true,"جستجو");
		$this->search->setAction("search");
		$this->search->setDisplayMode(Button::$DISPLAYMODE_BUTTON);
		$this->search->setClass("btn btn-primary");
	}
}
?>