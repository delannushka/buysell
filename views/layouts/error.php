<?php

/** @var yii\web\View $this */
/** @var yii\web\View $content */
/** @var Exception$exception */

use app\assets\AppAsset;

AppAsset::register($this);
$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0']);
$this->registerMetaTag(['name' => 'description', 'content' => "Доска объявлений — современный веб-сайт, упрощающий продажу или покупку абсолютно любых вещей." ?? '']);
$this->registerMetaTag(['http-equiv' => 'X-UA-Compatible', 'content' => 'ie=edge']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'img/favicon.ico', 'href' => Yii::getAlias('@web/img/favicon.ico')]);

if ($exception->statusCode < 500) {
    $htmlClass = "html-not-found";
    $bodyClass = "body-not-found";
} else {
    $htmlClass = "html-server";
    $bodyClass = "body-server";
}
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="ru" class=<?=$htmlClass; ?>>
<head>
    <title>Куплю Продам</title>
    <?php $this->head() ?>
</head>
<body class=<?=$bodyClass; ?>>
<?php $this->beginBody() ?>
<main>
    <?=$content; ?>
</main>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
