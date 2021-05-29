<?php

namespace backend\modules\archive\controllers;

use backend\modules\document\models\DocumentSearch;
use backend\modules\document\services\DocumentService;
use Yii;
use yii\web\Controller;

class ArchiveController extends Controller
{
    public function actionIndex()
    {
        $archieve = [
            'ДонНТУ' => [
                'ФКНТ' => [
                    'АСУ' => [
                        'Документация' => [
                            'Кафедры',
                            'Статьи' => [
                                'ИУСКМ' => [
                                    'ИУС',
                                    'ПИ',
                                    'КМ',
                                    'ИИ',
                                    'ВЕБ',
                                    'КС',
                                    'АСУ'
                                ]
                            ],
                            'Научно-методическая работа' => [

                            ],
                            'Дисциплины' => [
                                'Работы' => [
                                    'ВКР',
                                    'КР+КП',
                                    'Лб'
                                ]
                            ]
                        ],
                        'Преподаватели' => [
                            'Андриевская' => [
                                'Личная',
                                'Статьи' => [
                                    'ИУСКМ' => [
                                        'ИУС' => [
                                            'Контейниризация в микросервисах',
                                            'Иерархические СУБД',
                                            'Онтологический подход в поиске'
                                        ]
                                    ]
                                ],
                                'Кафедра' => [],
                                'Дисциплины' => [
                                    'ОБДЗ' => [
                                        'Документация',
                                        'Методички',
                                        'Лекции',
                                        'Материалы',
                                        'Работы' => [
                                            'ВКР',
                                            'КР+КП',
                                            'Лб'
                                        ]
                                    ],
                                    'СУБД' => [

                                    ]
                                ]
                            ]
                        ]
                    ],
                    'ИИСА' => [

                    ],
                    'ПИ' => [

                    ]
                ],
                'ФКИТА' => [

                ]
            ]
        ];

        $docService = new DocumentService();
        $outputArchieve = $docService->tree($archieve);

        return $this->render('index', ['archieve' => $outputArchieve]);
    }
}