<?php

namespace Modules\sfman\Controllers;

use core\CoreClasses\html\CheckBox;
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
 * @author Hadi AmirNahavandi
 * @creationDate 1395/10/9 - 2016/12/29 19:36:38
 * @lastUpdate 1395/10/9 - 2016/12/29 19:36:38
 * @SweetFrameworkHelperVersion 1.112
 */
abstract class manageDBLaravelAPIFormController extends manageDBSenchaFormController
{

    private function _codeGeneratorTemplate($ModuleName,$FormName,$FieldName,$PureFieldName,$TranslatedFieldName){
        $AddGetterCodes = "";
        $AddFieldSetCodes = "";
        $UpdateGetterCodes = "";
        $UpdateFieldSetCodes = "";
        $ListQueryCodes = "";
        $ListFieldLoadCodes = "";
        $SingleLoadFieldLoadCodes = "";
        $ValidationRules = "";
        $FieldCode=new LaravelFieldCode($AddGetterCodes, $AddFieldSetCodes, $UpdateGetterCodes, $UpdateFieldSetCodes, $ListQueryCodes, $ListFieldLoadCodes, $SingleLoadFieldLoadCodes,$ValidationRules,FieldCode::$ADD_POLICY_TO_WITH_CURRENT);
        return $FieldCode;
    }
    private function _getGeneralFieldCodes($ModuleName,$FormName,$FieldName,$PureFieldName,$TranslatedFieldName){
        $UCFieldInput = "Input".ucfirst($PureFieldName);
        $UCFormName = ucfirst($FormName);

        $AddGetterCodes = "\n\t\t\$$UCFieldInput=\$request->input('$PureFieldName',' ');";
        $UpdateGetterCodes = "\n\t\t\$$UCFieldInput=\$request->get('$PureFieldName',' ');";
        $AddFieldSetCodes = "'$FieldName'=>\$$UCFieldInput";
        $UpdateFieldSetCodes = "\n        \$$UCFormName->$FieldName=\$$UCFieldInput;";
        $ListQueryCodes = "\n        \$$UCFormName"."Query =SweetQueryBuilder::WhereLikeIfNotNull(\$$UCFormName"."Query,'$FieldName',\$request->get('$PureFieldName'));";
        $ListQueryCodes.="\n        \$$UCFormName"."Query =SweetQueryBuilder::OrderIfNotNull(\$$UCFormName"."Query,'$PureFieldName"."__sort','$FieldName',\$request->get('$PureFieldName"."__sort'));";;
        $ListFieldLoadCodes = "";
        $SingleLoadFieldLoadCodes = "";
        $ValidationRules = "\n            '$PureFieldName' => 'required',";
        $ValidationMessages = "\n            '$PureFieldName".".required' => 'وارد کردن ".$TranslatedFieldName." اجباری می باشد',";
        $FieldCode=new LaravelFieldCode($AddGetterCodes, $AddFieldSetCodes, $UpdateGetterCodes, $UpdateFieldSetCodes, $ListQueryCodes, $ListFieldLoadCodes, $SingleLoadFieldLoadCodes,$ValidationRules,$ValidationMessages,FieldCode::$ADD_POLICY_TO_WITH_CURRENT);
        return $FieldCode;
    }


