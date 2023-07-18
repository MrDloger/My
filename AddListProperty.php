<?
//Создание свойств списка (инфоблока) с отображением в публичной части

\Bitrix\Main\Loader::includeModule('lists');
$iBlockId = 45;
$feald = new CListFieldList($iBlockId);
$feald->AddField([
	'NAME' => 'test3',
	'TYPE' => 'S',
	'SORT' => 50,
	'CODE' => 'TEST3',
	//'ID' => 'PROPERTY_222',
	'SETTINGS' => [
		'SHOW_ADD_FORM' => 'Y',
		'SHOW_EDIT_FORM' =>'Y',
		'SHOW_FIELD_PREVIEW' => 'Y',
    'EDIT_READ_ONLY_FIELD' => 'N',
    'ADD_READ_ONLY_FIELD' => 'N',
	],
]);
