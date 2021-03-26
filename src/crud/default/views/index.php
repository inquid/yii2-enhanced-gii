<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator \inquid\enhancedgii\crud\Generator */
/* @var $tableCommentName */
$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
$tableSchema = $generator->getTableSchema();
$baseModelClass = StringHelper::basename($generator->modelClass);
$fk = $generator->generateFK($tableSchema);
echo "<?php\n";
?>

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use <?= $generator->indexWidgetType === 'grid' ? "kartik\\grid\\GridView;" : "yii\\widgets\\ListView;" ?>


$this->title = "<?php if($generator->useTableComment){ echo $tableCommentName;  } else{ echo ($generator->pluralize) ? $generator->generateString(Inflector::pluralize(Inflector::camel2words($baseModelClass))) : $generator->generateString(Inflector::camel2words($baseModelClass)); }?>";
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
?>
<div class="<?= Inflector::camel2id($baseModelClass) ?>-index">

    <h1><?= "<?= " ?>Html::encode($this->title) ?></h1>
<?php if (!empty($generator->searchModelClass)): ?>
<?= "    <?php " . ($generator->indexWidgetType === 'grid' ? "// " : "") ?>echo $this->render('_search', ['model' => $searchModel]); ?>
<?php endif; ?>

    <p>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('Agregar ' . Inflector::camel2words($baseModelClass)) ?>, ['create'], ['class' => 'btn btn-success']) ?>
<?php if (!empty($generator->searchModelClass)): ?>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('Busqueda Avanzada')?>, '#', ['class' => 'btn btn-info search-button']) ?>
<?php endif; ?>
<?php if (!empty($generator->importExcel)): ?>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('Importar desde Excel ') ?>, ['import'], ['class' => 'btn btn-danger']) ?>
<?php endif; ?>
    </p>
<?php if (!empty($generator->searchModelClass)): ?>
    <div class="search-form" style="display:none">
        <?= "<?= " ?> $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    <?php endif; ?>
<?php
if ($generator->indexWidgetType === 'grid'):
?>
<?= "<?php \n" ?>
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'class' => 'yii\grid\CheckboxColumn', 'checkboxOptions' => function ($model) {
            return [
                'value' => $model-><?= $tableSchema->primaryKey[0] ?>,
                'contentOptions' => [
                    'class' => 'short_row'
                ]
            ];
        },
        ],
<?php
    if ($generator->expandable && !empty($fk)):
?>
        [
            'class' => 'kartik\grid\ExpandRowColumn',
            'width' => '50px',
            'value' => function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail' => function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('_expand', ['model' => $model]);
            },
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'expandOneOnly' => true
        ],
<?php
    endif;
?>
<?php
    if ($tableSchema === false) :
        foreach ($generator->getColumnNames() as $name) {
            if (++$count < 6) {
                echo "            '" . $name . "',\n";
            } else {
                echo "            // '" . $name . "',\n";
            }
        }
    else :
        foreach ($tableSchema->getColumnNames() as $attribute):
            if (!in_array($attribute, $generator->skippedColumns)) :
?>
        <?= $generator->generateGridViewFieldIndex($attribute, $fk, $tableSchema)?>
<?php
            endif;
        endforeach; ?>
        [
            'class' => 'kartik\grid\ActionColumn',
<?php if($generator->saveAsNew): ?>
            'template' => '{save-as-new} {view} {update} {delete}',
            'buttons' => [
                'save-as-new' => function ($url) {
                    return Html::a('<span class="glyphicon glyphicon-copy"></span>', $url, ['title' => 'Save As New']);
                },
            ],
<?php endif; ?>
        ],
    ];
<?php
    endif;
?>
    ?>
    <?= "<?= " ?>GridView::widget([
        'dataProvider' => $dataProvider,
        <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n        'columns' => \$gridColumn,\n" : "'columns' => \$gridColumn,\n"; ?>
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-<?= Inflector::camel2id(StringHelper::basename($generator->modelClass))?>']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
        ],
        'rowOptions' => function ($model) {
            if (Yii::$app->request->get('id') == $model-><?= $tableSchema->primaryKey[0] ?>) {
                return ['class' => 'short_row', 'style' => 'background-color:#dff0d8;'];
            }
            return [];
        },
    ]); ?>
<?php
else:
?>
    <?= "<?= " ?>ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return $this->render('_index',['model' => $model, 'key' => $key, 'index' => $index, 'widget' => $widget, 'view' => $this]);
        },
    ]) ?>
<?php
endif;
?>

</div>
