<?php

use Plib\View;

if (!defined("CMSIMPLE_XH_VERSION")) {http_response_code(403); exit;}

/**
 * @var View $this
 * @var string $url
 * @var string $category
 * @var bool $has_classes
 * @var list<object{value:string,label:string,selected:string}> $available_classes
 * @var string $toxic_class
 */
?>

<form id="toxic_tab" action="<?=$this->esc($url)?>" method="post">
  <p>
    <label>
      <span><?=$this->text("label_category")?></span>
      <input type="text" name="toxic_category" value="<?=$this->esc($category)?>">
    </label>
  </p>
  <p>
    <label>
      <span><?=$this->text("label_class")?></span>
<?if (!$has_classes):?>
      <input type="text" name="toxic_class" value="<?=$this->esc($toxic_class)?>">
<?else:?>
      <select name="toxic_class">
<?  foreach ($available_classes as $class):?>
        <option label="<?=$this->esc($class->label)?>" <?=$this->esc($class->selected)?>><?=$this->esc($class->value)?></option>
<?  endforeach?>
      </select>
<?endif?>
    </label>
  </p>
  <p class="toxic_tab_buttons">
    <button name="save_page_data"><?=$this->text("label_save")?></button>
  </p>
</form>
