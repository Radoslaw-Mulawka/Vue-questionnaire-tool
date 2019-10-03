<template>
    <h2>
        <div v-if='!$route.params.message' style='text-align: center;'>
            <span>{{title}}</span> do najlepszej <div>aplikacji ankietującej</div>
        </div>
        <p style='text-align: center;' v-html='$route.params.message'></p>
    </h2>
</template>

<script>
export default {
  props: {
    'title': String,
    'api_response_message': String,
  },
};
</script>

<style lang="scss" scoped>
a {
    text-decoration: underline !important;
}
</style>
