<template>
  <button v-bind="$attrs" class="rounded hover:bg-opacity-75" :class="`${sizeClass} ${variantClass} ${block ? 'w-full' : ''}`">
      <slot />
  </button>
</template>

<script>
export default {
    props: {
        variant: {
            default: 'primary'
        },
        size: {
            default: ''
        },
        block: {
            type:Boolean,
            default: false
        }
    },
    computed: {
        variantClass: function(){
            let variant = ""
            switch (this.variant) {
                case 'primary':
                    variant = 'bg-blue-500 text-white'
                    break;
                case 'danger':
                    variant = 'bg-red-500 text-white'
                    break;
                case 'warning':
                    variant = 'bg-yellow-500 text-white'
                    break;
                default:
                    variant = 'bg-blue-500 text-white'
                    break;
            }
            return variant
        },
        sizeClass: function(){
            let size = "";
            switch (this.size) {
                case "sm":
                    size = "px-2 py-2 text-sm";
                    break;
            
                case "lg":
                    size = "px-4 py-5 text-lg";
                    break;
                default:
                    size = 'px-3 py-3'
                    break;
            }
            return size
        }
    }

}
</script>

<style>

</style>