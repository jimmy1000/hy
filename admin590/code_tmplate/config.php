include 'common.php';

/**
 * 配置文件定义
*/

<?php foreach($global['config_key'] as $key => $config): ?>
<?php if($config == '') continue; ?>
// <?= $global['config_note'][$key] . PHP_EOL ?>
$<?= $config ?> = <?= input_value($global['config_value'][$key]) ?>;

<?php endforeach; ?>

<?php if($is_many_request == 1): ?>
$pay_query_url_arr = [
    <?php foreach($global['many_request_key'] as $key => $value): ?>
    <?php if($value == '') continue; ?>
    '<?= $value ?>' => <?= input_value($global['many_request_url'][$key]) ?>, //<?= $global['many_request_note'][$key] . PHP_EOL ?>
    <?php endforeach; ?>
];
<?php endif; ?>