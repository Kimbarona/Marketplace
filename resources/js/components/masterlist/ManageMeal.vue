<template>
<div>
    <br />
    <br />
    <div class="card-header">
        <b>Manage Meal</b>
    </div>
    <br />
    <div class="card-header">
        <div class="my-2">
            <v-btn small color="success" dark @click="createMealList">create</v-btn>
        </div>
    </div>
    <b-card title>
        <v-card-title>
            Meal List
            <v-spacer></v-spacer>
            <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
        </v-card-title>
        <v-data-table :headers="headers" :items="MealData" :search="search">
            <template v-slot:item.meal_image="{ item }">
                <div class="p-2">
                    <v-img :src="
                                `${$store.state.serverPath}/storage/${item.meal_image}`
                            " :alt="item.product_image" height="40px" width="40px">
                    </v-img>
                </div>
            </template>
            <template v-slot:item.actions="{ item }">
                <v-icon small class="mr-2" @click="viewRecipe(item)">mdi-eye</v-icon>
                <v-icon small class="mr-2" @click="editMeal(item)">mdi-pencil</v-icon>
                <v-icon small @click="deleteMeal(item)">mdi-delete</v-icon>
            </template>
        </v-data-table>

        <b-modal size="xl" ref="newMealModal" hide-footer title="Add New Meal">
            <div class>
                <v-form @submit.prevent="createMeal">
                    <v-container>
                        <v-row>
                            <v-col cols="12" md="2">
                                <v-select v-model="mealData.store_id" :items="Stores" item-text="store_name" item-value="store_id" label="Store"></v-select>
                                <div class="invalid-feedback" v-if="errors.store_id">
                                    {{ errors.store_id[0] }}
                                </div>
                            </v-col>
                            <v-col cols="12" md="2">
                                <v-text-field v-model="mealData.meal_id" label="Meal Code"></v-text-field>
                                <div class="invalid-feedback" v-if="errors.meal_id">
                                    {{ errors.meal_id[0] }}
                                </div>
                            </v-col>
                            <v-col cols="12" md="3">
                                <v-text-field v-model="mealData.meal_name" label="Meal Name"></v-text-field>
                                <div class="invalid-feedback" v-if="errors.meal_name">
                                    {{ errors.meal_name[0] }}
                                </div>
                            </v-col>
                            <v-col cols="12" md="3">
                                <v-select :items="Recipe" v-model="mealData.recipe_id" item-text="recipe_name" item-value="recipe_id" label="Recipe Tag"></v-select>
                                <div class="invalid-feedback" v-if="errors.recipe_id">
                                    {{ errors.recipe_id[0] }}
                                </div>
                            </v-col>
                            <v-col cols="12" md="2">
                                <v-select v-model="mealData.meat_type" :items="MeatData" item-text="product_name" item-value="product_name" label="Meat Type"></v-select>
                                <div class="invalid-feedback" v-if="errors.meat_type">
                                    {{ errors.meat_type[0] }}
                                </div>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="12" md="2">
                                <v-select v-model="mealData.meal_serving" :items="ServingItems" label="Serving"></v-select>
                                <div class="invalid-feedback" v-if="errors.meal_serving">
                                    {{ errors.meal_serving[0] }}
                                </div>
                            </v-col>
                            <v-col cols="12" md="2">
                                <v-text-field type="number" v-model="mealData.meal_preparation" label="Preparation"></v-text-field>
                                <div class="invalid-feedback" v-if="errors.meal_preparation">
                                    {{ errors.meal_preparation[0] }}
                                </div>
                            </v-col>
                             <v-col cols="12" md="1">
                                <v-select v-model="mealData.uom" :items="Uom" label=""></v-select>
                                <div class="invalid-feedback" v-if="errors.uom">
                                    {{ errors.uom[0] }}
                                </div>
                            </v-col>
                             <v-col cols="12" md="2">
                                <v-text-field v-model="mealData.estimated_amount" label="Estimated Amount"  @keypress="isNumber"></v-text-field>
                                <div class="invalid-feedback" v-if="errors.estimated_amount">
                                    {{ errors.estimated_amount[0] }}
                                </div>
                            </v-col>
                            <v-col cols="12" md="5">
                                <v-textarea append-outer-icon="mdi-comment" v-model="mealData.meal_description" label="Description" rows="1"></v-textarea>
                                <div class="invalid-feedback" v-if="errors.meal_description">
                                    {{ errors.meal_description[0] }}
                                </div>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="12" md="6">
                                <v-textarea append-outer-icon="mdi-comment" v-model="mealData.meal_instruction" label="Cooking Instruction" rows="1"></v-textarea>
                                <div class="invalid-feedback" v-if="errors.meal_instruction">
                                    {{ errors.meal_instruction[0] }}
                                </div>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-textarea append-outer-icon="mdi-comment" v-model="mealData.meal_nutrition" label="Nutrition Facts" rows="1"></v-textarea>
                                <div class="invalid-feedback" v-if="errors.meal_nutrition">
                                    {{ errors.meal_nutrition[0] }}
                                </div>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="12" md="12">
                                <div v-if="mealData.meal_image.name" align="center">
                                    <img src ref="newMealImageDisplay" class="meal-div-edit" />
                                </div>

                                <v-btn small color="blue-grey" class="ma-2 white--text" @click="pickFile">
                                    Upload Image
                                    <v-icon right dark>mdi-cloud-upload</v-icon>
                                </v-btn>
                                <input type="file" class="form-control" accept="image/png, image/jpeg, image/bmp" v-on:change="attachImage" ref="newMealImage" style="display:none" />
                                <div class="invalid-feedback" v-if="errors.meal_image">
                                    {{ errors.meal_image[0] }}
                                </div>
                            </v-col>
                        </v-row>
                    </v-container>
                    <div class="text-right">
                        <v-btn color="blue darken-1" text @click="hideNewMealModal">Cancel</v-btn>
                        <v-btn color="blue darken-1" type="submit" text>Save</v-btn>
                    </div>
                </v-form>
            </div>
        </b-modal>

        <b-modal size="lg" ref="updateMealModal" hide-footer title="Edit Meal">
            <div class>
                <v-form @submit.prevent="updateMeal">
                    <v-container>
                        <v-row>
                            <v-col cols="12" md="3">
                                <v-text-field v-model="editMealData.recipe_id" label="Recipe Code" disabled></v-text-field>
                                <div class="invalid-feedback" v-if="errors.recipe_id">
                                    {{ errors.recipe_id[0] }}
                                </div>
                            </v-col>
                            <v-col cols="12" md="3">
                                <v-text-field v-model="editMealData.meal_id" label="Meal Code" disabled></v-text-field>
                                <div class="invalid-feedback" v-if="errors.meal_id">
                                    {{ errors.meal_id[0] }}
                                </div>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field v-model="editMealData.meal_name" label="Meal Name" disabled=""></v-text-field>
                                <div class="invalid-feedback" v-if="errors.meal_name">
                                    {{ errors.meal_name[0] }}
                                </div>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="12" md="3">
                                <v-select v-model="editMealData.meat_type" :items="MeatData" item-text="product_name" item-value="product_name" disabled="" label="Meat Type"></v-select>
                                <div class="invalid-feedback" v-if="errors.meat_type">
                                    {{ errors.meat_type[0] }}
                                </div>
                            </v-col>
                            <v-col cols="12" md="3">
                                <v-text-field v-model="editMealData.estimated_amount" label="Estimated Amount"  @keypress="isNumber"></v-text-field>
                                <div class="invalid-feedback" v-if="errors.estimated_amount">
                                    {{ errors.estimated_amount[0] }}
                                </div>
                            </v-col>
                            <v-col cols="12" md="3">
                                <v-text-field v-model="editMealData.meal_preparation" label="Preparation" disabled=""></v-text-field>
                                <div class="invalid-feedback" v-if="errors.meal_preparation">
                                    {{ errors.meal_preparation[0] }}
                                </div>
                            </v-col>
                            <v-col cols="12" md="3">
                                <v-select v-model="editMealData.meal_serving" :items="ServingItems" label="Serving/Meal"></v-select>
                                <div class="invalid-feedback" v-if="errors.meal_serving">
                                    {{ errors.meal_serving[0] }}
                                </div>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-textarea append-outer-icon="mdi-comment" v-model="editMealData.meal_instruction" label="Cooking Instruction" rows="1"></v-textarea>
                                <div class="invalid-feedback" v-if="errors.meal_instruction">
                                    {{ errors.meal_instruction[0] }}
                                </div>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-textarea append-outer-icon="mdi-comment" v-model="editMealData.meal_nutrition" label="Nutrition Facts" rows="1"></v-textarea>
                                <div class="invalid-feedback" v-if="errors.meal_nutrition">
                                    {{ errors.meal_nutrition[0] }}
                                </div>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="12" sm="6">
                                <div v-if="editMealData.meal_image">
                                    <img :src="
                                                `${$store.state.serverPath}/storage/${editMealData.meal_image}`
                                            " ref="editMealImageDisplay" class="meal-div-edit" />
                                </div>
                                <input type="file" class="form-control" accept="image/png, image/jpeg, image/bmp" v-on:change="editAttachImage" ref="editMealImage" style="display:none" />
                                <div class="invalid-feedback" v-if="errors.meal_image">
                                    {{ errors.meal_image[0] }}
                                </div>
                                <v-btn small color="blue-grey" class="ma-2 white--text" @click="pickeditFile">
                                    Upload Image
                                    <v-icon right dark>mdi-cloud-upload</v-icon>
                                </v-btn>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-textarea append-outer-icon="mdi-comment" v-model="editMealData.meal_description" label="Description" rows="1"></v-textarea>
                                <div class="invalid-feedback" v-if="errors.meal_description">
                                    {{ errors.meal_description[0] }}
                                </div>
                            </v-col>
                        </v-row>
                    </v-container>
                    <div class="text-right">
                        <v-btn color="blue darken-1" text @click="hideEditMealModal">Cancel</v-btn>
                        <v-btn color="blue darken-1" type="submit" text>Update</v-btn>
                    </div>
                </v-form>
            </div>
        </b-modal>

        <b-modal size="lg" ref="viewRecipeModal" hide-footer title="Recipe Details">
            <v-card :loading="loading" class="mx-auto my-12" max-width="auto">
                <v-img height="250" :src="
                            `${$store.state.serverPath}/storage/${editMealData.meal_image}`
                        " ref="editMealImageDisplay"></v-img>
                <v-card-title>{{ editMealData.meal_name }}</v-card-title>
                <v-card-text>
                    <div>{{ editMealData.meal_description }}</div>
                </v-card-text>

                <v-divider class="mx-4"></v-divider>

                <v-card-title>Recipe</v-card-title>

                <v-card-text>
                    <v-chip-group v-model="selection" column>
                        <v-chip v-for="item in recipeData.recipeitem" :key="item.product_name">
                            {{ item.quantity + "-"+ item.uom  +" of " + item.product_name }}
                        </v-chip>
                    </v-chip-group>
                </v-card-text>
                <v-divider class="mx-4"></v-divider>

                <v-card-title>Cooking Instruction</v-card-title>
                <v-card-text>
                    <div>{{ editMealData.meal_instruction }}</div>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="deep-purple lighten-2" text @click="hideViewRecipeModal">
                        Close
                    </v-btn>
                </v-card-actions>
            </v-card>
        </b-modal>
    </b-card>
