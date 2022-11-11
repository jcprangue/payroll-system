<template>
  <app-layout>
    <v-card class="mx-auto mt-6" max-width="500px" outlined>
      <v-card-title>
        <h2 class="text-h6">Welcome back!</h2>
      </v-card-title>
      <v-card-text>
        <div class="pa-6">
          <form @submit.prevent="submit">
            <v-text-field
              class="rounded-0 mb-2"
              placeholder="Username"
              solo
              dense
              flat
              type="text"
              v-model="form.email"
              :error-messages="form.errors.email"
              outlined
            ></v-text-field>
            <v-text-field
              class="rounded-0 mb-2"
              placeholder="Password"
              solo
              dense
              flat
              outlined
              v-model="form.password"
              :error-messages="form.errors.password"
              :type="show_password ? 'text' : 'password'"
            >
              <template #append>
                <v-btn plain fab small @click="show_password = !show_password">
                  <v-icon v-if="show_password" icon="eye"></v-icon>
                  <v-icon v-if="!show_password" icon="eye-slash"></v-icon>
                </v-btn>
              </template>
            </v-text-field>
            <v-checkbox
              v-model="form.remember"
              label="Remember Me"
            ></v-checkbox>
            <v-btn
              block
              type="submit"
              large
              color="green darken-1 white--text"
              >LOGIN</v-btn
            >
          </form>
        </div>
      </v-card-text>
    </v-card>
  </app-layout>
</template>

<script>
import AppLayout from "@/Partials/PublicLayout";
export default {
  components: {
    AppLayout,
  },
  props: {
    canResetPassword: Boolean,
    status: String,
  },

  data() {
    return {
      form: this.$inertia.form({
        email: "",
        password: "",
        remember: false,
      }),
      show_password: false,
    };
  },

  methods: {
    submit() {
      this.form
        .transform((data) => ({
          ...data,
          remember: this.form.remember ? "on" : "",
        }))
        .post(this.route("login"), {
          onFinish: () => this.form.reset("password"),
        });
    },
  },
};
</script>

<style>
</style>