<?php
/**
 * Product view
 * @var StoreProduct $model
 */

// Set meta tags
$this->pageTitle = ($model->meta_title) ? $model->meta_title : $model->name;
$this->pageKeywords = $model->meta_keywords;
$this->pageDescription = $model->meta_description;

// Create breadcrumbs
$ancestors = $model->mainCategory->excludeRoot()->ancestors()->findAll();

foreach($ancestors as $c)
	$this->breadcrumbs[$c->name] = $c->getViewUrl();

// Do not add root category to breadcrumbs
if ($model->mainCategory->id != 1)
	$this->breadcrumbs[$model->mainCategory->name] = $model->mainCategory->getViewUrl();

$this->breadcrumbs[] = $model->name;

// Register script for configurable products
if($model->use_configurations)
	Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl.'/product.view.configurations.js', CClientScript::POS_END);

// Fancybox ext
$this->widget('application.extensions.fancybox.EFancyBox', array(
	'target'=>'a.thumbnail',
	'config'=>array(),
));

?>

<h3><?php echo CHtml::encode($model->name); ?></h3>

<div class="row">
	<!-- Left column  -->
	<div class="span4">
		<ul class="thumbnails">
			<li class="span4">
				<?php
					if($model->mainImage)
						echo CHtml::link(CHtml::image($model->mainImage->getUrl('360x268', 'resize')), $model->mainImage->getUrl(), array('class'=>'thumbnail'));
					else
						echo CHtml::link(CHtml::image('http://placehold.it/360x268'), '#', array('class'=>'thumbnail'));
				?>
			</li>
			<?php
				foreach($model->imagesNoMain as $image)
				{
					echo CHtml::openTag('li', array('class'=>'span2'));
					echo CHtml::link(CHtml::image($image->getUrl('160x120')), $image->getUrl(), array('class'=>'thumbnail'));
					echo CHtml::closeTag('li');
				}
			?>
		</ul>
	</div>

	<!-- Right column -->
	<div class="span7">
		<p><?php echo $model->short_description; ?></p>
		<p><?php echo $model->full_description; ?></p>

		<div>
			<table class="table table-striped table-bordered table-condensed">
				<?php
				foreach($model->processVariants() as $variant)
				{
					echo '<tr>';
					echo '<td>';
					echo $variant['attribute']->title;
					echo '</td>';
					echo '<td>';
					$dropDownData = array();
					foreach($variant['options'] as $v)
						$dropDownData[$v->id] = $v->option->value;
					echo CHtml::dropDownList(1, null, $dropDownData);
					echo '</td>';
					echo '</tr>';
				}

				// Display product configurations
				if($model->use_configurations)
				{
					$configurables = $model->configurations;
					$attributeModels = StoreAttribute::model()->findAllByPk($model->configurable_attributes);
					$models = StoreProduct::model()->findAllByPk($configurables);

					$data = array();
					$prices = array();
					foreach($attributeModels as $attr)
					{
						foreach($models as $m)
						{
							$prices[$m->id] = $m->price;
							if(!isset($data[$attr->name]))
								$data[$attr->name] = array('---'=>'---');

							$method = 'eav_'.$attr->name;
							$value = $m->$method;

							if(!isset($data[$attr->name][$value]))
								$data[$attr->name][$value] = '';

							$data[$attr->name][$value] .= $m->id.'/';
						}
					}

					Yii::app()->clientScript->registerScript('productPrices', strtr('
						var productPrices = {prices};
					', array(
						'{prices}'=>CJavaScript::encode($prices)
					)), CClientScript::POS_END);

					foreach($attributeModels as $attr)
					{
						echo $attr->title.': ';
						echo CHtml::dropDownList('eav_'.$attr->name, null, array_flip($data[$attr->name]), array('class'=>'eavData'));

						echo '<br/>';
					}
				}

				?>
			</table>
		</div>

		<h4>Цена: <span id="productPrice"><?php echo $model->price ?></span></h4>
		<a href="#" class="btn btn-large btn-primary">Купить</a>

		<div class="row">&nbsp;</div>

		<?php
			if($model->getEavAttributes())
			{
				// Display product custom options table.
				$this->widget('application.modules.store.widgets.SAttributesTableRenderer', array(
					'model'=>$model,
					'htmlOptions'=>array('class'=>'table table-bordered table-striped'),
				));
			}else
				echo 'Нет характеристик';
		?>
	</div>

</div>
