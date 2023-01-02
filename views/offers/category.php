<?php
/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\ListView;

?>

    <section class="categories-list">
        <h1 class="visually-hidden">Сервис объявлений "Куплю - продам"</h1>
        <ul class="categories-list__wrapper">
            <?php foreach ($categories as $cat):?>
                <li class="categories-list__item">
                    <a href="<?=Url::to('/offers/category/' . $cat->id); ?>" class="category-tile <?=$cat->id == Yii::$app->request->get('id') ? 'category-tile--active' : '' ;?>">
            <span class="category-tile__image">
                <?php if ($cat->countTicketsInCategory!== 0){ ?>
                    <img src="<?=Url::to('/uploads/tickets/'. $cat->getRandomTitleImage()) ;?>" srcset = "<?=Url::to('/uploads/tickets/'. $cat->getRandomTitleImage()) ;?> 2x " alt="Иконка категории">
            <?php } else { ?>
                    <img src="<?=Url::to('/img/blank.png') ;?>" srcset="<?=Url::to('/img/blank.png') ;?> 2x" alt="Иконка категории">
                <?php } ?>
            </span>
                        <span class="category-tile__label"><?=$cat->name; ?> <span class="category-tile__qty js-qty"><?=$cat->countTicketsInCategory; ?></span></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>

    <?php if($category->countTicketsInCategory === 0){
        echo '<p>Объявления данной категории пока отсутсвуют</p>';
    } else { ?>
        <section class="tickets-list">
            <h2 class="visually-hidden">Предложения из категории электроника</h2>
            <?=ListView::widget([
                'dataProvider' => $dataProvider,
                'options' => [
                    'class' => 'tickets-list__wrapper',
                ],
                'summary' => "<p class='tickets-list__title'>{$category->name} <b class='js-qty'>{$category->countTicketsInCategory}</b></p>",
                'summaryOptions' => ['class' => 'tickets-list__header'],
                'itemView' => '/_list_ticket',
                'itemOptions' => ['tag' => false],
                'layout' => '{summary}<ul>{items}</ul><div class="tickets-list__pagination">{pager}</ul>',
                'pager' => [
                    'options' => ['class' => 'pagination'],
                    'activePageCssClass' => 'active',
                    'nextPageLabel' => 'дальше',
                    'prevPageLabel' => false,
                    'disableCurrentPageButton' => true,
                    'maxButtonCount' => 5
                ]
            ]);
            ?>
        </section>
<?php
    }
?>


