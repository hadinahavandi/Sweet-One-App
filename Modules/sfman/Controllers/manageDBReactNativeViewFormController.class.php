<?php

namespace Modules\sfman\Controllers;


/**
 * @author Hadi AmirNahavandi
 * @creationDate 1395/10/9 - 2016/12/29 19:36:38
 * @lastUpdate 1395/10/9 - 2016/12/29 19:36:38
 * @SweetFrameworkHelperVersion 1.112
 */
abstract class manageDBReactNativeViewFormController extends manageDBReactNativeManageFormController
{

    private function _getCityAreaFieldCode($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName, $LoadedDataSubClass)
    {

        $StateVariableCodes = "
                    provinceinfo:{},
                    cityinfo:{},
                    $PureFieldName" . "info:{},";
        $ConstructorCodes = "";
        $ImportCodes = "";
        $ClassFieldDefinitionCodes = "";
        $LoaderMethodCodes = "";
        $LoaderMethodCallCodes = "";

        $ViewCodes = "
                            <TextRow title={'محل'} content={this.state.LoadedData.$LoadedDataSubClass" . "provinceinfo.title+' - '+this.state.LoadedData.$LoadedDataSubClass" . "cityinfo.title+' - '+this.state.LoadedData.$LoadedDataSubClass" . "$PureFieldName" . "info.title} />";
        $SaveCodes = "";
        $FieldCode = new ReactFieldCode($ImportCodes, $ClassFieldDefinitionCodes, $ConstructorCodes,"", $StateVariableCodes,"", $LoaderMethodCodes, $LoaderMethodCallCodes, $ViewCodes, $SaveCodes, ReactFieldCode::$ADD_POLICY_TO_WITH_CURRENT);
        return $FieldCode;
    }

    private function _getForeignIDFieldCode($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName, $LoadedDataSubClass)
    {
        $GFC = $this->_getGeneralFieldCodes($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName, $LoadedDataSubClass);

        $StateVariableCodes = "
                    $PureFieldName" . "info:{},";
        $ConstructorCodes = "";
        $ImportCodes = "";
        $ClassFieldDefinitionCodes = "";
        $LoaderMethodCodes = "";
        $LoaderMethodCallCodes = "";
        $ViewCodes = "";
        $SaveCodes = "";
        $FiledModule = strtolower($this->getModuleNameFromFIDFieldName($FieldName, $ModuleName));
        $TableName = strtolower($this->getTableNameFromFIDFieldName($FieldName));
        if ($FiledModule != "") {
            $ViewCodes = "
                            <TextRow title={'$TranslatedFieldName'} content={this.state.LoadedData.$LoadedDataSubClass" . "$PureFieldName" . "info.name} />";
            $SaveCodes = "";
        }
        $FieldCode = new ReactFieldCode($ImportCodes, $ClassFieldDefinitionCodes, $ConstructorCodes,"", $StateVariableCodes,"", $LoaderMethodCodes, $LoaderMethodCallCodes, $ViewCodes, $SaveCodes, ReactFieldCode::$ADD_POLICY_TO_WITH_CURRENT);
        return $FieldCode;
    }

    private function _getPlaceFidCode($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName, $LoadedDataSubClass)
    {
        $FFC = $this->_getForeignIDFieldCode($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName, $LoadedDataSubClass);

        $ConstructorCodes = $FFC->getConstructorCodes();
        $ImportCodes = $FFC->getImportCodes();
        $ClassFieldDefinitionCodes = $FFC->getClassFieldDefinitionCodes();
        $LoaderMethodCodes = $FFC->getLoaderMethodCodes();
        $LoaderMethodCallCodes = $FFC->getLoaderMethodCallCodes();
        $SaveCodes = $FFC->getSaveCodes();

        /*********************************************************/
        $Fields = $this->getTableFields("placeman_place");
        $AllFields = $this->getAllFormsOfFields($Fields);
        $Fields = $AllFields['fields'];
        $PersianFields = $AllFields['persianfields'];
        $PureFields = $AllFields['purefields'];
        $ViewCodes = "";
        $EndViewCodes = "";
        $StateVars="";
        for ($i = 0; $i < count($Fields); $i++) {
            $FC = $this->_getFieldCodes($ModuleName, $FormName, $Fields[$i], $PureFields[$i], $PersianFields[$i], $PureFieldName."info.");
            if ($FC->getAddPolicy() == ReactFieldCode::$ADD_POLICY_TO_TOP)
                $ViewCodes = $FC->getViewCodes() . $ViewCodes;
            elseif ($FC->getAddPolicy() == ReactFieldCode::$ADD_POLICY_TO_BOTTOM)
                $EndViewCodes .= $FC->getViewCodes();
            else
                $ViewCodes .= $FC->getViewCodes();
            $StateVars.=$FC->getDataStateVariableCodes();
        }
        $ViewCodes .= $EndViewCodes;
        /*********************************************************/
        $StateVariableCodes = "
                    $PureFieldName" . "info:{".$StateVars."},";
        $FieldCode = new ReactFieldCode($ImportCodes, $ClassFieldDefinitionCodes, $ConstructorCodes,"", $StateVariableCodes,"", $LoaderMethodCodes, $LoaderMethodCallCodes, $ViewCodes, $SaveCodes, ReactFieldCode::$ADD_POLICY_TO_BOTTOM);
        return $FieldCode;
    }

