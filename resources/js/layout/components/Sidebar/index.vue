<template>
  <el-scrollbar wrap-class="scrollbar-wrapper">
    <div class="sidebar-logo">
      <img :src='image' alt="Logo">
    </div>
    <el-menu
      :show-timeout="200"
      :default-active="$route.path"
      :collapse="isCollapse"
      mode="vertical"
      background-color="#151627"
      text-color="#bfcbd9"
      active-text-color="#409EFF"
      position="relative"
    >
      <sidebar-item v-for="route in routes" :key="route.path" :item="route" :base-path="route.path"/>
      <hamburger
        ref='hamburderContainer'
        id="hamburger-container"
        :is-active="sidebar.opened"
        class="hamburger-container"
        @toggleClick="toggleSideBar"
        :isOpened='sidebar.opened'/>
    </el-menu>
  </el-scrollbar>
</template>

<script>
import { mapGetters } from 'vuex';
import SidebarItem from './SidebarItem';
import Hamburger from '@/components/Hamburger';
export default {
  components: { SidebarItem, Hamburger },
  data() {
    return {
      image: '',
    };
  },
  created() {
    this.imageImport();
  },
  computed: {
    ...mapGetters([
      'sidebar',
      'permission_routers',
    ]),
    routes() {
      return this.$store.state.permission.routes;
    },
    isCollapse() {
      return !this.sidebar.opened;
    },
  },
  methods: {
    toggleSideBar() {
      this.$store.dispatch('app/toggleSideBar');
    },
    async imageImport() {
      const myImage = await import(`../../../assets/sidebar/light-logo.png`);
      this.image = myImage['default'];
    },
  },
};
</script>

<style lang="scss" scoped>
.sidebar-logo {
  height: 65px;
  background-color: #ff426a;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 10px;
  position: relative;
  img {
    width: 50px;
    max-width: 100%;
    height: auto;
  }
}
.hamburger-container{
  position:absolute;
  bottom: 0;
  width: 100%;
  height: 35px;
  background-color: #21223a;
  display:flex;
  align-items: center;
}
</style>
