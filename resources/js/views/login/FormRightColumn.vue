<template>
    <div class='right-column'>
        <img src="../../assets/login_page/cloud-login.png" alt="top-cloud">
        <img src="../../assets/login_page/town-login.png" alt="town">
        <img src="../../assets/login_page/balloon-login.png" alt="balloon">
    </div>
</template>

<script>
export default {

};
</script>

<style lang="scss" scoped>
.right-column {
    background-color: #151627;
    position:relative;
    background-image:url('../../assets/login_page/cloud-login.png'), url('../../assets/login_page/cloud-login.png');
    background-position:100px 170px, 200px 200px;
    background-repeat: no-repeat;
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
    box-shadow: 2px 3px 6px 0 #80808087;
    @media screen and (max-width: 1240px){
      &{
        display:none;
      }
    }
    img[alt='top-cloud']{
      position:absolute;
      top:50px;
      right:0;
      @media screen and (min-width: 1240px){
        &{
          left:-50px;
        }
      }
    }
    img[alt='balloon']{
      position:absolute;
      animation: balloon 25s ease-out 0s infinite;
      top: 10%;
      right:40%;
    }
    img[alt='town']{
      position: absolute;
      right: 0;
      @media screen and (min-width: 1240px){
        &{
          max-width: 700px;
          bottom: -18px;
        }
      }
    }
}
</style>
