<template>
    <div>
        <v-card class="flexcard"> 
            <v-toolbar dark color="deep-purple darken-13 darken-1">
                <v-btn
                    text
                    @click="closeModal">
                    <v-icon class="white--text" :icon="['fas', 'times']"></v-icon>
                </v-btn>
                <v-toolbar-title>Payroll Remarks of {{ item.payroll_groups.group_name }}</v-toolbar-title>
                <v-spacer></v-spacer>
            </v-toolbar>

            <div class="ma-5" v-html="item.remarks"></div>
            <v-card-actions>
                <v-spacer></v-spacer>
            <v-btn
                color="deep-purple lighten-2"
                text
                @click="closeModal"
            >
                Close
            </v-btn>
            </v-card-actions>
      </v-card>
    </div>
</template>

<script>
export default {
  name: "PayrollHistory",
  props: {
    item: Object,
  },
  data() {
    return {
      
    };
  },
  methods: {
    closeModal() {
      this.$emit("close", true);
    },
  },
  
};
</script>