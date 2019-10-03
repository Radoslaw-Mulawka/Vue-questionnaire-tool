<template>
    <div class='block'>
        <div>
            <div style='position:relative;'>
                <img :src='image' :alt='imgAlt'/>
                <span class='block__coloured-circle'>{{data}}</span>
            </div>
        </div>
        <div>
            <div class='block__text'>{{squareText}}</div>
            <router-link v-if='buttonExists' :to='linkPath'>
                <span>{{buttonText}}</span>
                <div class='clearfix'></div>
            </router-link>
        </div>
    </div>
</template>

<script>
import { getFirstWordOfName } from '@/utils/imageImport';
const getFirstWord = getFirstWordOfName;
export default {
  name: 'DashboardSquare',
  props: ['squareNumber', 'imageName', 'imgAlt', 'data', 'squareText', 'buttonExists', 'linkPath', 'buttonText'],
  data() {
    return {
      image: '',
    };
  },
  methods: {
    async imageImport() {
      const myImage = await import(`../../assets/${getFirstWord(this.$options.name)}/${this.imageName}.png`);
      this.image = myImage['default'];
    },
  },
  created() {
    this.imageImport();
  },
};
</script>

<style lang='scss'>
.block{
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    align-items: center;
    height: 100%;
    background-color: white;
    border-radius: 5px;
    box-shadow: 0px 4px 4px #00000038;
    padding: 30px 0;
    img{
        position: absolute;
        right: 50px;
        top: -33px;
        max-width: 70px;
        height: auto;
    }
    &__coloured-circle {
        border: 2px solid #f7c8d2;
        border-radius: 50%;
        background-color: #f7c8d2;
        text-align: center;
        display: block;
        width: 80px;
        height: 80px;
        line-height: 80px;
    }
    &__text {
        font-size: 20px;
        font-weight: 400;
        color: #63676d;
    }
    &:last-child{
        background-image: linear-gradient(90deg,#9e2ad5,#3742ae);
    }
}
</style>