    private function _getDateFieldCode($ModuleName,$FormName,$FieldName,$PureFieldName,$TranslatedFieldName){
        $GFC=$this->_getGeneralFieldCodes($ModuleName,$FormName,$FieldName,$PureFieldName,$TranslatedFieldName);
        $UCFormName = ucfirst($FormName);
        $UCFieldInput = "Input".ucfirst($PureFieldName);
        $AddGetterCodes = "\n\t\t\$$UCFieldInput= SweetDateManager::getTimeStampFromString(\$request->input('$PureFieldName',' '));";
        $UpdateGetterCodes = "\n\t\t\$$UCFieldInput=SweetDateManager::getTimeStampFromString(\$request->get('$PureFieldName',' '));";
//        $AddGetterCodes = $GFC->getAddGetterCodes();
//        $UpdateGetterCodes =  $GFC->getUpdateGetterCodes();
        $AddFieldSetCodes = $GFC->getAddFieldSetCodes();
        $UpdateFieldSetCodes = $GFC->getUpdateFieldSetCodes();
        $ListQueryCodes =$GFC->getListQueryCodes();
        $ListFieldLoadCodes = "\n            \$$UCFormName" . "sArray[\$i]['$FieldName']=SweetDateManager::getStringFromTimeStamp(\$$UCFormName" . "sArray[\$i]['$FieldName']);";
        $SingleLoadFieldLoadCodes = "\n       \$$UCFormName"."ObjectAsArray['$FieldName']=SweetDateManager::getStringFromTimeStamp(\$$UCFormName"."ObjectAsArray['$FieldName']);";
        $ValidationRules = $GFC->getValidationRules();
        $ValidationMessages = $GFC->getValidationMessages();
        $FieldCode=new LaravelFieldCode($AddGetterCodes, $AddFieldSetCodes, $UpdateGetterCodes, $UpdateFieldSetCodes, $ListQueryCodes, $ListFieldLoadCodes, $SingleLoadFieldLoadCodes,$ValidationRules,$ValidationMessages,FieldCode::$ADD_POLICY_TO_WITH_CURRENT);
        return $FieldCode;
    }
    private function _getFileUploadFiledCodes($ModuleName,$FormName,$FieldName,$PureFieldName,$TranslatedFieldName){
        $GFC=$this->_getGeneralFieldCodes($ModuleName,$FormName,$FieldName,$PureFieldName,$TranslatedFieldName);
        $UCFieldInput = "Input".ucfirst($PureFieldName)."Path";
        $UCFormName = ucfirst($FormName);
        $AddGetterCodes = "\n\t\t\$$UCFieldInput=new SweetDBFile(SweetDBFile::\$GENERAL_DATA_TYPE_IMAGE,\$this->ModuleName,'$FormName','$PureFieldName',\$$UCFormName"."->id,'jpg');";
        $AddFieldSetCodes = "\n        \$$UCFormName->$FieldName=\$$UCFieldInput"."->uploadFromRequest(\$request->file('$PureFieldName'));";
        $UpdateGetterCodes = "\n\t\t\$$UCFieldInput=new SweetDBFile(SweetDBFile::\$GENERAL_DATA_TYPE_IMAGE,\$this->ModuleName,'$FormName','$PureFieldName',\$$UCFormName"."->id,'jpg');";
        $UpdateFieldSetCodes = "\n        if(\$$UCFieldInput!=null)
            \$$UCFormName->$FieldName=\$$UCFieldInput"."->uploadFromRequest(\$request->file('$PureFieldName'));";
        $ListQueryCodes = "";
        $ListFieldLoadCodes = $GFC->getListFieldLoadCodes();
        $SingleLoadFieldLoadCodes = $GFC->getSingleLoadFieldLoadCodes();
        $ValidationRules = "\n            '$PureFieldName' => 'required',";
        $ValidationMessages = $GFC->getValidationMessages();
        $FieldCode=new LaravelFieldCode($AddGetterCodes, $AddFieldSetCodes, $UpdateGetterCodes, $UpdateFieldSetCodes, $ListQueryCodes, $ListFieldLoadCodes, $SingleLoadFieldLoadCodes,$ValidationRules,$ValidationMessages,FieldCode::$ADD_POLICY_TO_AFTER_JOB);
        return $FieldCode;
    }

