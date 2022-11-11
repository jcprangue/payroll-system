<template>
  <div>
    <v-card>
    <v-card-title class="grey lighten-2" primary-title>
    <strong>
        <h4>Delete Item</h4>
    </strong>
    </v-card-title>
    <v-card-text>
    <h5 class="py-2">
        Are you sure you want to delete? <br>
    </h5>
    </v-card-text>
    <v-card-actions>
    <v-spacer></v-spacer>
    <v-btn depressed @click="submit()" color="error"
        >Delete</v-btn
    >
    <v-btn depressed @click="close">Cancel</v-btn>
    </v-card-actions>
</v-card>
  </div>
</template>

<script>
export default {
    name: "DeleteModal",
    props: {
        item: Object,
    },
    methods: {
        close() {
            this.$emit("close", true);
        },
        submit() {
           
            this.$emit("submit", this.item.id);
        },
    }
};
</script>