<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '创建管理员';
$this->params['breadcrumbs'][] = $this->title;
$this->context->layout=false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= $this->title ?></title>
  <meta name="description" content="particles.js is a lightweight JavaScript library for creating particles.">
  <meta name="author" content="Vincent Garreau" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <link rel="stylesheet" media="screen" href="/plugins/particles/style.css">
  <style >
          .head_title{
        font-family: SimHei;
        font-size: 36px;
        font-weight: 700;
      }
      .head_subtitle{
        font-family: Arial;
        font-size: 18px;
      }
  </style>
</head>
<body>

<!-- particles.js container -->
<div class="content">
    
    <div class="header">
        <label> 
            <p class="head_title">iConnect安全管理平台2.0</p>   
            <p class="head_subtitle">iConnect Security Management Platform 2.0</p>   
        </label>
    </div>
    <div class="body">
        <div class="site-login">
            <div class="row">
                <div class="col-lg-5">
                    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                        <?= $form->field($model, 'username',['labelOptions' => ['label'=>null]])
                          ->textInput([
                            'autofocus' => true,
                            'placeholder'=>'用户名',
                          ]) ?>

                        <?= $form->field($model, 'password',['labelOptions' => ['label'=>null]])->passwordInput([
                            'placeholder'=>'密码',
                          ]) ?>

                        <?= $form->field($model, 'repassword',['labelOptions' => ['label'=>null]])->passwordInput([
                            'placeholder'=>'确认密码',
                          ]) ?>

                        <div class="form-group">
                            <?= Html::submitButton('创建管理员', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                        </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>


    
<div id="particles-js"></div>


<!-- scripts -->
<script src="/plugins/particles/particles.min.js"></script>
<script src="/plugins/particles/app.js"></script>


</body>
</html>

