<template>
  <v-app id="inspire">
    <v-main>
      <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
          <v-col cols="12" sm="8" md="4">
            <v-card class="elevation-12">
              <v-toolbar color="primary" dark flat>
                <v-toolbar-title>Login form</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-tooltip bottom></v-tooltip>
              </v-toolbar>
              <v-card-text>
                    <v-form>
                        <v-container>
                            <v-row>
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="form.name"
                                        label="Full Name"
                                    ></v-text-field>
                                    <div
                                        class="invalid-feedback"
                                        v-if="errors.name"
                                    >
                                        {{ errors.name[0] }}
                                    </div>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="form.email"
                                        label="Email"
                                    ></v-text-field>
                                    <div
                                        class="invalid-feedback"
                                        v-if="errors.email"
                                    >
                                        {{ errors.email[0] }}
                                    </div>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col cols="12" md="6">
                                    <v-select
                                        v-model="form.role"
                                        :items="User_Role"
                                        label="User Role"
                                    >
                                    </v-select>
                                    <div
                                        class="invalid-feedback"
                                        v-if="errors.role"
                                    >
                                        {{ errors.role[0] }}
                                    </div>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="form.password"
                                        label="Password"
                                    ></v-text-field>
                                    <div
                                        class="invalid-feedback"
                                        v-if="errors.password"
                                    >
                                        {{ errors.password[0] }}
                                    </div>
                                </v-col>
                            </v-row>
                        </v-container>
                        <div class="text-right">
                             <v-btn color="green darken-1" type="submit" @click.prevent="register" text>Register</v-btn>
                        </div>
                    </v-form>
              </v-card-text>
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
        name: "",
        email: "",
        password: "",
        role: ""
      },
    errors: {},

    User_Role: [
        "Admin",
        "User"
    ],

    };
  },

  methods: {
      register(){

        User.register(this.form)
        .then(() => {
            this.$router.push({name: "login"});
        })
        .catch(error => {
            if(error.response.status === 422){
                this.errors = error.response.data.errors;
            }
        })
      }
  }
};
</script>
