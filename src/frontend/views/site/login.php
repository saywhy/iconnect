<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '登录';
$this->params['breadcrumbs'][] = $this->title;
$this->context->layout=false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?=$this->title ?></title>
  <meta name="description" content="particles.js is a lightweight JavaScript library for creating particles.">
  <meta name="author" content="Vincent Garreau" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <link rel="stylesheet" media="screen" href="/plugins/particles/style.css">
  <style lang="">
      .head_title{
        font-family: SimHei;
        font-size: 36px;
        font-weight: 700;
      }
      .head_subtitle{
        font-family: Arial;
        font-size: 18px;
      }
      .hoohoolab-footer-login{
            color:#fff;
            position: absolute;
            bottom: 20px;
            left: 50%;
            font-weight: 700;
            font-size: 14px;
            transform: translateX(-50%);
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
                    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                        <?= $form->field($model, 'username',['labelOptions' => ['label'=>null]])
                          ->textInput([
                            'autofocus' => true,
                            'placeholder'=>'用户名',
                          ]) ?>

                        <?= $form->field($model, 'password',['labelOptions' => ['label'=>null]])->passwordInput([
                            'placeholder'=>'密码',
                          ]) ?>


                        <div class="form-group">
                            <?= Html::submitButton('登录', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                        </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
     <div class="hoohoolab-footer-login">
     <span>&copy; 2018 虎特信息科技(上海)有限公司 版权所有</span>
    </div>
</div>


    
<div id="particles-js"></div>


<!-- scripts -->
<script src="/plugins/particles/particles.min.js"></script>
<script src="/plugins/particles/app.js"></script>


</body>
</html>