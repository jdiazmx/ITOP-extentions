<?php
//
// File generated by ... on the 2016-01-19T12:23:21+0100
// Please do not edit manually
//

/**
 * Classes and menus for esguardian-role-mgmt (version 1.2.0)
 *
 * @author      iTop compiler
 * @license     http://opensource.org/licenses/AGPL-3.0
 */



class SecurityRoleTemplate extends SecurityCI
{
	public static function Init()
	{
		$aParams = array
		(
			'category' => 'bizmodel,searchable',
			'key_type' => 'autoincrement',
			'name_attcode' => 'name',
			'state_attcode' => '',
			'reconc_keys' => array('name', 'org_id', 'org_name'),
			'db_table' => 'securityroletemplate',
			'db_key_field' => 'id',
			'db_finalclass_field' => '',
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("roles_list", array("linked_class"=>'lnkSecurityRoleTemplateToSecurityRole', "ext_key_to_me"=>'securityroletemplate_id', "ext_key_to_remote"=>'securityrole_id', "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "duplicates"=>false, "depends_on"=>array(), "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("applyto_list", array("linked_class"=>'lnkApplySRTemplateToPerson', "ext_key_to_me"=>'securityroletemplate_id', "ext_key_to_remote"=>'person_id', "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "duplicates"=>false, "depends_on"=>array(), "always_load_in_tables"=>false)));



		MetaModel::Init_SetZListItems('details', array (
  0 => 'name',
  1 => 'org_id',
  2 => 'description',
  3 => 'managers_list',
  4 => 'roles_list',
  5 => 'applyto_list',
  6 => 'documents_list',
  7 => 'tickets_list',
));
		MetaModel::Init_SetZListItems('standard_search', array (
  0 => 'org_id',
  1 => 'name',
));
		MetaModel::Init_SetZListItems('list', array (
  0 => 'org_id',
  1 => 'name',
));

	}


	/**
                    * Placeholder for backward compatibility (iTop <= 2.1.0)
                    * in case an extension attempts to redefine this function...     
                    */
 
                    public function GetConflictingRoles()
                    {
                        $sRoleListAttCode = 'roles_list';
                        $oResultSet = DBObjectSet::FromScratch('lnkSecurityRoleBidirectional');
                        if (MetaModel::IsValidAttCode(get_class($this), $sRoleListAttCode))
                        {
                            $oAttDef = MetaModel::GetAttributeDef(get_class($this), $sRoleListAttCode);
                            $sLnkClass = $oAttDef->GetLinkedClass();
                            $sExtKeyToMe = $oAttDef->GetExtKeyToMe();
                            $sExtKeyToRemote = $oAttDef->GetExtKeyToRemote();
                            $me = $this->GetKey();
                            $oSearch = DBSearch::FromOQL("SELECT $sLnkClass WHERE $sExtKeyToMe = '$me'");
                            $oSet = new DBObjectSet($oSearch);
                            if ($oSet->Count() == 0) 
                            {
                                return $oResultSet;
                            }
                            $oSet->Seek(0);
                            $aRoleIDs = array();
                            while ($oObject = $oSet->Fetch())
                            {
                                $aRoleIDs[] = $oObject->Get($sExtKeyToRemote);
                            }
                            $aConflictingRolesPairs = array();
                            foreach ($aRoleIDs as $RoleID)
                            {
                                $oSearch = DBSearch::FromOQL("SELECT lnkSecurityRoleBidirectional WHERE left_securityrole_id = '$RoleID'");
                                $oSet = new DBObjectSet($oSearch);
                                if ($oSet->Count() != 0)
                                {
                                    $oSet->Seek(0);
                                    while($oObject = $oSet->Fetch())
                                    {
                                        $NewID = $oObject->Get('right_securityrole_id');
                                        if (in_array($NewID,$aRoleIDs))
                                        {
                                            $aNewPair = array($RoleID,$NewID);
                                            $aNewPairSym = array($NewID,$RoleID);
                                            if (!(in_array($aNewPair,$aConflictingRolesPairs) || in_array($aNewPairSym,$aConflictingRolesPairs)))
                                            {
                                                $aConflictingRolesPairs[] = $aNewPair;
                                                $oResultSet->AddObject($oObject);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        return $oResultSet; 
                    } 
                    


 
                    function DisplayBareRelations(WebPage $oPage, $bEditMode = false)
                    {
                        parent::DisplayBareRelations($oPage, $bEditMode);
                        try
                        {
                            $oSet = $this->GetConflictingRoles();
                            $iTotal = $oSet->Count();

                            $sCount = ($iTotal > 0) ? ' ('.$iTotal.')' : '';
                            $oPage->SetCurrentTab(Dict::S('Class:SecurityRoleTemplate/Tab:ConflictingRoles').$sCount);
                            $sBlockId = 'TEST';
                            $oPage->add('<fieldset>');
                            $oBlock = DisplayBlock::FromObjectSet($oSet, 'list');
                            $oBlock->Display($oPage, $sBlockId, array('menu' => false));
                            $oPage->add('</fieldset>');
                        }
                        catch (Exception $e)
		                {
                			throw $e;
		                }
                    }
                    

}


abstract class SecurityRole extends SecurityCI
{
	public static function Init()
	{
		$aParams = array
		(
			'category' => 'bizmodel,searchable',
			'key_type' => 'autoincrement',
			'name_attcode' => 'name',
			'state_attcode' => '',
			'reconc_keys' => array('name', 'org_id', 'org_name', 'finalclass'),
			'db_table' => 'securityrole',
			'db_key_field' => 'id',
			'db_finalclass_field' => 'finalclass',
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("occupants_list", array("linked_class"=>'lnkPersonToSecurityRole', "ext_key_to_me"=>'securityrole_id', "ext_key_to_remote"=>'person_id', "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "duplicates"=>false, "depends_on"=>array(), "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("conflictingroles_list", array("linked_class"=>'lnkSecurityRoleBidirectional', "ext_key_to_me"=>'left_securityrole_id', "ext_key_to_remote"=>'right_securityrole_id', "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "duplicates"=>false, "depends_on"=>array(), "always_load_in_tables"=>false)));



		MetaModel::Init_SetZListItems('details', array (
  0 => 'name',
  1 => 'org_id',
  2 => 'occupants_list',
  3 => 'documents_list',
));
		MetaModel::Init_SetZListItems('standard_search', array (
  0 => 'finalclass',
  1 => 'org_id',
  2 => 'name',
));
		MetaModel::Init_SetZListItems('list', array (
  0 => 'finalclass',
  1 => 'org_id',
  2 => 'name',
));

	}


}


class ApplicationRole extends SecurityRole
{
	public static function Init()
	{
		$aParams = array
		(
			'category' => 'bizmodel,searchable',
			'key_type' => 'autoincrement',
			'name_attcode' => 'name',
			'state_attcode' => '',
			'reconc_keys' => array('name', 'org_id', 'org_name'),
			'db_table' => 'applicationrole',
			'db_key_field' => 'id',
			'db_finalclass_field' => '',
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("applicationsolution_id", array("targetclass"=>'ApplicationSolution', "allowed_values"=>new ValueSetObjects("SELECT ApplicationSolution WHERE org_id = :this->org_id"), "sql"=>'applicationsolution_id', "is_null_allowed"=>true, "on_target_delete"=>DEL_AUTO, "depends_on"=>array('org_id'), "display_style"=>'select', "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeExternalField("applicationsolution_name", array("allowed_values"=>null, "extkey_attcode"=>'applicationsolution_id', "target_attcode"=>'name', "always_load_in_tables"=>false)));



		MetaModel::Init_SetZListItems('details', array (
  0 => 'name',
  1 => 'org_id',
  2 => 'applicationsolution_id',
  3 => 'description',
  4 => 'occupants_list',
  5 => 'conflictingroles_list',
  6 => 'documents_list',
  7 => 'tickets_list',
));
		MetaModel::Init_SetZListItems('standard_search', array (
  0 => 'org_id',
  1 => 'applicationsolution_id',
  2 => 'name',
));
		MetaModel::Init_SetZListItems('list', array (
  0 => 'org_id',
  1 => 'applicationsolution_id',
  2 => 'name',
));

	}


}


class BusinessRole extends SecurityRole
{
	public static function Init()
	{
		$aParams = array
		(
			'category' => 'bizmodel,searchable',
			'key_type' => 'autoincrement',
			'name_attcode' => 'name',
			'state_attcode' => '',
			'reconc_keys' => array('name', 'org_id', 'org_name'),
			'db_table' => 'businessrole',
			'db_key_field' => 'id',
			'db_finalclass_field' => '',
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("businessprocess_id", array("targetclass"=>'BusinessProcess', "allowed_values"=>new ValueSetObjects("SELECT BusinessProcess WHERE org_id = :this->org_id"), "sql"=>'businessprocess_id', "is_null_allowed"=>true, "on_target_delete"=>DEL_AUTO, "depends_on"=>array('org_id'), "display_style"=>'select', "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeExternalField("businessprocess_name", array("allowed_values"=>null, "extkey_attcode"=>'businessprocess_id', "target_attcode"=>'name', "always_load_in_tables"=>false)));



		MetaModel::Init_SetZListItems('details', array (
  0 => 'name',
  1 => 'org_id',
  2 => 'businessprocess_id',
  3 => 'description',
  4 => 'occupants_list',
  5 => 'conflictingroles_list',
  6 => 'documents_list',
  7 => 'tickets_list',
));
		MetaModel::Init_SetZListItems('standard_search', array (
  0 => 'org_id',
  1 => 'businessprocess_id',
  2 => 'name',
));
		MetaModel::Init_SetZListItems('list', array (
  0 => 'org_id',
  1 => 'businessprocess_id',
  2 => 'name',
));

	}


}


class lnkSecurityRoleTemplateToSecurityRole extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			'category' => 'bizmodel',
			'key_type' => 'autoincrement',
			'is_link' => true,
			'name_attcode' => array('securityroletemplate_id', 'securityrole_id'),
			'state_attcode' => '',
			'reconc_keys' => array('securityroletemplate_id', 'securityrole_id'),
			'db_table' => 'lnksecurityroletemplatetosecurityrole',
			'db_key_field' => 'id',
			'db_finalclass_field' => '',
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("securityroletemplate_id", array("targetclass"=>'SecurityRoleTemplate', "allowed_values"=>null, "sql"=>'securityroletemplate_id', "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array(), "display_style"=>'select', "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeExternalField("securityroletemplate_name", array("allowed_values"=>null, "extkey_attcode"=>'securityroletemplate_id', "target_attcode"=>'name', "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("securityrole_id", array("targetclass"=>'SecurityRole', "allowed_values"=>null, "sql"=>'securityrole_id', "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array(), "display_style"=>'select', "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeExternalField("securityrole_name", array("allowed_values"=>null, "extkey_attcode"=>'securityrole_id', "target_attcode"=>'name', "always_load_in_tables"=>false)));



		MetaModel::Init_SetZListItems('details', array (
  0 => 'securityroletemplate_id',
  1 => 'securityrole_id',
));
		MetaModel::Init_SetZListItems('standard_search', array (
  0 => 'securityroletemplate_id',
  1 => 'securityrole_id',
));
		MetaModel::Init_SetZListItems('list', array (
  0 => 'securityroletemplate_id',
  1 => 'securityrole_id',
));

	}


	/**
                    * Placeholder for backward compatibility (iTop <= 2.1.0)
                    * in case an extension attempts to redefine this function...     
                    */
 
                    private function GetLinkedPersonIDs()
                    {
                        $aResult = array();
                        $TemlateID = $this->Get('securityroletemplate_id');
                        $oSearch = DBSearch::FromOQL("SELECT lnkApplySRTemplateToPerson WHERE securityroletemplate_id = '$TemlateID'");
                        $oSet = new DBObjectSet($oSearch);
                        if ($oSet->Count() == 0) {return $aResult;}
                        $oSet->Seek(0);
                        while ($oObject = $oSet->Fetch())
                        {
                            $aResult[] = $oObject->Get('person_id');
                        }                          
                        return $aResult;
                    } 
                    

	/**
                    * Placeholder for backward compatibility (iTop <= 2.1.0)
                    * in case an extension attempts to redefine this function...     
                    */
 
                    private function GetPersonRoleIDs($PersonID)
                    {
                        $aResult = array();
                        $oSearch = DBSearch::FromOQL("SELECT lnkPersonToSecurityRole WHERE person_id = '$PersonID'");
                        $oSet = new DBObjectSet($oSearch);
                        if ($oSet->Count() == 0) {return $aResult;}
                        $oSet->Seek(0);
                        while ($oObject = $oSet->Fetch())
                        {
                            $aResult[] = $oObject->Get('securityrole_id');
                        }                          
                        return $aResult;
                    } 
                    

	/**
                    * Placeholder for backward compatibility (iTop <= 2.1.0)
                    * in case an extension attempts to redefine this function...     
                    */
 
                    private function AddNewRoleForPerson($PersonID,$RoleID)
                    {
                        $NewPersonToSecurityRole = MetaModel::NewObject('lnkPersonToSecurityRole');
                        $NewPersonToSecurityRole->Set('person_id',$PersonID);
                        $NewPersonToSecurityRole->Set('securityrole_id',$RoleID);
                        $NewPersonToSecurityRole->DBInsert();                              
                        return;
                    } 
                    

	/**
                    * Placeholder for backward compatibility (iTop <= 2.1.0)
                    * in case an extension attempts to redefine this function...     
                    */
 
                    public function DBInsert()
                    {
                        $ret = parent::DBInsert();
                        $ThisRoleID = $this->Get('securityrole_id');
                        $aPersonIDs = $this->GetLinkedPersonIDs();
                        foreach ($aPersonIDs as $PersonID)
                        {
                            $aPersonRoleIDs = GetPersonRoleIDs($PersonID);
                            if (!in_array($ThisRoleID,$aPersonRoleIDs))
                            {
                                $this->AddNewRoleForPerson($PersonID,$ThisRoleID);
                            }
                        }                           
                        return $ret;
                    } 
                    

	/**
                    * Placeholder for backward compatibility (iTop <= 2.1.0)
                    * in case an extension attempts to redefine this function...     
                    */
 
                    private function GetPersonOtherTemlatesRoleIDs($PersonID)
                    {
                        $aResult = array();
                        $aTemlateIDs = array();
                        $TemplateID = $this->Get('securityroletemplate_id');
                        $oSearch = DBSearch::FromOQL("SELECT lnkApplySRTemplateToPerson WHERE person_id = '$PersonID' AND securityroletemplate_id != '$TemplateID'");
                        $oSet = new DBObjectSet($oSearch);
                        if ($oSet->Count() == 0) {return $aResult;}
                        $oSet->Seek(0);
                        while ($oObject = $oSet->Fetch())
                        {
                            $aTemlateIDs[] = $oObject->Get('securityroletemplate_id');
                        }
                        foreach ($aTemlateIDs as $TemplateID)
                        {
                            $oSearch = DBSearch::FromOQL("SELECT lnkSecurityRoleTemplateToSecurityRole WHERE securityroletemplate_id = '$TemplateID'");
                            $oSet = new DBObjectSet($oSearch);
                            $oSet->Seek(0);
                            while ($oObject = $oSet->Fetch())
                            {
                                $NewRoleID = $oObject->Get('securityrole_id');
                                if (!in_array($NewRoleID,$aResult))
                                {
                                    $aResult[] = $NewRoleID;
                                }
                            }
                        }                         
                        return $aResult;
                    } 
                    

	/**
                    * Placeholder for backward compatibility (iTop <= 2.1.0)
                    * in case an extension attempts to redefine this function...     
                    */
 
                    private function RemoveRoleFromPerson($PersonID,$RoleID)
                    {
                        $oObjectSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT lnkPersonToSecurityRole WHERE person_id='$PersonID' AND  securityrole_id='$RoleID'"));
                        $oObjectSet->Seek(0);
                        while ($oObject = $oObjectSet->Fetch())
                        {
                            $oObject->DBDeleteSingleObject();
                        }
                        return;
                    } 
                    

	/**
                    * Placeholder for backward compatibility (iTop <= 2.1.0)
                    * in case an extension attempts to redefine this function...     
                    */
 
                    protected function AfterDelete()
                    {
                        $ThisRoleID = $this->Get('securityrole_id');
                        $aPersonIDs = $this->GetLinkedPersonIDs();
                        foreach ($aPersonIDs as $PersonID)
                        {
                            $aOtherTemlatesRoleIDs = $this->GetPersonOtherTemlatesRoleIDs($PersonID);
                            if (!in_array($ThisRoleID,$aOtherTemlatesRoleIDs))
                            {
                                $this->RemoveRoleFromPerson($PersonID,$ThisRoleID);
                            }
                        } 

                        return;
                    } 
                    

}


class lnkApplySRTemplateToPerson extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			'category' => 'bizmodel',
			'key_type' => 'autoincrement',
			'is_link' => true,
			'name_attcode' => array('securityroletemplate_id', 'person_id'),
			'state_attcode' => '',
			'reconc_keys' => array('securityroletemplate_id', 'person_id'),
			'db_table' => 'lnkapplysrtemplatetoperson',
			'db_key_field' => 'id',
			'db_finalclass_field' => '',
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("securityroletemplate_id", array("targetclass"=>'SecurityRoleTemplate', "allowed_values"=>null, "sql"=>'securityroletemplate_id', "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array(), "display_style"=>'select', "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeExternalField("securityroletemplate_name", array("allowed_values"=>null, "extkey_attcode"=>'securityroletemplate_id', "target_attcode"=>'name', "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("person_id", array("targetclass"=>'Person', "allowed_values"=>null, "sql"=>'person_id', "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array(), "display_style"=>'select', "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeExternalField("person_name", array("allowed_values"=>null, "extkey_attcode"=>'person_id', "target_attcode"=>'name', "always_load_in_tables"=>false)));



		MetaModel::Init_SetZListItems('details', array (
  0 => 'securityroletemplate_id',
  1 => 'person_id',
));
		MetaModel::Init_SetZListItems('standard_search', array (
  0 => 'securityroletemplate_id',
  1 => 'person_id',
));
		MetaModel::Init_SetZListItems('list', array (
  0 => 'securityroletemplate_id',
  1 => 'person_id',
));

	}


	/**
                    * Placeholder for backward compatibility (iTop <= 2.1.0)
                    * in case an extension attempts to redefine this function...     
                    */
 
                    private function GetLinkedPersonSecurityRoleIDs()
                    {
                        $aResult = array();
                        $PersonID = $this->Get('person_id');
                        $oSearch = DBSearch::FromOQL("SELECT lnkPersonToSecurityRole WHERE person_id = '$PersonID'");
                        $oSet = new DBObjectSet($oSearch);
                        if ($oSet->Count() == 0) {return $aResult;}
                        $oSet->Seek(0);
                        while ($oObject = $oSet->Fetch())
                        {
                            $aResult[] = $oObject->Get('securityrole_id');
                        }                          
                        return $aResult;
                    } 
                    

	/**
                    * Placeholder for backward compatibility (iTop <= 2.1.0)
                    * in case an extension attempts to redefine this function...     
                    */
 
                    private function GetTemplateSecurityRoleIDs()
                    {
                        $aResult = array();
                        $TemplateID = $this->Get('securityroletemplate_id');
                        $oSearch = DBSearch::FromOQL("SELECT lnkSecurityRoleTemplateToSecurityRole WHERE securityroletemplate_id = '$TemplateID'");
                        $oSet = new DBObjectSet($oSearch);
                        if ($oSet->Count() == 0) {return $aResult;}
                        $oSet->Seek(0);
                        while ($oObject = $oSet->Fetch())
                        {
                            $aResult[] = $oObject->Get('securityrole_id');
                        }                          
                        return $aResult;
                    } 
                    

	/**
                    * Placeholder for backward compatibility (iTop <= 2.1.0)
                    * in case an extension attempts to redefine this function...     
                    */
 
                    private function AddNewRoleForLinkedPerson($RoleID)
                    {
                        $NewPersonToSecurityRole = MetaModel::NewObject('lnkPersonToSecurityRole');
                        $NewPersonToSecurityRole->Set('person_id',$this->Get('person_id'));
                        $NewPersonToSecurityRole->Set('securityrole_id',$RoleID);
                        $NewPersonToSecurityRole->DBInsert();                              
                        return;
                    } 
                    

	/**
                    * Placeholder for backward compatibility (iTop <= 2.1.0)
                    * in case an extension attempts to redefine this function...     
                    */
 
                    private function RemoveRoleFromLinkedPerson($RoleID)
                    {
                        $LinkedPersonID = $this->Get('person_id');
                        $oObjectSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT lnkPersonToSecurityRole WHERE person_id='$LinkedPersonID' AND  securityrole_id='$RoleID'"));
                        $oObjectSet->Seek(0);
                        while ($oObject = $oObjectSet->Fetch())
                        {
                            $oObject->DBDeleteSingleObject();
                        }
                        return;
                    } 
                    

	/**
                    * Placeholder for backward compatibility (iTop <= 2.1.0)
                    * in case an extension attempts to redefine this function...     
                    */
 
                    public function DBInsert()
                    {
                        $ret = parent::DBInsert();
                        $aPersonRoles = $this->GetLinkedPersonSecurityRoleIDs();
                        $aTemlateRoles = $this->GetTemplateSecurityRoleIDs();
                        foreach ($aTemlateRoles as $RoleID)
                        {
                            if (!in_array($RoleID,$aPersonRoles))
                            {
                                $this->AddNewRoleForLinkedPerson($RoleID);
                            }
                        }                           
                        return $ret;
                    } 
                    

	/**
                    * Placeholder for backward compatibility (iTop <= 2.1.0)
                    * in case an extension attempts to redefine this function...     
                    */
 
                    private function GetLinkedPersonOtherTemlatesRoleIDs()
                    {
                        $aResult = array();
                        $aTemlateIDs = array();
                        $PersonID = $this->Get('person_id');
                        $TemplateID = $this->Get('securityroletemplate_id');
                            
                        $oSearch = DBSearch::FromOQL("SELECT lnkApplySRTemplateToPerson WHERE person_id = '$PersonID' AND securityroletemplate_id != '$TemplateID'");
                        $oSet = new DBObjectSet($oSearch);
                        if ($oSet->Count() == 0) {return $aResult;}
                        $oSet->Seek(0);
                        while ($oObject = $oSet->Fetch())
                        {
                            $aTemlateIDs[] = $oObject->Get('securityroletemplate_id');
                        }
                        foreach ($aTemlateIDs as $TemplateID)
                        {
                            $oSearch = DBSearch::FromOQL("SELECT lnkSecurityRoleTemplateToSecurityRole WHERE securityroletemplate_id = '$TemplateID'");
                            $oSet = new DBObjectSet($oSearch);
                            $oSet->Seek(0);
                            while ($oObject = $oSet->Fetch())
                            {
                                $NewRoleID = $oObject->Get('securityrole_id');
                                if (!in_array($NewRoleID,$aResult))
                                {
                                    $aResult[] = $NewRoleID;
                                }
                            }
                        }                         
                        return $aResult;
                    } 
                    

	/**
                    * Placeholder for backward compatibility (iTop <= 2.1.0)
                    * in case an extension attempts to redefine this function...     
                    */
 
                    protected function AfterDelete()
                    {
                        $aPersonRoles = $this->GetLinkedPersonSecurityRoleIDs();
                        $aTemlateRoles = $this->GetTemplateSecurityRoleIDs();
                        $aOtherTemlatesRoles = $this->GetLinkedPersonOtherTemlatesRoleIDs();
                        foreach ($aPersonRoles as $RoleID)
                        {
                            if ((in_array($RoleID,$aTemlateRoles)) && (!in_array($RoleID,$aOtherTemlatesRoles)))
                            {
                               $this->RemoveRoleFromLinkedPerson($RoleID);
                            }
                        } 
                        return;
                    } 
                    

}


class lnkPersonToSecurityRole extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			'category' => 'bizmodel',
			'key_type' => 'autoincrement',
			'is_link' => true,
			'name_attcode' => array('securityrole_id', 'person_id'),
			'state_attcode' => '',
			'reconc_keys' => array('securityrole_id', 'person_id'),
			'db_table' => 'lnkpersontosecurityrole',
			'db_key_field' => 'id',
			'db_finalclass_field' => '',
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("securityrole_id", array("targetclass"=>'SecurityRole', "allowed_values"=>null, "sql"=>'securityrole_id', "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array(), "display_style"=>'select', "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeExternalField("securityrole_name", array("allowed_values"=>null, "extkey_attcode"=>'securityrole_id', "target_attcode"=>'name', "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("person_id", array("targetclass"=>'Person', "allowed_values"=>null, "sql"=>'person_id', "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array(), "display_style"=>'select', "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeExternalField("person_name", array("allowed_values"=>null, "extkey_attcode"=>'person_id', "target_attcode"=>'name', "always_load_in_tables"=>false)));



		MetaModel::Init_SetZListItems('details', array (
  0 => 'securityrole_id',
  1 => 'person_id',
));
		MetaModel::Init_SetZListItems('standard_search', array (
  0 => 'securityrole_id',
  1 => 'person_id',
));
		MetaModel::Init_SetZListItems('list', array (
  0 => 'securityrole_id',
  1 => 'person_id',
));

	}


}


class lnkSecurityRoleBidirectional extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			'category' => 'bizmodel',
			'key_type' => 'autoincrement',
			'is_link' => true,
			'name_attcode' => array('left_securityrole_id', 'right_securityrole_id'),
			'state_attcode' => '',
			'reconc_keys' => array('left_securityrole_id', 'right_securityrole_id'),
			'db_table' => 'lnksecurityrolebidirectional',
			'db_key_field' => 'id',
			'db_finalclass_field' => '',
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("left_securityrole_id", array("targetclass"=>'SecurityRole', "allowed_values"=>null, "sql"=>'left_securityrole_id', "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array(), "display_style"=>'select', "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeExternalField("left_securityrole_name", array("allowed_values"=>null, "extkey_attcode"=>'left_securityrole_id', "target_attcode"=>'name', "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("right_securityrole_id", array("targetclass"=>'SecurityRole', "allowed_values"=>null, "sql"=>'right_securityrole_id', "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array(), "display_style"=>'select', "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeExternalField("right_securityrole_name", array("allowed_values"=>null, "extkey_attcode"=>'right_securityrole_id', "target_attcode"=>'name', "always_load_in_tables"=>false)));



		MetaModel::Init_SetZListItems('details', array (
  0 => 'left_securityrole_id',
  1 => 'right_securityrole_id',
));
		MetaModel::Init_SetZListItems('standard_search', array (
  0 => 'left_securityrole_id',
  1 => 'right_securityrole_id',
));
		MetaModel::Init_SetZListItems('list', array (
  0 => 'left_securityrole_id',
  1 => 'right_securityrole_id',
));

	}


	/**
                    * Placeholder for backward compatibility (iTop <= 2.1.0)
                    * in case an extension attempts to redefine this function...     
                    */
 
                    public function DBInsert()
                    {
                        $ret = parent::DBInsert();
                        $oMyClone = clone $this;
                        $mem_left = $oMyClone->Get('left_securityrole_id');
                        $mem_right = $oMyClone->Get('right_securityrole_id');
                        $oMyClone->Set('left_securityrole_id',$mem_right);
                        $oMyClone->Set('right_securityrole_id',$mem_left);
                        $oMyClone->DBInsertNoReload();
                        $oMyClone->Reload();
                        return $ret;
                    } 
                    

	/**
                    * Placeholder for backward compatibility (iTop <= 2.1.0)
                    * in case an extension attempts to redefine this function...     
                    */
 
                    protected function AfterDelete()
                    {
                        $mem_left = $this->Get('right_securityrole_id');
                        $mem_right = $this->Get('left_securityrole_id');
                        $oObjectSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT lnkSecurityRoleBidirectional WHERE left_securityrole_id='$mem_left' AND  right_securityrole_id='$mem_right'"));
                        $oObjectSet->Seek(0);
                        while ($oObject = $oObjectSet->Fetch())
                        {
                            $oObject->DBDeleteSingleObject();
                        }
                        return;
                    } 
                    

}
//
// Menus
//
class MenuCreation_esguardian_role_mgmt extends ModuleHandlerAPI
{
	public static function OnMenuCreation()
	{
		global $__comp_menus__; // ensure that the global variable is indeed global !
		$__comp_menus__['SecurityConfigManagement'] = new MenuGroup('SecurityConfigManagement', 21);
		$__comp_menus__['NewBusinessRole'] = new NewObjectMenuNode('NewBusinessRole', 'BusinessRole', $__comp_menus__['SecurityConfigManagement']->GetIndex(), 3);
		$__comp_menus__['NewApplicationRole'] = new NewObjectMenuNode('NewApplicationRole', 'ApplicationRole', $__comp_menus__['SecurityConfigManagement']->GetIndex(), 4);
		$__comp_menus__['NewSecurityRoleTemplate'] = new NewObjectMenuNode('NewSecurityRoleTemplate', 'SecurityRoleTemplate', $__comp_menus__['SecurityConfigManagement']->GetIndex(), 5);
	}
} // class MenuCreation_esguardian_role_mgmt