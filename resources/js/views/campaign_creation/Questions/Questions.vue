<template>
    <div class="campaign__questions">
        <template v-if='questions.length>0'>
            <Question v-for='(question,index) in questions'
            :id='question.id !== undefined ? question.id : null'
            :localQuestionId='question.id !== undefined ? null : index'
            :type = question.type
            :required = question.required
            :questionMainText = question.questionMainText
            :questionAdditionalText = question.questionAdditionalText
            :options = question.options
            :key='question.id'
            :index='index'>
            </Question>
        </template>
    </div>
</template>

<script>
import Question from './Question.vue';
export default {
  components: {
    Question,
  },
  computed: {
    questions() {
      return this.$store.getters.getCampaignData.questionsList;
    },
  },
};
</script>

<style lang="scss">
.campaign{
    &__questions {
        padding: 50px 100px;
        @media screen and (max-width: 768px){
            padding: 0 20px !important;
        }
    }
    &__question {
        margin-bottom: 50px;
        &:last-child {
            margin-bottom:0;
        }
    }
}
</style>

