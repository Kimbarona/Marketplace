<template>
    <div>
        <v-spacer></v-spacer>
        <v-spacer></v-spacer>
        <div class="card-header">
            <b>Manage Store</b>
        </div>
        <v-spacer></v-spacer>
        <div class="card-header">
            <div class="my-2">
                <v-btn small color="primary" dark @click="createStoreList">create</v-btn>
                 <v-btn small color="primary" dark @click="showconfirm">test</v-btn>
            </div>
        </div>
        <b-card title>
            <v-card-title>
                Store List
                <v-spacer></v-spacer>
                <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
            </v-card-title>
            <v-data-table :headers="headers" :items="DataStore" :search="search">
                <template v-slot:item.actions="{ item }">
                    <v-icon small class="mr-2" @click="editStore(item)">mdi-pencil</v-icon>
                    <v-icon small @click="deleteCategory(item)">mdi-delete</v-icon>
                </template>
            </v-data-table>

            <b-modal ref="newStoreModal" hide-footer title="Add New Store">
                <div class>
                    <v-form @submit.prevent="createStore">
                        <v-container>
                            <v-row>
                                <v-col cols="12" md="5">
                                    <v-text-field type="number" v-model="storeData.store_code" label="Code"></v-text-field>
                                    <div class="invalid-feedback" v-if="errors.store_code">{{ errors.store_code[0] }}</div>
                                </v-col>
                                <v-col cols="12" md="7">
                                    <v-text-field v-model="storeData.store_name" label="Store Name"></v-text-field>
                                    <div class="invalid-feedback" v-if="errors.store_name">{{ errors.store_name[0] }}</div>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col cols="12" md="12">
                                    <v-text-field v-model="storeData.store_location" label="Location/Address"></v-text-field>
                                    <div class="invalid-feedback" v-if="errors.store_location">{{ errors.store_location[0] }}</div>
                                </v-col>
                            </v-row>
                        </v-container>
                        <div class="text-right">
                            <v-btn color="blue darken-1" text @click="hideNewStoreModal">Cancel</v-btn>
                            <v-btn color="blue darken-1" type="submit" text>Save</v-btn>
                        </div>
                    </v-form>
                </div>
            </b-modal>

            <b-modal ref="updateStoreModal" hide-footer title="Edit Store">
                <div class>
                    <v-form @submit.prevent="updateStore">
                        <v-container>
                            <v-row>
                                <v-col cols="12" md="5">
                                    <v-text-field type="number" v-model="editStoreData.store_code" label="Code"></v-text-field>
                                    <div class="invalid-feedback"></div>
                                </v-col>
                                <v-col cols="12" md="7">
                                    <v-text-field v-model="editStoreData.store_name" label="Store Name"></v-text-field>
                                    <div class="invalid-feedback" v-if="errors.store_name">{{ errors.store_name[0] }}</div>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col cols="12" md="12">
                                    <v-text-field v-model="editStoreData.store_location" label="Location/Address"></v-text-field>
                                    <div class="invalid-feedback" v-if="errors.store_location">{{ errors.store_location[0] }}</div>
                                </v-col>
                            </v-row>
                        </v-container>
                        <div class="text-right">
                            <v-btn color="blue darken-1" text @click="hideEditStoreModal">Cancel</v-btn>
                            <v-btn color="blue darken-1" type="submit" text>Save</v-btn>
                        </div>
                    </v-form>
                </div>
            </b-modal>
        </b-card>
    </div>
</template>

