<template>
    <div class="campaign__end-text">
        <span>Treść zakończenia do ankiety ( ten tekst wyświetli się na ankiecie widocznej dla użytkownika )</span>
        <input :disabled='isSending || this.$store.getters.getCampaignData.status == 1' v-model.lazy='campaignEndText' type="text" placeholder="Dziękujemy za wypełnienie ankiety - Twoja opinia jest dla nas ważna">
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
    campaignEndText: {
      set(endText) {
        this.temporaryValue = endText;
        this.isSending = true;
        this.$store.dispatch('campaign/changeCampaignEndText', endText).then(() => {
          this.isSending = false;
        });
      },
      get() {
        return this.isSending ? this.temporaryValue : this.$store.getters.getCampaignData.endText;
      },
    },
  },
};
</script>
