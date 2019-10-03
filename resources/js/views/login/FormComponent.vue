<template>
    <el-form ref="form" :model="formData" :rules="formRules" class="login-form" auto-complete="on" label-position="left">
            <div class="left-column">
            <!-- <lang-select class="set-language" /> -->
            <FormTitle :title='title'></FormTitle>
            <template v-if='isLoading'>
                <div class='loader' style='text-align:center;'>
                    <div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                </div>
            </template>
            <template v-else>
                <FormInputGroup
                    :type='type'
                    :formData='formData'
                    :terms_error='terms_error'
                    :showTerms='showTerms'
                    :handleRegistration='handleRegistration'
                    :resetPassword='resetPassword'
                    :resetPasswordStep2='resetPasswordStep2'
                    :handleLogin='handleLogin'
                    :sendAgain='sendAgain'>
                </FormInputGroup>

                <FormLoginBtnGroup v-if='$route.path == "/login"'></FormLoginBtnGroup>
            </template>
            <img src="../../assets/login_page/cloud-login.png" alt="top-cloud">
            <img src="../../assets/login_page/town-login.png" alt="town">
            <img src="../../assets/login_page/balloon-login.png" alt="balloon">
            </div>
        <FormRightColumn></FormRightColumn>
    </el-form>
</template>