<script>
import * as manageStoreService from "../../services/manage_store_services";
export default {
    name: "store",
    data() {
        return {
            dialog: false,
            search: "",

            storeData: {
                store_code: "",
                store_name: "",
                store_location: ""
            },

            headers: [{
                    text: "Store Code",
                    align: "start",
                    sortable: false,
                    value: "store_code"
                },
                {
                    text: "Store Name",
                    value: "store_name"
                },
                {
                    text: "Store Location",
                    value: "store_location"
                },
                {
                    text: "Actions",
                    value: "actions",
                    sortable: false
                }
            ],

            DataStore: [],

            editStoreData: {},

            errors: {}
        };
    },

    mounted() {
        this.retrieveData();
    },

    methods: {
        retrieveData: async function () {
            try {
                const response = await manageStoreService.retrieveData();
                // console.log(response);
                this.DataStore = response.data.data;
                // console.log(this.DataStore);
            } catch (error) {
                this.flashMessage.error({
                    message: "Some error occured, Please refresh!",
                    time: 5000
                });
            }
        },

        initialize() {
            this.DataStore = [];
            // {
            //         store_code: "1001",
            //         store_name: "Mp_Dolores",
            //         store_location: "Dolorores, San Fernando, Pampanga"
            //     },
            //     {
            //         store_code: "1002",
            //         store_name: "Mp_Apalit",
            //         store_location: "Apalit, Pampanga"
            //     },
        },

        hideNewStoreModal() {
            this.$refs.newStoreModal.hide();
        },
        createStoreList() {
            this.$refs.newStoreModal.show();
        },

        hideEditStoreModal() {
            this.$refs.updateStoreModal.hide();
        },
        showEditStoreList() {
            this.$refs.updateStoreModal.show();
        },

        showconfirm() {
             this.$confirm(
                {
                message: `Are you sure?`,
                button: {
                    no: 'No',
                    yes: 'Yes'
                },
                /**
                 * Callback Function
                 * @param {Boolean} confirm
                 */
                callback: confirm => {
                    if (confirm) {
                    // ... do something
                    }
                }
                }
            )
            }
        },

        createStore: async function () {
            let formData = new FormData();
            formData.append("store_code", this.storeData.store_code);
            formData.append("store_name", this.storeData.store_name);
            formData.append("store_location", this.storeData.store_location);

            try {
                const response = await manageStoreService.createStore(formData);
                // to load database without reload the browser
                this.DataStore.unshift(response.data);

                this.hideNewStoreModal();

                this.flashMessage.setStrategy("single");
                this.flashMessage.success({
                    message: "Category stored successfully!",
                    time: 5000
                });

                // to empty the element
                this.storeData = {
                    store_code: "",
                    store_name: "",
                    store_location: ""
                };
            } catch (error) {
                // console.log(error.response.status);
                switch (error.response.status) {
                    case 422:
                        this.errors = error.response.data.errors;
                        // console.log(error.response.data.errors);
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

        editStore(item) {
            this.editStoreData = {...item};
            // this.editCategoryData = category; //kung gusto mo mkita real time editing
            this.showEditStoreList();
        },

        updateStore: async function () {
            try {
                const formData = new FormData();
                formData.append("store_code", this.editStoreData.store_code);
                formData.append("store_name", this.editStoreData.store_name);
                formData.append("store_location", this.editStoreData.store_location);
                formData.append("_method", "put");

                const response = await manageStoreService.updateStore(
                    this.editStoreData.id,
                    formData
                );
                // console.log(this.editStoreData.id);
                this.DataStore.map(item => {
                    if (item.id == response.data.id) {
                        for (let key in response.data) {
                            item[key] = response.data[key];
                        }
                    }
                });

                this.hideEditStoreModal();
                this.flashMessage.success({
                    message: "Store Updated Successfully!",
                    time: 5000
                });
            } catch (error) {
                console.log(error.response.data.message);
                // this.flashMessage.error({
                //     message: error.response.data.message,
                //     time: 5000
                // });
            }
        },

        deleteCategory: async function (item) {
             if(!window.confirm(`Are you sure you want to delete ${item.store_name}`)){
                 return;
             }
                try {
                    await manageStoreService.deleteCategory(item.id);
                    this.DataStore = this.DataStore.filter(obj => {
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
            // this.$dialog
            // .confirm('Please confirm to continue')
            // .then(function(dialog) {
            //     console.log('Clicked on proceed');
            // })
            // .catch(function() {
            //     console.log('Clicked on cancel');
            // });
        }
};
</script>
