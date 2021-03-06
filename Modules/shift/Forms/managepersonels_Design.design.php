<?php
namespace Modules\shift\Forms;
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
*@creationDate 1396-10-28 - 2018-01-18 17:32
*@lastUpdate 1396-10-28 - 2018-01-18 17:32
*@SweetFrameworkHelperVersion 2.004
*@SweetFrameworkVersion 2.004
*/
class managepersonels_Design extends FormDesign {
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
        private $listPage;
    private $itemPage;
    private $itemViewPage;
    /**
     * @param bool $adminMode
     */
    public function setAdminMode($adminMode)
    {
        $this->adminMode = $adminMode;
        $this->itemViewPage = 'personel';
        if($adminMode==true)
        {
            $this->itemPage = 'managepersonel';
            $this->listPage = 'managepersonels';
        }
        else
        {
            $this->itemPage = 'manageuserpersonel';
            $this->listPage = 'manageuserpersonels';
        }
    }
	public function __construct()
	{
		parent::__construct();
	}
	public function getBodyHTML($command=null)
	{

		$Page=new Div();
		$Page->setClass("sweet_formtitle");
		$Page->setId("shift_managepersonels");
		$Page->addElement($this->getPageTitlePart(" " . $this->Data['personel']->getTableTitle() . " ها"));


        if($this->getMessage()!="")
            $Page->addElement($this->getMessagePart());

		$IncreaseYearsURL=new AppRooter('shift',$this->listPage);
        $IncreaseYearsURL->addParameter(new UrlParameter('increaseyears','1'));
        $LblIncreaseYears=new Lable('افزایش سابقه کارکنان');
        $lnkIncreaseYears=new link($IncreaseYearsURL->getAbsoluteURL(),$LblIncreaseYears);
        $lnkIncreaseYears->setClass('linkbutton btn btn-danger');
        $lnkIncreaseYears->setGlyphiconClass('glyphicon glyphicon-calendar');
        $Page->addElement($lnkIncreaseYears);

		$addUrl=new AppRooter('shift',$this->itemPage);
		$LblAdd=new Lable('تعریف شخص جدید');
		$lnkAdd=new link($addUrl->getAbsoluteURL(),$LblAdd);
		$lnkAdd->setClass('linkbutton btn btn-primary');
		$lnkAdd->setGlyphiconClass('glyphicon glyphicon-plus');
		$lnkAdd->setId('addpersonellink');
		$Page->addElement($lnkAdd);
		$SearchUrl=new AppRooter('shift',$this->listPage);
		$SearchUrl->addParameter(new URLParameter('search',null));
		$LblSearch=new Lable('جستجو');
		$lnkSearch=new link($SearchUrl->getAbsoluteURL(),$LblSearch);
		$lnkSearch->setClass('linkbutton btn btn-primary');
		$lnkSearch->setGlyphiconClass('glyphicon glyphicon-search');
		$lnkSearch->setId('searchpersonellink');
		$Page->addElement($lnkSearch);
		if($this->getMessage()!="")
			$Page->addElement($this->getMessagePart());
		$TableDiv=new Div();
		$TableDiv->setClass('table-responsive');
		$LTable1=new ListTable(4);
		$LTable1->setHeaderRowCount(1);
		$LTable1->setClass("table-striped table-hover managelist");
		$LTable1->addElement(new Lable('#'));
		$LTable1->setLastElementClass("listtitle");
		$LTable1->addElement(new Lable('نام'));
		$LTable1->setLastElementClass("listtitle");
        $LTable1->addElement(new Lable('کد ملی'));
        $LTable1->setLastElementClass("listtitle");
//        $LTable1->addElement(new Lable('بخش'));
//        $LTable1->setLastElementClass("listtitle");
		$LTable1->addElement(new Lable('عملیات'));
		$LTable1->setLastElementClass("listtitle");
		for($i=0;$i<count($this->Data['data']);$i++){
			$url=new AppRooter('shift',$this->itemPage);
			$url->addParameter(new UrlParameter('id',$this->Data['data'][$i]->getID()));
			$Title=$this->Data['data'][$i]->getName() . " " . $this->Data['data'][$i]->getFamily();
			if($Title=="")
				$Title='- بدون نام -';
			$lbTit[$i]=new Lable($Title);
			$liTit[$i]=new link($url->getAbsoluteURL(),$lbTit[$i]);
			$ViewURL=new AppRooter('shift',$this->itemViewPage);
			$ViewURL->addParameter(new UrlParameter('id',$this->Data['data'][$i]->getID()));
			$lbView[$i]=new Lable('اصلاح');
			$lnkView[$i]=new link($url->getAbsoluteURL(),$lbView[$i]);
			$lnkView[$i]->setGlyphiconClass('glyphicon glyphicon-pencil');
			$lnkView[$i]->setClass('btn btn-primary');
			$delurl=new AppRooter('shift',$this->listPage);
			$delurl->addParameter(new UrlParameter('id',$this->Data['data'][$i]->getID()));
			$delurl->addParameter(new UrlParameter('delete',1));
			$lbDel[$i]=new Lable('حذف');
			$lnkDel[$i]=new link($delurl->getAbsoluteURL(),$lbDel[$i]);
			$lnkDel[$i]->setGlyphiconClass('glyphicon glyphicon-remove');
			$lnkDel[$i]->setClass('btn btn-danger');
			$operationDiv[$i]=new Div();
			$operationDiv[$i]->setClass('operationspart');
			$operationDiv[$i]->addElement($lnkView[$i]);
			$operationDiv[$i]->addElement($lnkDel[$i]);
			$LTable1->addElement(new Lable($i+1));
			$LTable1->setLastElementClass("listcontent");
			$LTable1->addElement($liTit[$i]);
			$LTable1->setLastElementClass("listcontent");
            $LTable1->addElement(new Lable($this->Data['data'][$i]->getMelliCode()));
            $LTable1->setLastElementClass("listcontent");
			$LTable1->addElement($operationDiv[$i]);
			$LTable1->setLastElementClass("listcontent");
		}
		$TableDiv->addElement($LTable1);
		$Page->addElement($TableDiv);
		$Page->addElement($this->getPaginationPart($this->Data['pagecount'],"shift",$this->listPage,null,true));
		$form=new SweetFrom("", "POST", $Page);
		return $form->getHTML();
	}    
    public function getJSON()
    {
       parent::getJSON();
       $Result=['message'=>$this->getMessage(),'messagetype'=>$this->getMessageType()];
       return json_encode($Result);
    }
}
?>