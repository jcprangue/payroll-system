<template>

  <v-app id="inspire">
    <v-navigation-drawer
      v-model="drawer"
      app
    >
      <app-sidebar />
    </v-navigation-drawer>
    <v-app-bar app dense style="background-color:green;color:#FFF">
      <v-app-bar-nav-icon @click="drawer = !drawer" style="background-color:green;color:#FFF">
      </v-app-bar-nav-icon>
      <v-toolbar-title>Payroll System</v-toolbar-title>
      <v-spacer />
            <template v-if="$page.props.user">
                <v-menu offset-y>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            plain
                            v-bind="attrs"
                            v-on="on"
                            fab
                             style="color:#FFF"
                        >
                        <v-icon icon="user" ></v-icon>
                        </v-btn>
                    </template>
                    <v-list>
                        <!-- <inertia-link as="v-list-item" :href="route('user.profile')" link> 
                              <v-list-item-icon>
                                <v-icon class="grey--text lighten-4" icon="user"></v-icon>
                            </v-list-item-icon>
                              <v-list-item-content>
                                <v-list-item-title>Profile</v-list-item-title>
                            </v-list-item-content>
                        </inertia-link> -->
                        
                        <v-list-item link @click="logout">
                            <v-list-item-icon>
                                <v-icon class="grey--text lighten-4" icon="sign-out-alt"></v-icon>
                            </v-list-item-icon>
                              <v-list-item-content>
                                <v-list-item-title>Logout</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </template>
    </v-app-bar>
    <v-main class="grey lighten-5">
      <slot />
    </v-main>
  </v-app>
</template>

<script>
  import AppSidebar from '@/Partials/Sidebar' 
export default {
  components: {
    AppSidebar
  },
  data: () => ({ drawer: null }),
  methods: {
      logout() {
          this.$inertia.post(route('logout'));
      },
  }
}
</script>

<style>

</style>