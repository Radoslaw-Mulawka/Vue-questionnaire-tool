<template>
  <div class="dashboard-container">
    <!-- <component :is="currentRole"/> -->
    <DashboardSquare
      imageName='Active'
      linkPath="/campaigns"
      imgAlt="active-campaigns-icon"
      squareText="Opublikowanych kampanii"
      buttonExists
      buttonText="Pokaż kampanie"
      squareNumber="1"
      data="111" />
    <DashboardSquare
      imageName="Ending"
      linkPath="/campaigns"
      imgAlt="ending-campaign-icon"
      squareText="Kończących się kampanii"
      buttonExists
      buttonText="Pokaż kampanie"
      squareNumber="2"
      data="111" />
    <DashboardSquare
      imageName="Answers"
      imgAlt="answers-icon"
      squareText="Wszystkich odpowiedzi"
      squareNumber="3"
      data="111" />
    <DashboardSquare
      imageName="See"
      imgAlt="show-icon"
      squareText="Wszystkich wyświetleń"
      squareNumber="4"
      data="111" />
    <DashboardSquare
      imageName="Questions"
      linkPath="/campaigns"
      imgAlt="question-icon"
      squareText="Wszystkich pytań"
      buttonExists
      buttonText="Pokaż najpopularniejszą ankietę"
      squareNumber="5"
      data="111" />

    <div class="block">
      <router-link to='/new-campaign' tag='div' style='display: flex; flex-direction: column; align-items: center; color: white; cursor: pointer'>
          <svg class="svg-icon" aria-hidden="true" style='max-width: 100px; height: auto;'>
            <use xlink:href="#icon-plus-circle" />
          </svg>
          <p>Dodaj kampanię</p>
      </router-link>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import DashboardSquare from './DashboardSquare.vue';

export default {
  name: 'Dashboard',
  components: { DashboardSquare },
  data() {
    return {
      currentRole: 'adminDashboard',
    };
  },
  computed: {
    ...mapGetters([
      'roles',
    ]),
  },
  created() {
    this.$store.dispatch('app/getDashboardInfo');

    if (!this.roles.includes('admin')) {
      this.currentRole = 'editorDashboard';
    }
  },
};
</script>

<style lang="scss" scoped>
.dashboard-container {
    display: grid;
    justify-content: center;
    align-content: center;
    grid-template-columns: repeat(auto-fit, minmax(300px,1fr));
    grid-template-rows: 300px 300px;
    grid-auto-rows: 300px;
    grid-gap: 40px;
}
</style>
