<?php

namespace Modules\sfman\Controllers;
use core\CoreClasses\services\Controller;
use core\CoreClasses\db\dbaccess;
use core\CoreClasses\SweetDate;
use Modules\common\PublicClasses\AppDate;
use Modules\languages\PublicClasses\CurrentLanguageManager;
use Modules\sfman\Entity\sfman_formelementEntity;
use Modules\sfman\Entity\sfman_formelementtypeEntity;
use Modules\sfman\Entity\sfman_formEntity;
use Modules\sfman\Entity\sfman_moduleEntity;
use Modules\sfman\Entity\sfman_tableEntity;


/**
 *@author Hadi AmirNahavandi
 *@creationDate 1395/10/9 - 2016/12/29 19:36:38
 *@lastUpdate 1395/10/9 - 2016/12/29 19:36:38
 *@SweetFrameworkHelperVersion 1.112
*/

abstract class manageDBControllerFormController extends manageDBFormController {

    protected function getEntityObjectFieldSetCode($ObjectName,$EntityClassName,$isInsert)
    {
        $InsertCode = "";
        for($i=0; $i<count($this->getCurrentTableFields()); $i++)
        {
            if(FieldType::getFieldType($this->getCurrentTableFields()[$i])!=FieldType::$METAINF && FieldType::getFieldType($this->getCurrentTableFields()[$i])!=FieldType::$ID){
                $UCField=$this->getCurrentTableFields()[$i];

                if($isInsert || trim(strtolower($UCField))!="role_systemuser_fid")
                {
                    $FieldValueVariable=$UCField;
                    if(FieldType::getFieldType($this->getCurrentTableFields()[$i])==FieldType::$FILE)
                    {
                        $FieldValueVariable .="URL";
                        $InsertCode .= "\n\t\t\tif(\$$FieldValueVariable!='')";
                    }
                    $InsertCode .= "\n\t\t\t$ObjectName" . "->set" . ucwords($UCField) . "(\$$FieldValueVariable);";
                }
            }
        }
        return $InsertCode;
    }

    protected function getEntityObjectFieldValidateCode($ObjectName,$EntityClassName)
    {
        $ValidateCode = "";
        $FieldsCode="";
        $FieldIndex=0;
        for($i=0; $i<count($this->getCurrentTableFields()); $i++)
        {
            $UCField=$this->getCurrentTableFields()[$i];
                    if(FieldType::getFieldType($this->getCurrentTableFields()[$i])==FieldType::$FILE) {
                        $ValidateCode .="\r\n\t\t\$" . $UCField . "URL='';";
                        $ValidateCode .="\r\n\t\tif(\$" . $UCField ."!=null && count(\$" . $UCField .")>0)";
                        $ValidateCode .="\r\n\t\t\t\$" . $this->getCurrentTableFields()[$i] . "URL=\$" . $UCField ."[0]['url'];";
                    }
        }
        $ValidateCode .= "\n\t\t\$this->ValidateFieldArray([";
        for($i=0; $i<count($this->getCurrentTableFields()); $i++)
        {
            if(FieldType::getFieldType($this->getCurrentTableFields()[$i])!=FieldType::$METAINF && FieldType::getFieldType($this->getCurrentTableFields()[$i])!=FieldType::$ID){
                $UCField=$this->getCurrentTableFields()[$i];

                if(trim(strtolower($UCField))!="role_systemuser_fid")
                {
                    if($FieldIndex>0)
                    {
                        $FieldsCode.=",";
                        $ValidateCode.=",";
                    }
                    $ValidateCode .= "\$$UCField";
                    $FieldsCode .= $ObjectName . "->getFieldInfo(";
                    $FieldsCode .= "$EntityClassName" . "::\$" . strtoupper($UCField);
                    if(FieldType::getFieldType($this->getCurrentTableFields()[$i])==FieldType::$FILE)
                        $ValidateCode .="URL";
                    $FieldsCode.=")";
                    $FieldIndex++;
                }

            }

        }
        $ValidateCode.="],[" . $FieldsCode . "]);";
        return $ValidateCode;
    }

