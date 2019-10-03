<template>
  <div class="login-container">
    <img src="../../assets/login_page/tell-it-us_logo.png" alt="logo">

    <FormComponent v-if='$route.name == "verify"' type='verify' title='Rejestracja' :showTerms='showTerms'></FormComponent>
    <FormComponent v-if='$route.path == "/login"' title='Zaloguj się' type='login' :showTerms='showTerms'></FormComponent>
    <FormComponent v-if='$route.fullPath == "/registration"' title='Rejestracja' type='registration' :showTerms='showTerms'></FormComponent>
    <FormComponent v-if='$route.fullPath == "/sending-response"' type='sending-response' :showTerms='showTerms'></FormComponent>
    <FormComponent v-if='$route.fullPath == "/password-reset-step-1"' title='Przypomnienie hasła' type='passwordResetStep1' :showTerms='showTerms'></FormComponent>
    <FormComponent v-if='$route.name == "password/reset"' title='Przypomnienie hasła' type='password/reset' :showTerms='showTerms'></FormComponent>
    <FormComponent v-if='$route.name == "password-reset-step-2"' title='Przypomnienie hasła' type='passwordResetStep2' :showTerms='showTerms'></FormComponent>
    <FormComponent v-if='$route.name == "sendagain"' title='Weryfikacja konta' type='sendagain' :showTerms='showTerms'></FormComponent>

    <template v-if='terms_shown'>
      <embed src="files/Regulamin_serwisu_internetowego_TellItUS.pdf" type="application/pdf" width="70%" height="85%" style='position: fixed; left: 50%; top: 50%; transform: translate(-50%,-50%);'/>
      <button @click='terms_shown = false' class='terms_close_btn'>X</button>
    </template>
  </div>
</template>

<script>
import FormComponent from './FormComponent.vue';
export default {
  name: 'Login',
  components: { FormComponent },
  data() {
    return {
      redirect: undefined,
      terms_shown: false,
    };
  },
  watch: {
    $route: {
      handler: function(route) {
        this.redirect = route.query && route.query.redirect;
      },
      immediate: true,
    },
  },
  methods: {
    showTerms() {
      this.terms_shown = !this.terms_shown;
    },
  },
};
</script>

<style lang="scss">
.loader {
  .lds-roller {
    display: inline-block;
    position: relative;
    width: 64px;
    height: 64px;
  }
  .lds-roller div {
    animation: lds-roller 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
    transform-origin: 32px 32px;
    background-color:black;
  }
  .lds-roller div:after {
    content: " ";
    display: block;
    position: absolute;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: black;
    margin: -3px 0 0 -3px;
  }
  .lds-roller div:nth-child(1) {
    animation-delay: -0.036s;
  }
  .lds-roller div:nth-child(1):after {
    top: 50px;
    left: 50px;
  }
  .lds-roller div:nth-child(2) {
    animation-delay: -0.072s;
  }
  .lds-roller div:nth-child(2):after {
    top: 54px;
    left: 45px;
  }
  .lds-roller div:nth-child(3) {
    animation-delay: -0.108s;
  }
  .lds-roller div:nth-child(3):after {
    top: 57px;
    left: 39px;
  }
  .lds-roller div:nth-child(4) {
    animation-delay: -0.144s;
  }
  .lds-roller div:nth-child(4):after {
    top: 58px;
    left: 32px;
  }
  .lds-roller div:nth-child(5) {
    animation-delay: -0.18s;
  }
  .lds-roller div:nth-child(5):after {
    top: 57px;
    left: 25px;
  }
  .lds-roller div:nth-child(6) {
    animation-delay: -0.216s;
  }
  .lds-roller div:nth-child(6):after {
    top: 54px;
    left: 19px;
  }
  .lds-roller div:nth-child(7) {
    animation-delay: -0.252s;
  }
  .lds-roller div:nth-child(7):after {
    top: 50px;
    left: 14px;
  }
  .lds-roller div:nth-child(8) {
    animation-delay: -0.288s;
  }
  .lds-roller div:nth-child(8):after {
    top: 45px;
    left: 10px;
  }
  @keyframes lds-roller {
    0% {
      transform: rotate(0deg);
    }
    100% {
      transform: rotate(360deg);
    }
  }
}
</style>

