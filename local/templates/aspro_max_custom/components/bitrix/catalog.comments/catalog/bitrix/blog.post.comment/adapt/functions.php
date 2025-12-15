<?
function createField($entityId, $fieldName, $fieldType = 'string') {
	$arUserField = CUserTypeEntity::GetList([], ["ENTITY_ID" => $entityId, "FIELD_NAME" => $fieldName])->Fetch();
	if(!$arUserField)
	{
		$arFields = [
			"FIELD_NAME" => $fieldName,
			"ENTITY_ID" => $entityId,
			"USER_TYPE_ID" => $fieldType,
			"XML_ID" => $fieldName,
			"SORT" => 100,
			"MULTIPLE" => "N",
			"MANDATORY" => "N",
			"SHOW_FILTER" => "I",
			"SHOW_IN_LIST" => "Y",
			"EDIT_IN_LIST" => "Y",
			"IS_SEARCHABLE" => "N",
		];
		$ob = new CUserTypeEntity();
		return $ob->Add($arFields);
	} else {
		return false;
	}
}
?>