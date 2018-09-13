<template>
    <v-app>
        <v-navigation-drawer
                fixed
                :clipped="$vuetify.breakpoint.mdAndUp"
                app
                absolute
                v-model="drawer"
        >
            <v-list>
                <v-list-tile @click="navigateTo('patient-list')">
                    Списък с пациенти
                </v-list-tile>
                <v-list-tile>wef
                </v-list-tile>
            </v-list>
        </v-navigation-drawer>
        <v-toolbar
                color="blue darken-3"
                dark
                app
                :clipped-left="$vuetify.breakpoint.mdAndUp"
                fixed
        >
            <v-toolbar-title style="width: 300px" class="ml-0 pl-3">
                <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
                <span class="hidden-lg-only">ФДМ - Пловдив </span>
            </v-toolbar-title>
        </v-toolbar>
        <router-view></router-view>

    </v-app>
</template>

<script>
    export default {
        name: "App",
        data() {
            return {
                drawer: false,
            }
        },
        methods: {
            navigateTo(name){
                this.$router.push({ name: name});
                this.drawer = false
            }
        }
    }
</script>

<style scoped>

</style>