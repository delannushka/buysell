<?php

/** @var Category $model */

use app\models\Category;
use yii\helpers\Url;
?>

<?php if ($model->getCountTicketsInCategory() !== 0):?>
    <li class="categories-list__item">
        <a href="<?=Url::to('/offers/category/' . $model->id); ?>" class="category-tile category-tile--default">
            <span class="category-tile__image">
                <img src="<?='uploads/tickets/' . $model->getRandomTitleImage(); ?>" srcset="<?=Url::to('/uploads/tickets/'. $model->getRandomTitleImage()); ?> 2x" alt="Иконка категории">
            </span>
            <span class="category-tile__label"><?=$model->name; ?>
                <span class="category-tile__qty js-qty"><?=$model->getCountTicketsInCategory(); ?></span>
            </span>
        </a>
    </li>
<?php endif; ?>