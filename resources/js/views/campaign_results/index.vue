<template>
    <div class='campaign' style='margin-top: -50px;'>
        <div v-if='!isLoading' class="campaign__settings">
            <div class="campaign__start-stop">
                <div>WYNIKI</div>
            </div>
        </div>
        <div v-if='!isLoading' :style='loadingStyles' class='campaign__results'>
                <section class='all-side-padding campaign__results__name' :style='{"background-image": "url(" + campaignAnswersData.banner +")" }'>
                    <h2>{{campaignAnswersData.campaignName}}</h2>
                    <div>
                        <div>
                            <small>od: </small> {{campaignAnswersData.dateFrom}}
                        </div>
                        <div>
                            <small>do: </small> {{campaignAnswersData.dateTo ? campaignAnswersData.dateTo : 'trwa'}}
                        </div>
                    </div>
                </section>
                <section class='all-side-padding campaign__results__graph'>
                    <div class="date-progress-circle">
                        <radial-progress-bar :diameter="180"
                                            :completed-steps="completedSteps"
                                            :total-steps="totalSteps"
                                            startColor='#FC3A79'
                                            stopColor='#5d16e5'
                                            innerStrokeColor='#DDDDDD'>
                        <p>TODO 21 dni <br/>do końca</p>
                        </radial-progress-bar>
                        <div>
                            <p>TODO Wyświetleń: <span><b>11 (wszystkich)</b></span></p>
                            <p>TODO Wypełnień: <span><b>22 (wszystkich)</b></span></p>
                        </div>
                    </div>
                </section>
                <main class='all-side-padding'>
                    <h4>Wyniki według pytań</h4>
                    <ResultItem v-for='(question,index) in campaignAnswersData.questions' :key='index' :questionData='question' :index='index'></ResultItem>
                </main>
        </div>
        <div v-else class='loader loader--answers' style='text-align:center;'>
            <div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
        </div>
    </div>
</template>

<script>
import RadialProgressBar from 'vue-radial-progress';
import ResultItem from './ResultItem.vue';
export default {
  components: {
    RadialProgressBar,
    ResultItem,
  },
  data() {
    return {
      isLoading: false,
      completedSteps: 4,
      totalSteps: 10,
    };
  },
  created() {
    this.isLoading = true;
    this.$store.dispatch('campaign/getCampaignAnswers', this.$route.params.id).then(response => {
      this.isLoading = false;
    });
  },
  computed: {
    campaignAnswersData() {
      return this.$store.getters.getCampaignAnswersData;
    },
    loadingStyles() {
      return {
        'marginTop': this.isLoading ? '78px' : ' 0 ',
      };
    },
  },
};
</script>

<style lang="scss">
.vue-simple-progress {
    border-radius: 20px;
}
.vue-simple-progress-bar {
    background: linear-gradient(45deg, #FE3776, #5C16E6) !important;
    border-radius: 20px;
}
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
  &--answers {
    background-color: white;
    padding: 30px;
    text-align: center;
    margin-top: 66px;
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
        }
        .stop-icon {
            width:25px;
            height:25px;
            border:2px solid #A6AAF0;
        }
    }
    &__results {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-template-rows: auto auto;
        grid-gap: 50px;
        width: 90%;
        margin: 0 auto;
        border-radius: 5px;
        @media screen and (max-width: 575px) {
            grid-template-areas: 'name name'
            'circle circle'
            'details details';
        }
        section, main {
            background-color: white;
            box-shadow: 0px 2px 6px -1px grey;
        }
        section:first-of-type {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            &>div {
                display: flex;
                width: 100%;
                max-width: 400px;
                justify-content: space-around;
                flex-wrap: wrap;
            }
            h2 {
                text-align: center;
                margin: 0;
                margin-bottom: 50px;
            }
            span {
                margin-right: 20px;
            }
            @media screen and (max-width: 575px) {
                grid-area: name;
            }
        }
        section:nth-of-type(2) {
            @media screen and (max-width: 575px) {
                grid-area: circle;
            }
        }
        .date-progress-circle {
            display: flex;
            align-items: center;
            justify-content: space-around;
            flex-wrap: wrap;
            p {
                text-align: center;
            }
        }
        main {
            grid-column: span 2;
            @media screen and (max-width: 575px) {
                grid-area: details;
            }
        }
    }
    @media screen and (max-width: 1280px){
        &__settings, &__results {
            width: 100%;
        }
    }
}
.all-side-padding {
    padding:40px;
    @media screen and (max-width: 1200px){
        padding: 20px;
    }
}
</style>

