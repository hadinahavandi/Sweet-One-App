<?php
namespace Modules\kpex\Controllers;
use core\CoreClasses\services\Controller;
use core\CoreClasses\Exception\DataNotFoundException;
use core\CoreClasses\db\dbaccess;
use Modules\kpex\Entity\kpex_contextEntity;
use Modules\kpex\Entity\kpex_methodEntity;
use Modules\languages\PublicClasses\CurrentLanguageManager;
use Modules\users\PublicClasses\sessionuser;
use core\CoreClasses\db\QueryLogic;
use core\CoreClasses\db\FieldCondition;
use core\CoreClasses\db\LogicalOperator;
use Modules\kpex\Entity\kpex_testEntity;
/**
*@author Hadi AmirNahavandi
*@creationDate 1397-06-17 - 2018-09-08 05:13
*@lastUpdate 1397-06-17 - 2018-09-08 05:13
*@SweetFrameworkHelperVersion 2.004
*@SweetFrameworkVersion 2.004
*/
class managetestController extends Controller {
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
	public function load($ID)
	{
		$Language_fid=CurrentLanguageManager::getCurrentLanguageID();
		$DBAccessor=new dbaccess();
		$su=new sessionuser();
		$role_systemuser_fid=$su->getSystemUserID();        
		$UserID=null;
        if(!$this->getAdminMode())
            $UserID=$role_systemuser_fid;
		$result=array();
		$testEntityObject=new kpex_testEntity($DBAccessor);
		$contextEntityObject=new kpex_contextEntity($DBAccessor);
		$result['context_fid']=$contextEntityObject->FindAll(new QueryLogic());
		$methodEntityObject=new kpex_methodEntity($DBAccessor);
		$result['method_fid']=$methodEntityObject->FindAll(new QueryLogic());
		$RelationLogic=new QueryLogic();
		$RelationLogic->addCondition(new FieldCondition('test_fid',$ID));
		$result['test']=$testEntityObject;
		if($ID!=-1){
			$testEntityObject->setId($ID);
			if($testEntityObject->getId()==-1)
				throw new DataNotFoundException();
			if($UserID!=null && $testEntityObject->getRole_systemuser_fid()!=$UserID)
				throw new DataNotFoundException();
			$result['test']=$testEntityObject;
		}
		$result['param1']="";
		$DBAccessor->close_connection();
		return $result;
	}
	public function BtnSave($ID,$nouninfluence,$nounoutinfluence,$adjectiveinfluence,$adjectiveoutinfluence,$similarity_threshold,$similarity_influence,$resultcount,$context_fid,$description,$words,$is_postaged,$is_similarityedgeweighed,$method_fid,$apprate,$precisionrate,$recall,$fscore)
	{
		$Language_fid=CurrentLanguageManager::getCurrentLanguageID();
		$DBAccessor=new dbaccess();
		$su=new sessionuser();
		$role_systemuser_fid=$su->getSystemUserID();        
		$UserID=null;
        if(!$this->getAdminMode())
            $UserID=$role_systemuser_fid;
		$result=array();
		$testEntityObject=new kpex_testEntity($DBAccessor);
		$this->ValidateFieldArray([$nouninfluence,$nounoutinfluence,$adjectiveinfluence,$adjectiveoutinfluence,$similarity_threshold,$similarity_influence,$resultcount,$context_fid,$description,$words,$is_postaged,$is_similarityedgeweighed,$method_fid,$apprate,$precisionrate,$recall,$fscore],[$testEntityObject->getFieldInfo(kpex_testEntity::$NOUNINFLUENCE),$testEntityObject->getFieldInfo(kpex_testEntity::$NOUNOUTINFLUENCE),$testEntityObject->getFieldInfo(kpex_testEntity::$ADJECTIVEINFLUENCE),$testEntityObject->getFieldInfo(kpex_testEntity::$ADJECTIVEOUTINFLUENCE),$testEntityObject->getFieldInfo(kpex_testEntity::$SIMILARITY_THRESHOLD),$testEntityObject->getFieldInfo(kpex_testEntity::$SIMILARITY_INFLUENCE),$testEntityObject->getFieldInfo(kpex_testEntity::$RESULTCOUNT),$testEntityObject->getFieldInfo(kpex_testEntity::$CONTEXT_FID),$testEntityObject->getFieldInfo(kpex_testEntity::$DESCRIPTION),$testEntityObject->getFieldInfo(kpex_testEntity::$WORDS),$testEntityObject->getFieldInfo(kpex_testEntity::$IS_POSTAGED),$testEntityObject->getFieldInfo(kpex_testEntity::$IS_SIMILARITYEDGEWEIGHED),$testEntityObject->getFieldInfo(kpex_testEntity::$METHOD_FID),$testEntityObject->getFieldInfo(kpex_testEntity::$APPRATE),$testEntityObject->getFieldInfo(kpex_testEntity::$PRECISIONRATE),$testEntityObject->getFieldInfo(kpex_testEntity::$RECALL),$testEntityObject->getFieldInfo(kpex_testEntity::$FSCORE)]);
		if($ID==-1){
			$testEntityObject->setNouninfluence($nouninfluence);
			$testEntityObject->setNounoutinfluence($nounoutinfluence);
			$testEntityObject->setAdjectiveinfluence($adjectiveinfluence);
			$testEntityObject->setAdjectiveoutinfluence($adjectiveoutinfluence);
			$testEntityObject->setSimilarity_threshold($similarity_threshold);
			$testEntityObject->setSimilarity_influence($similarity_influence);
			$testEntityObject->setResultcount($resultcount);
			$testEntityObject->setContext_fid($context_fid);
			$testEntityObject->setDescription($description);
			$testEntityObject->setWords($words);
			$testEntityObject->setIs_postaged($is_postaged);
			$testEntityObject->setIs_similarityedgeweighed($is_similarityedgeweighed);
			$testEntityObject->setMethod_fid($method_fid);
			$testEntityObject->setApprate($apprate);
			$testEntityObject->setPrecisionrate($precisionrate);
			$testEntityObject->setRecall($recall);
			$testEntityObject->setFscore($fscore);
			$testEntityObject->setCreated_at(time());
			$testEntityObject->setUpdated_at(-1);
			$testEntityObject->Save();
			$ID=$testEntityObject->getId();
		}
		else{
			$testEntityObject->setId($ID);
			if($testEntityObject->getId()==-1)
				throw new DataNotFoundException();
			if($UserID!=null && $testEntityObject->getRole_systemuser_fid()!=$UserID)
				throw new DataNotFoundException();
			$testEntityObject->setNouninfluence($nouninfluence);
			$testEntityObject->setNounoutinfluence($nounoutinfluence);
			$testEntityObject->setAdjectiveinfluence($adjectiveinfluence);
			$testEntityObject->setAdjectiveoutinfluence($adjectiveoutinfluence);
			$testEntityObject->setSimilarity_threshold($similarity_threshold);
			$testEntityObject->setSimilarity_influence($similarity_influence);
			$testEntityObject->setResultcount($resultcount);
			$testEntityObject->setContext_fid($context_fid);
			$testEntityObject->setDescription($description);
			$testEntityObject->setWords($words);
			$testEntityObject->setIs_postaged($is_postaged);
			$testEntityObject->setIs_similarityedgeweighed($is_similarityedgeweighed);
			$testEntityObject->setMethod_fid($method_fid);
			$testEntityObject->setApprate($apprate);
			$testEntityObject->setPrecisionrate($precisionrate);
			$testEntityObject->setRecall($recall);
			$testEntityObject->setFscore($fscore);
			$testEntityObject->setUpdated_at(time());
			$testEntityObject->Save();
		}
		$RelationLogic=new QueryLogic();
		$RelationLogic->addCondition(new FieldCondition('test_fid',$ID));
		$result=$this->load($ID);
		$result['param1']="";
		$DBAccessor->close_connection();
		return $result;
	}
}
?>