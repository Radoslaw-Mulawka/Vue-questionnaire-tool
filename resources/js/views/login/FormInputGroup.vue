<template>
    <div style='display:grid; grid-row-gap: 40px; align-content: center;'>
      <template v-if='type=="login"'>
        <div class="input-group">
            <el-form-item prop="email">
                <el-input class='my-own-class' v-model="formData.email" name="email" type="text" auto-complete="on" :placeholder="$t('login.email')"  @keyup.enter.native="handleLogin"/>
            </el-form-item>
            <el-form-item prop="password">
                <el-input
                :type="pwdType"
                v-model="formData.password"
                name="password"
                auto-complete="on"
                placeholder="hasło"
                @keyup.enter.native="handleLogin" />
                <span class="show-pwd" @click="showPwd">
                  <svg-icon icon-class="eye" />
                </span>
            </el-form-item>
        </div>
        <el-form-item class='login-submit-group'>
          <el-button class='login-form__submit' type="primary" @click.native.prevent="handleLogin">
              Zaloguj się
          </el-button>
          <small style='line-height: 20px;'>Logując się, akceptujesz regulamin serwisu Tell-it.us</small>
        </el-form-item>
      </template>
      <template v-if='type=="registration"'>
        <div class="input-group">
          <el-form-item prop="email">
            <el-input class='my-own-class' v-model="formData.email" name="email" type="text" auto-complete="on" placeholder="Podaj swój adres e-mail" />
          </el-form-item>
          <el-form-item prop="password">
            <el-input
              :type="pwdType"
              v-model="formData.password"
              name="password"
              auto-complete="on"
              placeholder="Podaj hasło"
              @keyup.enter.native="handleRegistration" />
          </el-form-item>
          <el-form-item prop="password_confirmation">
            <el-input
              :type="pwdType"
              v-model="formData.password_confirmation"
              name="password_confirmation"
              auto-complete="on"
              placeholder="Potwierdź hasło"
              @keyup.enter.native="handleRegistration" />
          </el-form-item>
        </div>
        <div class="button-group">
          <label class="styled-checkbox" style='cursor: default'>
            <input v-model='formData.accept_terms' type="checkbox"  class='styled-checkbox__input'>
            <div class="styled-checkbox__div" style='cursor:pointer'></div>
            Akceptuję <span style='font-weight:bold; cursor: pointer' @click.stop.prevent='showTerms'>regulamin</span> w celu utworzenia bezpłatnego konta w serwisie Tell-it.us
          </label>
          <span v-if='terms_error' style='color:#F56C6C; font-size: 12px; padding-left: 35px;'>Musisz zaakceptować regulamin!</span>
        </div>
        <el-form-item class='login-submit-group'>
          <el-button class='login-form__submit' type="primary" @click.native.prevent="handleRegistration" style='cursor: pointer'>
            Rejestruj
          </el-button>
        </el-form-item>
      </template>
      <template v-if='type=="passwordResetStep1"'>
        <div class="input-group">
          <el-form-item prop="email">
            <el-input class='my-own-class' v-model="formData.email" name="email" type="text" auto-complete="on" :placeholder="$t('login.email')"/>
          </el-form-item>
        </div>
        <el-form-item class='login-submit-group'>
          <el-button class='login-form__submit' type="primary" @click.native.prevent="resetPassword" style='cursor: pointer'>
            Przypomnij hasło
          </el-button>
        </el-form-item>
      </template>
      <template v-if='type=="passwordResetStep2"'>
        <div class="input-group">
          <el-form-item prop="email">
            <el-input class='my-own-class' v-model="formData.email" name="email" type="text" auto-complete="on" placeholder="Podaj swój adres e-mail" />
          </el-form-item>
          <el-form-item prop="password">
            <el-input
              :type="pwdType"
              v-model="formData.password"
              name="password"
              auto-complete="on"
              placeholder="Podaj hasło"
              @keyup.enter.native="resetPasswordStep2" />
          </el-form-item>
          <el-form-item prop="password_confirmation">
            <el-input
              :type="pwdType"
              v-model="formData.password_confirmation"
              name="password_confirmation"
              auto-complete="on"
              placeholder="Potwierdź hasło"
              @keyup.enter.native="resetPasswordStep2" />
          </el-form-item>
        </div>
        <el-form-item class='login-submit-group'>
          <el-button class='login-form__submit' type="primary" @click.native.prevent="resetPasswordStep2" style='cursor: pointer'>
            Zmień hasło
          </el-button>
        </el-form-item>
      </template>
      <template v-if='type=="sendagain"'>
        <div class="input-group">
          <el-form-item prop="email">
            <el-input class='my-own-class' v-model="formData.email" name="email" type="text" auto-complete="on" placeholder="Podaj swój adres e-mail" />
          </el-form-item>
        </div>
        <el-form-item class='login-submit-group'>
          <el-button class='login-form__submit' type="primary" @click.native.prevent="sendAgain" style='cursor: pointer'>
            Wyślij
          </el-button>
        </el-form-item>
      </template>
      <template v-if='type=="sending-response"'>
        <el-form-item class='login-submit-group'>
          <router-link to='/login' style='text-align:center;'>
            <el-button class='login-form__submit' type="primary" style='cursor: pointer'>
              Wróć do logowania
            </el-button>
          </router-link>
        </el-form-item>
      </template>
    </div>
</template>

<script>
export default {
  props: {
    'type': String,
    'formData': Object,
    'terms_error': Boolean,
    'showTerms': Function,
    'handleRegistration': Function,
    'resetPassword': Function,
    'resetPasswordStep2': Function,
    'handleLogin': Function,
    'sendAgain': Function,
  },
  data() {
    return {
      pwdType: 'password',
    };
  },
  methods: {
    showPwd() {
      if (this.pwdType === 'password') {
        this.pwdType = '';
      } else {
        this.pwdType = 'password';
      }
    },
  },
};
</script>

