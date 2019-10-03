<template>
    <div class='result-item'>
        <div class='result-item__index'>{{index + 1}}</div>
        <div class='result-item__question'>
            <p>
                {{questionData.name}}
            </p>
            <ul>
                <li>Respondentów: <span>{{questionData.respondents}}</span></li>
                <li>Pominięć: <span>{{questionData.omissions}}</span></li>
                <li>Łącznie wybranych opcji: <span>{{questionData.numberOfAnswers}}</span></li>
            </ul>
        </div>
        <div v-if='questionData.type == "radio" || questionData.type== "checkbox"' class='result-item__statistics'>
            <div v-for='optionData in questionData.options' :key='optionData.optionId' class='result-item__statistics--choice'>
                <span>{{optionData.label}}</span>
                <progress-bar max='100' :val='optionData.percentage' size='medium'></progress-bar>
                <div>
                    <span><b>{{optionData.percentage}}%</b></span>
                    <span>{{optionData.results}} odp.</span>
                </div>
            </div>
        </div>
        <div v-if='questionData.type == "text"' class='result-item__statistics'>
            <div class='result-item__statistics--input'>
                <ul>
                    <template v-if='questionData.options.length>0'>
                        <li v-for='(userInput,index) of questionData.options' :key='index'>{{userInput}}</li>
                    </template>
                    <template v-else>
                        <h3>Brak odpowiedzi</h3>
                    </template>
                </ul>
            </div>
        </div>
        <div v-if='questionData.type == "votes"' class='result-item__statistics result-item__statistics--votes'>
            <div class='votes-container'>
                <div>
                    <div>
                        <svg class="svg-class gold-star" aria-hidden="true">
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
                    <span>{{questionData.options['1'].results}} odpowiedzi / {{questionData.options['1'].percent}}%</span>
                </div>
                <div>
                    <div>
                        <svg class="svg-class gold-star" aria-hidden="true">
                            <use xlink:href="#icon-star" />
                        </svg>
                        <svg class="svg-class gold-star" aria-hidden="true">
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
                    <span>{{questionData.options['2'].results}} odpowiedzi / {{questionData.options['2'].percent}}%</span>
                </div>
                <div>
                    <div>
                        <svg class="svg-class gold-star" aria-hidden="true">
                            <use xlink:href="#icon-star" />
                        </svg>
                        <svg class="svg-class gold-star" aria-hidden="true">
                            <use xlink:href="#icon-star" />
                        </svg>
                        <svg class="svg-class gold-star" aria-hidden="true">
                            <use xlink:href="#icon-star" />
                        </svg>
                        <svg class="svg-class" aria-hidden="true">
                            <use xlink:href="#icon-star" />
                        </svg>
                        <svg class="svg-class" aria-hidden="true">
                            <use xlink:href="#icon-star" />
                        </svg>
                    </div>
                    <span>{{questionData.options['3'].results}} odpowiedzi / {{questionData.options['3'].percent}}%</span>
                </div>
                <div>
                    <div>
                        <svg class="svg-class gold-star" aria-hidden="true">
                            <use xlink:href="#icon-star" />
                        </svg>
                        <svg class="svg-class gold-star" aria-hidden="true">
                            <use xlink:href="#icon-star" />
                        </svg>
                        <svg class="svg-class gold-star" aria-hidden="true">
                            <use xlink:href="#icon-star" />
                        </svg>
                        <svg class="svg-class gold-star" aria-hidden="true">
                            <use xlink:href="#icon-star" />
                        </svg>
                        <svg class="svg-class" aria-hidden="true">
                            <use xlink:href="#icon-star" />
                        </svg>
                    </div>
                    <span>{{questionData.options['4'].results}} odpowiedzi / {{questionData.options['4'].percent}}%</span>
                </div>
                <div>
                    <div>
                        <svg class="svg-class gold-star" aria-hidden="true">
                            <use xlink:href="#icon-star" />
                        </svg>
                        <svg class="svg-class gold-star" aria-hidden="true">
                            <use xlink:href="#icon-star" />
                        </svg>
                        <svg class="svg-class gold-star" aria-hidden="true">
                            <use xlink:href="#icon-star" />
                        </svg>
                        <svg class="svg-class gold-star" aria-hidden="true">
                            <use xlink:href="#icon-star" />
                        </svg>
                        <svg class="svg-class gold-star" aria-hidden="true">
                            <use xlink:href="#icon-star" />
                        </svg>
                    </div>
                    <span>{{questionData.options['5'].results}} odpowiedzi / {{questionData.options['5'].percent}}%</span>
                </div>
                <div class="average-score">
                    <div class="circle">
                        {{questionData.options.questionVotesAverage}}
                    </div>
                    <p>Średnia z <br/>wszystkich ocen</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ProgressBar from 'vue-simple-progress';