<script>
// import LangSelect from '@/components/LangSelect';
import FormRightColumn from './FormRightColumn.vue';
import FormTitle from './FormTitle.vue';
import FormInputGroup from './FormInputGroup.vue';
import FormLoginBtnGroup from './FormLoginBtnGroup.vue';
import { validEmail } from '@/utils/validate';
import request from '@/utils/request';
import NProgress from 'nprogress';
export default {
  components: {
    // LangSelect,
    FormRightColumn,
    FormTitle,
    FormInputGroup,
    FormLoginBtnGroup,
  },
  props: {
    'title': String,
    'type': String,
    'showTerms': Function,
  },
  created() {
    if (this.$route.path.indexOf('/password/reset') !== -1) {
      this.isLoading = true;
      request({
        url: `/password/reset/${this.$route.params.hash}`,
        method: 'get',
      }).then(response => {
        this.$router.push({ name: 'password-reset-step-2', params: { permissions: ['fetchRedirect'] }});
        this.isLoading = false;
      }).catch(() => {
        this.$router.push({ name: 'login' });
        this.isLoading = false;
      });
    };
    if (this.$route.path.indexOf('/verify') !== -1) {
      this.isLoading = true;
      request({
        url: `/verify/${this.$route.params.hash}`,
        method: 'get',
      }).then(response => {
        this.$router.push({ name: 'sending-response', params: { permissions: ['fetchRedirect'], message: response.message }});
        this.isLoading = false;
      }).catch(error => {
        this.$router.push({ name: 'sending-response', params: { permissions: ['fetchRedirect'], message: error.response.data.message }});
        this.isLoading = false;
      });
    }
  },
  data() {
    const validateEmail = (rule, value, callback) => {
      if (!validEmail(value)) {
        callback(new Error('Wprowadź właściwy e-mail'));
      } else {
        callback();
      }
    };
    const validatePass = (rule, value, callback) => {
      if (value.length < 6) {
        callback(new Error('Hasło jest za krótkie'));
      } else {
        callback();
      }
    };
    const validateRegistrationPass = (rule, value, callback) => {
      if (value.length < 6) {
        callback(new Error('Hasło jest za krótkie'));
      } else if (this.formData.password !== this.formData.password_confirmation) {
        callback(new Error('Hasła muszą być takie same'));
      } else {
        callback();
      }
    };
    return {
      formData: {
        email: '',
        password: '',
        password_confirmation: '',
        accept_terms: false,
      },
      formRules: {
        email: [{ required: true, trigger: 'blur', validator: validateEmail }],
        password: [{ required: true, trigger: 'blur', validator: validatePass }],
        password_confirmation: [{ required: true, trigger: 'blur', validator: validateRegistrationPass }],
      },
      terms_error: false,
      api_response_message: '',
      isLoading: false,
    };
  },
  methods: {
    handleLogin() {
      this.$refs['form'].validate(valid => {
        if (valid) {
          NProgress.start();
          this.$store.dispatch('user/login', this.formData)
            .then(() => {
              NProgress.done();
              this.$router.push({ path: this.redirect || '/' });
            })
            .catch((error) => {
              NProgress.done();
              this.$router.push({ name: 'sending-response', params: { permissions: ['fetchRedirect'], message: error.response.data.message }});
              this.isLoading = false;
            });
        } else {
          console.error('error submit!!');
          return false;
        }
      });
    },
    handleRegistration() {
      if (this.formData.accept_terms) {
        this.terms_error = false;
        this.$refs['form'].validate(valid => {
          if (valid) {
            NProgress.start();
            this.$store.dispatch('user/register', this.formData)
              .then((response) => {
                NProgress.done();
                this.$router.push({ name: 'sending-response', params: { permissions: ['fetchRedirect'], message: response.message }});
              })
              .catch((error) => {
                NProgress.done();
                let message = ``;
                if (error.response.data.message === '') {
                  for (const exception in error.response.data.data) {
                    message += `${error.response.data.data[exception][0]} </br>`;
                  }
                } else {
                  message = error.response.data.message;
                }
                this.$router.push({ name: 'sending-response', params: { permissions: ['fetchRedirect'], message: message }});
              });
          } else {
            return false;
          }
        });
      } else {
        this.terms_error = true;
      }
    },
    resetPassword() {
      if (this.formData.email.trim()) {
        this.$refs['form'].validate(valid => {
          if (valid) {
            NProgress.start();
            request({
              url: `/password/forgotten`,
              method: 'post',
              data: {
                email: this.formData.email.trim(),
              },
            }).then(response => {
              this.$router.push({ name: 'sending-response', params: { permissions: ['fetchRedirect'], message: response.message, password_reset: true }});
              NProgress.done();
            }).catch(error => {
              NProgress.done();
              let message = ``;
              if (error.response.data.message === '') {
                for (const exception in error.response.data.data) {
                  message = `${error.response.data.data[exception][0]} </br>`;
                }
              } else {
                message = error.response.data.message;
              }
              this.$router.push({ name: 'sending-response', params: { permissions: ['fetchRedirect'], message: message, password_reset: true }});
            });
          } else {
            console.log('error submit!!');
            return false;
          }
        });
      }
    },
    resetPasswordStep2() {
      if (this.formData.email.trim() && this.formData.password.trim() && this.formData.password_confirmation.trim()) {
        this.$refs['form'].validate(valid => {
          if (valid) {
            NProgress.start();
            request({
              url: `/password/reset`,
              method: 'post',
              data: {
                email: this.formData.email.trim(),
                password: this.formData.password,
                password_confirmation: this.formData.password_confirmation,
              },
            }).then(response => {
              NProgress.done();
              this.$router.push({ name: 'sending-response', params: { permissions: ['fetchRedirect'], message: response.message, password_reset: true }});
            }).catch(error => {
              NProgress.done();
              let message = ``;
              if (error.response.data.message === '') {
                for (const exception in error.response.data.data) {
                  message = `${error.response.data.data[exception][0]} </br>`;
                }
              } else {
                message = error.response.data.message;
              }
              this.$router.push({ name: 'sending-response', params: { permissions: ['fetchRedirect'], message: message, password_reset: true }});
            });
          } else {
            console.log('error submit!!');
            return false;
          }
        });
      }
    },
    sendAgain() {
      if (this.formData.email.trim()) {
        this.$refs['form'].validate(valid => {
          if (valid) {
            NProgress.start();
            request({
              url: `/sendagain`,
              method: 'post',
              data: {
                email: this.formData.email.trim(),
              },
            }).then(response => {
              NProgress.done();
              this.$router.push({ name: 'sending-response', params: { permissions: ['fetchRedirect'], message: response.message }});
            }).catch(error => {
              NProgress.done();
              let message = ``;
              if (error.response.data.message === '') {
                for (const exception in error.response.data.data) {
                  message = `${error.response.data.data[exception][0]} </br>`;
                }
              } else {
                message = error.response.data.message;
              }
              this.$router.push({ name: 'sending-response', params: { permissions: ['fetchRedirect'], message: message }});
            });
          } else {
            console.log('error submit!!');
            return false;
          }
        });
      }
    },
  },
};
</script>

<style lang="scss">

</style>
