<template>
    <div class="campaign__enter-text">
        <span>Wprowadzenie do ankiety <small>( ten tekst wyświetli się na ankiecie widocznej dla użytkownika )</small></span>
        <input :disabled='isSending || this.$store.getters.getCampaignData.status == 1' v-model.lazy='campaignEnterText' type="text" placeholder="Zapraszamy do wypełnienia ankiety. Dzięki temu możesz pomóc nam zmienić się na lepsze">
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
    campaignEnterText: {
      set(enterText) {
        this.temporaryValue = enterText;
        this.isSending = true;
        this.$store.dispatch('campaign/changeCampaignEnterText', enterText).then(() => {
          this.isSending = false;
        });
      },
      get() {
        return this.isSending ? this.temporaryValue : this.$store.getters.getCampaignData.enterText;
      },
    },
  },
};
</script>
