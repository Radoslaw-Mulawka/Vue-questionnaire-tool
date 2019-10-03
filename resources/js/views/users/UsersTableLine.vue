<template>
    <tr>
        <td data-th="Imie"> {{user.firstName}} </td>
        <td data-th="Nazwisko"> {{user.lastName}} </td>
        <td data-th="E-mail"> {{user.email}} </td>
        <td data-th="Kampanie"> {{user.campaigns}} </td>
    </tr>
</template>

<script>
export default {
  props: ['user'],
};
</script>

<style lang="scss">
    .date {
        white-space: nowrap;
        span{
            color: #9b9fa5;
        }
    }
</style>
