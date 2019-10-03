<template>
    <div class="profile">
        <div class="profile__data">
            <div>
                <h4>Dane konta</h4>
                <label for="name">Imię</label>
                <input type="text" name="name" id="name" v-model.lazy='userName'><br>
                <label for="surname">Nazwisko</label>
                <input type="text" name="surname" id="surname" v-model.lazy='userLastname'>
            </div>
            <div>
                <h4>Dane firmy</h4>
                <label for="company-logo">Logo firmy</label>
                <!-- <input type="file" name="company-logo" id="company-logo"><br> -->
                <div class='img-wrap' id='img-wrap'>
                    <img :src='allUserData.companyLogo' alt="Baner"/>
                    <template v-if='imageLoading'>
                        <div class='img-wrap__loader'>
                            <div class='loader' style='text-align:center;'>
                                <div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div class='img-wrap--hover-block'>
                            <label for='image'>
                                <svg class="svg-class" aria-hidden="true">
                                    <use xlink:href="#icon-upload" />
                                </svg>
                            </label>
                            <input @change='bannerEditHandler' type="file" name="" id="image">
                            <button @click='removeBanner'>
                                <svg class="svg-class" aria-hidden="true">
                                    <use xlink:href="#icon-trash-2" />
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>

                <label for="company_name">Nazwa firmy</label>
                <input type="text" name="company_name" id="company_name" v-model.lazy='companyName'><br>
                <label for="company_address">Adres firmy</label>
                <input type="text" name="company_address" id="company_address" v-model.lazy='companyAddress'>
            </div>
        </div>

        <div class="profile__settings">
            <div class="info">
                <h4>Ustawienia konta</h4>
                <label>Email:  </label><span>{{allUserData.email}}</span> <br>
                <label>Data rejestracji:  </label><span>{{allUserData.createdAt | cutDateOfCreation}}</span>
            </div>
            <div class="password-change">
                <h4>Zmiana hasła</h4>
                <label for="current-password">Aktualne hasło</label>
                <input v-model='oldPassword' type="password" name="current-password" id="current-password"><br>
                <label for="new-password">Nowe hasło</label>
                <input v-model='password' type="password" name="new-password" id="new-password">
                <label for="confirm-password">Potwierdź hasło</label>
                <input v-model='password_confirmation' type="password" name="confirm-password" id="confirm-password">
            </div>
            <button @click='changePassword' class="change-password">Zmień hasło</button>
        </div>

        <button @click='deleteProfile' id="deleteProfile">Usuń konto</button>
    </div>
</template>

<script>
export default {
  data() {
    return {
      oldPassword: '',
      password: '',
      password_confirmation: '',
      imageLoading: false,
    };
  },
  computed: {
    userName: {
      set(value) {
        this.$store.dispatch('user/changeUserName', value);
      },
      get() {
        return this.$store.getters.getUserData.firstName;
      },
    },
    userLastname: {
      set(value) {
        this.$store.dispatch('user/changeUserLastname', value);
      },
      get() {
        return this.$store.getters.getUserData.lastName;
      },
    },
    companyName: {
      set(value) {
        this.$store.dispatch('user/changeCompanyName', value);
      },
      get() {
        return this.$store.getters.getUserData.companyName || '';
      },
    },
    companyAddress: {
      set(value) {
        this.$store.dispatch('user/changeCompanyAddress', value);
      },
      get() {
        return this.$store.getters.getUserData.companyAddress || '';
      },
    },
    allUserData() {
      return this.$store.getters.getUserData;
    },
  },
  methods: {
    changePassword() {
      this.$store.dispatch('user/changePassword', { oldPassword: this.oldPassword, password: this.password, password_confirmation: this.password_confirmation });
    },
    bannerEditHandler(e) {
      const file = e.target.files[0];
      const formData = new FormData();
      formData.append('logo', file);
      formData.append('_method', 'PUT');

      this.imageLoading = true;
      this.$store.dispatch('user/changeCompanyBanner', formData).then(() => {
        this.imageLoading = false;
      }).catch(e => {
        this.imageLoading = false;
      });
    },
    removeBanner() {
      this.$store.dispatch('user/deleteCompanyLogo');
    },
    deleteProfile() {
      this.$store.dispatch('user/deleteProfile');
    },
  },
  filters: {
    cutDateOfCreation: (value) => {
      return value.slice(0, 10);
    },
  },
};
</script>

