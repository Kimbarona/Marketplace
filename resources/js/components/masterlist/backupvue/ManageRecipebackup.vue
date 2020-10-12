<template>
<div>
    <br /><br />
    <div class="card-header">
        <b>Manage Recipe</b>
    </div>
    <br />
    <div class="card-header">
        <div class="my-2">
            <v-btn small color="success" dark @click="createRecipeList">create</v-btn>
        </div>
    </div>
    <b-card title>
        <v-card-title>
            Recipe
            <v-spacer></v-spacer>
            <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
        </v-card-title>
        <v-data-table :headers="headers" :items="RecipeDatas" :search="search">
            <template v-slot:item.actions="{ item }">
                <v-icon small class="mr-2" @click="editRecipe(item)">mdi-pencil</v-icon>
                <v-icon small @click="deleteRecipe(item)">mdi-delete</v-icon>
            </template>
        </v-data-table>

        <b-modal size="lg" ref="newRecipeModal" hide-footer title="Add New Recipe">
            <div class>
                <v-form @submit.prevent="createRecipe">
                    <v-container>
                        <v-row>
                            <v-col cols="12" md="3">
                                <v-text-field type="number" v-model="recipeData.recipe_id" label="Recipe Code" solo>
                                </v-text-field>
                                <div class="invalid-feedback" v-if="errors.recipe_id">
                                    {{ errors.recipe_id[0] }}
                                </div>
                            </v-col>
                            <v-col cols="12" md="9">
                                <v-text-field v-model="recipeData.recipe_name" label="Recipe Name" solo>
                                </v-text-field>
                                <div class="invalid-feedback" v-if="errors.recipe_name">
                                    {{ errors.recipe_name[0] }}
                                </div>
                            </v-col>
                        </v-row>
                        <div  v-for="(recipeItem,k) in recipeList" :key="k">
                            <v-row>
                                <v-col cols="12" sm="6" md="6">
                                    <v-select name="product_id[]" :items="items" v-model="recipeItem.product_id"
                                        item-text="product_name"
                                        item-value="product_id"
                                    label="Product Name" solo required>
                                    </v-select>
                                    <span class="invalid-feedback" v-if="errors.product_id">
                                        {{ errors.product_id[0] }}
                                    </span>
                                </v-col>
                                <v-col cols="12" sm="6" md="5">
                                    <v-text-field name="quantity[]" v-model="recipeItem.quantity"  label="Quantity" solo required>
                                    </v-text-field>
                                    <span class="invalid-feedback" v-if="errors.quantity">
                                        {{ errors.quantity[0] }}
                                    </span>
                                </v-col>
                                <v-col cols="12" md="1">
                                    <span>
                                        <i class="fas fa-minus-circle" @click="remove(k)" v-show="k || ( !k && recipeList.length > 1)"></i>
                                        <i class="fas fa-plus-circle" @click="add(k)" v-show="k == recipeList.length-1"></i>
                                    </span>
                                </v-col>
                            </v-row>
                        </div>
                    </v-container>
                    <div class="text-right">
                        <v-btn color="blue darken-1" text @click="hideNewRecipeModal">Cancel</v-btn>
                        <v-btn color="blue darken-1" type="submit" text>Save</v-btn>
                    </div>
                </v-form>
            </div>
        </b-modal>

        <b-modal size="lg" ref="updateRecipeModal" hide-footer title="Edit Recipe">
            <div class>
                <v-form @submit.prevent="updateRecipe">
                    <v-container>
                        <v-row>
                            <v-col cols="12" md="4">
                                <v-text-field v-model="editRecipeData.recipe_id" label="Meal" disabled=""></v-text-field>
                                <div class="invalid-feedback" v-if="errors.meal_code">
                                    {{ errors.meal_code[0] }}
                                </div>
                            </v-col>
                            <v-col cols="12" md="4">
                                <v-text-field v-model="editRecipeData.recipe_name" label="Product" disabled=""></v-text-field>
                                <div class="invalid-feedback" v-if="errors.recipe_name">
                                    {{ errors.recipe_name[0] }}
                                </div>
                            </v-col>
                            <v-col cols="12" md="4">
                                <v-text-field v-model="editRecipeData.recipe_quantity" label="Quantity"></v-text-field>
                                <div class="invalid-feedback" v-if="errors.recipe_quantity">
                                    {{ errors.recipe_quantity[0] }}
                                </div>
                            </v-col>
                        </v-row>
                    </v-container>
                    <div class="text-right">
                        <v-btn color="blue darken-1" text @click="hideEditRecipeModal">Cancel</v-btn>
                        <v-btn color="blue darken-1" type="submit" text>Update</v-btn>
                    </div>
                </v-form>
            </div>
        </b-modal>
    </b-card>
