<template>
    <el-menu default-active="2" class="el-menu-vertical-demo" :collapse="isCollapse">
        <label for="menu-arrow">
            <svg v-if='isCollapse' class="svg-class" aria-hidden="true">
                <use xlink:href="#icon-arrow-left-circle" />
            </svg>
            <svg v-else class="svg-class" aria-hidden="true">
                <use xlink:href="#icon-arrow-right-circle" />
            </svg>
            <input type="checkbox" name="" id="menu-arrow" v-model="isCollapse" style="margin-bottom: 20px;">
        </label>
        <div v-if='!isCollapse' id="watermark">Premium</div>
        <el-menu-item index="2" v-show='!isCollapse'>
            <h3>Pobierz</h3>
            <div class="qr-block">
                <div>
                    <svg class="svg-class" aria-hidden="true">
                        <use xlink:href="#icon-qr-code" />
                    </svg>
                    <span>PDF</span>
                </div>
                <div>
                    <svg class="svg-class" aria-hidden="true">
                        <use xlink:href="#icon-qr-code" />
                    </svg>
                    <span>JPG</span>
                </div>
                <div>
                    <svg class="svg-class" aria-hidden="true">
                        <use xlink:href="#icon-qr-code" />
                    </svg>
                    <span>PNG</span>
                </div>
                <div>
                    <svg class="svg-class" aria-hidden="true">
                        <use xlink:href="#icon-link2" />
                    </svg>
                    <span>Link</span>
                </div>
            </div>
            <h3>Materiały marketingowe</h3>
            <div class="marketing-block">
                <div>
                    <svg class="svg-class" aria-hidden="true">
                        <use xlink:href="#icon-poster" />
                    </svg>
                    <span>Plakat</span>
                </div>
                <div>
                    <svg class="svg-class" aria-hidden="true">
                        <use xlink:href="#icon-poster-horizontal" />
                    </svg>
                    <span>Kartka</span>
                </div>
            </div>
        </el-menu-item>
        <el-menu-item index="4" v-show='!isCollapse'>
            <h3>Udostępnij</h3>
            <div class="share-block">
                <div>
                    <img src='../../assets/campaign_creation/facebook.png' alt="Facebook Logo"/>
                    <img src='../../assets/campaign_creation/email.png' alt="Email Logo"/>
                    <img src='../../assets/campaign_creation/skype.png' alt="Skype Logo"/>
                    <img src='../../assets/campaign_creation/google.png' alt="Google Logo"/>
                </div>
            </div>
        </el-menu-item>
    </el-menu>
</template>

<script>
export default {
  data() {
    return {
      isCollapse: true,
    };
  },
};
</script>

<style lang="scss" scoped>

#watermark {
    top: 34.5%;
    opacity: 0.7;
    text-align: center;
    background-color: rgba(217, 217, 217, 0.29);
    z-index: 99;
    color: black;
    position: absolute;
    width: 100%;
    height: 66%;
    font-size: 30px;
    padding-top: 90px;
    font-weight: bold;
}
.el-menu--collapse {
    width: 30px;
}
.el-menu {
    position: fixed;
    top: 10%;
    right: 0;
    padding-top: 10px;
    height: 425px !important;
    box-shadow: 0 2px 6px -1px grey;
    h2,h3{
        margin-top:0;
        margin-bottom: 20px;
        color:#54545D;
    }
    &>label{
        position:relative;
        cursor: pointer;
        display: inline-block;
        left:5px;
        svg {
            width:20px;
            height:20px;
            color: blue;
        }
        input {
            display: none;
            position: absolute;
            right: -999999999999999999px;
        }
    }
    &-item{
        height: unset;
        line-height: unset;
        color: #A6B2BE;
        fill: #A6B2BE;
        cursor:default;
        margin-bottom: 20px;
        h3 {
            padding-left: 30px !important;
            font-weight: lighter;
        }
        &:hover {
            background-color:transparent !important;
        }
    }
    .qr-block{
        display: grid;
        grid-template-columns: 1fr 1fr;
        justify-items: center;
        grid-column-gap: 20px;
        grid-row-gap: 20px;
        margin-bottom: 20px;
        margin-left: 15px;
        &>div {
            cursor: pointer;
            transition: transform 0.4s ease;
        }
        &>div:hover {
            transform: scale(1.2);
            fill: #6161F5;
            color: #6161F5;
        }
        svg {
            width: 20px;
            height: 20px;
        }
    }
    .marketing-block {
        display: grid;
        grid-template-columns: 1fr 1fr;
        justify-items: center;
        align-items: center;
        margin-left: 10px;
        &>div:hover {
            fill: #6161F5;
            color: #6161F5;
        }
        svg {
            width: 70px;
            height: 70px;
            margin-bottom: 10px;
        }
        &>div {
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-right: 10px;
        }
    }
    .share-block {
        margin-left: 25px;
        img {
            width: 40px;
            height: 40px;
            padding: 5px;
        }
    }
}
.el-menu-vertical-demo:not(.el-menu--collapse) {
    width: 250px;
    border: 1px solid #E4E9EF;
}
</style>
