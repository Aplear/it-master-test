<?php

use jino5577\daterangepicker\DateRangePicker; // add widget
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchMail */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Mails');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mail-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Mail'), ['create'], ['class' => 'modal-btn-create btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'InBox'), ['in-box'], ['class' => 'modal-btn-inbox btn btn-primary']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'user_id',
            //'from',
            'to',
            //'subject',
             'text:ntext',
            [
                // the attribute
                'attribute' => 'created_at',
                // format the value
                'value' => function ($model) {
                    if (extension_loaded('intl')) {
                        return Yii::t('app', '{0, date, MMMM dd, YYYY HH:mm}', [$model->created_at]);
                    } else {
                        return date('Y-m-d G:i:s', $model->created_at);
                    }
                },
                // some styling?
                'headerOptions' => [
                    'class' => 'col-md-2'
                ],
                // here we render the widget
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at_range',
                    'pluginOptions' => [
                        'format' => 'd-m-Y',
                        'autoUpdateInput' => false
                    ]
                ])
            ],
             //'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}'
            ],
        ],
    ]); ?>
</div>

​
<?php
yii\bootstrap\Modal::begin([
    'header' => '',
    'id' => 'modal',
    'size' => 'modal-md',
]);
?>
<div id='modal-content'>Loading...</div>
<?php yii\bootstrap\Modal::end(); ?>
<?php
//create handler
$this->registerJs("

$('.modal-btn-create').click(function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    var modal_body = $('.modal-body');
    $.get(url, function(data) {
        if(data === false) {
            var link = window.location.href;
            window.location.replace(link);
            return false;
        }
        // apply mail model window
        $('#modal').modal('show')
            .find('#modal-content')
            .load($(this).attr('data-target'));
        //insert form into modal body
            modal_body.html(data);
    });
    return false;
});
",\yii\web\View::POS_END);
?>
