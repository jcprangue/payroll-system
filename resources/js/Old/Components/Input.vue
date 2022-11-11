<template>
  <div :class="`${formDisplay}`">
      <label v-if="label != ''">{{label}}</label>
      <input :type="type" v-bind="$attrs" :class="inputClass" v-model="inputValue">
      <template v-if="errorText != null">
          <template v-if="typeof errorText == 'object'">
              <span class="text-xs text-red-500" v-for="error in errorText" :key="error">
                  {{error}}
              </span>
          </template>
          <template v-if="typeof errorText == 'string'">
              <span class="text-xs text-red-500" >
                  {{errorText}}
              </span>
          </template>
      </template>
  </div>
</template>

<script>
export default {
    props: {
        display: {
            default: 'column'
        },
        type: {
            default: 'text'
        },
        label: {
            default: ''
        },
        errorText: {
            default: null
        },
        densed: {
            type: Boolean,
            default: false
        },
        modelValue: {
            default: ''
        }
    },
    emits: ["update:modelValue"],
    computed: {
        inputClass: function(){
            let style = "py-3 px-4 border focus:ring-0 outline-none ";
            let borderColor = "border border-gray-300 focus:border-blue-300"
            if(this.errorText != null)
            {
                borderColor = " border-red-300 focus:border-red-500"
            }
            style += this.densed ? 'text-sm py-2 px-2' : '' 
            return `${style} ${borderColor}`;
        },
        inputValue: {
            set: function(val){
                this.$emit('update:modelValue', val)
            },
            get: function(){
                return this.modelValue;
            }
        },
        formDisplay: function(){
            if(this.display == 'row')
            {
                return `flex flex-row mb-4`
            }
            return 'flex flex-col mb-4'
        }
    }
}
</script>

<style>

</style>