<template>
    <div>
        <br />
        <br />
        <div class="card-header">
            <b>Manage User</b>
        </div>
        <br />
        <div class="card-header">
            <div class="my-2">
                <v-btn small color="success" dark @click="createUserList"
                    >create</v-btn
                >
            </div>
        </div>
        <b-card title>
            <v-card-title>
                User List
                <v-spacer></v-spacer>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Search"
                    single-line
                    hide-details
                ></v-text-field>
            </v-card-title>
            <v-data-table :headers="headers" :items="DataUser" :search="search">
                <template v-slot:item.actions="{ item }">
                    <v-icon small class="mr-2" @click="editUser(item)"
                        >mdi-pencil</v-icon
                    >
                    <v-icon small @click="deleteUser(item)">mdi-delete</v-icon>
                </template>
            </v-data-table>

            <b-modal ref="newUserModal" hide-footer title="Add New User">
                <div class>
                    <v-form @submit.prevent="createUser">
                        <v-container>
                            <v-row>
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="userData.name"
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
                                        v-model="userData.email"
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
                                        v-model="userData.role"
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
                                        v-model="userData.password"
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
                            <v-btn
                                color="blue darken-1"
                                text
                                @click="hideNewUserModal"
                                >Cancel</v-btn
                            >
                            <v-btn color="blue darken-1" type="submit" text
                                >Save</v-btn
                            >
                        </div>
                    </v-form>
                </div>
            </b-modal>

            <b-modal ref="updateUserModal" hide-footer title="Edit User">
                <div class>
                    <v-form @submit.prevent="updateUser">
                        <v-container>
                            <v-row>
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="editUserData.name"
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
                                        v-model="editUserData.email"
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
                                        v-model="editUserData.role"
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
                                        v-model="editUserData.password"
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
                            <v-btn
                                color="blue darken-1"
                                text
                                @click="hideEditUserModal"
                                >Cancel</v-btn
                            >
                            <v-btn color="blue darken-1" type="submit" text
                                >Update</v-btn
                            >
                        </div>
                    </v-form>
                </div>
            </b-modal>
        </b-card>
    </div>
</template>

<script>
import * as manageUserService from "../../services/users_services";
export default {
    name: "user",
    data() {
        return {
            search: "",
            userData: {
                name: "",
                email: "",
                role: "",
                password: ""
            },

            headers: [
                {
                    text: "Name",
                    align: "start",
                    sortable: false,
                    value: "name"
                },
                {
                    text: "Email",
                    value: "email"
                },
                {
                    text: "Role",
                    value: "role"
                },
                {
                    text: "Actions",
                    value: "actions",
                    sortable: false
                }
            ],

            DataUser: [],

            User_Role: [],

            editUserData: {},

            errors: {}
        };
    },

    mounted() {
        this.retrieveData();
    },

    methods: {
        retrieveData: async function() {
            try {
                const response = await manageUserService.retrieveData();
                console.log(response);
                this.DataUser = response.data.data;
                this.User_Role = ["admin", "user"];
            } catch (error) {
                this.flashMessage.error({
                    message: "Some error occured, Please refresh!",
                    time: 5000
                });
            }
        },

        hideNewUserModal() {
            this.$refs.newUserModal.hide();
            this.userData = {
                name: "",
                email: "",
                role: "",
                password: ""
            };
        },

        createUserList() {
            this.$refs.newUserModal.show();
        },

        hideEditUserModal() {
            this.$refs.updateUserModal.hide();
        },

        showEditUserList() {
            this.$refs.updateUserModal.show();
        },

        createUser: async function() {
            let formData = new FormData();
            formData.append("name", this.userData.name);
            formData.append("email", this.userData.email);
            formData.append("role", this.userData.role);
            formData.append("password", this.userData.password);

            try {
                const response = await manageUserService.createUser(formData);
                this.DataUser.unshift(response.data);
                this.hideNewUserModal();

                this.flashMessage.setStrategy("single");
                this.flashMessage.success({
                    message: "Category userd successfully!",
                    time: 5000
                });
                this.userData = {
                    name: "",
                    email: "",
                    role: "",
                    password: ""
                };
            } catch (error) {
                switch (error.response.status) {
                    case 422:
                        this.errors = error.response.data.errors;
                        break;

                    default:
                        this.flashMessage.error({
                            message: "Some error occured, Please try again!",
                            time: 5000
                        });
                        break;
                }
            }
        },

        editUser(item) {
            this.editUserData = {
                ...item
            };
            // this.editCategoryData = category; //kung gusto mo mkita real time editing
            this.showEditUserList();
        },

        updateUser: async function() {
            try {
                const formData = new FormData();
                formData.append("name", this.editUserData.name);
                formData.append("email", this.editUserData.email);
                formData.append("role", this.editUserData.role);
                formData.append("password", this.editUserData.password);
                formData.append("_method", "put");

                const response = await manageUserService.updateUser(
                    this.editUserData.id,
                    formData
                );
                // console.log(this.editUserData.id);
                this.DataUser.map(item => {
                    if (item.id == response.data.id) {
                        for (let key in response.data) {
                            item[key] = response.data[key];
                        }
                    }
                });

                this.hideEditUserModal();
                this.flashMessage.success({
                    message: "User Updated Successfully!",
                    time: 5000
                });
            } catch (error) {
                switch (error.response.status) {
                    case 422:
                        this.errors = error.response.data.errors;
                        break;

                    default:
                        this.flashMessage.error({
                            message: "Some error occured, Please try again!",
                            time: 5000
                        });
                        break;
                }
            }
        },

        deleteUser: async function(item) {
            // await manageUserService.deleteCategory(item.id);
            Vue.$confirm({
                message: `Are you sure you want to delete ${item.name}?`,
                button: {
                    no: "No",
                    yes: "Yes"
                },
                callback: confirm => {
                    if (!confirm) {
                    } else {
                        try {
                            manageUserService.deleteUser(item.id);
                            this.DataUser = this.DataUser.filter(obj => {
                                return obj.id != item.id;
                            });

                            this.flashMessage.success({
                                message: "Category Deleted successfully!",
                                time: 5000
                            });
                        } catch (error) {
                            this.flashMessage.error({
                                message: error.response.data.message,
                                time: 5000
                            });
                        }
                    }
                }
            });
        }
    }
};
</script>
