<template>
  <div class="layout w-full h-screen">
      <Layout class="h-full">
          <Sider ref="side1" breakpoint="xs" :hide-trigger="true" collapsible v-model="isCollapsed"
              class="h-full w-full fixed">
              <Menu active-name="dashboard" class="w-full" theme="dark" width="auto" :class="menuitemClasses"    >

                  <MenuItem v-if="!this.isLogged_in" name="dashboard">
                  <router-link to="/user/dashboard">
                      <p>Dashboard</p>
                  </router-link>
                  </MenuItem>

                  <!-- Consumer -->
                  <MenuItem v-if="isConsumerVisible" name="purchasedHistory">
                  <router-link to="/user/purchasedHistory">
                      <p>Purchased History</p>
                  </router-link>
                  </MenuItem>
                  <!-- /Consumer -->

                  <!-- Cashier -->


                  <!-- /Cashier -->

                  <!-- Admin -->


                  <!-- /Admin -->




                  <MenuItem name="logout" v-if="!this.isLogged_in" @click="logout()">
                  <span>Logout</span>
                  </MenuItem>

                  <!-- Not Yet Login -->
                  <MenuItem v-if="this.isLogged_in" name="dashboard">
                  <router-link to="/">
                      <span>Dashboard</span>
                  </router-link>
                  </MenuItem>

                  <MenuItem name="login" v-if="this.isLogged_in" @click="this.$router.push({ path: '/login' })">
                  <span>Login</span>
                  </MenuItem>
                  <!-- //Not Yet Login -->
              </Menu>
          </Sider>
          <Layout>
              <Header :style="{ padding: 0 }" class="layout-header-bar  w-full">
                  <Icon @click="collapsedSider" :class="rotateIcon" :style="{ margin: '0 20px' }" type="md-menu"
                      size="24"></Icon>
              </Header>
              <Content :style="{ margin: '10px', background: '#fff' }" class="p-2 shadow-lg content">
                  <router-view></router-view>
              </Content>
          </Layout>
      </Layout>
  </div>
</template>
<script>
export default {
  data() {
      return {
          isCollapsed: false,
          isConsumerVisible: false,
          isLogged_in: false
      }
  },
  computed: {
      rotateIcon() {
          return [
              'menu-icon',
              this.isCollapsed ? 'rotate-icon' : ''
          ];
      },
      menuitemClasses() {
          return [
              'menu-item',
              this.isCollapsed ? 'collapsed-menu' : ''
          ]
      }
  },
  methods: {
      collapsedSider() {
          // v-if="this.localStorage.getItem('userRole')"
          this.$refs.side1.toggleCollapse();
      },
      logout() {
          axios.get('/sanctum/csrf-cookie').then(response => {
              axios.post('/api/logout')
                  .then(response => {
                      localStorage.removeItem('authToken');
                      localStorage.removeItem('userRole');
                      this.$router.push({ path: '/login' })
                  })
                  .catch(function (error) {
                      console.error(error);
                  });
          })
      }
  },
  created() {
      const thiss = this;
      console.log();
      if (localStorage.getItem('userRole') == 3) {
          thiss.isConsumerVisible = true;
      }
      if (localStorage.getItem('authToken') == null) {
          thiss.isLogged_in = true;
      } else {
          thiss.isLogged_in = false;
      }
  }
}
</script>
<style scoped>
.layout {
  background: #f5f7f9;
}

.layout-header-bar {
  background: #fff;
  box-shadow: 0 1px 1px rgba(0, 0, 0, .1);
}

.menu-item span {
  display: inline-block;
  overflow: hidden;
  width: 69px;
  text-overflow: ellipsis;
  white-space: nowrap;
  vertical-align: bottom;
  transition: width .2s ease .2s;
}

.menu-item i {
  transform: translateX(0px);
  transition: font-size .2s ease, transform .2s ease;
  vertical-align: middle;
  font-size: 16px;
}

.collapsed-menu span {
  width: 0px;
  transition: width .2s ease;
}

.collapsed-menu i {
  transform: translateX(5px);
  transition: font-size .2s ease .2s, transform .2s ease .2s;
  vertical-align: middle;
  font-size: 22px;
}

.content {
  margin: 10px;
  background: #fff;
  padding: 2px;
  overflow-y: auto;
  height: calc(100vh - 64px);
}

.dev-run-preview .dev-run-preview-edit {
  display: none
}
</style>