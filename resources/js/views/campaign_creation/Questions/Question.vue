<template>
    <section class="campaign__question campaign-question">
        <header class='campaign-question__header'>
            <div>
                <button @click='isClosed=!isClosed'>{{isClosed ? 'Zwiń' : 'Rozwiń'}}</button>
                <span v-if='type=="text"' class='campaign-question__type'>Odpowiedź tekstowa</span>
                <span v-else-if='type=="checkbox"' class='campaign-question__type'>Odpowiedź wielokrotnego wyboru</span>
                <span v-else-if='type=="radio"' class='campaign-question__type'>Odpowiedź jednokrotnego wybotu</span>
                <span v-else-if='type=="votes"' class='campaign-question__type'>Odpowiedź typu ocena</span>
            </div>
            <div>
                <label>
                    <input :disabled='$store.getters.getCampaignData.status === 1' v-model='isRequired' type="checkbox">
                    <span><span>Pytanie</span> wymagane</span>
                </label>
                <template v-if='$store.getters.getCampaignData.status !== 1'>
                    <button :disabled='((questionIsBeingChanged.questionId === id) && questionIsBeingChanged.isBeingChanged) || questionMainText.trim() == ""' :style='{opacity: ((questionIsBeingChanged.questionId === id) && questionIsBeingChanged.isBeingChanged) || questionMainText.trim() == "" ? 0.5 : 1}' @click='copyQuestion'>
                        <svg class="svg-class" aria-hidden="true">
                            <use xlink:href="#icon-copy" />
                        </svg>
                        <span>Kopiuj</span>
                    </button>
                    <button class='delete' :disabled='((questionIsBeingChanged.questionId === id) && questionIsBeingChanged.isBeingChanged)' :style='{opacity: ((questionIsBeingChanged.questionId === id) && questionIsBeingChanged.isBeingChanged) ? 0.5 : 1}' @click='deleteQuestion'>
                        <svg class="svg-class" aria-hidden="true">
                            <use xlink:href="#icon-trash-2" />
                        </svg>
                        <span>Usuń</span>
                    </button>
                </template>
            </div>
        </header>
        <main v-if='isClosed' v-show='isClosed' class='campaign-question__main' :class='{"campaign-question__main--shortened": type == "text" || type == "votes"}'>
            <div class="index">
                {{index + 1}}
            </div>
            <input :disabled='$store.getters.getCampaignData.status == 1' v-model.lazy='mainText'  placeholder='Tutaj podaj treść swojego pytania' type='text' class='campaign-question__main-text'/>
            <input :disabled='$store.getters.getCampaignData.status == 1' v-model.lazy='additionalText' placeholder='Dodatkowa informacja dla użytkownika. Możesz ją pominąć' class="campaign-question__additional-text">
            <div class="campaign-question__options">
                <!-- TEXT OPTION -->
                <input :disabled='$store.getters.getCampaignData.status == 1' v-if='type=="text"' type="text" id="11" placeholder="Tą treść wprowadza użytkownik">
                <!-- RADIO / CHECKBOX OPTION -->
                <template  v-for='(option, index) in options' v-else-if='type=="radio" || type=="checkbox"'>
                    <div class='option' :key='index'>
                        <input :type='type' :name='type'>
                        <input :disabled='$store.getters.getCampaignData.status == 1' placeholder='Tekst odpowiedzi' type='text' :value='option.optionText' @change='changeOptionText(id, option.id)' class='option__text'/>  <!-- v-model='changeOptionText' -->
                        <button v-if='options.length>1' @click='deleteOption(option.id)'>
                            <svg class="svg-class" aria-hidden="true">
                                <use xlink:href="#icon-trash-2" />
                            </svg>
                        </button>
                    </div>
                </template>
                <!-- VOTES OPTION -->
                <template v-else-if='type=="votes"'>
                    <div>
                        <svg class="svg-class" aria-hidden="true">
                            <use xlink:href="#icon-star" />
                        </svg>
                        <svg class="svg-class" aria-hidden="true">
                            <use xlink:href="#icon-star" />
                        </svg>
                        <svg class="svg-class" aria-hidden="true">
                            <use xlink:href="#icon-star" />
                        </svg>
                        <svg class="svg-class" aria-hidden="true">
                            <use xlink:href="#icon-star" />
                        </svg>
                        <svg class="svg-class" aria-hidden="true">
                            <use xlink:href="#icon-star" />
                        </svg>
                    </div>
                </template>
            </div>
            <template v-if='$store.getters.getCampaignData.status !== 1'>
                <button v-if='type=="checkbox" || type=="radio"' class="add-answer" @click='addOption'>Dodaj odpowiedź</button>
            </template>
        </main>
    </section>
</template>

