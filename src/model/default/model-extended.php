<?php
/**
 * This is the template for generating the model class of a specified table.
 *
 * @var yii\web\View
 * @var inquid\enhancedgii\model\Generator $generator
 * @var string                             $tableName full table name
 * @var string                             $className class name
 * @var yii\db\TableSchema                 $tableSchema
 * @var string[]                           $labels list of attribute labels (name => label)
 * @var string[]                           $rules list of validation rules
 * @var array                              $relations list of relations (name => relation declaration)
 */
echo "<?php\n";
?>

namespace <?= $generator->nsModel ?>;

use Yii;
use \<?= $generator->nsModel ?>\base\<?= $className ?> as Base<?= $className ?>;
use \<?= $generator->nsComponent ?>\<?= $className ?>Component;

/**
* This is the model class for table "<?= $tableName ?>".
*/
class <?= $className ?> extends Base<?= $className."\n" ?>
{
<?php if ($generator->excelImport): ?>
    public $fileExcelImport;
<?php endif; ?>
<?php foreach ($generator->tableSchema->columns as $column) {
    if ($generator->containsAnnotation($column, '@file')) {
        echo 'public $'.$column->name."File;\n";
    } elseif ($generator->containsAnnotation($column, '@image')) {
        echo 'public $'.$column->name."Image;\n";
    }
} ?>
<?php if ($generator->generateAttributeHints): ?>
    /**
    * @inheritdoc
    */
    public function attributeHints()
    {
    return [
    <?php foreach ($labels as $name => $label): ?>
        <?php if (!in_array($name, $generator->skippedColumns)): ?>
            <?= "'$name' => ".$generator->generateString($label).",\n" ?>
        <?php endif; ?>
    <?php endforeach; ?>
    ];
    }
<?php endif; ?>

   /**
    * Executed before save
    */
    public function beforeSave($insert)
    {
        $component = new <?= $className ?>Component();
        $this->attributes = $component->beforeSave($this);
        return parent::beforeSave($insert);
    }

   /**
    * Executed before save
    */
    public function afterSave($insert, $changedAttributes)
    {
        $component = new <?= $className ?>Component();
        $this->attributes = $component->afterSave($this);
        parent::afterSave($insert, $changedAttributes);
    }
}