<style lang="scss" >
@keyframes balloon {
  0%   {right:50%; top:10%}
  25%  {right:30%; top:30%;}
  35%  {right:60%; top:40%;}
  50%  {right:70%; top:30%;}
  65%  {right:30%; top:20%;}
  75%  {right:10%; top:20%;}
  100% {right:50%; top:10%;}
}
.terms_close_btn{
    position: fixed;
    right: 0px;
    margin: 15px;
    padding: 7px 25px;
    background: -webkit-linear-gradient(-90deg,rgba(84,35,107,.65),#4d0498 99%);
    background: -o-linear-gradient(-90deg,rgba(84,35,107,.65) 0,#4d0498 99%);
    background: -webkit-gradient(linear,right top,left top,from(rgba(84,35,107,.65)),color-stop(99%,#4d0498));
    background: -webkit-linear-gradient(right,rgba(84,35,107,.65),#4d0498 99%);
    background: -o-linear-gradient(right,rgba(84,35,107,.65) 0,#4d0498 99%);
    background: linear-gradient(-90deg,rgba(84,35,107,.65),#4d0498 99%);
    border-radius: 18px;
    border-style: none;
    color: #fff;
    font-size: 18px;
    cursor: pointer;
}
.login-container {
  background-color: #e4e9ef;
  height: 100vh;
  width: 100%;
  display:grid;
  grid-template-columns: minmax(150px,1fr) minmax(auto, 1300px) minmax(150px,1fr);
  grid-template-rows: 150px auto 150px;
  position:relative;
  @media screen and (max-width: 768px){
      grid-template-areas: "image image image"
      "form form form";
  }
  .el-form-item{
    margin:0;
  }
  .show-pwd {
    position: absolute;
    right: 0;
    top: 0;
    cursor: pointer;
  }
  .set-language {
    position: absolute;
    top: 20px;
    left: 20px;
  }
  img[alt='logo']{
    max-width: 100%;
    height: 50%;
    align-self: center;
    justify-self: end;
    @media screen and (max-width: 768px){
        grid-area: image;
        margin: 0 auto;
    }
  }
  form{
    grid-row:2;
    grid-column:2;
    display: grid;
    grid-template-columns: 1fr 1fr;
    @media screen and (max-width:1240px){
      &{
        grid-template-columns: 1fr;
      }
    }
    @media screen and (max-width: 768px){
        grid-area: form;
        margin: 0 20px 20px 20px;
    }
  }
  .left-column {
    background-color: white;
    display: grid;
    grid-row-gap: 40px;
    align-content: center;
    padding: 50px 0;
    position: relative;
    box-shadow: -2px 3px 6px 0px #80808087;
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
    &>img[alt='town'],&>img[alt='balloon'],&>img[alt='top-cloud']{
      display:none;
    }
    h2{
      font-weight: 400;
      text-align: center;
      p{
        margin:5px;
      }
      span{
        font-weight: 700;
      }
    }
    .input-group {
      display:grid;
      grid-row-gap: 20px;
      justify-self: center;
      width: 70%;
      .el-form-item__content {
        position:relative;
      }
      input{
        display: block;
        width: 100%;
        height: 34px;
        padding: 6px 12px;
        padding-right:20px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        -webkit-appearance: none;
        box-shadow: none;
        outline:none;
        border-radius: 0;
        border-top: none;
        border-left: none;
        border-right: none;
      }
      input:first-child{
        grid-row: 1;
        grid-column: 1;
      }
      input:nth-child(2){
        grid-row: 2;
        grid-column: 1;
      }
    }
    .login-submit-group{
      .el-form-item__content{
        display:grid;
        grid-row-gap:20px;
        .login-form__submit{
          border: none;
          background: linear-gradient(-90deg, rgba(84, 35, 107, 0.65) 0%, #4d0498 99%);
          justify-self: center;
          padding: 10px 30px;
          color: white;
          outline: none;
          box-shadow: none;
          cursor: pointer;
          border-radius: 20px;
        }
        small {
          color: #6a6b7f;
          width: 70%;
          justify-self: center;
          position:relative;
          &:before {
            content: '*';
            color: red;
            position: absolute;
            top: 0;
            left: -10px;
          }
        }
      }
    }
    .button-group {
      display: grid;
      justify-items: start;
      grid-row-gap: 10px;
      width: 70%;
      justify-self: center;
      a{
        background-color: transparent;
        border: none;
        color: #1199f8;
        padding: 0;
        cursor: pointer;
        &:hover {
          text-decoration: underline;
        }
      }
    }
    @media screen and (max-width:1240px){
      &{
        position:relative;
          border-radius: 10px;
      }
      &>img[alt='town'],&>img[alt='balloon'],&>img[alt='top-cloud']{
        display:block;
        position:absolute;
        @media screen and (max-width: 768px){
            display: none;
        }
      }
      &>img[alt='town']{
        bottom:- 13px;
        right:0;
        max-width:60%;
        height:auto;
      }
      &>img[alt='balloon']{
        top: 15px;
        right: 9px;
        bottom:0;
        max-width: 80px;
        height: auto;
        opacity: 0.5;
      }
      &>img[alt='top-cloud']{
        top: 100px;
        left: 50px;
        max-width: 80px;
        height: auto;
        opacity: 0.5;
      }
    }
  }
}
.styled-checkbox {
  display: block;
  position: relative;
  padding-left: 35px;
  font-weight:400;
  color: #6a6b7f;
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;

  &__input{
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
  }
  &__input:checked ~ &__div:after {
    display:block;
  }
  &__div{
    position: absolute;
    top: 0;
    left: 0;
    height: 20px;
    width: 20px;
    background-color: white;
    border: 1px solid #cccaca;
    border-radius: 3px;
    &:after {
      display: none;
      content: '\2714';
      position: absolute;
      left: 3px;
      top: 3px;
      font-size: 15px;
      line-height: 0.8;
      color: green;
      -webkit-transition: all .2s;
      -o-transition: all .2s;
      transition: all .2s;
      font-family: Helvetica, Arial, sans-serif;
    }
  }
}
</style>