<script>
export default {
  props: {
    'type': {
      type: String,
      required: true,
    },
    'id': {
      type: Number || null,
    },
    'localQuestionId': {
      type: Number || null,
    },
    'required': {
      type: Number,
    },
    'questionMainText': {
      type: String,
    },
    'questionAdditionalText': {
      type: String,
    },
    'options': {
      type: Array,
    },
    'index': {
      type: Number,
    },
  },
  data() {
    return {
      isClosed: false,
    };
  },
  computed: {
    isRequired: {
      set(required) {
        this.$store.dispatch('campaign/setQuestionRequiredField', { isRequired: required ? 1 : 0, localQuestionId: this.localQuestionId, id: this.id });
      },
      get() {
        if (typeof this.id === 'number') {
          return this.$store.getters.getCampaignData.questionsList.find(item => item.id === this.id).required;
        } else {
          return this.$store.getters.getCampaignData.questionsList[this.localQuestionId].required;
        }
      },
    },
    mainText: {
      set(mainText) {
        this.$store.dispatch('campaign/changeMainText', { mainText: mainText, localQuestionId: this.localQuestionId, id: this.id });
      },
      get() {
        if (typeof this.id === 'number') {
          return this.$store.getters.getCampaignData.questionsList.find(item => item.id === this.id).questionMainText;
        } else {
          return this.$store.getters.getCampaignData.questionsList[this.localQuestionId].questionMainText;
        }
      },
    },
    additionalText: {
      set(additionalText) {
        this.$store.dispatch('campaign/changeAdditionalText', { additionalText: additionalText, localQuestionId: this.localQuestionId, id: this.id });
      },
      get() {
        if (typeof this.id === 'number') {
          return this.$store.getters.getCampaignData.questionsList.find(item => item.id === this.id).questionAdditionalText;
        } else {
          return this.$store.getters.getCampaignData.questionsList[this.localQuestionId].questionAdditionalText;
        }
      },
    },
    questionIsBeingChanged() {
      return this.$store.getters.questionIsBeingChanged;
    },
  },
  methods: {
    deleteQuestion() {
      if (this.$store.getters.getCampaignData.status !== 1) {
        this.$store.dispatch('campaign/deleteQuestion', { id: this.id });
      };
    },
    deleteOption(optionId) {
      if (this.$store.getters.getCampaignData.status !== 1) {
        this.$store.dispatch('campaign/deleteOption', { questionId: this.id, optionId: optionId });
      };
    },
    copyQuestion() {
      if (this.$store.getters.getCampaignData.status !== 1) {
        this.$store.dispatch('campaign/copyQuestion', { id: this.id });
      };
    },
    addOption() {
      if (this.$store.getters.getCampaignData.status !== 1) {
        this.$store.dispatch('campaign/addOption', { id: this.id });
      };
    },
    changeOptionText(id, optionId) {
      if (this.$store.getters.getCampaignData.status !== 1) {
        this.$store.dispatch('campaign/changeOptionText', { optionText: event.target.value, questionId: id, optionId: optionId });
      };
    },
  },
};
</script>

