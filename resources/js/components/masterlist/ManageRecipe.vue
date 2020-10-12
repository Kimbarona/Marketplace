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
                <!-- <v-icon small @click="deleteRecipe(item)">mdi-delete</v-icon> -->
                <v-icon small @click="addRecipe(item)">mdi-plus</v-icon>
                <!-- <v-btn class="mr-2" tile outlined color="warning" @click="editRecipe(item)">
                    <v-icon small >mdi-pencil</v-icon>
                </v-btn>
                 <v-btn class="mr-2" tile outlined color="sucess" @click="addRecipe(item)">
                    <v-icon small >mdi-plus</v-icon>
                </v-btn> -->
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
                            <v-col cols="12" md="3">
                                <v-text-field type="number" v-model="editRecipeData.recipe_id" label="Recipe Code" solo disabled="">
                                </v-text-field>
                                <div class="invalid-feedback" v-if="errors.recipe_id">
                                    {{ errors.recipe_id[0] }}
                                </div>
                            </v-col>
                            <v-col cols="12" md="9">
                                <v-text-field v-model="editRecipeData.recipe_name" label="Recipe Name" solo>
                                </v-text-field>
                                <div class="invalid-feedback" v-if="errors.recipe_name">
                                    {{ errors.recipe_name[0] }}
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

        <b-modal size="lg" ref="addRecipeItems" hide-footer title="Add Recipe Items">
            <div class>
                <v-form @submit.prevent="createItemsRecipe">
                    <v-container>
                        <v-row>
                            <v-col cols="12" md="3">
                                <v-text-field type="number" v-model="editRecipeData.recipe_id" label="Recipe Code" solo disabled="">
                                </v-text-field>
                                <div class="invalid-feedback" v-if="errors.recipe_id">
                                    {{ errors.recipe_id[0] }}
                                </div>
                            </v-col>
                            <v-col cols="12" sm="6" md="4">
                                <v-select name="product_id" :items="Productid" v-model="recipeData.product_id"
                                    item-text="product_name"
                                    item-value="product_id"
                                    label="Product Name" solo required>
                                </v-select>
                                <span class="invalid-feedback" v-if="errors.product_id">
                                    {{ errors.product_id[0] }}
                                </span>
                            </v-col>
                            <v-col cols="12" md="3">
                                <v-text-field v-model="recipeData.quantity" label="Quantity" solo  @keypress="isNumber">
                                </v-text-field>
                                <div class="invalid-feedback" v-if="errors.quantity">
                                    {{ errors.quantity[0] }}
                                </div>
                            </v-col>
                             <v-col cols="12" sm="6" md="2">
                                <v-select name="product_id" :items="Quan" v-model="recipeData.uom"
                                    label="Uom" solo required>
                                </v-select>
                                <span class="invalid-feedback" v-if="errors.uom">
                                    {{ errors.uom[0] }}
                                </span>
                            </v-col>
                        </v-row>
                    </v-container>
                    <div class="text-right">
                        <v-btn color="blue darken-1" text @click="hideadditemModal">Cancel</v-btn>
                        <v-btn color="blue darken-1" type="submit" text>Add Item</v-btn>
                    </div>
                </v-form>
            </div>
        </b-modal>
    </b-card>
    <br><br>
    <div class="card-header">
        <b>Recipe Item List</b>
    </div>
      <b-card title>
        <v-card-title>
            <v-spacer></v-spacer>
            <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
        </v-card-title>
        <v-data-table :headers="Itemheaders" :items="RecipeItemsDatas" :search="search">
            <template v-slot:item.actions="{ item }">
                <v-icon small class="mr-2" @click="editItemRecipe(item)">mdi-pencil</v-icon>
                <v-icon small @click="deleteItemRecipe(item)">mdi-delete</v-icon>
            </template>
            <template v-slot:item.product_image="{ item }">
                <div class="p-2">
                    <v-img
                        :src="`${$store.state.serverPath}/storage/${item.product_image}`"
                        :alt="item.product_image"
                        height="40px"
                        width="40px"
                    ></v-img>
                </div>
            </template>
        </v-data-table>

        <b-modal size="lg" ref="updateRecipeItemModal" hide-footer title="Edit Recipe">
            <div class>
                <v-form @submit.prevent="updateItemRecipe">
                    <v-container>
                        <v-row>
                            <v-col cols="12" md="3">
                                <v-text-field v-model="editItemRecipeData.product_name" label="Recipe Item" disabled="" solo>
                                </v-text-field>
                                <div class="invalid-feedback" v-if="errors.product_name">
                                    {{ errors.product_name[0] }}
                                </div>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field v-model="editItemRecipeData.quantity" label="Quantity" solo  @keypress="isNumber">
                                </v-text-field>
                                <div class="invalid-feedback" v-if="errors.quantity">
                                    {{ errors.quantity[0] }}
                                </div>
                            </v-col>
                             <v-col cols="12" sm="6" md="2">
                                <v-select name="uom" :items="Quan" v-model="editItemRecipeData.uom"
                                    label="Uom" solo required>
                                </v-select>
                                <span class="invalid-feedback" v-if="errors.uom">
                                    {{ errors.uom[0] }}
                                </span>
                            </v-col>
                        </v-row>
                    </v-container>
                    <div class="text-right">
                        <v-btn color="blue darken-1" text @click="hideEditItemRecipeModal">Cancel</v-btn>
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
import * as manageRecipeItemsService from "../../services/recipeitems_services";
export default {
    name: "recipe",
    data() {
        return {
            search: "",

            recipeData: {
                recipe_id: "",
                recipe_name: "",
                product_id: "",
                quantity: "",
                uom: ""
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

             Itemheaders: [{
                    text: "Recipe Code",
                    align: "start",
                    sortable: false,
                    value: "recipe_id"
                },
                {
                    text: "Recipe Item",
                    value: "product_name"
                },
                {
                    text: "Quantity",
                    value: "quantity"
                },
                {
                    text: "UOM",
                    value: "uom"
                },
                {
                    text: "Product",
                    value: "product_image"
                },
                {
                    text: "Actions",
                    value: "actions",
                    sortable: false
                }
            ],

            Productid: [],
            Quan: [],
            RecipeDatas: [],
            RecipeItemsDatas: [],
            editRecipeData: {},
            editItemRecipeData: {},
            errors: {}
        };
    },

    mounted() {
        this.retrieveData();
        this.retrieveItemData();
    },

    methods: {

        retrieveItemData: async function () {
            try {
                const response = await manageRecipeItemsService.retrieveItemData();
                this.RecipeItemsDatas = response.data.recipeitems.data;
                this.Quan = ['kl','mg','gram/s', 'whole', 'piece/s', 'L', 'ml', 'tablespoon', 'teaspoon', 'cup'];
            } catch (error) {
                this.flashMessage.error({
                    message: "Some error occured, Please refresh!",
                    time: 5000
                });
            }
        },

        isNumber(evt) {
            evt = evt ? evt : window.event;
            var charCode = evt.which ? evt.which : evt.keyCode;
            if (
                charCode > 31 &&
                (charCode < 48 || charCode > 57) &&
                charCode !== 46
            ) {
                evt.preventDefault();
            } else {
                return true;
            }
        },

        retrieveData: async function () {
            try {
                const response = await manageRecipeService.retrieveData();
                // console.log(response);
                this.RecipeDatas = response.data.recipe.data;
                this.Productid = response.data.products.data;
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

        hideadditemModal() {
            this.$refs.addRecipeItems.hide();
        },

        hideEditRecipeModal() {
            this.$refs.updateRecipeModal.hide();
        },

        hideEditItemRecipeModal() {
            this.$refs.updateRecipeItemModal.hide();
        },

        showEditRecipeList() {
            this.$refs.updateRecipeModal.show();
        },

        showEditItemRecipe() {
            this.$refs.updateRecipeItemModal.show();
        },

        showAddRecipeItem() {
            this.$refs.addRecipeItems.show();
        },

        createRecipe: async function () {
            let formData = new FormData();
            formData.append("recipe_id", this.recipeData.recipe_id);
            formData.append("recipe_name", this.recipeData.recipe_name);

            try {
                const response = await manageRecipeService.createRecipe(formData);
                this.RecipeDatas.unshift(response.data);
                this.RecipeItemsDatas.unshift(response.data);
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

        createItemsRecipe: async function () {
            let fData = new FormData();
            fData.append("recipe_id", this.editRecipeData.recipe_id);
            fData.append("product_id", this.recipeData.product_id);
            fData.append("quantity", this.recipeData.quantity);
            fData.append("uom", this.recipeData.uom);

            try {
                const response = await manageRecipeItemsService.createItemRecipe(fData);
                this.RecipeItemsDatas.unshift(response.data);
                this.hideadditemModal();
                this.flashMessage.setStrategy("single");
                this.flashMessage.success({
                    message: "Category reciped successfully!",
                    time: 5000
                });

                // to empty the element
                this.recipeData = {
                    recipe_id: "",
                    quantity: "",
                    product_id: ""
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

        editItemRecipe(item) {
            this.editItemRecipeData = {
                ...item
            };
            // this.editCategoryData = category; //kung gusto mo mkita real time editing
            this.showEditItemRecipe();
        },

        addRecipe(item) {
            this.editRecipeData = {
                ...item
            };
            // this.editCategoryData = category; //kung gusto mo mkita real time editing
            this.showAddRecipeItem();
            // this.recipeData = {
            //     recipe_id: "",
            //     recipe_name: "",
            //     recipe_quantity: ""
            // };
        },

        updateRecipe: async function () {
            try {
                const formData = new FormData();
                formData.append("recipe_id", this.editRecipeData.recipe_id);
                formData.append("recipe_name", this.editRecipeData.recipe_name);
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

        updateItemRecipe: async function () {
            try {
                const formData = new FormData();
                formData.append("quantity", this.editItemRecipeData.quantity);
                formData.append("uom", this.editItemRecipeData.uom);
                formData.append("_method", "put");

                const response = await manageRecipeItemsService.updateItemRecipe(
                    this.editItemRecipeData.id,
                    formData
                );
                // console.log(this.editRecipeData.id);
                this.RecipeItemsDatas.map(item => {
                    if (item.id == response.data.id) {
                        for (let key in response.data) {
                            item[key] = response.data[key];
                        }
                    }
                });

                this.hideEditItemRecipeModal();
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
        },

        deleteItemRecipe: async function (item) {
            // await manageRecipeService.deleteCategory(item.id);
            Vue.$confirm({
                message: `Are you sure you want to delete ${item.product_name}?`,
                button: {
                    no: "No",
                    yes: "Yes"
                },
                callback: confirm => {
                    if (!confirm) {} else {
                        try {
                            manageRecipeItemsService.deleteItemRecipe(item.id);
                            this.RecipeItemsDatas = this.RecipeItemsDatas.filter(obj => {
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
        },
    }
};
</script>
