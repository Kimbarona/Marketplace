<template>
<v-app id="inspire">
    <v-main>
        <v-container class="fill-height" fluid>
            <v-row align="center" justify="center">
                <v-col cols="12" sm="8" md="4">
                    <v-card class="elevation-8">
                        <v-toolbar class="loginHeader" dark flat>
                            <v-toolbar-title>User's Login</v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-tooltip bottom></v-tooltip>
                        </v-toolbar>
                        <v-card-text>
                            <v-form>
                                <v-text-field label="Email" v-model="form.email" name="email" prepend-icon="mdi-mail" type="text"></v-text-field>
                                <div class="invalid-feedback" v-if="errors.email">
                                    {{ errors.email[0] }}
                                </div>
                                <v-text-field label="Password" v-model="form.password" name="password" prepend-icon="mdi-lock" type="password"></v-text-field>
                                <div class="invalid-feedback" v-if="errors.password">
                                    {{ errors.password[0] }}
                                </div>
                            </v-form>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="green darken-1" type="submit" @click.prevent="login" text>Login</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </v-main>
</v-app>
</template>

<script>
import User from "../../services/Apis/User"
export default {
    props: {
        source: String,
    },
    data() {
        return {
            form: {
                email: "",
                password: "",
            },

            errors: {},
        };
    },

    methods: {
        login() {
            User.login(this.form)
                .then(() => {
                    this.$root.$emit("login", true);
                    localStorage.setItem("auth", "true");
                    this.$router.push({ name: "manage-store" });
                })
                .catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors;
                }
                });
            }
    }
};
</script>
