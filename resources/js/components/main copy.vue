<template>
    <div class="layout w-full h-screen">
        <Layout>
            <Sider ref="side1" breakpoint="xs" :hide-trigger="true" collapsible v-model="isCollapsed"
                class="h-screen w-full fixed">
                <Menu active-name="dashboard" class="fixed w-full" theme="dark" width="auto" :class="menuitemClasses"    >
                    <!-- Not Yet Login -->
                    <MenuItem name="dashboard">
                    <router-link to="/">
                        <span>Dashboard</span>
                    </router-link>
                    </MenuItem>

                    <MenuItem name="login">
                    <span>Login</span>
                    </MenuItem>
                    <!-- //Not Yet Login -->
                </Menu>
            </Sider>
            <Layout>
                <Header :style="{ padding: 0 }" class="layout-header-bar fixed w-full">
                    <Icon @click="collapsedSider" :class="rotateIcon" :style="{ margin: '0 20px' }" type="md-menu"
                        size="24"></Icon>
                </Header>
                <Content :style="{ margin: '10px', background: '#fff' }" class="p-2 shadow-lg">
                    <router-view class="bg-white"></router-view>
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
}
</script>
<style scoped>
.layout {
    border: 1px solid #d7dde4;
    background: #f5f7f9;
    position: relative;
    border-radius: 4px;
    /* overflow: hidden; */
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

.dev-run-preview .dev-run-preview-edit {
    display: none
}
</style>