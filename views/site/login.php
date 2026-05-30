<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Masuk';
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

/* ── Reset AdminLTE login page chrome ── */
body.login-page {
  background: #fbfbf9 !important;
  font-family: 'Inter', -apple-system, system-ui, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
}

.login-box,
.login-box-body {
  background: transparent !important;
  box-shadow: none !important;
  border: none !important;
  padding: 0 !important;
  margin: 0 !important;
  width: auto !important;
  float: none !important;
}

/* ── Full-screen centering wrapper ── */
.pin-login-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #fbfbf9;
  padding: 24px;
  font-family: 'Inter', -apple-system, system-ui, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
}

/* ── Modal card ── */
.pin-modal-card {
  background: #ffffff;
  border-radius: 32px;
  padding: 40px 36px 36px;
  width: 100%;
  max-width: 420px;
  box-shadow: 0 16px 48px rgba(0,0,0,0.10);
}

/* ── Brand mark ── */
.pin-brand {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
  margin-bottom: 28px;
}
.pin-brand-icon {
  width: 48px;
  height: 48px;
  background: #e60023;
  border-radius: 9999px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 22px;
  font-weight: 700;
  letter-spacing: -1px;
  line-height: 1;
  flex-shrink: 0;
}
.pin-brand-title {
  font-size: 22px;
  font-weight: 600;
  color: #000000;
  letter-spacing: -0.3px;
  text-align: center;
  line-height: 1.25;
}
.pin-brand-sub {
  font-size: 14px;
  font-weight: 400;
  color: #62625b;
  text-align: center;
  line-height: 1.4;
  margin-top: -4px;
}

/* ── Field wrapper ── */
.pin-field {
  margin-bottom: 12px;
}

/* ── Input with left icon ── */
.pin-input-group {
  position: relative;
  display: flex;
  align-items: center;
}
.pin-input-group .pin-input-icon {
  position: absolute;
  left: 14px;
  color: #91918c;
  font-size: 14px;
  pointer-events: none;
  z-index: 1;
}
.pin-input-group input {
  width: 100%;
  height: 44px;
  padding: 11px 15px 11px 40px;
  background: #ffffff;
  border: 1px solid #91918c;
  border-radius: 16px;
  font-family: 'Inter', -apple-system, system-ui, sans-serif;
  font-size: 16px;
  font-weight: 400;
  color: #000000;
  outline: none;
  transition: border-color .15s, box-shadow .15s;
  box-sizing: border-box;
}
.pin-input-group input::placeholder { color: #91918c; }
.pin-input-group input:focus {
  border-color: #000000;
  border-width: 2px;
  box-shadow: 0 0 0 4px rgba(67,94,229,0.18);
}

/* ── Validation error ── */
.pin-field .help-block {
  font-size: 12px;
  color: #9e0a0a;
  margin: 5px 4px 0;
  display: block;
}
.pin-field.has-error .pin-input-group input {
  border-color: #9e0a0a;
}

/* ── Primary button ── */
.pin-btn-submit {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 44px;
  margin-top: 20px;
  background: #e60023;
  color: #ffffff;
  font-family: 'Inter', -apple-system, system-ui, sans-serif;
  font-size: 14px;
  font-weight: 700;
  line-height: 1;
  border: none;
  border-radius: 16px;
  cursor: pointer;
  transition: background .15s;
}
.pin-btn-submit:hover,
.pin-btn-submit:focus  { background: #cc001f; color: #fff; outline: none; }
.pin-btn-submit:active { background: #cc001f; }

/* ── Divider ── */
.pin-divider-row {
  display: flex;
  align-items: center;
  gap: 12px;
  margin: 20px 0;
  color: #c8c8c1;
  font-size: 12px;
}
.pin-divider-row::before,
.pin-divider-row::after {
  content: '';
  flex: 1;
  height: 1px;
  background: #dadad3;
}

/* ── Footer note ── */
.pin-login-footer {
  margin-top: 16px;
  text-align: center;
  font-size: 12px;
  color: #62625b;
  line-height: 1.5;
}
</style>

<?php
$fieldOptions1 = [
    'options' => ['class' => 'pin-field'],
    'template' => '<div class="pin-input-group"><i class="pin-input-icon fa fa-user"></i>{input}</div>{error}',
];
$fieldOptions2 = [
    'options' => ['class' => 'pin-field'],
    'template' => '<div class="pin-input-group"><i class="pin-input-icon fa fa-lock"></i>{input}</div>{error}',
];
?>

<div class="pin-login-page">
  <div class="pin-modal-card">

    <!-- Brand -->
    <div class="pin-brand">
      <div class="pin-brand-icon">P</div>
      <span class="pin-brand-title">Selamat datang</span>
      <span class="pin-brand-sub">Masuk untuk melanjutkan ke POS System</span>
    </div>

    <!-- Form -->
    <?php $form = ActiveForm::begin([
        'id'                   => 'login-form',
        'enableClientValidation' => false,
        'fieldConfig'          => ['errorOptions' => ['class' => 'help-block']],
    ]); ?>

    <?= $form
        ->field($model, 'username', $fieldOptions1)
        ->label(false)
        ->textInput(['placeholder' => 'Username', 'autocomplete' => 'username']) ?>

    <?= $form
        ->field($model, 'password', $fieldOptions2)
        ->label(false)
        ->passwordInput(['placeholder' => 'Password', 'autocomplete' => 'current-password']) ?>

    <?= Html::submitButton('Masuk', [
        'class' => 'pin-btn-submit',
        'name'  => 'login-button',
    ]) ?>

    <?php ActiveForm::end(); ?>

    <p class="pin-login-footer">
      &copy; <?php echo date('Y'); ?> POS System &mdash; All rights reserved
    </p>

  </div>
</div>
