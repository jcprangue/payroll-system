<template>
<app-layout>
    <v-container>
        <v-row>
            <v-col cols="12" md="4">
                <template>
                    <v-card
                        class="mx-auto"
                        tile
                    >
                        <v-list dense>
                        <v-subheader>USER INFORMATION</v-subheader>
                        <v-list-item-group
                            v-model="selectedItem"
                            color="primary"
                        >
                            <v-list-item
                            v-for="(item, i) in items"
                            :key="i"
                            >
                            <v-list-item-icon class="align-center">
                                <v-icon class="grey--text lighten-4" :icon="item.icon"></v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>
                                <v-list-item-title v-text="item.text"></v-list-item-title>
                            </v-list-item-content>
                            </v-list-item>
                        </v-list-item-group>
                        </v-list>
                    </v-card>
                    </template>
            </v-col>
            <v-col>
                <v-card>
                    <v-card-text><h1>ACCOUNT PROFILE</h1></v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</app-layout>
</template>

<script>
import AppLayout from '@/Partials/PublicLayout'
export default {
    components: {
        AppLayout
    },
     data: () => ({
      selectedItem: 1,
      items: [
        { text: 'Acount Information', icon: 'user' },
        { text: 'Update Profile', icon: 'pencil-alt' },
        { text: 'Orders', icon: 'book' },
        { text: 'Address', icon: 'map-marker' },
        { text: 'Change Password', icon: 'lock' },
      ],
    }),
}
</script>

<style>

</style>