<template>
    <div class="campaign__add-question add-question">
        <span>Dodaj pytanie</span>
        <div> <!-- :class='{"add-question__question--loader" : ($store.getters.getQuestionLoader.isLoading && ($store.getters.getQuestionLoader.type == "radio") ) }'  -->
            <div
                class='add-question__question'
                @click='$store.dispatch("campaign/addCampaignQuestion", "radio")'>
                <div class="type" :class='{"add-question__question--loader" : ($store.getters.getQuestionLoader.isLoading && ($store.getters.getQuestionLoader.type == "radio") ) }'>
                    <div>
                        <input type="radio" name="" id="" checked disabled>
                        <div></div>
                    </div>
                    <div>
                        <input type="radio" name="" id="" disabled>
                        <div></div>
                    </div>
                    <div>
                        <input type="radio" name="" id="" disabled>
                        <div></div>
                    </div>
                </div>
                <span :class='{"add-question__question--loader" : ($store.getters.getQuestionLoader.isLoading && ($store.getters.getQuestionLoader.type == "radio") ) }'>Jednokrotnego wyboru</span>
                <div v-if='($store.getters.getQuestionLoader.isLoading && ($store.getters.getQuestionLoader.type == "radio") )' class='loader' style='text-align:center;'>
                    <div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                </div>
            </div>
            <div
                class='add-question__question'
                @click='$store.dispatch("campaign/addCampaignQuestion", "checkbox")'>
                <div class="type" :class='{"add-question__question--loader" : ($store.getters.getQuestionLoader.isLoading && ($store.getters.getQuestionLoader.type == "checkbox") ) }'>
                    <div>
                        <input type="checkbox" name="" id="" checked disabled>
                        <div></div>
                    </div>
                    <div>
                        <input type="checkbox" name="" id="" disabled>
                        <div></div>
                    </div>
                    <div>
                        <input type="checkbox" name="" id="" checked disabled>
                        <div></div>
                    </div>
                </div>
                <span :class='{"add-question__question--loader" : ($store.getters.getQuestionLoader.isLoading && ($store.getters.getQuestionLoader.type == "checkbox") ) }'>Wielokrotnego wyboru</span>
                <div v-if='($store.getters.getQuestionLoader.isLoading && ($store.getters.getQuestionLoader.type == "checkbox") )' class='loader' style='text-align:center;'>
                    <div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                </div>
            </div>
            <div
                class='add-question__question'
                @click='$store.dispatch("campaign/addCampaignQuestion", "votes")'>
                <div class="type" :class='{"add-question__question--loader" : ($store.getters.getQuestionLoader.isLoading && ($store.getters.getQuestionLoader.type == "votes") ) }'>
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
                <span :class='{"add-question__question--loader" : ($store.getters.getQuestionLoader.isLoading && ($store.getters.getQuestionLoader.type == "votes") ) }'>Odpowiedź typu ocena</span>
                <div v-if='($store.getters.getQuestionLoader.isLoading && ($store.getters.getQuestionLoader.type == "votes") )' class='loader' style='text-align:center;'>
                    <div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                </div>
            </div>
            <div
                class='add-question__question'
                @click='$store.dispatch("campaign/addCampaignQuestion", "text")'>
                <div class="type" :class='{"add-question__question--loader" : ($store.getters.getQuestionLoader.isLoading && ($store.getters.getQuestionLoader.type == "text") ) }'>
                    <input type="text" disabled placeholder="Abc">
                </div>
                <span :class='{"add-question__question--loader" : ($store.getters.getQuestionLoader.isLoading && ($store.getters.getQuestionLoader.type == "text") ) }'>Odpowiedź tekstowa</span>
                <div v-if='($store.getters.getQuestionLoader.isLoading && ($store.getters.getQuestionLoader.type == "text") )' class='loader' style='text-align:center;'>
                    <div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
.add-question {
    &>span {
        display: block;
        color: #8A8A8E;
        margin-bottom: 20px;
        font-size: 16px;
    }
    &>div {
        display: grid;
        grid-template-columns: repeat(auto-fill , 225px);
        grid-row-gap: 50px;
        grid-auto-rows: 200px;
        grid-column-gap: 50px;
        justify-content: center;
        color: #A6B2BE;
    }
    &__question {
        display: grid;
        align-items: end;
        background-color: #EFF2F5;
        text-align: center;
        padding-top: 15px;
        padding-bottom: 15px;
        cursor: pointer;
        transition: transform 0.5s ease;
        position: relative;
        &:hover {
            transform: scale(1.1);
        }
        .type {
            margin-bottom: 40px;
            &>div {
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 10px;
            }
            &>div:last-child {
                margin-bottom:0;
            }
            input[type='text']{
                width: 150px;
            };
            input[type='radio'], input[type='checkbox']{
                height: 25px;
                width: 25px;
            }
            input[type='radio']~div, input[type='checkbox']~div {
                width: 100px;
                height: 10px;
                background-color: #A6B2BE;
                margin-left: 10px;
            }
            svg {
                width: 30px;
                height:30px;
                fill: #A6B2BE;
                &:nth-child(1),
                &:nth-child(2),
                &:nth-child(3) {
                    fill: darken(#A6B2BE, 20%);
                }
            }
        }
        .loader {
            position: absolute;
            left: 50%;
            top:50%;
            transform: translate(-50%, -50%);
            .lds-roller {
                margin: 0;
            }

        }
        @media screen and (max-width: 768px){
            margin: 0 20px !important;
        }
    &__question:nth-child(3), &__question:nth-child(4){
        display: grid;
        grid-template-rows: 1fr 1fr;
        align-items: end;
        .type {
            margin-bottom: 0;
        }
        input[type='text']{
            padding: 5px 10px;
            border-radius: 5px;
            border: 1px solid #A6B2BE;
            background-color: transparent;
        }
    }
    &__question--loader {
        filter: blur(3px);

    }
    @media screen and (max-width: 768px){
        padding: 0 30px !important;
    }
}}
</style>
