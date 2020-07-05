<?php
/**
 * Created by PhpStorm.
 * User: gogl92
 * Date: 2019-02-24
 * Time: 01:18.
 */
echo "<?php\n";

?>

namespace <?= $generator->nsComponent ?>;
use Yii;
use <?= ltrim($generator->modelClass, '\\') ?>;
use yii\base\Component;

/**
 * <?= $componentClassName ?> implements all the functionality and business layer of the <?= $generator->generateTableName($tableName) ?> table.
 */
class <?= $componentClassName ?> extends Component
{
<?php
echo
"\t/**
\t * @param ".$className.' '.lcfirst($className)."
\t * @return ".$className."
\t */
\tpublic function beforeSave($".lcfirst($className).'): '.$className."
\t{
        return \$".lcfirst($className).";
\t}\n"; ?>

<?php
echo "\t/**
\t * @param ".$className.' '.lcfirst($className)."
\t * @return ".$className."
\t */
\tpublic function afterSave($".lcfirst($className).'): '.$className."
\t{
        return \$".lcfirst($className).";
\t}\n"; ?>
}