    private function _getImageUploadCodes($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName, $LoadedDataSubClass)
    {
        $StateVariableCodes = "";
        $ConstructorCodes = "";
        $ImportCodes = "";
        $ClassFieldDefinitionCodes = "";
        $LoaderMethodCodes = "";
        $LoaderMethodCallCodes = "";
        $ViewCodes = "
                          <Image style={generalStyles.topimage} source={{uri: Constants.ServerURL+'/'+this.state.LoadedData.$LoadedDataSubClass" . "$PureFieldName}}/>
";
        $SaveCodes = "";
        $FieldCode = new ReactFieldCode($ImportCodes, $ClassFieldDefinitionCodes, $ConstructorCodes,"", $StateVariableCodes,"", $LoaderMethodCodes, $LoaderMethodCallCodes, $ViewCodes, $SaveCodes, ReactFieldCode::$ADD_POLICY_TO_TOP);
        return $FieldCode;
    }

    private function _getBooleanFieldCodes($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName, $LoadedDataSubClass)
    {
        $GFC = $this->_getGeneralFieldCodes($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName, $LoadedDataSubClass);

        $StateVariableCodes = "";
        $ConstructorCodes = "";
        $ImportCodes = "";
        $ClassFieldDefinitionCodes = "";
        $LoaderMethodCodes = "";
        $LoaderMethodCallCodes = "";
        $ViewCodes = "
                            {this.state.LoadedData.$LoadedDataSubClass" . "$PureFieldName==1 && <TextRow title={''} content={'$TranslatedFieldName'} />}";
        $SaveCodes = $GFC->getSaveCodes();
        $FieldCode = new ReactFieldCode($ImportCodes, $ClassFieldDefinitionCodes, $ConstructorCodes,"", $StateVariableCodes,"", $LoaderMethodCodes, $LoaderMethodCallCodes, $ViewCodes, $SaveCodes, ReactFieldCode::$ADD_POLICY_TO_WITH_CURRENT);
        return $FieldCode;
    }

    private function _getLocationFieldCodes($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName, $LoadedDataSubClass)
    {
        $StateVariableCodes = "
                    latitude:0.0,
                    longitude:0.0,";
        $ConstructorCodes = "";
        $ImportCodes = "";
        $ClassFieldDefinitionCodes = "";
        $LoaderMethodCodes = "";
        $LoaderMethodCallCodes = "";
        $ViewCodes = "
                            <View style={generalStyles.mapContainer}>
                                <SimpleMap style={generalStyles.map} latitude={parseFloat(this.state.LoadedData.".$LoadedDataSubClass."latitude)+0} longitude={parseFloat(this.state.LoadedData.".$LoadedDataSubClass."longitude)+0} />
                            </View>";
        $SaveCodes = "";
        $FieldCode = new ReactFieldCode($ImportCodes, $ClassFieldDefinitionCodes, $ConstructorCodes,"", $StateVariableCodes,"", $LoaderMethodCodes, $LoaderMethodCallCodes, $ViewCodes, $SaveCodes, ReactFieldCode::$ADD_POLICY_TO_BOTTOM);
        return $FieldCode;
    }

    private function _getGeneralFieldCodes($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName, $LoadedDataSubClass)
    {
        $StateVariableCodes = "";
        $ConstructorCodes = "";
        $ImportCodes = "";
        $ClassFieldDefinitionCodes = "";
        $LoaderMethodCodes = "";
        $LoaderMethodCallCodes = "";
        $ViewCodes = "
                            <TextRow title={'$TranslatedFieldName'} content={this.state.LoadedData.$LoadedDataSubClass" . "$PureFieldName} />";
        $SaveCodes = "";
        $FieldCode = new ReactFieldCode($ImportCodes, $ClassFieldDefinitionCodes, $ConstructorCodes,"", $StateVariableCodes,"", $LoaderMethodCodes, $LoaderMethodCallCodes, $ViewCodes, $SaveCodes, ReactFieldCode::$ADD_POLICY_TO_WITH_CURRENT);
        return $FieldCode;
    }

