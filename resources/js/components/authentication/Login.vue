<template>
    <v-app >
        <v-main>
            <v-container class="fill-height" fluid>
                <v-row align="center" justify="center">
                    <v-col cols="12" sm="8" md="4">
                        <v-card class="elevation-12">
                            <v-window v-model="step">
                                <v-window-item :value="1">
                                    <v-row>
                                        <v-col cols="12" md="8">
                                            <v-card-text class="mt-12">
                                                <h3 class="text-center display-2 green--text ">Sign In</h3>
                                                <div class="text-center" mt-4>
                                                    <v-btn class="mx-2" fab color="" outland>
                                                        <v-icon>fab fa-facebook-f</v-icon>
                                                    </v-btn>
                                                    <v-btn class="mx-2" fab color="" outland>
                                                        <v-icon>fab fa-google-plus-g</v-icon>
                                                    </v-btn>
                                                </div>
                                                <h6 class="text-center mlt-4"></h6>
                                                <v-form>
                                                    <v-text-field
                                                        type="text"
                                                        label="Email"
                                                        name="Email"
                                                        v-model="form.email"
                                                        prepend-icon="email"
                                                        color="green lighten-3">
                                                    </v-text-field>
                                                    <div class="invalid-feedback" v-if="errors.email">
                                                        {{ errors.email[0] }}
                                                    </div>
                                                    <v-text-field
                                                        type="password"
                                                        label="password"
                                                        name="password"
                                                        v-model="form.password"
                                                        prepend-icon="lock"
                                                        color="green lighten-3">
                                                    </v-text-field>
                                                    <div class="invalid-feedback" v-if="errors.password">
                                                        {{ errors.password[0] }}
                                                    </div>
                                                </v-form>
                                                <!-- <h6 class="text-center mt-3">Forget your password ?</h6> -->
                                            </v-card-text>
                                            <div class="text-center mt-3">
                                                <v-btn rounded color="green" dark @click.prevent="login">Login</v-btn>
                                            </div>
                                        </v-col>
                                        <v-col cols="12" md="4" class="green">
                                            <v-card-text class="white--text mt-10">
                                                <h3 class="text-center display-1">Fresh, Morning!</h3>
                                                <!-- <h5 class="text-center">Please, Sign to Manage FindMeal</h5> -->
                                            </v-card-text>
                                            <!-- <div class="text-center">
                                                <v-btn rounded outlined="" dark @click="step++">SIGN UP</v-btn>
                                            </div> -->
                                        </v-col>
                                    </v-row>
                                </v-window-item>
                                <v-window-item :value="2">

                                </v-window-item>
                            </v-window>
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
            step: 1,
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
}
</script>
