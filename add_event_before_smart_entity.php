<?php
use Bitrix\Main\Error;
use Bitrix\Main\Result;
use Bitrix\Crm\Item;
use Bitrix\Crm\Service;
use Bitrix\Crm\Service\Operation;
use Bitrix\Main\DI;

\Bitrix\Main\Loader::includeModule('crm');

$entityProjectID  = Bitrix\Crm\Model\Dynamic\TypeTable::getList([
    'filter' => [
        'CODE' => ['PROEKT'],
    ],
    'select' => ['ENTITY_TYPE_ID'],
    'cache' => ['ttl' => 3600],
])->fetch()['ENTITY_TYPE_ID'];

$IdUF = \Bitrix\Main\UserFieldTable::getList([
        'filter' => [
            'FIELD_NAME' => ['UF_CRM_3_1693426294470'],
        ],
		'select' => ['ID'],
        'cache' => ['ttl' => 3600],
])->fetch()['ID'];
$res = CUserFieldEnum::GetList([], [
	'USER_FIELD_ID' => $IdUF,
	'VALUE' => ['Почасовая ставка без кэпа', 'Почасовая ставка с кэпом'],
]);

while($enum = $res->fetch()){
	$arEnum[$enum['ID']] = $enum['ID'];
	if ($enum['VALUE'] === 'Почасовая ставка с кэпом') $rateKap = $enum['ID'];
}

//переопределим контейнер, что бы была возможность переопределить любой метод контейнера
$container = new class extends Service\Container {
    //переопределим фабрику, установив проверку работы нашего кода, только для необходимого бизнес процесса
    public function getFactory(int $entityTypeId): ?Service\Factory
    {
    	global $entityProjectID;
        if ($entityTypeId == $entityProjectID)
        {
            //получим orm-объект смарт-процесса по его $entityTypeId
            $type = $this->getTypeByEntityTypeId($entityTypeId);
            //подменим фабрику
            $factory = new class($type) extends Service\Factory\Dynamic {
                //переопределим метод
                public function getUpdateOperation($item, $context = null): Operation\Update
                {
                    //получим операции сущности
                    $operation = parent::getUpdateOperation($item, $context);
                    //добавим дополнительное действие, перед сохранением элемента
                    return $operation->addAction(
                        Operation::ACTION_BEFORE_SAVE,
                        new class extends Operation\Action {
                            public function process(Item $item): Result
                            {
                                $result = new Result();

                                global $arEnum, $rateKap;
                                 //добавим условия, по которым получим ошибку
                                if (!$item->get('UF_CRM_3_1693426378854') AND $arEnum[$item->get('UF_CRM_3_1693426294470')]) {
                                	$result->addError(new Error('Поле Ставка обязательно для заполнения при выбранном значении «Почасовая ставка» в поле «Условия оплаты»'));
                                }
                                if (!$item->get('UF_CRM_3_1693426389164') AND $item->get('UF_CRM_3_1693426294470') == $rateKap) $result->addError(new Error('Поле Кэп обязательно для заполнения при выбранном значении «Почасовая ставка с кэпом» в поле «Условия оплаты»'));
 
                                return $result;
                            }
                        }
                    );
                }
            };
            return $factory;
        }
        return parent::getFactory($entityTypeId);
    }
};
//подменяем преопределенный контейнер
DI\ServiceLocator::getInstance()->addInstance('crm.service.container', $container);
