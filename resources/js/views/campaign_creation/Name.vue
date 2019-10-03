<template>
    <div class="campaign__name">
        <span>Nazwij swoją kampanię <small>(to jest tylko dla Ciebie)</small></span>
        <input v-if='!$route.params.id' :disabled='isSending || this.$store.getters.getCampaignData.status == 1' type="text" v-model='campaignNameCampaignCreation'>
        <input v-else :disabled='isSending || this.$store.getters.getCampaignData.status == 1' type="text" v-model.lazy='campaignName'>
    </div>
</template>

<script>
export default {
  data() {
    return {
      isSending: false,
      temporaryValue: '',
    };
  },
  computed: {
    campaignName: {
      set(name) {
        this.temporaryValue = name;
        this.isSending = true;
        this.$store.dispatch('campaign/changeCampaignName', name).then(() => {
          this.isSending = false;
        });
      },
      get() {
        return this.isSending ? this.temporaryValue : this.$store.getters.getCampaignData.name;
      },
    },
    campaignNameCampaignCreation: {
      set(name) {
        this.$store.dispatch('campaign/changeCampaignNameCampaignCreation', name);
      },
      get() {
        return this.$store.getters.getCampaignData.name;
      },
    },
  },
};
</script>