<style lang="scss">
@import "~@/styles/mixin.scss";
input:disabled:not([type='checkbox']) {
    background-color: #e6e6e6a6 !important;
}
.campaign-question {
    &__type{
        @media screen and (max-width: 1000px){
            display: none;
        }
    }
    &__header {
        background-color: #DBE0E5;
        height:50px;
        display:flex;
        justify-content: space-between;
        align-items: center;
        padding:10px 20px;
        font-size: 15px;
        &>div {
            display: flex;
            align-items: center;
        }
        button {
            background-color: transparent;
            border: none;
            display:flex;
            align-items: center;
            margin-right:10px;
            cursor: pointer;
            &:focus {
                outline:none;
            }
            &:first-child {
                border-radius: 20px;
                color: white;
                background-color: #A6B2BE;
                padding: 8px 25px;
                margin-right: 10px;
                font-size: 14px;
                width: 96px;
                justify-content: center;
            }
            &:not(:first-child){
                background-color: transparent;
                color: #6161F5;
                padding:0;
            }
            &:last-child{
                margin-right:0;
                // color: #FF426A;
            }
            &>svg {
                margin-right:5px;
                width: 25px;
                height: 25px;
                @media screen and (max-width: 1280px){
                    &{
                        width: 20px;
                        height: 20px;
                    }
                }
            }
        }
        button.delete {
            color: #FF426A;
        }
        label {
            display: flex;
            align-items: center;
            margin-right: 10px;
            font-weight: normal;
            cursor: pointer;
            @media screen and (max-width: 1280px){
                &>span>span{
                    display: none;
                }
            }
        }
        @media screen and (max-width: 525px){
            height: auto;
            display: block;
            &>div {
                display: block;
            }
            label, button {
                margin-bottom: 10px;
            }
        }
    }
    &__main {
        background-color: #EFF2F5;
        display: grid;
        grid-template-columns: 50px 1fr;
        grid-template-rows: auto auto 1fr;
        grid-auto-rows: 50px;
        grid-template-areas: 'index main-text'
            '. additional-text'
            '. answers-list'
            '. add-answer-btn';
        grid-column-gap: 20px;
        grid-row-gap: 20px;
        align-items: center;
        padding:20px;
        .index {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            border: 2px solid #A6B2BE;
            color: #A6B2BE;
            border-radius: 50%;
            grid-area: index;
            justify-self: center;
        }
        @media screen and (max-width: 625px){
            display: block;
        }
    }
    &__main--shortened {
        grid-template-areas: 'index main-text'
            '. additional-text'
            '. answers-list';
        padding: 20px 20px 40px;
    }
    &__main-text, &__additional-text {
      color: #40404B;
      padding: 5px 10px;
      border:none;
      outline:1px solid #b0b4b9;
      background-color: transparent;
        @media screen and (max-width: 425px){
            margin: 10px 0;
            @include absolute-width(200px);
        }
        @media screen and (min-width: 425px) and (max-width: 625px){
            margin: 10px 0;
            @include absolute-width(300px);
        }
    }
    &__main-text {
        font-size:20px;
        font-weight: bold;
        grid-area: main-text;
        @media screen and (max-width: 1280px){
            &{
                font-size: 16px;
            }
        }
    }
    &__additional-text {
        grid-area: additional-text;
        @media screen and (max-width: 1280px){
            &{
                font-size: 14px;
            }
        }
    }
    &__options {
        display: grid;
        grid-template-columns: 70%;
        grid-gap: 30px;
        grid-area: answers-list;
        @media screen and (max-width: 1280px){
            &{
                font-size: 16px;
                grid-gap: 20px;
            }
        }
        label, .option {
            font-weight: normal;
            grid-column: 1;
            display: grid;
            grid-template-columns: 50px 1fr 50px;
            align-items: center;
        }
        .option button {
            color: #FF426A;
        }
        button {
            background-color: transparent;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            // color: #FF426A;
            &:focus, &:active {
                outline: none;
            }
        }
        svg {
            width: 20px;
            height: 20px;
        }
        input[type='radio'] {
            width: 30px;
            height: 30px;
            background-color: white;
            color: white;
            -webkit-appearance: none;
            position:relative;
            margin-right:10px;
            cursor: pointer;
            border-radius:50%;
            &:focus, &:active {
                outline: none;
            }
            &:checked{
                &:after {
                    content: '';
                    display:block;
                    border-radius: 50%;
                    width:15px;
                    height:15px;
                    background-color: #a9a9a9;
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%,-50%);
                    font-size: 20px;
                    font-weight: bold;
                }
            }
            @media screen and (max-width: 1280px){
                &{
                    width: 20px;
                    height: 20px;
                }
            }
        }
        input[type='text']:not(.option__text) {
            border-radius: 3px;
            border: 1px solid #DCDCDE;
            padding: 10px;
            font-size: 14px;
            color: #232323;
        }
        .option__text {
            color: #40404B;
            padding: 5px 10px;
            border:none;

            outline:1px solid #b0b4b9;
            background-color: transparent;
            @media screen and (max-width: 425px){
                margin: 10px 0;
                @include absolute-width(150px);
            }
            @media screen and (min-width: 425px) and (max-width: 625px){
                margin: 10px 0;
                @include absolute-width(250px);
            }
        }
        & > div > svg {
            width: 30px;
            height: 30px;
            fill: #FFCA77;
            margin-right: 10px;
        }
    }
    .add-answer {
        background-color: #6161F5;
        border: none;
        font-size: 15px;
        padding: 8px 25px;
        border-radius: 20px;
        color: #E4E9EF;
        cursor: pointer;
        grid-area: add-answer-btn;
        justify-self: start;
        transition: transform 0.3s ease;
        &:active, &:focus {
            outline: none;
        }
        &:hover {
            transform: scale(1.1)
        };
    }
    input[type='checkbox'] {
        width: 30px;
        height: 30px;
        background-color: white;
        color: white;
        -webkit-appearance: none;
        position:relative;
        margin-right:10px;
        cursor: pointer;
        &:focus, &:active {
            outline: none;
        }
        &:checked{
            &:after {
                content: '\2713';
                color: #585050;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%,-50%);
                font-size: 20px;
                font-weight: bold;
            }
        }
        @media screen and (max-width: 1280px){
            & {
                width: 20px;
                height: 20px;
            }
        }
    }
}
</style>