<style lang="scss">
.profile {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-row-gap: 50px;
    // grid-template-rows: auto 50px;
    grid-column-gap: 50px;
    @media screen and (max-width: 980px){
        grid-template-columns: 1fr;
        grid-template-rows: auto;
        grid-row-gap: 50px;
    }
    h4 {
        margin-top:0;
        color: #474751;
    }
    &__data, &__settings {
        background-color: #FFFFFF;
        padding:50px 20px;
        border-radius: 5px;
        box-shadow: 0px 2px 5px 1px #d6d6d6;
    }
    &__data {
        display: grid;
        grid-template-columns: 1fr;
        grid-row-gap: 20px;
        label {
            display: block;
            font-weight: normal;
            font-size: 13px;
            color: #606266;
            margin-bottom: 5px;
        }
    }
    &__settings {
        display: grid;
        grid-template-columns: 1fr;
        grid-row-gap: 20px;
        .info {
            label {
                font-weight: normal;
                display:inline-block;
                font-size: 13px;
                color: #606266;
                margin-bottom: 20px;
                margin-right: 10px;
            }
            label + span {
                font-size: 14px;
                color: #474751;
            }
        }
        .password-change {
            label {
                display: block;
                font-weight: normal;
                font-size: 13px;
                color: #606266;
                margin-bottom: 5px;
            }
        }
        .change-password {
            background-color: #6161F5;
            border: none;
            padding: 10px 30px;
            border-radius: 20px;
            color: #E4E9EF;
            cursor: pointer;
            justify-self: flex-end;
            align-self: center;
            @media screen and (max-width: 980px){
              justify-self: center;
            }
            &:focus, &:active {
                outline: none;
            }
        }
    }
    input {
        margin-bottom: 30px;
        border-radius: 3px;
        border: 1px solid #DCDCDE;
        padding: 10px;
        font-size: 14px;
        color: #232323;
        width: 100%;
        &:last-child {
            margin-bottom: 0;
        }
    }
}
.img-wrap{
    position:relative;
    /*height: 260px;*/
    /*width: 690px;*/
    display: flex;
    justify-content: flex-start;
    height: auto;
    max-width: 300px;
    margin-bottom: 30px;
    img {
        height: auto;
        cursor: pointer;
        object-fit: scale-down;
        max-width: 100%;
        cursor:pointer;
    }
    &__loader {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(251, 251, 251, 0.81);
    }
    &--hover-block {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        display: none;
        background-color: rgba(251, 251, 251, 0.81);
        label {
            margin-right:10px;
        }
        input {
            width: 100%;
            position: absolute;
            z-index: -999999999999;
            left: -99999999999999px;
        }
        svg {
            width: 20px;
            height: 20px;
            cursor: pointer;
            color: #4c4cbb;
        }
        button {
            background-color: transparent;
            border:none;
            &:focus, &:active {
                outline: none;
            }
        }
    }
}
.img-wrap:hover .img-wrap--hover-block {
    display: flex;
    align-items: center;
    justify-content: center;
}
#deleteProfile {
    background-color: #FF426A;
    margin-right: 0;
    border: none;
    padding: 10px 30px;
    border-radius: 20px;
    color: #E4E9EF;
    cursor: pointer;
    grid-column: 2;
    justify-self: flex-end;
    &:active, &:focus {
        outline: none;
    }
    @media screen and (max-width: 980px){
        grid-column: 1;
    }
}
</style>
