<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Masuk';

$fieldOptions1 = [
    'options' => ['class' => 'pin-field-group'],
    'inputTemplate' => "<span class='pin-field-icon'><i class='fa fa-user'></i></span>{input}",
];

$fieldOptions2 = [
    'options' => ['class' => 'pin-field-group'],
    'inputTemplate' => "<span class='pin-field-icon'><i class='fa fa-lock'></i></span>{input}",
];
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

/* ── Reset AdminLTE login page chrome ── */
body.login-page { background: #fbfbf9 !important; }

.login-box,
.login-box-body { all: unset; display: block; }

/* ── Pinterest tokens ── */
:root {
  --pin-primary:        #e60023;
  --pin-primary-pressed:#cc001f;
  --pin-ink:            #000000;
  --pin-body:           #33332e;
  --pin-mute:           #62625b;
  --pin-ash:            #91918c;
  --pin-hairline:       #dadad3;
  --pin-canvas:         #ffffff;
  --pin-surface-soft:   #fbfbf9;
  --pin-surface-card:   #f6f6f3;
  --pin-secondary-bg:   #e5e5e0;
  --pin-focus-outer:    #435ee5;
  --pin-r-md:           16px;
  --pin-r-lg:           32px;
  --pin-r-full:         9999px;
  --pin-font: 'Inter', -apple-system, system-ui, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
}

/* ── Page ── */
.pin-login-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--pin-surface-soft);
  font-family: var(--pin-font);
  padding: 24px;
}

/* ── Modal card (modal-card spec: canvas bg, 32px radius, 32px padding) ── */
.pin-login-card {
  background: var(--pin-canvas);
  border-radius: var(--pin-r-lg);
  padding: 40px 36px 36px;
  width: 100%;
  max-width: 400px;
  box-shadow: 0 4px 32px rgba(0,0,0,.08);
}

/* ── Brand mark ── */
.pin-brand {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  margin-bottom: 28px;
}
.pin-brand-dot {
  width: 36px; height: 36px;
  background: var(--pin-primary);
  border-radius: var(--pin-r-full);
  display: flex; align-items: center; justify-content: center;
  color: #fff;
  font-size: 18px;
  font-weight: 700;
}
.pin-brand-name {
  font-size: 20px;
  font-weight: 700;
  color: var(--pin-ink);
  letter-spacing: -0.3px;
}
.pin-brand-name span { color: var(--pin-primary); }

/* ── Heading ── */
.pin-login-title {
  font-size: 22px;
  font-weight: 600;
  color: var(--pin-ink);
  text-align: center;
  margin-bottom: 6px;
  line-height: 1.25;
  letter-spacing: 0;
}
.pin-login-sub {
  font-size: 14px;
  font-weight: 400;
  color: var(--pin-mute);
  text-align: center;
  margin-bottom: 28px;
  line-height: 1.4;
}

/* ── Field group ── */
.pin-field-group {
  position: relative;
  margin-bottom: 14px;
}
.pin-field-group .pin-field-icon {
  position: absolute;
  left: 14px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--pin-ash);
  font-size: 13px;
  pointer-events: none;
  z-index: 2;
  line-height: 1;
}
/* shift icon when there's an error message below */
.pin-field-group.has-error .pin-field-icon { top: 22px; }

.pin-field-group input.form-control {
  display: block;
  width: 100%;
  height: 44px;
  padding: 11px 15px 11px 38px;
  background: var(--pin-canvas);
  border: 1px solid var(--pin-hairline);
  border-radius: var(--pin-r-md);
  font-family: var(--pin-font);
  font-size: 16px;
  font-weight: 400;
  color: var(--pin-ink);
  line-height: 1.4;
  box-sizing: border-box;
  outline: none;
  transition: border-color .15s, box-shadow .15s;
  -webkit-appearance: none;
}
.pin-field-group input.form-control::placeholder { color: var(--pin-ash); }
.pin-field-group input.form-control:focus {
  border-color: var(--pin-ink);
  box-shadow: 0 0 0 3px rgba(67,94,229,.18);
}
.pin-field-group.has-error input.form-control {
  border-color: var(--pin-primary);
}
.pin-field-group .help-block {
  font-size: 12px;
  color: var(--pin-primary);
  margin-top: 4px;
  margin-left: 2px;
  display: block;
}

/* ── Submit button (button-primary spec) ── */
.pin-btn-submit {
  display: block;
  width: 100%;
  height: 44px;
  margin-top: 8px;
  background: var(--pin-primary);
  color: #fff;
  font-family: var(--pin-font);
  font-size: 14px;
  font-weight: 700;
  line-height: 1;
  text-align: center;
  border: none;
  border-radius: var(--pin-r-md);
  cursor: pointer;
  transition: background .15s;
  letter-spacing: 0;
}
.pin-btn-submit:hover,
.pin-btn-submit:focus  { background: var(--pin-primary-pressed); outline: none; }
.pin-btn-submit:active { background: var(--pin-primary-pressed); }

/* ── Footer note ── */
.pin-login-footer {
  margin-top: 20px;
  text-align: center;
  font-size: 12px;
  color: var(--pin-ash);
  line-height: 1.5;
}
</style>

<div class="pin-login-page">
  <div class="pin-login-card">

    <!-- Brand -->
    <div class="pin-brand">
      <div class="pin-brand-dot"><i class="fa fa-shopping-bag"></i></div>
      <span class="pin-brand-name"><span>POS</span> System</span>
    </div>

    <!-- Heading -->
    <h1 class="pin-login-title">Selamat datang</h1>
    <p class="pin-login-sub">Masuk untuk memulai sesi Anda</p>

    <!-- Form -->
    <?php $form = ActiveForm::begin([
      'id'                   => 'login-form',
      'enableClientValidation' => false,
      'fieldConfig'          => ['errorOptions' => ['class' => 'help-block']],
    ]); ?>

    <?= $form
        ->field($model, 'username', $fieldOptions1)
        ->label(false)
        ->textInput(['placeholder' => 'Username', 'class' => 'form-control']) ?>

    <?= $form
        ->field($model, 'password', $fieldOptions2)
        ->label(false)
        ->passwordInput(['placeholder' => 'Password', 'class' => 'form-control']) ?>

    <?= Html::submitButton('Masuk', [
        'class' => 'pin-btn-submit',
        'name'  => 'login-button',
    ]) ?>

    <?php ActiveForm::end(); ?>

    <p class="pin-login-footer">
      &copy; <?php echo date('Y'); ?> POS System. All rights reserved.
    </p>

  </div>
</div>