    private function _getEmptyCodedFieldCodes($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName, $LoadedDataSubClass)
    {
        $StateVariableCodes = "";
        $ConstructorCodes = "";
        $ImportCodes = "";
        $ClassFieldDefinitionCodes = "";
        $LoaderMethodCodes = "";
        $LoaderMethodCallCodes = "";
        $ViewCodes = "";;
        $SaveCodes = "";
        $FieldCode = new ReactFieldCode($ImportCodes, $ClassFieldDefinitionCodes, $ConstructorCodes,"", $StateVariableCodes,"", $LoaderMethodCodes, $LoaderMethodCallCodes, $ViewCodes, $SaveCodes, ReactFieldCode::$ADD_POLICY_TO_WITH_CURRENT);
        return $FieldCode;
    }

    private function _getAutoFieldCodes($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName, $LoadedDataSubClass)
    {
        return $this->_getEmptyCodedFieldCodes($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName, $LoadedDataSubClass);
    }

    /**
     * @param string $ModuleName
     * @param string $FormName
     * @param string $FieldName
     * @param string $PureFieldName
     * @param string $TranslatedFieldName
     * @param string $LoadedDataSubClass
     * @return ReactFieldCode
     */
    private function _getFieldCodes($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName, $LoadedDataSubClass)
    {

        if (FieldType::fieldIsAutoGenerated($FieldName))
            return $this->_getAutoFieldCodes($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName, $LoadedDataSubClass);
        if (FieldType::fieldIsLongitude($FieldName))
            return $this->_getEmptyCodedFieldCodes($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName, $LoadedDataSubClass);
        if (FieldType::fieldIsLatitude($FieldName))
            return $this->_getLocationFieldCodes($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName, $LoadedDataSubClass);
        if (FieldType::getFieldType($FieldName) == FieldType::$CLOCK)
            return $this->_getClockFieldCodes($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName, $LoadedDataSubClass);
        if (FieldType::getFieldType($FieldName) == FieldType::$BOOLEAN)
            return $this->_getBooleanFieldCodes($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName, $LoadedDataSubClass);
        if (FieldType::getFieldType($FieldName) == FieldType::$FID) {

            if (FieldType::fieldIsCityAreaFid($FieldName))
                return $this->_getCityAreaFieldCode($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName, $LoadedDataSubClass);
            if (FieldType::fieldIsPlaceFid($FieldName))
                return $this->_getPlaceFidCode($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName, $LoadedDataSubClass);
            return $this->_getForeignIDFieldCode($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName, $LoadedDataSubClass);
        }
        if (FieldType::fieldIsImageUpload($FieldName))
            return $this->_getImageUploadCodes($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName, $LoadedDataSubClass);
        return $this->_getGeneralFieldCodes($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName, $LoadedDataSubClass);
    }

    private function _codeGeneratorTemplate($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName, $LoadedDataSubClass)
    {

        $GFC = $this->_getGeneralFieldCodes($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName, $LoadedDataSubClass);
        $StateVariableCodes = "";
        $ConstructorCodes = "";
        $ImportCodes = "";
        $ClassFieldDefinitionCodes = "";
        $LoaderMethodCodes = "";
        $LoaderMethodCallCodes = "";
        $ViewCodes = "";
        $SaveCodes = "";
        $FieldCode = new ReactFieldCode($ImportCodes, $ClassFieldDefinitionCodes, $ConstructorCodes,"", $StateVariableCodes,"", $LoaderMethodCodes, $LoaderMethodCallCodes, $ViewCodes, $SaveCodes, ReactFieldCode::$ADD_POLICY_TO_WITH_CURRENT);
        return $FieldCode;
    }

    private function _getClockFieldCodes($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName, $LoadedDataSubClass)
    {
        $GFC = $this->_getGeneralFieldCodes($ModuleName, $FormName, $FieldName, $PureFieldName, $TranslatedFieldName, $LoadedDataSubClass);

        $StateVariableCodes = $GFC->getDataStateVariableCodes();
        $ConstructorCodes = "";
        $ImportCodes = "";
        $ClassFieldDefinitionCodes = "";
        $LoaderMethodCodes = "";
        $LoaderMethodCallCodes = "";
        $ViewCodes = $GFC->getViewCodes();
        $SaveCodes = $GFC->getSaveCodes();
        $FieldCode = new ReactFieldCode($ImportCodes, $ClassFieldDefinitionCodes, $ConstructorCodes,"", $StateVariableCodes,"", $LoaderMethodCodes, $LoaderMethodCallCodes, $ViewCodes, $SaveCodes, ReactFieldCode::$ADD_POLICY_TO_WITH_CURRENT);
        return $FieldCode;
    }

