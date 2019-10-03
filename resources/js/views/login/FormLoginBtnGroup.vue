<template>
    <div class="button-group">
        <a @click.prevent.stop='changePath("passwordReset", $event)'>Zapomniałem hasła</a>
        <a @click.prevent='changePath("registration")' tag='button' to='registration' >Zarejestruj się</a>
    </div>
</template>

<script>
export default {
  methods: {
    changePath(path, event) {
      switch (path) {
        case 'passwordReset': this.$router.push('password-reset-step-1');
          break;
        case 'registration': this.$router.push('registration');
          break;
      }
    },
  },
};
</script>
