<template>
    <div class="campaign__banner">
        <div>
            <span class='header'>Baner kampanii</span>
            <div class='img-wrap' id='img-wrap'>
                <div class="width"><div></div></div>
                <div class="height"><div></div></div>
                <img :src='campaignImage' alt="Baner"/>
                <template v-if='imageLoading'>
                    <div class='img-wrap__loader'>
                        <div class='loader' style='text-align:center;'>
                            <div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <div v-if='this.$store.getters.getCampaignData.status !== 1'  class='img-wrap--hover-block'>
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
        </div>
    </div>
</template>

<script>
export default {
  data() {
    return {
      imageLoading: false,
    };
  },
  methods: {
    bannerEditHandler(e) {
      const file = e.target.files[0];
      const formData = new FormData();
      formData.append('banner', file);
      formData.append('_method', 'PUT');

      this.imageLoading = true;
      this.$store.dispatch('campaign/changeCampaignBanner', formData).then(() => {
        this.imageLoading = false;
      });
    },
    removeBanner() {
      this.$store.dispatch('campaign/removeBanner');
    },
  },
  computed: {
    campaignImage() {
      return this.$store.getters.getCampaignData.banner;
    },
  },
};
</script>

<style lang="scss">
.banner-header {
    color: #8A8A8E;
    font-size: 14px;
}
.campaign__banner {
    display: flex;
    justify-content: center;
    padding: 50px 0;
    .header {
        display: block;
        color: #8A8A8E;
        font-size: 14px;
        margin-bottom: 60px;
    }
    .img-wrap{
        position:relative;
        /*height: 260px;*/
        /*width: 690px;*/
        display: flex;
        justify-content: center;
        .width, .height {
            position: absolute;
            div {
                background-color: #8A8A8E;
            }
        }
        .width {
            padding: 5px 0;
            border-left: 1px solid #8A8A8E;
            border-right: 1px solid #8A8A8E;
            width: 100%;
            top: -20px;
            div {
                height: 1px;
            }
            &:after {
                content: '690';
                color: #8A8A8E;
                display: inline-block;
                position: absolute;
                top: -20px;
                left: 50%;
                transform: translate(-50%,0);
                font-size: 14px;
            }
        }
        .height {
            height: 100%;
            left: -20px;
            border-top: 1px solid #8A8A8E;
            border-bottom: 1px solid #8A8A8E;
            padding: 0 5px;
            div {
                width: 1px;
                height: 100%;
            }
            &:before {
                content: '260';
                color: #8A8A8E;
                display: inline-block;
                position: absolute;
                top: 50%;
                left: -30px;
                transform: translate(0,-50%);
                font-size: 14px;
            }
        }
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

    @media screen and (max-width: 768px){
        padding: 0 60px !important;
    }
}
</style>
