<template>
  <app-layout>
      <v-card
            class="mx-auto mt-6"
            max-width="500px"
            outlined
        >
        <v-card-title>
            <h2 class="text-h6">Become our vendor</h2>
        </v-card-title>
        <v-card-text >
            <div class="pa-6">
                <form @submit.prevent="submit">
                    <v-text-field
                        class="rounded-0 mb-2"
                        placeholder="First Name"
                        solo
                        dense
                        flat
                        v-model="form.first_name"
                        :error-messages="form.errors.first_name"
                        outlined
                    ></v-text-field>
                    <v-text-field
                        class="rounded-0 mb-2"
                        placeholder="Last Name"
                        solo
                        dense
                        flat
                        v-model="form.last_name"
                        :error-messages="form.errors.last_name"
                        outlined
                    ></v-text-field>
                    <v-text-field
                        class="rounded-0 mb-2"
                        placeholder="Email"
                        solo
                        dense
                        flat
                        type="email"
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
                                <v-icon  v-if="show_password" icon="eye" ></v-icon>
                                <v-icon  v-if="!show_password" icon="eye-slash" ></v-icon>
                            </v-btn>
                        </template>
                    </v-text-field>
                    <v-text-field
                        class="rounded-0 mb-2"
                        placeholder="Confirm Password"
                        solo
                        dense
                        flat
                        outlined
                        v-model="form.password_confirmation"
                        :error-messages="form.errors.password_confirmation"
                        :type="show_password_confirmation ? 'text' : 'password'"
                    >
                        <template #append>
                            <v-btn plain fab small @click="show_password_confirmation = !show_password_confirmation">
                                <v-icon  v-if="show_password_confirmation" icon="eye" ></v-icon>
                                <v-icon  v-if="!show_password_confirmation" icon="eye-slash" ></v-icon>
                            </v-btn>
                        </template>
                    </v-text-field>
                    <v-btn block type="submit" large color="yellow accent-4">Register</v-btn>
                </form>
            </div>
        </v-card-text>
         <v-card-text>
            <p>Connect with: </p>
            <v-btn :href="route('socialite', 'facebook')" tile color="blue darken-4 " large>
                <v-icon class="white--text" size="2x" :icon="['fab', 'facebook']"></v-icon>
            </v-btn>
            
            <v-btn :href="route('socialite', 'google')" tile color="red darken-4 " large>
                <v-icon class="white--text" size="2x" :icon="['fab', 'google-plus-g']"></v-icon>
            </v-btn>
            
            <v-btn :href="route('socialite', 'github')" tile color="grey darken-4 " large>
                <v-icon class="white--text" size="2x" :icon="['fab', 'github']"></v-icon>
            </v-btn>
        </v-card-text>
      </v-card>
  </app-layout>
</template>

<script>
import AppLayout from '@/Partials/PublicLayout'
export default {
    components: {
        AppLayout
    },
    data() {
        return {
            form: this.$inertia.form({
                first_name: '',
                last_name: '',
                email: '',
                password: '',
                password_confirmation: '',
                terms: false,
				role: [2, 3],

            }),
            show_password: false,
            show_password_confirmation: false
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('register'), {
                onFinish: () => this.form.reset('password', 'password_confirmation'),
            })
        }
    }
}
</script>

<style>

</style>