    protected function getTableItemControllerTopCode($formInfo,$isManager)
    {
        $formName=$formInfo['form']['name'];
        $moduleName=$this->getCodeModuleName();
        $EntityNames=array();

        $EntityClassName=$moduleName . "_" . $this->getTableName() . "Entity";;
        $C = "<?php";
        $C .= $this->getControllerNamespaceDefiner();
        $C .= $this->getControllerUsage();
        $C .= "\nuse Modules\\$moduleName\\Entity\\$EntityClassName;";
        for($i=0; $i<count($this->getCurrentTableFields()); $i++) {
            $fl1=$this->getFieldName($i);
            if($fl1!=null && array_search($fl1,$EntityNames)==null) {
                $fl = $moduleName . "_" . $fl1 . "Entity";
                $C .= "\nuse Modules\\$moduleName\\Entity\\$fl;";
            }
            $EntityNames[$i]=$fl1;
        }
        $C.=$this->getFileInfoComment();
        $C .= "\nclass $formName" . "Controller extends Controller {";
        if($isManager)
            $C.=$this->getIsAdminModeDefine(true);
        $C .= "\n\tpublic function load(\$ID)";
        $C .= "\n\t{";
        $C .= $this->getControllerActionInits($isManager);
        $ObjectName="\$" . $this->getTableName() . "EntityObject";
        $C .= "\n\t\t$ObjectName=new $EntityClassName(\$DBAccessor);";
        return $C;
    }
    protected function getTableItemControllerLoadCode($formInfo,$isManager)
    {
        $ObjectName="\$" . $this->getTableName() . "EntityObject";
        $C = "\n\t\t\$result['".$this->getTableName()."']=$ObjectName;";
        $C .= "\n\t\tif(\$ID!=-1){";
        $C .= "\n\t\t\t$ObjectName" . "->setId(\$ID);";
        $C .= "\n\t\t\t" . "if($ObjectName" . "->getId()==-1)";
        $C .= "\n\t\t\t\tthrow new DataNotFoundException();";
        if($isManager)
        {
            $C .="\n\t\t\tif(\$UserID!=null && $ObjectName" . "->getRole_systemuser_fid()!=\$UserID)";
            $C .= "\n\t\t\t\tthrow new DataNotFoundException();";
        }
        $C .= "\n\t\t\t\$result['".$this->getTableName()."']=$ObjectName;";

        return $C;
    }
    protected function makeTableItemController($formInfo)
    {
        $moduleName=$this->getCodeModuleName();
        $C =$this->getTableItemControllerTopCode($formInfo,false);
        $C .= $this->getTableItemControllerLoadCode($formInfo,false);
        for($i=0; $i<count($this->getCurrentTableFields()); $i++) {
            $fl1=$this->getFieldName($i);
            if($fl1!=null && FieldType::getFieldType($this->getCurrentTableFields()[$i])==FieldType::$FID) {
                $fl = $moduleName . "_" . $fl1 . "Entity";
                $FiledName=substr($this->getCurrentTableFields()[$i],0,strlen($this->getCurrentTableFields()[$i])-4);
                $ObjectName2="\$" . $FiledName . "EntityObject";
                $C .= "\n\t\t\t$ObjectName2=new " .  $fl . "(\$DBAccessor);";
                $C .= "\n\t\t\t$ObjectName2" . "->SetId(\$result['".$this->getTableName()."']->get".ucwords($this->getCurrentTableFields()[$i])."());";
                $C .= "\n\t\t\tif($ObjectName2" . "->getId()==-1)";
                $C .= "\n\t\t\t\tthrow new DataNotFoundException();";
                $C .= "\n\t\t\t\$result['" . $this->getCurrentTableFields()[$i] . "']=$ObjectName2;";
            }
        }
        $C .="\n\t\t}";


        $C .= "\n\t\t\$result['param1']=\"\";";
        $C .= "\n\t\t\$DBAccessor->close_connection();";
        $C .= "\n\t\treturn \$result;";
        $C .= "\n\t}";
        $C .= "\n}";
        $C .= "\n?>";
        $this->SaveFile($this->getControllerFile(), $C);

    }
    protected function makeTableItemManageController($formInfo)
    {
        $moduleName=$this->getCodeModuleName();
        $C =$this->getTableItemControllerTopCode($formInfo,true);
        for($i=0; $i<count($this->getCurrentTableFields()); $i++) {
            $fl1=$this->getFieldName($i);
            if($fl1!=null &&  FieldType::getFieldType($this->getCurrentTableFields()[$i])==FieldType::$FID) {
                $fl = $moduleName . "_" . $fl1 . "Entity";
                $FiledName=substr($this->getCurrentTableFields()[$i],0,strlen($this->getCurrentTableFields()[$i])-4);
                $ObjectName2="\$" . $FiledName . "EntityObject";
                $C .= "\n\t\t\t$ObjectName2=new " .  $fl . "(\$DBAccessor);";
                $C .= "\n\t\t\t\$result['" . $this->getCurrentTableFields()[$i] . "']=$ObjectName2" . "->FindAll(new QueryLogic());";
            }
        }
        $C .= $this->getTableItemControllerLoadCode($formInfo,true);
        $C .="\n\t\t}";
        $C .= "\n\t\t\$result['param1']=\"\";";
        $C .= "\n\t\t\$DBAccessor->close_connection();";
        $C .= "\n\t\treturn \$result;";
        $C .= "\n\t}";

        for($i=0;$i<count($formInfo['elements']);$i++)
            if($formInfo['elements'][$i]['type_fid']==7)
                $C.=$this->getActionFormController($formInfo,$formInfo['elements'][$i]['name'],true);
        $C .= "\n}";
        $C .= "\n?>";
        $this->SaveFile($this->getControllerFile(), $C);

    }
	protected function makeTableManageListController($formInfo)
	{
        $formName=$formInfo['form']['name'];
        $ListName=$formInfo['form']['listname'];
        $moduleName=$formInfo['module']['name'];
        $TableName=$this->getTableName();
        $C = $this->getTableListControllerTopCode($formInfo);
        $EntityClassName=$moduleName . "_" . $TableName . "Entity";
        $C .= "\nclass $formName" . "Controller extends $ListName" . "Controller {";
        $C .= "\n\tprivate \$PAGESIZE=10;";
		$C .= "\n\tpublic function DeleteItem(\$ID)";
        $C .= "\n\t{";
        $C .= "\n\t\t\$Language_fid=CurrentLanguageManager::getCurrentLanguageID();";
        $C .= "\n\t\t\$DBAccessor=new dbaccess();";
        $C.=<<<EOT
\n\t\t\$su=new sessionuser();
        \$role_systemuser_fid=\$su->getSystemUserID();
        \$UserID=null;
        if(!\$this->getAdminMode())
            \$UserID=\$role_systemuser_fid;
EOT;

        $C .= "\n\t\t\$$TableName" . "Ent=new $EntityClassName(\$DBAccessor);";
        $C .= "\n\t\t\$$TableName" . "Ent->setId(\$ID);";
        $C .= "\n\t\tif(\$$TableName" . "Ent->getId()==-1)";
        $C .= "\n\t\t\tthrow new DataNotFoundException();";

        $C .= "\n\t\tif(\$UserID!=null && \$$TableName" . "Ent->getRole_systemuser_fid()!=\$UserID)";
        $C .= "\n\t\t\tthrow new DataNotFoundException();";
        $C .= "\n\t\t\$$TableName" . "Ent->Remove();";
        $C .= "\n\t\t\$DBAccessor->close_connection();";
        $C .= "\n\t\treturn \$this->load(-1);";
        $C .="\n\t}";
		$C .= "\n}";
		$C .= "\n?>";
        $this->SaveFile($this->getControllerFile(), $C);

	}
    protected function makeTableListController($formInfo)
    {
        $C = $this->getTableListControllerCode($formInfo,array("PageNum"),false);
        $C .= "\n}";
        $C .= "\n?>";
        $this->SaveFile($this->getControllerFile(), $C);

    }