export default {
  props: ['questionData', 'index'],
  components: {
    ProgressBar,
  },
};
</script>

<style lang="scss" scoped>
.result-item {
    padding: 15px 20px;
    display: grid;
    grid-template-columns: 50px 1fr 1fr;
    border: 1px solid #d8d8dc;
    border-radius: 5px;
    @media screen and (max-width: 1200px){
        grid-template-columns: 1fr;
        grid-row-gap: 30px;
    }
    &:not(:last-of-type){
        margin-bottom: 50px;
    }
    &__index {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        border: 2px solid #0000006b;
        border-radius: 50%;
        color: #0000006b;
    }
    &__question {
        padding-top: 10px;
        p {
            margin: 0;
            text-align: justify;
            padding-right: 50px;
            @media screen and (max-width: 1200px){
                padding: 0;
            }
        }
        ul {
            list-style-type: none;
            color: #64646f;
            @media screen and (max-width: 1200px){
                padding: 0;
            }
        }
        li {
            display: flex;
            justify-content: space-between;
            max-width: 300px;
            margin-bottom: 10px;
            &:last-child {
                margin-bottom: 0;
            }
        }
    }
    &__statistics {
        align-self: center;
    }
    &__statistics--choice {
        display: grid;
        grid-template-columns: 1fr 100px;
        grid-column-gap: 40px;
        align-items: center;
        &:not(:last-of-type){
            margin-bottom: 20px;
        }
        span {
            grid-column: span 2;
        }
        div {
            display: flex;
            flex-direction: column;
            text-align: left;
        }
        progress[value] {
            -webkit-appearance: none;
            appearance: none;
            width: 100%;
            height: 15px;
            background-image: linear-gradient(to right, red , blue);
        }
        progress[value]::-webkit-progress-bar {
            background-color: #DDDDDD;
        }
        progress[value]::-webkit-progress-value {
            border-radius: 2px;
            background-size: 35px 20px, 100% 100%, 100% 100%;
        }
        @media screen and (max-width: 505px){
            grid-template-columns: 1fr 40px;
        }
    }
    &__statistics--input {
        ul {
            list-style: none;
            margin:0;
            padding-left: 20px;
            text-align: justify;
        }
        li {
            position: relative;
            margin-bottom:15px;
            &:last-child {
                margin-bottom: 0;
            }
        }
        ul li::before {
            content: "";
            background-color: #dddddd;
            font-weight: bold;
            display: inline-block;
            border-radius: 50%;
            width: 8px;
            height: 8px;
            position: absolute;
            left: -20px;
            top: 50%;
            transform: translate(0, -50%);
        }
    }
    &__statistics--votes {
        svg {
            width: 25px;
            height:25px;
            fill: #DBDBDD;
        }
        svg.gold-star {
            fill: #EBCA12;
        }
        span {
            font-size:14px;
        }
        .votes-container {
            display: grid;
            grid-template-columns: 50% 50%;
            grid-template-rows: repeat(5,50px);
            grid-row-gap: 10px;
            grid-template-areas: 'star1 total' 'star2 total' 'star3 total' 'star4 total' 'star5 total';
            @media screen and (max-width: 575px) {
                grid-template-rows: auto;
                grid-template-areas: 'total total' 'star1 star1' 'star2 star2' 'star3 star3' 'star4 star4' 'star5 star5';
            }
        }
        .votes-container > div {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            &>div {
                margin-right:10px;
            }
        }
        .votes-container > div:first-child {
            grid-area: star1;
        }
        .votes-container > div:nth-child(2) {
            grid-area: star2;
        }
        .votes-container > div:nth-child(3) {
            grid-area: star3;
        }
        .votes-container > div:nth-child(4) {
            grid-area: star4;
        }
        .votes-container > div:nth-child(5) {
            grid-area: star5;
        }
        div.average-score {
            grid-area: total;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            p {
                text-align: center;
            }
        }
        .circle {
            width: 70px;
            height: 70px;
            background-color: #dbdbdd;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
    }
}
</style>

