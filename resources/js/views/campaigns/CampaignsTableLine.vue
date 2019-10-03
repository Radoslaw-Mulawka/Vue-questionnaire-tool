<template>
    <tr>
        <td data-th="Nazwa"> {{campaign.name}} </td>
        <td data-th="Status"><span> </span>
            {{statusText}}
        </td>
        <td data-th="Data wyświetlania">
            <div class="date"><span>od</span> {{campaign.date_from ? campaign.date_from : '-'}}</div>
            <div class="date"><span>do</span> {{campaign.date_to ? campaign.date_to : '00-00-0000'}}</div>
        </td>
        <td data-th="Akcje">
            <router-link :to='`/campaigns/${campaign.id}`'>Edytuj</router-link>
            <router-link :to='`/results/${campaign.id}`'>Wyniki</router-link>
            <button id='deleteCampaign' @click='deleteCampaign(campaign.id)'>Usuń</button>
        </td>
    </tr>
</template>

<script>
export default {
  props: ['campaign'],
  computed: {
    statusText() {
      switch (this.campaign.status) {
        case 0:
          return 'Wersja robocza';
        case 1:
          return 'Opublikowana';
        case 2:
          return 'Wstrzymana';
        case 3:
          return 'Zakończona';
        default:
          return 'Nieznany status';
      }
    },
  },
  methods: {
    deleteCampaign(campaignId) {
      this.$store.dispatch('campaign/deleteCampaign', campaignId).then(() => {
        // this.$router.push({ name: 'campaign' });
      }).catch(error => {
        console.error(error);
      });
    },
  },
};
</script>

<style lang="scss">
.date {
    white-space: nowrap;
    span{
        color: #9b9fa5;
    }
    @media screen and (max-width: 600px) {
        display: inline-block;
        margin-right:10px;
        &:last-child {
            margin-right: 0;
        }
    }
}
td[data-th="Akcje"] > a {
    margin-left: 10px;
    &:first-child {
        margin-left: 0;
    }
}
#deleteCampaign {
    background: transparent;
    border: none;
    color: #FF426A;
    &:focus, &:active {
        outline: none;
    }
}
</style>