    protected function getTableListControllerGetDataCode($formInfo,$EntityClassName,$isManager)
    {
        $moduleName=$formInfo['module']['name'];
        $TableName=$this->getTableName();
        $C = "\n\tpublic function getData(\$PageNum,QueryLogic \$QueryLogic)";
        $C .= "\n\t{";
        $C .= $this->getControllerActionInits($isManager);
        for($i=0; $i<count($this->getCurrentTableFields()); $i++) {
            $fl1=$this->getFieldName($i);
            if($fl1!=null &&  FieldType::getFieldType($this->getCurrentTableFields()[$i])==FieldType::$FID) {
                $fl = $moduleName . "_" . $fl1 . "Entity";
                $FiledName=substr($this->getCurrentTableFields()[$i],0,strlen($this->getCurrentTableFields()[$i])-4);
                $ObjectName2="\$" . $FiledName . "EntityObject";
                $C .= "\n\t\t$ObjectName2=new " .  $fl . "(\$DBAccessor);";
                $C .= "\n\t\t\$result['" . $this->getCurrentTableFields()[$i] . "']=$ObjectName2" . "->FindAll(new QueryLogic());";
            }
        }
        $C .= "\n\t\tif(\$PageNum<=0)";
        $C .= "\n\t\t\t\$PageNum=1;";
        $C .=<<<EOT
        \n\t\t\$UserID=null;
        if(!\$this->getAdminMode())
            \$UserID=\$role_systemuser_fid;
EOT;
        $C.=<<<EOT
\n\t\tif(\$UserID!=null)
            \$QueryLogic->addCondition(new FieldCondition($EntityClassName::\$ROLE_SYSTEMUSER_FID,\$UserID));
EOT;
        $C .= "\n\t\t\$$TableName" . "Ent=new $EntityClassName(\$DBAccessor);";
        $ObjectName="\$$TableName" . "Ent";
        $C .= "\n\t\t\$result['".$this->getTableName()."']=$ObjectName;";
        $C .= "\n\t\t\$allcount=\$$TableName" . "Ent" . "->FindAllCount(\$QueryLogic);";
        $C .= "\n\t\t\$result['pagecount']=\$this->getPageCount(\$allcount,\$this->PAGESIZE);";
        $C .= "\n\t\t\$QueryLogic->setLimit(\$this->getPageRowsLimit(\$PageNum,\$this->PAGESIZE));";
        $C .= "\n\t\t\$result['data']=\$$TableName" . "Ent" . "->FindAll(\$QueryLogic);";
        $C .= "\n\t\t\$DBAccessor->close_connection();";
        $C .= "\n\t\treturn \$result;";
        $C .= "\n\t}";
        return $C;
    }
    protected function getTableListControllerLoadCode($formInfo,$LoadParams,$MethodName,$EntityClassName,$QueryParams,$isManager)
    {
        $TableName=$this->getTableName();
        $C = "\n\tpublic function $MethodName(";
        for ($i=0;$i<count($LoadParams);$i++) {
            if($i>0)
                $C.=",";
            $C.="\$" . $LoadParams[$i];
        }
        $C .=")";
        $C .= "\n\t{";

        $C .= "\n\t\t\$DBAccessor=new dbaccess();";
        $C .= "\n\t\t\$$TableName" . "Ent=new $EntityClassName(\$DBAccessor);";
        $C .= "\n\t\t\$q=new QueryLogic();";
        if($QueryParams!=null)
            $C.=$QueryParams;
        $C .= "\n\t\t\$DBAccessor->close_connection();";
        $C .= "";
        $C .= "\n\t\treturn \$this->getData(\$PageNum,\$q);";
        $C .= "\n\t}";
        return $C;
    }
protected function getTableListControllerTopCode($formInfo)
{
    $moduleName=$formInfo['module']['name'];
    $TableName=$this->getTableName();
    $EntityClassName=null;
    $EntityNames=array();
    $C = "<?php";
    $C .= $this->getControllerNamespaceDefiner();
    $C .= $this->getControllerUsage();
    $EntityClassName=$moduleName . "_" . $TableName . "Entity";
    $C .= "\nuse Modules\\$moduleName\\Entity\\$EntityClassName;";
    for($i=0; $i<count($this->getCurrentTableFields()); $i++) {
        $fl1=$this->getFieldName($i);
        if($fl1!=null && array_search($fl1,$EntityNames)==null) {
            $fl = $moduleName . "_" . $fl1 . "Entity";
            $C .= "\nuse Modules\\$moduleName\\Entity\\$fl;";
        }
        $EntityNames[$i]=$fl1;
    }

    $C.=$this->getFileInfoComment();
    return $C;
}
    protected function getTableListControllerCode($formInfo,$LoadParams,$isManager)
    {
        $formName=$formInfo['form']['name'];
        $moduleName=$formInfo['module']['name'];
        $TableName=$this->getTableName();
        $EntityClassName=null;
        $C = $this->getTableListControllerTopCode($formInfo);
        $EntityClassName=$moduleName . "_" . $TableName . "Entity";
        $C .= "\nclass $formName" . "Controller extends Controller {";
        $C .= "\n\tprivate \$PAGESIZE=10;";
        $C .=$this->getTableListControllerGetDataCode($formInfo,$EntityClassName,$isManager);
        $Qparams="";
            $C.=$this->getIsAdminModeDefine(true);


        $Qparams.="\n\t\t\$q->addOrderBy(\"id\",true);";
        $C .= $this->getTableListControllerLoadCode($formInfo,$LoadParams,"load",$EntityClassName,$Qparams,true);

        $LoadParams2=$LoadParams;

        for($i=0;$i<count($formInfo['elements']);$i++) {
            $el=$formInfo['elements'][$i];
            if($el['type_fid']!="7" ) {
                if($el['name']!="sortby" && $el['name']!="isdesc")
                {

                    $Operator="LIKE";
                    $elName=$el['name'];
                    if(substr($el['name'],strlen($el['name'])-3,3)=="_to")
                    {
                        $elName=substr($el['name'],0,strlen($el['name'])-3);
                        $Operator="Smaller";
                    }
                    elseif(substr($el['name'],strlen($el['name'])-5,5)=="_from")
                    {

                        $elName=substr($el['name'],0,strlen($el['name'])-5);
                        $Operator="Bigger";
                    }

                    if($Operator=="LIKE")
                        $Qparams.="\n\t\t\$q->addCondition(new FieldCondition(\"".$elName."\",\"%\$" . $el['name'] . "%\",LogicalOperator::$Operator));";
                    else
                        $Qparams.="\n\t\t\$q->addCondition(new FieldCondition(\"".$elName."\",\$" . $el['name'] . ",LogicalOperator::$Operator));";
                }

                array_push($LoadParams2,$el['name']);
            }
        }

        $ObjectName="\$$TableName" . "Ent";
        $Qparams.="\n\t\t\$sortByField=$ObjectName" . "->getTableField(\$sortby);";
        $Qparams.="\n\t\tif(\$sortByField!=null)";
        $Qparams.="\n\t\t\t\$q->addOrderBy(\$sortByField,\$isdesc);";
        for($i=0;$i<count($formInfo['elements']);$i++) {
            $el=$formInfo['elements'][$i];
            if($el['type_fid']!="7") {
            }
        }
        $C .= $this->getTableListControllerLoadCode($formInfo,$LoadParams2,"Search",$EntityClassName,$Qparams,true);
        return $C;

    }
	protected function getActionFormController($formInfo,$ActionName,$isManager)
	{

        $EntityClassName=$this->getCodeModuleName() . "_" . $this->getTableName() . "Entity";;
        $ObjectName="\$" . $this->getTableName() . "EntityObject";
        $InsertCode=$this->getEntityObjectFieldSetCode($ObjectName,$EntityClassName,true);
        $InsertCode .= "\n\t\t\t$ObjectName" . "->Save();";
        $UpdateCode = "\n\t\t\t$ObjectName" . "->setId(\$ID);";
        $UpdateCode .= "\n\t\t\tif($ObjectName" . "->getId()==-1)";
        $UpdateCode .= "\n\t\t\t\tthrow new DataNotFoundException();";
        if($isManager)
        {
            $UpdateCode .="\n\t\t\tif(\$UserID!=null && $ObjectName" . "->getRole_systemuser_fid()!=\$UserID)";
            $UpdateCode .= "\n\t\t\t\tthrow new DataNotFoundException();";
        }
        $UpdateCode.=$this->getEntityObjectFieldSetCode($ObjectName,$EntityClassName,false);
        $UpdateCode .= "\n\t\t\t$ObjectName" . "->Save();";

		$Params="";
		for($i=0;$i<count($formInfo['elements']);$i++)
		{
			$E=$formInfo['elements'][$i];
			if($E['type_fid']==2 || $E['type_fid']==3 || $E['type_fid']==4 || $E['type_fid']==5 || $E['type_fid']==6 || $E['type_fid']==8 || $E['type_fid']==9)//TextBox or checkbox or ...
			{
				$ParamName="\$" . $E['name'];
				if($Params!="")
					$Params.=",";
				$Params.=$ParamName;
			}

		}
		$C  = "\n\tpublic function " . ucwords($ActionName) . "(\$ID,$Params)";
		$C .= "\n\t{";
		$C .= "\n\t\t\$Language_fid=CurrentLanguageManager::getCurrentLanguageID();";
		$C .= "\n\t\t\$DBAccessor=new dbaccess();";
        $C .= "\n\t\t\$su=new sessionuser();";
        $C .= "\n\t\t\$role_systemuser_fid=\$su->getSystemUserID();";
        if($isManager)
        {
            $C .=<<<EOT
        \n\t\t\$UserID=null;
        if(!\$this->getAdminMode())
            \$UserID=\$role_systemuser_fid;
EOT;
        }
		$C .= "\n\t\t\$result=array();";
        if($InsertCode!==null)
        {

            $C .= "\n\t\t$ObjectName=new $EntityClassName(\$DBAccessor);";
            $C .=$this->getEntityObjectFieldValidateCode($ObjectName,$EntityClassName);
        }
        $C .= "\n\t\tif(\$ID==-1){";
        if($InsertCode===null)
            $C .= "\n\t\t\t//INSERT NEW DATA";
        else
            $C .=$InsertCode;
        $C .= "\n\t\t}";
        $C .= "\n\t\telse{";

        if($UpdateCode===null)
            $C .= "\n\t\t\t//UPDATE DATA";
        else
            $C .=$UpdateCode;
        $C .= "\n\t\t}";

        $C .= "\n\t\t\$result=\$this->load(\$ID);";
		$C .= "\n\t\t\$result['param1']=\"\";";
		$C .= "\n\t\t\$DBAccessor->close_connection();";
		$C .= "\n\t\treturn \$result;";
		$C .= "\n\t}";
		return $C;
	}
}

?>
