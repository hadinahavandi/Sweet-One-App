<?php
namespace Modules\kpex\Entity;
use core\CoreClasses\services\EntityClass;
use core\CoreClasses\services\FieldInfo;
use core\CoreClasses\db\dbquery;
use core\CoreClasses\db\dbaccess;
use core\CoreClasses\services\FieldType;
/**
*@author Hadi AmirNahavandi
*@creationDate 1397-07-08 - 2018-09-30 19:29
*@lastUpdate 1397-07-08 - 2018-09-30 19:29
*@SweetFrameworkHelperVersion 2.014
*@SweetFrameworkVersion 1.018
*/

class kpex_testgroupEntity extends EntityClass {
	public function __construct(dbaccess $DBAccessor)
	{
		$this->setDatabase(new dbquery($DBAccessor));
		$this->setTableName("kpex_testgroup");
		$this->setTableTitle("kpex_testgroup");
		$this->setTitleFieldName("title");

		/******** created_at ********/
		$Created_atInfo=new FieldInfo();
		$Created_atInfo->setTitle("تاریخ ایجاد");
		$this->setFieldInfo(kpex_testgroupEntity::$CREATED_AT,$Created_atInfo);
		$this->addTableField('1',kpex_testgroupEntity::$CREATED_AT);

		/******** updated_at ********/
		$Updated_atInfo=new FieldInfo();
		$Updated_atInfo->setTitle("تاریخ بروزرسانی");
		$this->setFieldInfo(kpex_testgroupEntity::$UPDATED_AT,$Updated_atInfo);
		$this->addTableField('2',kpex_testgroupEntity::$UPDATED_AT);

		/******** title ********/
		$TitleInfo=new FieldInfo();
		$TitleInfo->setTitle("عنوان");
		$this->setFieldInfo(kpex_testgroupEntity::$TITLE,$TitleInfo);
		$this->addTableField('3',kpex_testgroupEntity::$TITLE);
	}
	public static $CREATED_AT="created_at";
	/**
	 * @return mixed
	 */
	public function getCreated_at(){
		return $this->getField(kpex_testgroupEntity::$CREATED_AT);
	}
	/**
	 * @param mixed $Created_at
	 */
	public function setCreated_at($Created_at){
		$this->setField(kpex_testgroupEntity::$CREATED_AT,$Created_at);
	}
	public static $UPDATED_AT="updated_at";
	/**
	 * @return mixed
	 */
	public function getUpdated_at(){
		return $this->getField(kpex_testgroupEntity::$UPDATED_AT);
	}
	/**
	 * @param mixed $Updated_at
	 */
	public function setUpdated_at($Updated_at){
		$this->setField(kpex_testgroupEntity::$UPDATED_AT,$Updated_at);
	}
	public static $TITLE="title";
	/**
	 * @return mixed
	 */
	public function getTitle(){
		return $this->getField(kpex_testgroupEntity::$TITLE);
	}
	/**
	 * @param mixed $Title
	 */
	public function setTitle($Title){
		$this->setField(kpex_testgroupEntity::$TITLE,$Title);
	}
}
?>