<template>
      <nav class="bg-yellow-400">
          <div class="container mx-auto px-7 py-8 flex justify-between ">
                <div class="w-4/12">
                    <img src="/images/logo_light.png" alt="">
                </div>
                <div>
                    <template v-if="!$page.props.user">
                        <inertia-link class="mx-2" :href="route('login')">Login</inertia-link>
                        <inertia-link class="mx-2" :href="route('register')">Register</inertia-link>
                    </template>
                    <template v-if="$page.props.user">
                        <form method="POST" @submit.prevent="logout">
                            <button type="submit"> Log Out</button>
                        </form>
                    </template>
                </div>
          </div>
      </nav>
      <div class="border-t border-yellow-500 bg-yellow-400 sticky top-0">
          <nav class="container mx-auto flex flex-row">
              <div class="flex h-full w-96 nav-left">
                  <ul class="flex flex-row">
                        <li class="flex flex-col">
                            <a class="px-7 py-3 font-bold" href=""> 
                                <v-icon icon="bars"></v-icon> Shop by Department
                            </a>
                        </li>
                  </ul>
              </div>
              <div class="nav-right flex flex-row justify-between w-full">
                  <ul class="flex flex-grow flex-row flex-wrap">
                        <li class="flex flex-col">
                            <a class="px-5 py-3 text-sm" href=""> 
                                Home
                                <v-icon size="sm" class="ml-2" icon="chevron-down"></v-icon>
                            </a>
                        </li>
                        <li class="flex flex-col">
                            <a class="px-5 py-3 text-sm" href=""> 
                                Shop
                                <v-icon size="sm" class="ml-2" icon="chevron-down"></v-icon>
                            </a>
                        </li>
                        <li class="flex flex-col">
                            <a class="px-5 py-3 text-sm" href=""> 
                                Pages
                                <v-icon size="sm" class="ml-2" icon="chevron-down"></v-icon>
                            </a>
                        </li>
                        <li class="flex flex-col">
                            <a class="px-5 py-3 text-sm" href=""> 
                                Blogs
                                <v-icon size="sm" class="ml-2" icon="chevron-down"></v-icon>
                            </a>
                        </li>
                  </ul>
                  <ul class="flex flex-row flex-wrap">
                        <li class="flex flex-col">
                            <inertia-link class="px-5 py-3 text-sm" href=""> 
                                Became a vendor
                            </inertia-link>
                        </li>
                        <span class="py-3">|</span>
                        <li class="flex flex-col">
                            <a class="px-5 py-3 text-sm" href=""> 
                                Track your order
                            </a>
                        </li>
                        <span class="py-3">|</span>
                        <li class="flex flex-col">
                            <a class="px-5 py-3 text-sm" href=""> 
                               US Dollar
                            </a>
                        </li>
                        <span class="py-3">|</span>
                        <li class="flex flex-col">
                            <a class="px-5 py-3 text-sm" href=""> 
                               English
                            </a>
                        </li>
                  </ul>
              </div>
          </nav>
      </div>
</template>

<script>
export default {
    methods : {
        logout() {
            this.$inertia.post(route('logout'));
        },
    }
}
</script>

<style>

</style>