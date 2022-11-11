<template>
  <div class="w-full relative bg-gray-100">
      <app-header></app-header>
        <main>
            <slot />sdrfgsdg
        </main>
      <app-footer></app-footer>
  </div>
</template>

<script>
import AppHeader from '@/Partials/AppHeader'
import AppFooter from '@/Partials/AppFooter'
export default {
    components: {
        AppHeader,
        AppFooter,
    }
}
</script>

<style>

</style>