<template>
    <div class='campaign' style='margin-top: -50px;'>
        <div v-if='!isLoading' class="campaign__settings">
            <div class="campaign__start-stop">
                <div>SZKIC</div>
                <template v-if='editMode'>
                    <label :class='{activeButton : campaignData.status == 1 }'>
                        <button @click='changeCampaignStatus("start")'>
                            <svg class="svg-class" aria-hidden="true">
                                <use xlink:href="#icon-play-button" />
                            </svg>
                        </button>
                        <span>Start</span>
                    </label>
                    <label :class='{activeButton : campaignData.status == 2 }'>
                        <button @click='changeCampaignStatus("pause")'>
                            <svg class="svg-class" aria-hidden="true">
                                <use xlink:href="#icon-pause" />
                            </svg>
                        </button>
                        <span>Pause</span>
                    </label>
                    <label :class='{activeButton : campaignData.status == 3 }'>
                        <button @click='changeCampaignStatus("stop")'>
                            <div class="stop-icon"></div>
                        </button>
                        <span>Stop</span>
                    </label>
                </template>
            </div>
            <div class="campaign__save-group">
                <button v-if='!editMode' @click='createCampaign' class="save" :style='{"margin-right" : (editMode ? "20px" : "0 !important")}'>Dodaj</button>
                <button v-if='editMode' @click='deleteCampaign($route.params.id)' class="delete">Usuń</button>
            </div>
        </div>
        <div :style='loadingStyles' class='campaign__creation'>
            <template v-if='isLoading'>
                <div class='loader' style='text-align:center;'>
                    <div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                </div>
            </template>
            <template v-else>
                <Name></Name>
                <Banner v-if='editMode'></Banner>
                <EnterText v-if='editMode'></EnterText>
                <Questions v-if='editMode'></Questions>
                <AddQuestion v-if='editMode'></AddQuestion>
                <EndText v-if='editMode'></EndText>
            </template>
        </div>
        <SideMenu v-if='editMode'></SideMenu>
    </div>
</template>

<script>
import Name from './Name.vue';
import Banner from './Banner.vue';
import EnterText from './EnterText.vue';
import Questions from './Questions/Questions.vue';
import AddQuestion from './AddQuestion.vue';
import EndText from './EndText.vue';
import Campaigns from '@/api/campaigns';
import SideMenu from '../campaign_creation/SideMenu.vue';
const apiCampaign = new Campaigns('campaigns');
export default {
  name: 'CampaignCreation',
  components: {
    Name,
    Banner,
    EnterText,
    Questions,
    AddQuestion,
    EndText,
    SideMenu,
  },
  data() {
    return {
      isCollapse: true,
      isLoading: false,
      editMode: false,
    };
  },
  created() {
    if (this.$route.params.id !== undefined) {
      this.editMode = true;
      this.isLoading = true;
      this.$store.dispatch('campaign/getCampaignData', this.$route.params.id).then(response => {
        this.isLoading = false;
      });
    } else {
      this.$store.dispatch('campaign/clearCampaignData');
    }
  },
  computed: {
    campaignData() {
      return this.$store.getters.getCampaignData;
    },
    loadingStyles() {
      return {
        'marginTop': this.isLoading ? '78px' : ' 0 ',
      };
    },
  },
  methods: {
    async createCampaign() {
      const response = await apiCampaign.store({ name: this.campaignData.name });
      this.$router.push(`/campaigns/${response.data.id}`);
    },
    changeCampaignStatus(campaignStatus) {
      let status;
      switch (campaignStatus) {
        case 'start':
          status = 1;
          break;
        case 'pause':
          status = 2;
          break;
        case 'stop':
          status = 3;
          break;
      };
      this.$store.dispatch('campaign/changeStatus', { status: status });
    },
    deleteCampaign(campaignId) {
      this.$store.dispatch('campaign/deleteCampaign', campaignId).then(() => {
        this.$router.push({ name: 'campaign' });
      }).catch(error => {
        console.error(error);
      });
    },
  },
};
</script>

<style lang="scss">
.activeButton {
    position: relative;
    &:after {
        content:'';
        display:block;
        position:absolute;
        bottom: -10px;
        width:100%;
        border:1px solid #A6AAF0;
    }
    &:not(:last-of-type){
        &:after {
            @media screen and (max-width: 545px) {
                bottom: 5px;
            }
        }
    }
}
.loader {
  .lds-roller {
    display: inline-block;
    position: relative;
    width: 64px;
    height: 64px;
    margin: 20px 0;
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
.campaign {
    &__settings {
        width: 90%;
        margin: 0 auto;
        padding: 20px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    &__start-stop {
        display: flex;
        align-items: center;
        &>div {
            margin-right:20px;
            background-color: #C0CCD9;
            padding: 5px 10px;
            font-size: 14px;
        }
        label {
            color: #A6AAF0;
            display: flex;
            align-items: center;
            margin-right:20px;
        }
        button{
            cursor: pointer;
            margin-right:5px;
            padding: 0;
            display: inherit;
            background-color: transparent;
            border: none;
        }
        svg {
            width: 25px;
            height: 25px;
            fill: #A6AAF0;
            @media screen and (max-width: 545px){
                margin: 10px 0;
            }
        }
        .stop-icon {
            width:25px;
            height:25px;
            border:2px solid #A6AAF0;
        }
        @media screen and (max-width: 545px){
            display: block;
        }
    }
    &__save-group {
        display: flex;
        align-items: center;
        button {
            background-color: #6161F5;
            border: none;
            padding: 10px 30px;
            border-radius: 20px;
            color: #E4E9EF;
            cursor: pointer;
        }
        .delete {
            background-color: #FF426A;
            margin-right:0;
        }
    }
    &__creation {
        background-color: white;
        width: 90%;
        margin: 0 auto;
        border-radius: 5px;
        box-shadow: 0 2px 6px -1px grey;
    }
    @media screen and (max-width: 1280px){
        &__settings, &__creation {
            width: 100%;
        }
    }
    @media screen and (max-width: 1280px){
        .campaign__name,
        .campaign__enter-text,
        .questions,
        .questions-choice,
        .campaign__end-text {
            padding: 50px !important;
        }
    }
    @media screen and (max-width: 750px){
        .campaign__name,
        .campaign__enter-text,
        .questions,
        .questions-choice,
        .campaign__end-text {
            padding: 20px 10px !important;
        }
    }
}
.campaign__name,
.campaign__enter-text,
.campaign__end-text {
    display: flex;
    flex-direction: column;
    background-color: #F6F6F6;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    span {
        color: #8A8A8E;
        margin-bottom: 20px;
        font-size:16px;
        small {
            position: relative;
            color: #797878;
            font-size:14px;
            margin-left: 5px;
            &:before {
                content: '*';
                color: red;
                position:absolute;
                left: -8px;
            }
        }
    }
    input {
        border-radius: 3px;
        border: 1px solid #DCDCDE;
        padding: 10px;
        font-size: 14px;
        color: #232323;
    }
}
.campaign__enter-text,
.campaign__end-text {
    background-color: transparent;
}
.campaign {
    &__name,
    &__banner,
    &__enter-text,
    &__questions,
    &__add-question,
    &__end-text {
        padding:0 100px;
        margin-bottom:50px;
    }
    &__name {
        padding-top:50px;
        padding-bottom:50px;
    }
    &__end-text {
        padding-bottom:50px;
        margin-bottom: 0;
    }
}
button:focus, button:active {
    outline: none;
}
</style>
