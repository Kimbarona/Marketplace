<template>
    <v-app id="inspire">
        <v-navigation-drawer v-model="drawer" app v-if="isLoggedIn">
            <v-list dense>
                <v-list-item to="/dashboard" link v-if="isLoggedIn">
                    <v-list-item-action>
                        <v-icon>fa fa-home</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Dashboard</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-divider class="my-4" v-if="isLoggedIn"></v-divider>
                <v-subheader v-if="isLoggedIn">Master List</v-subheader>

                <v-list-item to="/manage-store" link v-if="isLoggedIn">
                    <v-list-item-action>
                        <v-icon>fa fa-store</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Manage Store</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item to="/manage-product" link v-if="isLoggedIn">
                    <v-list-item-action>
                        <v-icon>fa fa-tasks</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Manage Product</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item to="/manage-price" link v-if="isLoggedIn">
                    <v-list-item-action>
                        <v-icon>fa fa-ruble-sign</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Manage Price</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item to="/manage-recipe" link v-if="isLoggedIn">
                    <v-list-item-action>
                        <v-icon>fa fa-carrot</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Manage Recipe</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item to="/manage-meal" link v-if="isLoggedIn">
                    <v-list-item-action>
                        <v-icon>fa fa-utensils</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Manage Meal</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-divider class="my-4" v-if="isLoggedIn"></v-divider>

                <!-- <v-list-item to="/manage-qr" link v-if="isLoggedIn">
          <v-list-item-action>
            <v-icon>fa fa-qrcode</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Manage Qr</v-list-item-title>
          </v-list-item-content>
        </v-list-item> -->

                <v-list-item to="/manage-user" link v-if="isLoggedIn">
                    <v-list-item-action>
                        <v-icon>fa fa-user-shield</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Manage User</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item to="/meals" link>
                    <v-list-item-action>
                        <v-icon>fa fa-eye</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>View App</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item link @click.prevent="logout" v-if="isLoggedIn">
                    <v-list-item-action>
                        <v-icon>mdi-power</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Logout</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar app color="indigo" dark>
            <v-app-bar-nav-icon
                @click.stop="drawer = !drawer"
            ></v-app-bar-nav-icon>
            <v-toolbar-title>Find Meal</v-toolbar-title>
            <v-spacer></v-spacer>
        </v-app-bar>
        <v-main>
            <v-container class fluid>
                <router-view></router-view>
                <!-- <FlashMessage />
        <vue-confirm-dialog></vue-confirm-dialog> -->
            </v-container>
        </v-main>

        <v-footer class="footer text-center white--text">
            <p class="text-margin">
                {{ new Date().getFullYear() }} — Powered By: MIS/Developers
                Unit.
            </p>
        </v-footer>
    </v-app>
</template>

<script>
import User from "../services/Apis/User";
export default {
    props: {
        source: String
    },

    data() {
        return {
            drawer: null,
            isLoggedIn: false
        };
    },

    mounted() {
        this.$root.$on("login", () => {
            this.isLoggedIn = true;
        });
        this.isLoggedIn = !!localStorage.getItem("auth");
    },
    methods: {
        logout() {
            User.logout().then(() => {
                localStorage.removeItem("auth");
                this.isLoggedIn = false;
                this.$router.push({ name: "login" });
            });
        }
    }
};
</script>
