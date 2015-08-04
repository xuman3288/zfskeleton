<?php

namespace Test\Rpc;


/**
 * Class Menus
 * @package Test\Rpc
 * @author Xuman
 * @version $Id$
 */
class Menus 
{
    function formConfig()
    {
        return [
            [
                'type'   => 'kendoDropDownList',
                'label'  => '渠道',
                'name'   => 'channel',
                'config' => [
                    'dataTextField'  => 'name',
                    'dataValueField' => 'id',
                    'dataSource'     => [
                        ['name' => "安卓混服", 'id' => 1],
                        ['name' => "IOS官服", 'id' => 2],
                        ['name' => "腾讯", 'id' => 3]
                    ]
                ]
            ],
            [
                'type'   => 'kendoDropDownList',
                'label'  => '游戏',
                'name'   => 'game',
                'config' => [
                    'dataTextField'  => 'name',
                    'dataValueField' => 'id',
                    'dataSource'     => [
                        ['name' => "武极天下", 'id' => 1],
                        ['name' => "征途", 'id' => 2],
                        ['name' => "黑猫警长", 'id' => 3]
                    ]
                ]
            ],
            [
                'type'   => 'text',
                'label'  => '状态',
                'name'   => 'state',
                'config' => [

                ]
            ],
            [
                'type'   => 'kendoDateTimePicker',
                'label'  => '更新时间',
                'name'   => 'datetime',
                'config' => [
                    'format' => "yyyy/MM/dd HH:mm:ss"
                ]
            ],

        ];
    }
} 