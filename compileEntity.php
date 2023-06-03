<?
/*
Кусок кода необходимый для присоединения таблицы свойст, если свойства хранятся в отдельной таблице
*/
\Bitrix\Main\Loader::includeModule('iblock');
$iblockId = 3;
$entityProps = \Bitrix\Main\Entity\Base::compileEntity(
	sprintf('IB_%s', $iblockId), //название скомпилированной сущности, $iblockId - айди инфоблока
	array(
		'IBLOCK_ELEMENT_ID' => ['data_type' => 'integer'],
		'PROPERTY_67' => ['data_type' => 'integer'], //Здесь 22 - айди нужного нам свойства. Если нужно больше свойств - перечисляем через запятую, не забывая указывать правильный тип свойства.
	),
	array(
		'table_name' => sprintf('b_iblock_element_prop_s%s', $iblockId), //название таблицы со свойствами. Такое название таблиц используется, если свойства инфоблока хранятся в отдельной таблице (проверьте эту галочку в настройках инфоблока)
	)
);
$arRes = \Bitrix\Iblock\Elements\ElementCrmCompanyTable::getList([
	'filter' => [
		//'ID' => $id
	],
	'select' => [
		'ID',
		'NAME',
		'ACTIVE',
		'PROP.*',
	],
	'runtime' => [
		'PROP' => [
			'data_type' => $entityProps,
			'reference' => ['this.ID' => 'ref.IBLOCK_ELEMENT_ID'],
			'join_type' => 'LEFT'
		]
	],
])->fetchAll();
print_r($arRes);
