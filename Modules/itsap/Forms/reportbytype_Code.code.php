<?php
namespace Modules\itsap\Forms;
/**
*@author Hadi AmirNahavandi
*@creationDate 1396-09-29 - 2017-12-20 15:49
*@lastUpdate 1396-09-29 - 2017-12-20 15:49
*@SweetFrameworkHelperVersion 2.004
*@SweetFrameworkVersion 2.004
*/
class reportbytype_Code extends manageservicerequests_Code {
	public function __construct($namespace)
	{
		parent::__construct($namespace);
		$this->setTitle("جستجو بر اساس نوع خدمت");
		$this->setVisibleFields(['servicetype_fid','servicetypegroup']);
		$this->setIsSearchForm(true);
	}
}
?>