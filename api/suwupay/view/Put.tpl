<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>支付</title>
</head>
<body onLoad="document.diy.submit();">
    <form id="diy" name="diy" class="form-inline" method="post" action="<?php echo $this->PutUrl; ?>">
        <?php
        foreach ($return as $key => $val) {
            echo '<input type="hidden" name="' . $key . '" value="' . $val . '">';
            }
        ?>
    </form>
</body>
</html>