    private function _getNumberFieldCode($ModuleName,$FormName,$FieldName,$PureFieldName,$TranslatedFieldName){
        $GFC=$this->_getGeneralFieldCodes($ModuleName,$FormName,$FieldName,$PureFieldName,$TranslatedFieldName);
        $UCFieldInput = "Input".ucfirst($PureFieldName);
        $AddGetterCodes = "\n\t\t\$$UCFieldInput=\$request->input('$PureFieldName',0);";
        $UpdateGetterCodes = "\n\t\t\$$UCFieldInput=\$request->get('$PureFieldName',0);";
        $AddFieldSetCodes = $GFC->getAddFieldSetCodes();
//        $UpdateGetterCodes = $GFC->getUpdateGetterCodes();
        $UpdateFieldSetCodes = $GFC->getUpdateFieldSetCodes();
        $ListQueryCodes =$GFC->getListQueryCodes();
        $ListFieldLoadCodes =$GFC->getListFieldLoadCodes();
        $SingleLoadFieldLoadCodes = $GFC->getSingleLoadFieldLoadCodes();
        $ValidationRules = "\n            '$PureFieldName' => 'required|numeric',";
        $ValidationMessages = $GFC->getValidationMessages();
        $ValidationMessages .= "\n            '$PureFieldName".".numeric' => 'مقدار ".$TranslatedFieldName." باید عدد انگلیسی باشد.',";
        $FieldCode=new LaravelFieldCode($AddGetterCodes, $AddFieldSetCodes, $UpdateGetterCodes, $UpdateFieldSetCodes, $ListQueryCodes, $ListFieldLoadCodes, $SingleLoadFieldLoadCodes,$ValidationRules,$ValidationMessages,FieldCode::$ADD_POLICY_TO_WITH_CURRENT);
        return $FieldCode;
    }
    private function _getForeignIDFieldCode($ModuleName,$FormName,$FieldName,$PureFieldName,$TranslatedFieldName){
        $GFC=$this->_getGeneralFieldCodes($ModuleName,$FormName,$FieldName,$PureFieldName,$TranslatedFieldName);

        $UCFormName = ucfirst($FormName);
        $PureFieldNameUCField=ucfirst($PureFieldName)."Field";
        $PureFieldNameUC=ucfirst($PureFieldName);
        $FieldTableName=$this->getTableNameFromFIDFieldName($FieldName);
        $FieldModuleName=$this->getModuleNameFromFIDFieldName($FieldName,$ModuleName);
        $ModelName=$FieldModuleName."_".$FieldTableName;
        if($FieldModuleName==="")
            $ModelName=$FieldTableName;
        $UCFieldInput = "Input".ucfirst($PureFieldName);
        $AddGetterCodes = "\n\t\t\$$UCFieldInput=\$request->input('$PureFieldName',-1);";
        $UpdateGetterCodes = "\n\t\t\$$UCFieldInput=\$request->get('$PureFieldName',-1);";
        $AddFieldSetCodes = $GFC->getAddFieldSetCodes();
        $UpdateFieldSetCodes = $GFC->getUpdateFieldSetCodes();
        $ListQueryCodes =$GFC->getListQueryCodes();
        $ListFieldLoadCodes = "\n            \$$PureFieldNameUCField=\$$UCFormName" . "s[\$i]->$PureFieldName();";
        $ListFieldLoadCodes .= "\n            \$$UCFormName" . "sArray[\$i]['$PureFieldName"."content']=\$$PureFieldNameUCField==null?'':\$$PureFieldNameUCField"."->name;";
        $SingleLoadFieldLoadCodes = "
        \$$PureFieldNameUC"."Object=\$$UCFormName->$PureFieldName"."();
        \$$PureFieldNameUC"."Object=\$$PureFieldNameUC"."Object==null?'':\$$PureFieldNameUC"."Object;
        \$$UCFormName"."ObjectAsArray['$PureFieldName"."info']=\$this->getNormalizedItem(\$$PureFieldNameUC"."Object->toArray());";
        $ValidationRules = "\n            '$PureFieldName' => 'required|min:-1|integer',";
        $ValidationMessages = $GFC->getValidationMessages();
        $ValidationMessages .= "\n            '$PureFieldName".".integer' => 'مقدار ".$TranslatedFieldName." صحیح وارد نشده است.',";
        $FieldCode=new LaravelFieldCode($AddGetterCodes, $AddFieldSetCodes, $UpdateGetterCodes, $UpdateFieldSetCodes, $ListQueryCodes, $ListFieldLoadCodes, $SingleLoadFieldLoadCodes,$ValidationRules,$ValidationMessages,FieldCode::$ADD_POLICY_TO_WITH_CURRENT);
        return $FieldCode;
    }
    private function _getPlaceFieldCode($ModuleName,$FormName,$FieldName,$PureFieldName,$TranslatedFieldName){
        $FFC=$this->_getForeignIDFieldCode($ModuleName,$FormName,$FieldName,$PureFieldName,$TranslatedFieldName);
        $AddGetterCodes =$FFC->getAddGetterCodes();
        $AddFieldSetCodes = $FFC->getAddFieldSetCodes();
        $UpdateGetterCodes = $FFC->getUpdateGetterCodes();
        $UpdateFieldSetCodes = $FFC->getUpdateFieldSetCodes();
        $ListQueryCodes =$FFC->getListQueryCodes();
        $ListFieldLoadCodes =$FFC->getListFieldLoadCodes();
        $SingleLoadFieldLoadCodes=$FFC->getSingleLoadFieldLoadCodes();
        $PureFieldNameUC=ucfirst($PureFieldName);
        $UCFormName = ucfirst($FormName);
        $SingleLoadFieldLoadCodes .= "
        \$area= \$$PureFieldNameUC"."Object->area();
        \$city=\$area->city();
        \$province=\$city->province();
        \$$UCFormName"."ObjectAsArray['$PureFieldName"."info']['areainfo']=\$this->getNormalizedItem(\$area->toArray());
        \$$UCFormName"."ObjectAsArray['$PureFieldName"."info']['cityinfo']=\$this->getNormalizedItem(\$city->toArray());
        \$$UCFormName"."ObjectAsArray['$PureFieldName"."info']['provinceinfo']=\$this->getNormalizedItem(\$province->toArray());";
        $ValidationRules = $FFC->getValidationRules();

        $ValidationMessages = $FFC->getValidationMessages();
        $FieldCode=new LaravelFieldCode($AddGetterCodes, $AddFieldSetCodes, $UpdateGetterCodes, $UpdateFieldSetCodes, $ListQueryCodes, $ListFieldLoadCodes, $SingleLoadFieldLoadCodes,$ValidationRules,$ValidationMessages,FieldCode::$ADD_POLICY_TO_WITH_CURRENT);
        return $FieldCode;
    }
    private function _getCityAreaFieldCode($ModuleName,$FormName,$FieldName,$PureFieldName,$TranslatedFieldName){
        $FFC=$this->_getForeignIDFieldCode($ModuleName,$FormName,$FieldName,$PureFieldName,$TranslatedFieldName);

        $UCFormName = ucfirst($FormName);
        $AddGetterCodes =$FFC->getAddGetterCodes();
        $AddFieldSetCodes = $FFC->getAddFieldSetCodes();
        $UpdateGetterCodes = $FFC->getUpdateGetterCodes();
        $UpdateFieldSetCodes = $FFC->getUpdateFieldSetCodes();
        $ListQueryCodes =$FFC->getListQueryCodes();
        $ListFieldLoadCodes = "
            \$AreaField=\$$UCFormName" . "s[\$i]->$PureFieldName();
            \$CityField=\$AreaField==null?'':\$AreaField->city();
            \$ProvinceField=\$CityField==null?'':\$CityField->province();
            \$$UCFormName" . "sArray[\$i]['$PureFieldName"."content']=\$AreaField==null?'':\$AreaField->title;
            \$$UCFormName" . "sArray[\$i]['citycontent']=\$CityField==null?'':\$CityField->title;
            \$$UCFormName" . "sArray[\$i]['provincecontent']=\$ProvinceField==null?'':\$ProvinceField->title;";
        $SingleLoadFieldLoadCodes = "
        \$AreaField=\$$UCFormName" . "->$PureFieldName();
        \$CityField=\$AreaField==null?'':\$AreaField->city();
        \$ProvinceField=\$CityField==null?'':\$CityField->province();
        \$$UCFormName" . "ObjectAsArray['$PureFieldName"."info']=\$AreaField==null?'':\$AreaField;
        \$$UCFormName" . "ObjectAsArray['cityinfo']=\$CityField==null?'':\$CityField;
        \$$UCFormName" . "ObjectAsArray['provinceinfo']=\$ProvinceField==null?'':\$ProvinceField;";
        $ValidationRules = $FFC->getValidationRules();

        $ValidationMessages = $FFC->getValidationMessages();
        $FieldCode=new LaravelFieldCode($AddGetterCodes, $AddFieldSetCodes, $UpdateGetterCodes, $UpdateFieldSetCodes, $ListQueryCodes, $ListFieldLoadCodes, $SingleLoadFieldLoadCodes,$ValidationRules,$ValidationMessages,FieldCode::$ADD_POLICY_TO_WITH_CURRENT);
        return $FieldCode;
    }

