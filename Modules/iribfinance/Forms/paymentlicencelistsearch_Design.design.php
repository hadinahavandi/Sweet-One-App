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
*@creationDate 1396-11-05 - 2018-01-25 18:15
*@lastUpdate 1396-11-05 - 2018-01-25 18:15
*@SweetFrameworkHelperVersion 2.004
*@SweetFrameworkVersion 2.004
*/
class paymentlicencelistsearch_Design extends FormDesign {
	private $Data;
	/**
	 * @param mixed $Data
	 */
	public function setData($Data)
	{
		$this->Data = $Data;
	}
	/** @var combobox */
	private $programestimationemployee_fid;
	/**
	 * @return combobox
	 */
	public function getProgramestimationemployee_fid()
	{
		return $this->programestimationemployee_fid;
	}
	/** @var textbox */
	private $month;
	/**
	 * @return textbox
	 */
	public function getMonth()
	{
		return $this->month;
	}
	/** @var DatePicker */
	private $pay_date_from;
	/**
	 * @return DatePicker
	 */
	public function getPay_date_from()
	{
		return $this->pay_date_from;
	}
	/** @var DatePicker */
	private $pay_date_to;
	/**
	 * @return DatePicker
	 */
	public function getPay_date_to()
	{
		return $this->pay_date_to;
	}
	/** @var textbox */
	private $work;
	/**
	 * @return textbox
	 */
	public function getWork()
	{
		return $this->work;
	}
	/** @var textbox */
	private $decrementtime;
	/**
	 * @return textbox
	 */
	public function getDecrementtime()
	{
		return $this->decrementtime;
	}
	/** @var textbox */
	private $workfactor;
	/**
	 * @return textbox
	 */
	public function getWorkfactor()
	{
		return $this->workfactor;
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
	public function __construct()
	{
		parent::__construct();

		/******* programestimationemployee_fid *******/
		$this->programestimationemployee_fid= new combobox("programestimationemployee_fid");
		$this->programestimationemployee_fid->setClass("form-control");

		/******* month *******/
		$this->month= new textbox("month");
		$this->month->setClass("form-control");

		/******* pay_date_from *******/
		$this->pay_date_from= new DatePicker("pay_date_from");
		$this->pay_date_from->setClass("form-control");

		/******* pay_date_to *******/
		$this->pay_date_to= new DatePicker("pay_date_to");
		$this->pay_date_to->setClass("form-control");

		/******* work *******/
		$this->work= new textbox("work");
		$this->work->setClass("form-control");

		/******* decrementtime *******/
		$this->decrementtime= new textbox("decrementtime");
		$this->decrementtime->setClass("form-control");

		/******* workfactor *******/
		$this->workfactor= new textbox("workfactor");
		$this->workfactor->setClass("form-control");

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
	public function getBodyHTML($command=null)
	{
		$this->FillItems();
		$Page=new Div();
		$Page->setClass("sweet_formtitle");
		$Page->setId("iribfinance_paymentlicencelist");
		$Page->addElement($this->getPageTitlePart("جستجوی " . $this->Data['paymentlicence']->getTableTitle() . ""));
		if($this->getMessage()!="")
			$Page->addElement($this->getMessagePart());
		$LTable1=new Div();
		$LTable1->setClass("searchtable");
		$LTable1->addElement($this->getFieldRowCode($this->programestimationemployee_fid,$this->getFieldCaption('programestimationemployee_fid'),null,'',null));
		$LTable1->addElement($this->getFieldRowCode($this->month,$this->getFieldCaption('month'),null,'',null));
		$LTable1->addElement($this->getFieldRowCode($this->pay_date_from,$this->getFieldCaption('pay_date_from'),null,'',null));
		$LTable1->addElement($this->getFieldRowCode($this->pay_date_to,$this->getFieldCaption('pay_date_to'),null,'',null));
		$LTable1->addElement($this->getFieldRowCode($this->work,$this->getFieldCaption('work'),null,'',null));
		$LTable1->addElement($this->getFieldRowCode($this->decrementtime,$this->getFieldCaption('decrementtime'),null,'',null));
		$LTable1->addElement($this->getFieldRowCode($this->workfactor,$this->getFieldCaption('workfactor'),null,'',null));
		$LTable1->addElement($this->getFieldRowCode($this->sortby,$this->getFieldCaption('sortby'),null,'',null));
		$LTable1->addElement($this->getFieldRowCode($this->isdesc,$this->getFieldCaption('isdesc'),null,'',null));
		$LTable1->addElement($this->getSingleFieldRowCode($this->search));
		$Page->addElement($LTable1);
		$form=new SweetFrom("", "GET", $Page);
		$form->setClass('form-horizontal');
		return $form->getHTML();
	}
	public function FillItems()
	{
			$this->programestimationemployee_fid->addOption("", "مهم نیست");
		foreach ($this->Data['programestimationemployee_fid'] as $item)
			$this->programestimationemployee_fid->addOption($item->getID(), $item->getTitleField());
		if (key_exists("paymentlicence", $this->Data)){

			/******** programestimationemployee_fid ********/
			$this->programestimationemployee_fid->setSelectedValue($this->Data['paymentlicence']->getProgramestimationemployee_fid());
			$this->setFieldCaption('programestimationemployee_fid',$this->Data['paymentlicence']->getFieldInfo('programestimationemployee_fid')->getTitle());

			/******** month ********/
			$this->month->setValue($this->Data['paymentlicence']->getMonth());
			$this->setFieldCaption('month',$this->Data['paymentlicence']->getFieldInfo('month')->getTitle());

			/******** pay_date_from ********/
			$this->pay_date_from->setTime($this->Data['paymentlicence']->getPay_date_from());
			$this->setFieldCaption('pay_date_from',$this->Data['paymentlicence']->getFieldInfo('pay_date_from')->getTitle());

			/******** pay_date_to ********/
			$this->pay_date_to->setTime($this->Data['paymentlicence']->getPay_date_to());
			$this->setFieldCaption('pay_date_to',$this->Data['paymentlicence']->getFieldInfo('pay_date_to')->getTitle());
			$this->setFieldCaption('pay_date',$this->Data['paymentlicence']->getFieldInfo('pay_date')->getTitle());

			/******** work ********/
			$this->work->setValue($this->Data['paymentlicence']->getWork());
			$this->setFieldCaption('work',$this->Data['paymentlicence']->getFieldInfo('work')->getTitle());

			/******** decrementtime ********/
			$this->decrementtime->setValue($this->Data['paymentlicence']->getDecrementtime());
			$this->setFieldCaption('decrementtime',$this->Data['paymentlicence']->getFieldInfo('decrementtime')->getTitle());

			/******** workfactor ********/
			$this->workfactor->setValue($this->Data['paymentlicence']->getWorkfactor());
			$this->setFieldCaption('workfactor',$this->Data['paymentlicence']->getFieldInfo('workfactor')->getTitle());

			/******** sortby ********/

			/******** isdesc ********/

			/******** search ********/
		}
			$this->isdesc->addOption('0','صعودی');
			$this->isdesc->addOption('1','نزولی');

		/******** programestimationemployee_fid ********/
		$this->sortby->addOption($this->Data['paymentlicence']->getTableFieldID('programestimationemployee_fid'),$this->getFieldCaption('programestimationemployee_fid'));
		if(isset($_GET['programestimationemployee_fid']))
			$this->programestimationemployee_fid->setSelectedValue($_GET['programestimationemployee_fid']);

		/******** month ********/
		$this->sortby->addOption($this->Data['paymentlicence']->getTableFieldID('month'),$this->getFieldCaption('month'));
		if(isset($_GET['month']))
			$this->month->setValue($_GET['month']);

		/******** pay_date_from ********/

		/******** pay_date_to ********/
		$this->sortby->addOption($this->Data['paymentlicence']->getTableFieldID('pay_date'),$this->getFieldCaption('pay_date'));

		/******** work ********/
		$this->sortby->addOption($this->Data['paymentlicence']->getTableFieldID('work'),$this->getFieldCaption('work'));
		if(isset($_GET['work']))
			$this->work->setValue($_GET['work']);

		/******** decrementtime ********/
		$this->sortby->addOption($this->Data['paymentlicence']->getTableFieldID('decrementtime'),$this->getFieldCaption('decrementtime'));
		if(isset($_GET['decrementtime']))
			$this->decrementtime->setValue($_GET['decrementtime']);

		/******** workfactor ********/
		$this->sortby->addOption($this->Data['paymentlicence']->getTableFieldID('workfactor'),$this->getFieldCaption('workfactor'));
		if(isset($_GET['workfactor']))
			$this->workfactor->setValue($_GET['workfactor']);

		/******** sortby ********/
		if(isset($_GET['sortby']))
			$this->sortby->setSelectedValue($_GET['sortby']);

		/******** isdesc ********/
		if(isset($_GET['isdesc']))
			$this->isdesc->setSelectedValue($_GET['isdesc']);

		/******** search ********/
	}
}
?>