</div>
</template>

<script>
import * as manageMealService from "../../services/meal_services";
export default {
    name: "meal",
    data() {
        return {
            loading: "",
            search: "",
            selection: 1,

            mealData: {
                store_id: "",
                recipe_id: "",
                name: "",
                meal_id: "",
                meat_type: "",
                meal_name: "",
                meal_serving: "",
                estimated_amount: "",
                meal_preparation: "",
                meal_description: "",
                meal_instruction: "",
                meal_nutrition: "",
                meal_image: "",
                meat_type: "",
                uom: ""
            },

            headers: [
                // {
                //     text: "Recipe Code",
                //     align: "start",
                //     value: "id"
                // },
                // {
                //     text: "Recipe Code",
                //     align: "start",
                //     value: "recipe_id"
                // },
                {
                    text: "Meal Code",
                    align: "start",
                    value: "meal_id"
                },
                {
                    text: "Meal Name",
                    value: "meal_name"
                },
                {
                    text: "Meat Type",
                    value: "meat_type"
                },
                {
                    text: "Meal Serving",
                    value: "meal_serving"
                },
                {
                    text: "Meal Preparation",
                    value: "meal_preparation"
                },
                {
                    text: "Estimated Amount",
                    value: "estimated_amount"
                },
                {
                    text: "Meal Description",
                    value: "meal_description"
                },
                {
                    text: "Cooking Instruction",
                    value: "meal_instruction"
                },
                {
                    text: "Nutrition Facts",
                    value: "meal_nutrition"
                },
                {
                    text: "Meal Image",
                    value: "meal_image"
                },
                {
                    text: "Actions",
                    value: "actions",
                    sortable: false
                }
            ],

            MealData: [],

            Uom: [],

            recipeData: {},

            ServingItems: [],

            Recipe: [],

            MeatData: [],

            Stores: [],

            editMealData: {},

            errors: {}
        };
    },

    mounted() {
        this.retrieveData();
    },

    methods: {
        retrieveData: async function () {
            try {
                const response = await manageMealService.retrieveData();
                // console.log(response);
                this.MealData = response.data.meal.data;
                this.Recipe = response.data.recipe.data;
                this.MeatData = response.data.meat.data;
                this.Stores = response.data.stores.data;
                this.Uom = ['hr', 'minutes'];
                this.ServingItems = [
                    "1",
                    "2",
                    "3",
                    "4",
                    "5",
                    "6",
                    "7",
                    "8",
                    "9",
                    "10"
                ];
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

        pickFile() {
            this.$refs.newMealImage.click();
        },

        pickeditFile() {
            this.$refs.editMealImage.click();
        },

        attachImage() {
            this.mealData.meal_image = this.$refs.newMealImage.files[0];
            let reader = new FileReader();
            reader.addEventListener(
                "load",
                function () {
                    this.$refs.newMealImageDisplay.src = reader.result;
                }.bind(this),
                false
            );

            reader.readAsDataURL(this.mealData.meal_image);
            // console.log('clicked!');
        },

        hideNewMealModal() {
            this.$refs.newMealModal.hide();
            this.mealData = {
                meal_id: "",
                mel_name: "",
                meal_serving: "",
                meal_preparation: "",
                meal_description: "",
                meal_image: ""
            };
        },

        createMealList() {
            this.$refs.newMealModal.show();
        },

        hideEditMealModal() {
            this.$refs.updateMealModal.hide();
        },

        showEditMealList() {
            this.$refs.updateMealModal.show();
        },

        showViewRecipeModal() {
            this.$refs.viewRecipeModal.show();
        },

        hideViewRecipeModal() {
            this.$refs.viewRecipeModal.hide();
        },

        viewRecipe(item) {
            this.editMealData = {
                ...item
            };
            this.recipeData = {
                ...item
            };

            const a = this.recipeData.recipeitem;

            console.log(a);
            const mealId = item.meal_id;
            this.showViewRecipeModal();
            // alert(recipeData);
        },

        createMeal: async function () {
            let formData = new FormData();
            formData.append("store_id", this.mealData.store_id);
            formData.append("recipe_id", this.mealData.recipe_id);
            formData.append("meal_id", this.mealData.meal_id);
            formData.append("meal_name", this.mealData.meal_name);
            formData.append("meat_type", this.mealData.meat_type);
            formData.append("meal_description", this.mealData.meal_description);
            formData.append("meal_serving", this.mealData.meal_serving);
            formData.append("meal_preparation", this.mealData.meal_preparation);
            formData.append("uom", this.mealData.uom);
            formData.append("estimated_amount", this.mealData.estimated_amount);
            formData.append("meal_instruction", this.mealData.meal_instruction);
            formData.append("meal_nutrition", this.mealData.meal_nutrition);
            formData.append("meal_image", this.mealData.meal_image);

            try {
                const response = await manageMealService.createMeal(formData);
                // to load database without reload the browser
                this.MealData.unshift(response.data);
                this.hideNewMealModal();

                this.flashMessage.setStrategy("single");
                this.flashMessage.success({
                    message: "Category meald successfully!",
                    time: 5000
                });

                // to empty the element
                this.mealData = {
                    meal_id: "",
                    meal_name: "",
                    meal_serving: "",
                    meal_preparation: "",
                    meal_description: "",
                    meal_image: ""
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

        editAttachImage() {
            this.editMealData.meal_image = this.$refs.editMealImage.files[0];
            let reader = new FileReader();
            reader.addEventListener(
                "load",
                function () {
                    this.$refs.editMealImageDisplay.src = reader.result;
                }.bind(this),
                false
            );

            reader.readAsDataURL(this.editMealData.meal_image);
        },

        editMeal(item) {
            this.editMealData = {
                ...item
            };
            // this.editCategoryData = category; //kung gusto mo mkita real time editing
            this.showEditMealList();
            this.mealData = {
                meal_id: "",
                meal_name: "",
                meal_serving: "",
                meal_preparation: "",
                meal_description: "",
                meal_image: ""
            };
        },

        updateMeal: async function () {
            try {
                let formData = new FormData();
                formData.append("recipe_id", this.editMealData.recipe_id);
                formData.append("meal_id", this.editMealData.meal_id);
                formData.append("meal_name", this.editMealData.meal_name);
                formData.append("meat_type", this.editMealData.meat_type);
                formData.append("meal_description",this.editMealData.meal_description);
                formData.append("meal_serving", this.editMealData.meal_serving);
                formData.append("meal_preparation",this.editMealData.meal_preparation);
                formData.append("estimated_amount",this.editMealData.estimated_amount);
                formData.append("meal_instruction",this.editMealData.meal_instruction);
                formData.append("meal_nutrition",this.editMealData.meal_nutrition);
                formData.append("meal_image", this.editMealData.meal_image);
                formData.append("_method", "put");

                const response = await manageMealService.updateMeal(this.editMealData.id,formData);
                // console.log(this.editMealData.id);
                this.MealData.map(item => {
                    if (item.id == response.data.id) {
                        for (let key in response.data) {
                            item[key] = response.data[key];
                        }
                    }
                });

                this.hideEditMealModal();
                this.flashMessage.success({
                    message: "Meal Updated Successfully!",
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

        deleteMeal: async function (item) {
            // await manageMealService.deleteCategory(item.id);
            Vue.$confirm({
                message: `Are you sure you want to delete ${item.meal_name}?`,
                button: {
                    no: "No",
                    yes: "Yes"
                },
                callback: confirm => {
                    if (!confirm) {} else {
                        try {
                            manageMealService.deleteMeal(item.id);
                            this.MealData = this.MealData.filter(obj => {
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