    /**
     * @param string $ModuleName
     * @param string $FormName
     * @param string $FieldName
     * @param string $PureFieldName
     * @param string $TranslatedFieldName
     * @return LaravelFieldCode
     */
    private function _getFieldCodes($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName)
    {
//        if(FieldType::fieldIsAutoGenerated($FieldName))
//            return $this->_getAutoFieldCodes($ModuleName,$FormName,$FieldName,$PureFieldName,$TranslatedFieldName);
//        if(FieldType::fieldIsLongitude($FieldName))
//            return $this->_getEmptyCodedFieldCodes($ModuleName,$FormName,$FieldName,$PureFieldName,$TranslatedFieldName);
//        if(FieldType::fieldIsLatitude($FieldName))
//            return $this->_getLocationFieldCodes($ModuleName,$FormName,$FieldName,$PureFieldName,$TranslatedFieldName);
//        if(FieldType::getFieldType($FieldName)==FieldType::$CLOCK)
//            return $this->_getClockFieldCodes($ModuleName,$FormName,$FieldName,$PureFieldName,$TranslatedFieldName);
//        if (FieldType::getFieldType($FieldName) == FieldType::$BOOLEAN)
//            return $this->_getBooleanFieldCodes($ModuleName,$FormName,$FieldName,$PureFieldName,$TranslatedFieldName);
        if (FieldType::getFieldType($FieldName) == FieldType::$FID) {

            if (FieldType::fieldIsCityAreaFid($FieldName))
                return $this->_getCityAreaFieldCode($ModuleName,$FormName,$FieldName,$PureFieldName,$TranslatedFieldName);
            if (FieldType::fieldIsPlaceFid($FieldName))
                return $this->_getPlaceFieldCode($ModuleName,$FormName,$FieldName,$PureFieldName,$TranslatedFieldName);
            return $this->_getForeignIDFieldCode($ModuleName,$FormName,$FieldName,$PureFieldName,$TranslatedFieldName);
        }
        if (FieldType::fieldIsFileUpload($FieldName))
            return $this->_getFileUploadFiledCodes($ModuleName,$FormName,$FieldName,$PureFieldName,$TranslatedFieldName);
        if (FieldType::getFieldType($FieldName)==FieldType::$DATE)
            return $this->_getDateFieldCode($ModuleName,$FormName,$FieldName,$PureFieldName,$TranslatedFieldName);
        if (FieldType::fieldIsNumber($FieldName))
            return $this->_getNumberFieldCode($ModuleName,$FormName,$FieldName,$PureFieldName,$TranslatedFieldName);
        return $this->_getGeneralFieldCodes($ModuleName,$FormName,$FieldName,$PureFieldName,$TranslatedFieldName);
    }
    protected function makeLaravelAPIController($formInfo)
    {

        $this->makeLaravelAPIModel($formInfo);
        $this->makeLaravelRoutes($formInfo);
        $this->makeLaravelMigrations($formInfo);
        $this->makeLaravelRequests($formInfo);
        $ModuleName = $formInfo['module']['name'];
        $FormName = $formInfo['form']['name'];
        $UCFormName = ucfirst($FormName);
        $InsertRequestClassName=$ModuleName."_".$FormName."AddRequest";
        $UpdateRequestClassName=$ModuleName."_".$FormName."UpdateRequest";
        $FormNames = $FormName . "s";
        $ModuleNames = $ModuleName . "s";

        $AllFields=$this->getAllFormsOfFields();
        $Fields=$AllFields['fields'];
        $PersianFields=$AllFields['persianfields'];
        $PureFields=$AllFields['purefields'];
        $AddGetterCodes = "";
        $AddFieldSetCodes = "";
        $UpdateGetterCodes = "";
        $UpdateFieldSetCodes = "";
        $ListQueryCodes = "";
        $ListFieldLoadCodes = "";
        $SingleLoadFieldLoadCodes = "";
        $AfterSaveAddGetterCodes="";
        $AfterSaveAddFieldSetCodes="";
        $AfterSaveUpdateGetterCodes="";
        $AfterSaveUpdateFieldSetCodes="";
//        $ValidationRules="";
        for ($i = 0; $i < count($Fields); $i++) {
            $FC=$this->_getFieldCodes($ModuleName,$FormName,$Fields[$i],$PureFields[$i],$PersianFields[$i]);
            if($FC->getAddPolicy()!=FieldCode::$ADD_POLICY_TO_AFTER_JOB)
            {
                $AddGetterCodes.=$FC->getAddGetterCodes();
                if ($AddFieldSetCodes != "")
                    $AddFieldSetCodes .= ",";
                $AddFieldSetCodes.=$FC->getAddFieldSetCodes();
                $UpdateGetterCodes.=$FC->getUpdateGetterCodes();
                $UpdateFieldSetCodes.=$FC->getUpdateFieldSetCodes();
            }
            else{
                $AfterSaveAddGetterCodes.=$FC->getAddGetterCodes();
                $AfterSaveAddFieldSetCodes.=$FC->getAddFieldSetCodes();
                $AfterSaveUpdateGetterCodes.=$FC->getUpdateGetterCodes();
                $AfterSaveUpdateFieldSetCodes.=$FC->getUpdateFieldSetCodes();
            }
//            $ValidationRules.=$FC->getValidationRules();
            $ListQueryCodes.=$FC->getListQueryCodes();
            $ListFieldLoadCodes.=$FC->getListFieldLoadCodes();
            $SingleLoadFieldLoadCodes.=$FC->getSingleLoadFieldLoadCodes();
        }
        $C = "<?php
namespace App\\Http\\Controllers\\$ModuleName\\API;
use App\\models\\$ModuleName\\$ModuleName" . "_" . "$FormName;
use App\\Http\\Controllers\\Controller;
use App\\Sweet\\SweetQueryBuilder;
use App\\Sweet\\SweetController;
use Illuminate\\Http\\Request;
use App\Http\Controllers\common\classes\SweetDateManager;
use App\\Classes\\Sweet\\SweetDBFile;
use Illuminate\\Validation\\ValidationException;
use Validator;
use Bouncer;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use App\\Http\\Requests\\$ModuleName\\$InsertRequestClassName;
use App\\Http\\Requests\\$ModuleName\\$UpdateRequestClassName;

class $UCFormName" . "Controller extends SweetController
{
    private \$ModuleName='$ModuleName';
";
        $C .= "\n\tpublic function add($InsertRequestClassName \$request)
    {
        if(!Bouncer::can('$ModuleName" . "." . "$FormName.insert'))
            throw new AccessDeniedHttpException();
        \$request->validated();
        $AddGetterCodes
    ";
        $C .= "\n\t\t\$$UCFormName" . " = $ModuleName" . "_" . "$FormName::create([";
        $C .= $AddFieldSetCodes . ",'deletetime'=>-1]);";
        $C .= $AfterSaveAddGetterCodes;
        $C .= $AfterSaveAddFieldSetCodes;
        if($AfterSaveAddFieldSetCodes!="")
            $C .= "\n\t\t$$UCFormName" . "->save();";
        $C .= "\n\t\treturn response()->json(['Data'=>\$$UCFormName], 201);";
        $C .= "\n\t}";
        $C .= "\n\tpublic function update(\$id,$UpdateRequestClassName \$request)
    {
        if(!Bouncer::can('$ModuleName" . "." . "$FormName.edit'))
            throw new AccessDeniedHttpException();
        \$request->setIsUpdate(true);
        \$request->validated();
        $UpdateGetterCodes;
            
    ";
        $C .= "\n        \$$UCFormName" . " = new $ModuleName" . "_" . "$FormName();";
        $C .= "\n        \$$UCFormName" . " = \$$UCFormName" . "->find(\$id);";
        $C .= $UpdateFieldSetCodes;
        $C .= $AfterSaveUpdateGetterCodes;
        $C .= $AfterSaveUpdateFieldSetCodes;
        $C .= "\n        \$$UCFormName" . "->save();";
        $C .= "\n        return response()->json(['Data'=>\$$UCFormName], 202);";
        $C .= "\n    }";
        $C .= "\n    public function list(Request \$request)";
        $C .= "\n    {
        Bouncer::allow('admin')->to('$ModuleName" . "." . "$FormName.insert');
        Bouncer::allow('admin')->to('$ModuleName" . "." . "$FormName.edit');
        Bouncer::allow('admin')->to('$ModuleName" . "." . "$FormName.list');
        Bouncer::allow('admin')->to('$ModuleName" . "." . "$FormName.view');
        Bouncer::allow('admin')->to('$ModuleName" . "." . "$FormName.delete');
        //if(!Bouncer::can('$ModuleName" . "." . "$FormName.list'))
            //throw new AccessDeniedHttpException();
        \$SearchText=\$request->get('searchtext');";

        $C .= "\n        \$$UCFormName"."Query = $ModuleName" . "_" . "$FormName::where('id','>=','0');";
//        print_r($Fields);
        $titleField=$Fields[$this->getTitleFieldIndex($Fields)];
        $C .= "\n        \$$UCFormName"."Query =SweetQueryBuilder::WhereLikeIfNotNull(\$$UCFormName"."Query,'$titleField',\$SearchText);";

        $C.=$ListQueryCodes;
        $C .= "\n        \$$UCFormName"."sCount=\$$UCFormName"."Query->get()->count();";
        $C .= "\n        if(\$request->get('_onlycount')!==null)";
        $C .= "\n            return response()->json(['Data'=>[],'RecordCount'=>\$$UCFormName"."sCount], 200);";
        $C .= "\n        \$$UCFormName"."s=SweetQueryBuilder::setPaginationIfNotNull(\$$UCFormName"."Query,\$request->get('__startrow'),\$request->get('__pagesize'))->get();";
        $C .= "\n        \$$UCFormName"."sArray=[];";
        $C .= "\n        for(\$i=0;\$i<count(\$$UCFormName"."s);\$i++)";
        $C .= "\n        {";
        $C .= "\n            \$$UCFormName"."sArray[\$i]=" . "\$$UCFormName" . "s[\$i]->toArray();";
        $C.=$ListFieldLoadCodes;
        $C .= "\n        }";
        $C .= "\n        \$$UCFormName = \$this->getNormalizedList(\$$UCFormName" . "sArray);";
        $C .= "\n        return response()->json(['Data'=>\$$UCFormName,'RecordCount'=>\$$UCFormName"."sCount], 200);";
        $C .= "\n    }";
        $C .= "\n    public function get(\$id,Request \$request)";
        $C .= "\n    {
        //if(!Bouncer::can('$ModuleName" . "." . "$FormName.view'))
            //throw new AccessDeniedHttpException();";
        $C .= "
        \$$UCFormName"."=$ModuleName" . "_" . "$FormName::find(\$id);
        \$$UCFormName"."ObjectAsArray=\$$UCFormName"."->toArray();";
        $C.=$SingleLoadFieldLoadCodes;
        $C .= "\n        \$$UCFormName = \$this->getNormalizedItem(\$$UCFormName"."ObjectAsArray);";
        $C .= "\n        return response()->json(['Data'=>\$$UCFormName], 200);";
        $C .= "\n    }";
        $C .= "\n    public function delete(\$id,Request \$request)";
        $C .= "\n    {
        if(!Bouncer::can('$ModuleName" . "." . "$FormName.delete'))
            throw new AccessDeniedHttpException();";
        $C .= "\n        \$$UCFormName = $ModuleName" . "_" . "$FormName::find(\$id);";
        $C .= "\n        \$$UCFormName" . "->delete();";
        $C .= "\n        return response()->json(['message'=>'deleted','Data'=>[]], 202);";
        $C .= "\n    }";
        $C .= "\n}";
        $DesignFile = $this->getLaravelCodeModuleDir() . "/" . $ModuleName . "/app/Http/Controllers/$ModuleName/API/$FormName" . "Controller.php";
        $this->SaveFile($DesignFile, $C);
    }

    protected function makeLaravelRequests($formInfo)
    {
        $ModuleName = $formInfo['module']['name'];
        $FormName = $formInfo['form']['name'];
        $InsertRequestClassName=$ModuleName."_".$FormName."AddRequest";
        $UpdateRequestClassName=$ModuleName."_".$FormName."UpdateRequest";
        $AllFields=$this->getAllFormsOfFields();
        $Fields=$AllFields['fields'];
        $PersianFields=$AllFields['persianfields'];
        $PureFields=$AllFields['purefields'];
        $ValidationRules="";
        $ValidationMessages="";
        for ($i = 0; $i < count($Fields); $i++) {
            $FC=$this->_getFieldCodes($ModuleName,$FormName,$Fields[$i],$PureFields[$i],$PersianFields[$i]);
            $ValidationRules.=$FC->getValidationRules();
            $ValidationMessages.=$FC->getValidationMessages();
        }
        $InsertCode = "<?php
namespace App\\Http\\Requests\\$ModuleName;
use App\Http\Requests\sweetRequest;
class $InsertRequestClassName extends $UpdateRequestClassName
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        \$Fields = [
        ];
        
        \$Fields = array_merge(\$Fields, parent::rules());
        return \$Fields;
    }
    public function messages()
    {
        return [
            $ValidationMessages
        ];
    }
}";
        $UpdateCode = "<?php
namespace App\\Http\\Requests\\$ModuleName;
use App\\Http\\Requests\\sweetRequest;

class $UpdateRequestClassName extends sweetRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        \$Fields = [
            $ValidationRules
        ];
        return \$Fields;
    }
    public function messages()
    {
        return [
            $ValidationMessages
        ];
    }
}";
        $DesignFile1 = $this->getLaravelCodeModuleDir() . "/" . $ModuleName . "/app/Http/Requests/$ModuleName/$InsertRequestClassName" . ".php";
        $this->SaveFile($DesignFile1, $InsertCode);
        $DesignFile2 = $this->getLaravelCodeModuleDir() . "/" . $ModuleName . "/app/Http/Requests/$ModuleName/$UpdateRequestClassName" . ".php";
        $this->SaveFile($DesignFile2, $UpdateCode);
    }
    protected function makeLaravelMigrations($formInfo)
    {
//        $this->makeLaravelAPIModel($formInfo);
//        $this->makeLaravelRoutes($formInfo);
        $ModuleName = $formInfo['module']['name'];
        $UModuleName = ucfirst($ModuleName);
        $FormName = $formInfo['form']['name'];
        $UCFormName = ucfirst($FormName);
        $FormNames = $FormName . "s";
        $ModuleNames = $ModuleName . "s";

        $AllFields=$this->getAllFormsOfFields();
        $Fields=$AllFields['fields'];
        $PersianFields=$AllFields['persianfields'];
        $PureFields=$AllFields['purefields'];
        $C = "<?php
use Illuminate\\Support\\Facades\\Schema;
use Illuminate\\Database\Schema\\Blueprint;
use Illuminate\\Database\\Migrations\\Migration;

class Create$UModuleName$UCFormName" . "Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('$ModuleName" . "_$FormName', function (Blueprint \$table) {
            \$table->increments('id');
            ";
        for ($i = 0; $i < count($Fields); $i++) {
                $UCField = $Fields[$i];
                $Field = trim(strtolower($UCField));
                if ($Field != "deletetime") {
                    if (FieldType::getFieldType($Fields[$i]) == FieldType::$FID){
                        $TableName=$this->getTableNameFromFIDFieldName($Field);
                        $Module=$this->getModuleNameFromFIDFieldName($Field,$ModuleName);
                        $ModelName=$Module."_".$TableName;
                        if($Module==="")
                            $ModelName=$TableName;
                        $ModelName=strtolower($ModelName);
                        if($ModelName=="user")
                            $ModelName="users";
                        $C .= "\n\$table->integer('$Field')->unsigned()->nullable()->index();";
                        if(strtolower($FormName)!=strtolower($TableName))//Not Self Relation
                            $C .= "\n\$table->foreign('$Field')->references('id')->on('$ModelName');";
                    }
                    elseif (FieldType::getFieldType($Fields[$i]) == FieldType::$BOOLEAN)
                        $C .= "\n\$table->boolean('$Field');";
                    elseif (FieldType::fieldIsFileUpload($Fields[$i]) )
                        $C .= "\n\$table->string('$Field',500)->default('');";
                    elseif(FieldType::fieldTypesIsTextArea($Fields[$i]) )
                        $C .= "\n\$table->text('$Field')->default('');";
                    elseif(FieldType::fieldIsNumber($Fields[$i]))
                    {
                        if(FieldType::getFieldType($Fields[$i])==FieldType::$BIGPOSITIVENUMBER)
                            $C .= "\n\$table->bigInteger('$Field')->nullable();";
                        else
                            $C .= "\n\$table->integer('$Field')->nullable();";
                    }
                    else
                        $C .= "\n\$table->string('$Field',500)->default('');";
                }

        }
        $C .= "
            \$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('$FormNames');
    }
}
";
        $DesignFile = $this->getLaravelCodeModuleDir() . "/" . $ModuleName . "/database/migrations/2014_10_12_000000_create_$ModuleName" . "_$FormName" . "_table.php";
        $this->SaveFile($DesignFile, $C);
    }

    protected function makeLaravelAPIModel($formInfo)
    {

        $ModuleName = $formInfo['module']['name'];
        $FormName = $formInfo['form']['name'];

        $AllFields=$this->getAllFormsOfFields();
        $Fields=$AllFields['fields'];
        $PureFields=$AllFields['purefields'];
        $C = "<?php
namespace App\\models\\$ModuleName;

use App\\User;
use Illuminate\\Database\\Eloquent\\Model;

class $ModuleName" . "_" . "$FormName extends Model
{
    protected \$table = \"$ModuleName" . "_" . "$FormName\";
    protected \$fillable = [";
        $fieldSetCode = '';
        for ($i = 0; $i < count($Fields); $i++) {
            $UCField = $Fields[$i];
            $Field = trim(strtolower($UCField));
            $UCField = ucfirst($Field);
            if ($Field != "deletetime") {

                if ($fieldSetCode != "")
                    $fieldSetCode .= ",";
                $fieldSetCode .= "'$Field'";
            }
        }
        $C .= $fieldSetCode . "];";
        for ($i = 0; $i < count($Fields); $i++) {
            $Field = trim(strtolower($Fields[$i]));

            if (FieldType::getFieldType($Fields[$i]) == FieldType::$FID){
                $TableName=$this->getTableNameFromFIDFieldName($Field);
                $Module=$this->getModuleNameFromFIDFieldName($Field,$ModuleName);
                $ModelName=$Module."_".$TableName;
                if($Module==="")
                    $ModelName=$TableName;

                $C .= "\n\tpublic function $PureFields[$i]()
    {
        return \$this->belongsTo($ModelName::class,'$Field')->first();
    }";
            }

        }

        $C .= "\n}";
        $DesignFile = $this->getLaravelCodeModuleDir() . "/" . $ModuleName . "/app/models/$ModuleName/$ModuleName" . "_$FormName" . ".php";
        $this->SaveFile($DesignFile, $C);
    }

    protected function makeLaravelAPIRoutes($formInfo)
    {

        $ModuleName = $formInfo['module']['name'];
        $FormName = $formInfo['form']['name'];
        $FormNames = $FormName . "s";
        $C = "<?php
//------------------------------------------------------------------------------------------------------
Route::group(['middleware' => 'auth:api'], function() {";
        $C2 = "<?php
//------------------------------------------------------------------------------------------------------";
        $C2 .= "\nRoute::get('$ModuleName/$FormName', '$ModuleName\\\\API\\\\$FormName" . "Controller@list');";
        $C2 .= "\nRoute::get('$ModuleName/$FormName/{id}', '$ModuleName\\\\API\\\\$FormName" . "Controller@get');";
        $C .= "\n    Route::post('$ModuleName/$FormName', '$ModuleName\\\\API\\\\$FormName" . "Controller@add');";
        $C .= "\n    Route::put('$ModuleName/$FormName/{id}', '$ModuleName\\\\API\\\\$FormName" . "Controller@update');";
        $C .= "\n    Route::delete('$ModuleName/$FormName/{id}', '$ModuleName\\\\API\\\\$FormName" . "Controller@delete');";
        $C.="\n});
?>";
        $C2.="
?>";
        $DesignFile = $this->getLaravelCodeModuleDir() . "/" . $ModuleName . "/routes/" . $ModuleName . "/changer-api.php";
        $DesignFile2 = $this->getLaravelCodeModuleDir() . "/" . $ModuleName . "/routes/" . $ModuleName . "/viewer-api.php";
        $this->SaveFile($DesignFile, $C,true);
        $this->SaveFile($DesignFile2, $C2,true);
    }

    protected function makeLaravelWebRoutes($formInfo)
    {

        $ModuleName = $formInfo['module']['name'];
        $FormName = $formInfo['form']['name'];
        $FormNames = $FormName . "s";
        $C = "<?php
//------------------------------------------------------------------------------------------------------";
        $C .= "\nRoute::get('$ModuleName/management/$FormNames', '$ModuleName\\\\Web\\\\$FormName" . "Controller@managelist')->name('$FormName" . "manlist');";
        $C .= "\nRoute::post('$ModuleName/management/$FormNames/manage', '$ModuleName\\\\Web\\\\$FormName" . "Controller@managesave');";
        $C .= "\nRoute::get('$ModuleName/management/$FormNames/manage', '$ModuleName\\\\Web\\\\$FormName" . "Controller@manageload')->name('$FormName" . "manlist');";
        $C .= "\nRoute::get('$ModuleName/management/$FormNames/delete', '$ModuleName\\\\Web\\\\$FormName" . "Controller@delete');";
        $C .= "\n?>";
        $DesignFile = $this->getLaravelCodeModuleDir() . "/" . $ModuleName . "/routes/$ModuleName/web.php";
        $this->SaveFile($DesignFile, $C);
    }
    protected function makeLaravelRoutes($formInfo)
    {

        $this->makeLaravelAPIRoutes($formInfo);
        $this->makeLaravelWebRoutes($formInfo);
    }
}

?>