</div>
</template>

<script>
import * as manageRecipeService from "../../services/recipe_services";
export default {
    name: "recipe",
    data() {
        return {
            search: "",

            recipeList: [
                {
                    product_id: "",
                    quantity: ""
                }
            ],

            recipeData: {
                recipe_id: "",
                recipe_name: "",
            },

            headers: [{
                    text: "Recipe Code",
                    align: "start",
                    sortable: false,
                    value: "recipe_id"
                },
                {
                    text: "Recipe Name",
                    value: "recipe_name"
                },
                {
                    text: "Actions",
                    value: "actions",
                    sortable: false
                }
            ],

            items: [],
            RecipeDatas: [],
            editRecipeData: {},
            errors: {}
        };
    },

    mounted() {
        this.retrieveData();
    },

    methods: {
        add(index) {
            this.recipeList.push({
                product_id: '',
                quantity: ''
            });
        },
        remove(index) {
            this.recipeList.splice(index, 1);
        },

        retrieveData: async function () {
            try {
                const response = await manageRecipeService.retrieveData();
                // console.log(response);
                this.RecipeDatas = response.data.recipe.data;
                this.items = response.data.products.data;
            } catch (error) {
                this.flashMessage.error({
                    message: "Some error occured, Please refresh!",
                    time: 5000
                });
            }
        },

        hideNewRecipeModal() {
            this.$refs.newRecipeModal.hide();
            this.recipeData = {
                recipe_id: "",
                recipe_name: ""
            };
        },
        createRecipeList() {
            this.$refs.newRecipeModal.show();
        },
        hideEditRecipeModal() {
            this.$refs.updateRecipeModal.hide();
        },
        showEditRecipeList() {
            this.$refs.updateRecipeModal.show();
        },

        createRecipe: async function () {
            let formData = new FormData();
            formData.append("recipe_id", this.recipeData.recipe_id);
            formData.append("recipe_name", this.recipeData.recipe_name);

            const items = this.recipeList;
            for (let i = 0; i < items.length; i++) {
                for (let key of Object.keys(items[i])) {
                    // console.log('hello...', items[i][key]);
                    formData.append(`items[${i}][${key}]`, items[i][key]);
                    // console.log(`items[${i}][${key}]`, items[i][key]);
                }
            }

            try {
                const response = await manageRecipeService.createRecipe(formData);
                this.RecipeDatas.unshift(response.data);
                this.hideNewRecipeModal();

                this.flashMessage.setStrategy("single");
                this.flashMessage.success({
                    message: "Category reciped successfully!",
                    time: 5000
                });

                // to empty the element
                this.recipeData = {
                    recipe_id: "",
                    recipe_name: ""
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

        editRecipe(item) {
            this.editRecipeData = {
                ...item
            };
            // this.editCategoryData = category; //kung gusto mo mkita real time editing
            this.showEditRecipeList();
            this.recipeData = {
                recipe_id: "",
                recipe_name: "",
                recipe_quantity: ""
            };
        },

        updateRecipe: async function () {
            try {
                const formData = new FormData();
                formData.append("recipe_id", this.editRecipeData.recipe_id);
                formData.append("recipe_name", this.editRecipeData.recipe_name);
                formData.append("recipe_quantity", this.editRecipeData.recipe_quantity);
                formData.append("_method", "put");

                const response = await manageRecipeService.updateRecipe(
                    this.editRecipeData.id,
                    formData
                );
                // console.log(this.editRecipeData.id);
                this.RecipeDatas.map(item => {
                    if (item.id == response.data.id) {
                        for (let key in response.data) {
                            item[key] = response.data[key];
                        }
                    }
                });

                this.hideEditRecipeModal();
                this.flashMessage.success({
                    message: "Recipe Updated Successfully!",
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

        deleteRecipe: async function (item) {
            // await manageRecipeService.deleteCategory(item.id);
            Vue.$confirm({
                message: `Are you sure you want to delete ${item.recipe_name}?`,
                button: {
                    no: "No",
                    yes: "Yes"
                },
                callback: confirm => {
                    if (!confirm) {} else {
                        try {
                            manageRecipeService.deleteRecipe(item.id);
                            this.RecipeDatas = this.RecipeDatas.filter(obj => {
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
