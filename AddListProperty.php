<?
//Создание свойств списка (инфоблока) с отображением в публичной части

\Bitrix\Main\Loader::includeModule('lists');
$iBlockId = 45;
$feald = new CListFieldList($iBlockId);
$feald->AddField([
	'NAME' => 'test3',
	'TYPE' => 'S',
	/*
 	//Создание свойства/поля типа список
	'TYPE' => 'L',
	'LIST' => [
 		//что бы значение элемента типа список создалось, необходимо задать уникальные ключи массива, Bitrix по ключам проверяет наличие элемента по этому ID
		'l1' => [
			'VALUE' => 'Yes',
			'DEF' => 'N',
			'XML_ID' => 'Y',
			'SORT' => 10,
		],
		'l2' => [
			'VALUE' => 'No',
			'DEF' => 'N',
			'XML_ID' => 'N',
			'SORT' => 20,
		],
	],
	*/
	'SORT' => 50,
	'CODE' => 'TEST3',
	//'ID' => 'PROPERTY_222', //можно указать уже существующее свойство
	'SETTINGS' => [
		'SHOW_ADD_FORM' => 'Y',
		'SHOW_EDIT_FORM' =>'Y',
		'SHOW_FIELD_PREVIEW' => 'Y',
    'EDIT_READ_ONLY_FIELD' => 'N',
    'ADD_READ_ONLY_FIELD' => 'N',
	],
]);