    protected function makeReactNativeItemViewDesign($formInfo)
    {
        $ModuleName = $formInfo['module']['name'];
        $FormName = $formInfo['form']['name'];
        $FormNames = $FormName . "s";
        $UFormNames = ucfirst($FormNames);
        $UFormName = ucfirst($FormName);
        $ModuleNames = $ModuleName . "s";
        $FileName = $ModuleName . "_$FormName" . "View";
        $Translations = new Translator();
        $PageTitle = " " . $Translations->getPersian($FormName, $FormName);
        $AllFields = $this->getAllFormsOfFields();
        $Fields = $AllFields['fields'];
        $PersianFields = $AllFields['persianfields'];
        $PureFields = $AllFields['purefields'];
        $FieldCodes = [];
        $StateVariableCodes = "";
        $ConstructorCodes = "";
        $ImportCodes = "";
        $ClassFieldDefinitionCodes = "";
        $LoaderMethodCodes = "";
        $LoaderMethodCallCodes = "";
        $ViewCodes = "";
        $EndViewCodes = "";
        for ($i = 0; $i < count($Fields); $i++) {
            $FC = $this->_getFieldCodes($ModuleName, $FormName, $Fields[$i], $PureFields[$i], $PersianFields[$i], "");
            $StateVariableCodes .= $FC->getDataStateVariableCodes();
            $ConstructorCodes .= $FC->getConstructorCodes();
            $ImportCodes .= $FC->getImportCodes();
            $ClassFieldDefinitionCodes .= $FC->getClassFieldDefinitionCodes();
            $LoaderMethodCodes .= $FC->getLoaderMethodCodes();
            $LoaderMethodCallCodes .= $FC->getLoaderMethodCallCodes();
            if ($FC->getAddPolicy() == ReactFieldCode::$ADD_POLICY_TO_TOP)
                $ViewCodes = $FC->getViewCodes() . $ViewCodes;
            elseif ($FC->getAddPolicy() == ReactFieldCode::$ADD_POLICY_TO_BOTTOM)
                $EndViewCodes .= $FC->getViewCodes();
            else
                $ViewCodes .= $FC->getViewCodes();
        }
        $ViewCodes .= $EndViewCodes;

        $C = "import React, {Component} from 'react'
import { Button } from 'react-native-elements';
import {StyleSheet, View, Alert, ScrollView, Dimensions,AsyncStorage,Text,Image } from 'react-native';
import generalStyles from '../../../../styles/generalStyles';
import SweetFetcher from '../../../../classes/sweet-fetcher';
import Common from '../../../../classes/Common';
import AccessManager from '../../../../classes/AccessManager';
import Constants from '../../../../classes/Constants';
import TextRow from '../../../../sweet/components/TextRow';
import ComponentHelper from '../../../../classes/ComponentHelper';
import SimpleMap from '../../../../components/SimpleMap';
$ImportCodes
export default class  $FileName extends Component<{}> {
    $ClassFieldDefinitionCodes
    constructor(props) {
        super(props);
        this.state =
            {
                isLoading:false,
                LoadedData:{" . $StateVariableCodes . "
                },
                ";
        $C .= "
            };
        $ConstructorCodes
        this.loadData();
    }
    loadData=()=>{
        this.setState({isLoading:true});
        new SweetFetcher().Fetch('/$ModuleName/$FormName/'+global.itemID,SweetFetcher.METHOD_GET, null, data => {
            this.setState({LoadedData:data.Data,isLoading:false});
        });
$LoaderMethodCallCodes
    };
$LoaderMethodCodes
    render() {
        const {height: heightOfDeviceScreen} = Dimensions.get('window');
            return (
                <View style={{flex:1}}  >
                    <ScrollView contentContainerStyle={{minHeight: this.height || heightOfDeviceScreen}}>
                        <View style={generalStyles.container}>
                        $ViewCodes";

        $C .= "
                        </View>
                    </ScrollView>
                </View>
            )
    }
}
    ";
        $DesignFile = $this->getReactNativeCodeModuleDir() . "/modules/" . $ModuleName . "/pages/$FormName/" . $FileName . ".js";
        $this->SaveFile($DesignFile, $C);
    }

}

?>