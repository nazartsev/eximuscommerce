<?php

	/** Display installed modules list **/

	$this->pageHeader = Yii::t('CoreModule.core', 'Модули');

    $this->breadcrumbs = array(
        'Home'=>$this->createUrl('/admin'),
        Yii::t('CoreModule.core', 'Модули'),
    );

    $this->topButtons = $this->widget('application.modules.admin.widgets.SAdminTopButtons', array(
        'template'=>array('new'),
        'elements'=>array(
            'new'=>array(
                'link'=>$this->createUrl('install'),
                'title'=>Yii::t('CoreModule.core', 'Установить'),
                'icon'=>'plus',
            ),
        ),
    ));

    $this->widget('ext.sgridview.SGridView', array(
        'dataProvider'=>$model->search(),
        'id'=>'modulesListGrid',
        'filter'=>$model,
        'extended'=>false,
        'columns'=>array(
            array(
                'name'=>'name',
                'value'=>'$data->getInfo()->name',
                'filter'=>false,
            ),
            array(
                'name'=>'description',
                'value'=>'$data->getInfo()->description',
                'header'=>Yii::t('CoreModule.core', 'Описание'),
                'filter'=>false,
            ),
            array(
            	'name'=>'enabled',
            	'value'=>strtr('$data->enabled ? "{yes}":"{no}"', array(
                    '{yes}'=>Yii::t('CoreModule.core', 'Да'),
                    '{no}'=>Yii::t('CoreModule.core', 'Нет')
                )),
            	'filter'=>array(1=>Yii::t('CoreModule.core', 'Да'),0=>Yii::t('CoreModule.core', 'Нет')),
	        ), 
	        // Buttons
            array(
                'class'=>'CButtonColumn',
                'template'=>'{update}{delete}',
            ),
        ),
    ));