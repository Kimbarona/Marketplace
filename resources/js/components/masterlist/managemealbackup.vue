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
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Search"
          single-line
          hide-details
        ></v-text-field>
      </v-card-title>
      <v-data-table :headers="headers" :items="MealData" :search="search">
        <template v-slot:item.meal_image="{ item }">
          <div class="p-2">
            <v-img
              :src="
                                `${$store.state.serverPath}/storage/${item.meal_image}`
                            "
              :alt="item.product_image"
              height="40px"
              width="40px"
            ></v-img>
          </div>
        </template>
        <template v-slot:item.actions="{ item }">
          <v-icon small class="mr-2" @click="editMeal(item)">mdi-pencil</v-icon>
          <v-icon small @click="deleteMeal(item)">mdi-delete</v-icon>
        </template>
      </v-data-table>

      <b-modal size="lg" ref="newMealModal" hide-footer title="Add New Meal">
        <div class>
          <v-form @submit.prevent="createMeal">
            <v-container>
              <v-row>
                <v-col cols="12" md="2">
                  <v-select
                    :items="StoreCodeItems"
                    v-model="mealData.store_id"
                    item-text="store_name"
                    item-value="store_id"
                    label="Store"
                  ></v-select>
                  <div class="invalid-feedback" v-if="errors.store_id">{{ errors.store_id[0] }}</div>
                </v-col>
                <v-col cols="12" md="2">
                  <v-text-field v-model="mealData.meal_id" label="Meal Code"></v-text-field>
                  <div class="invalid-feedback" v-if="errors.meal_id">{{ errors.meal_id[0] }}</div>
                </v-col>
                <v-col cols="12" md="6">
                  <v-text-field v-model="mealData.meal_name" label="Meal Name"></v-text-field>
                  <div class="invalid-feedback" v-if="errors.meal_name">{{ errors.meal_name[0] }}</div>
                </v-col>
                <v-col cols="12" md="2">
                  <v-select v-model="mealData.meal_serving" :items="ServingItems" label="Serving"></v-select>
                  <div
                    class="invalid-feedback"
                    v-if="errors.meal_serving"
                  >{{ errors.meal_serving[0] }}</div>
                </v-col>
              </v-row>

              <v-row>
                <v-col cols="12" md="3">
                  <v-text-field v-model="mealData.meal_preparation" label="Preparation"></v-text-field>
                  <div
                    class="invalid-feedback"
                    v-if="errors.meal_preparation"
                  >{{ errors.meal_preparation[0] }}</div>
                </v-col>
                <v-col cols="12" md="9">
                  <v-textarea
                    append-outer-icon="mdi-comment"
                    v-model="mealData.meal_description"
                    label="Description"
                    rows="1"
                  ></v-textarea>
                  <div
                    class="invalid-feedback"
                    v-if="errors.meal_description"
                  >{{ errors.meal_description[0] }}</div>
                </v-col>
              </v-row>

              <v-row>
                <v-col cols="12" md="6">
                  <v-textarea
                    append-outer-icon="mdi-comment"
                    v-model="mealData.meal_instruction"
                    label="Cooking Instruction"
                    rows="1"
                  ></v-textarea>
                  <div
                    class="invalid-feedback"
                    v-if="errors.meal_instruction"
                  >{{ errors.meal_instruction[0] }}</div>
                </v-col>
                <v-col cols="12" md="6">
                  <v-textarea
                    append-outer-icon="mdi-comment"
                    v-model="mealData.meal_nutrition"
                    label="Nutrition Facts"
                    rows="1"
                  ></v-textarea>
                  <div
                    class="invalid-feedback"
                    v-if="errors.meal_nutrition"
                  >{{ errors.meal_nutrition[0] }}</div>
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
                  <input
                    type="file"
                    class="form-control"
                    accept="image/png, image/jpeg, image/bmp"
                    v-on:change="attachImage"
                    ref="newMealImage"
                    style="display:none"
                  />
                  <div class="invalid-feedback" v-if="errors.meal_image">{{ errors.meal_image[0] }}</div>
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
                  <v-text-field v-model="editMealData.store_id" label="Store Id" disabled></v-text-field>
                  <div class="invalid-feedback" v-if="errors.store_id">{{ errors.store_id[0] }}</div>
                </v-col>
                <v-col cols="12" md="3">
                  <v-text-field v-model="editMealData.meal_id" label="Meal Code" disabled></v-text-field>
                  <div class="invalid-feedback" v-if="errors.store_id">{{ errors.store_id[0] }}</div>
                </v-col>
                <v-col cols="12" md="6">
                  <v-text-field v-model="editMealData.meal_name" label="Meal Name"></v-text-field>
                  <div class="invalid-feedback" v-if="errors.meal_name">{{ errors.meal_name[0] }}</div>
                </v-col>
              </v-row>

              <v-row>
                <v-col cols="12" md="6">
                  <v-text-field v-model="editMealData.meal_preparation" label="Preparation"></v-text-field>
                  <div
                    class="invalid-feedback"
                    v-if="errors.meal_preparation"
                  >{{ errors.meal_preparation[0] }}</div>
                </v-col>
                <v-col cols="12" md="6">
                  <v-select
                    v-model="editMealData.meal_serving"
                    :items="ServingItems"
                    label="Serving/Meal"
                  ></v-select>
                  <div
                    class="invalid-feedback"
                    v-if="errors.meal_serving"
                  >{{ errors.meal_serving[0] }}</div>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" md="6">
                  <v-textarea
                    append-outer-icon="mdi-comment"
                    v-model="editMealData.meal_instruction"
                    label="Cooking Instruction"
                    rows="1"
                  ></v-textarea>
                  <div
                    class="invalid-feedback"
                    v-if="errors.meal_instruction"
                  >{{ errors.meal_instruction[0] }}</div>
                </v-col>
                <v-col cols="12" md="6">
                  <v-textarea
                    append-outer-icon="mdi-comment"
                    v-model="editMealData.meal_nutrition"
                    label="Nutrition Facts"
                    rows="1"
                  ></v-textarea>
                  <div
                    class="invalid-feedback"
                    v-if="errors.meal_nutrition"
                  >{{ errors.meal_nutrition[0] }}</div>
                </v-col>
              </v-row>

              <v-row>
                <v-col cols="12" sm="6">
                  <div v-if="editMealData.meal_image">
                    <img
                      :src="
                                                `${$store.state.serverPath}/storage/${editMealData.meal_image}`
                                            "
                      ref="editMealImageDisplay"
                      class="meal-div-edit"
                    />
                  </div>
                  <input
                    type="file"
                    class="form-control"
                    accept="image/png, image/jpeg, image/bmp"
                    v-on:change="editAttachImage"
                    ref="editMealImage"
                    style="display:none"
                  />
                  <div class="invalid-feedback" v-if="errors.meal_image">{{ errors.meal_image[0] }}</div>
                  <v-btn small color="blue-grey" class="ma-2 white--text" @click="pickeditFile">
                    Upload Image
                    <v-icon right dark>mdi-cloud-upload</v-icon>
                  </v-btn>
                </v-col>
                <v-col cols="12" sm="6">
                  <v-textarea
                    append-outer-icon="mdi-comment"
                    v-model="editMealData.meal_description"
                    label="Description"
                    rows="1"
                  ></v-textarea>
                  <div
                    class="invalid-feedback"
                    v-if="errors.meal_description"
                  >{{ errors.meal_description[0] }}</div>
                </v-col>
              </v-row>
            </v-container>
            <div class="text-right">
              <v-btn color="blue darken-1" text @click="hideEditMealModal">Cancel</v-btn>
              <v-btn color="blue darken-1" type="submit" text>Save</v-btn>
            </div>
          </v-form>
        </div>
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
      search: "",

      mealData: {
        store_id: "",
        name: "",
        meal_id: "",
        meal_name: "",
        meal_serving: "",
        meal_preparation: "",
        meal_description: "",
        meal_instruction: "",
        meal_nutrition: "",
        meal_image: ""
      },

      headers: [
        // {
        //     text: "Store Code",
        //     align: "start",
        //     value: "store_id"
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
          text: "Meal Serving",
          value: "meal_serving"
        },
        {
          text: "Meal Preparation",
          value: "meal_preparation"
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

      ServingItems: [],

      StoreCodeItems: [],

      editMealData: {},

      errors: {}
    };
  },

  mounted() {
    this.retrieveData();
  },

  methods: {
    retrieveData: async function() {
      try {
        const response = await manageMealService.retrieveData();
        this.MealData = response.data.meal.data;
        this.StoreCodeItems = response.data.sid.data;
        this.ServingItems = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"];
      } catch (error) {
        this.flashMessage.error({
          message: "Some error occured, Please refresh!",
          time: 5000
        });
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
        function() {
          this.$refs.newMealImageDisplay.src = reader.result;
        }.bind(this),
        false
      );

      reader.readAsDataURL(this.mealData.meal_image);
      // console.log('clicked!');
    },

    // initialize() {
    //     this.MealData = [
    //         {
    //             store_id: "1001",
    //             meal_id: "3001",
    //             meal_name: "Pork Sinigang",
    //             meal_description:
    //                 "Sinigang is a Filipino soup or stew characterized by its sour and savoury taste. It is most often associated with tamarind (Filipino: sampalok), although it can use other sour fruits and leaves as the souring agent. It is one of the more popular dishes in Filipino cuisine.",
    //             meal_serving: "4",
    //             meal_preparation: "45-mins",
    //             meal_image: "https://picsum.photos/510/300?random"
    //         },
    //         {
    //             store_id: "1002",
    //             meal_id: "3002",
    //             meal_name: "Adobo",
    //             meal_description:
    //                 "Philippine adobo from Spanish adobar marinade, sauce or seasoning, is a popular Filipino dish and cooking process in Philippine cuisine that involves meat, seafood, or vegetables marinated in vinegar, soy sauce, garlic, and black peppercorns, which is browned in oil, and simmered in the marinade.",
    //             meal_serving: "5",
    //             meal_preparation: "35-mins",
    //             meal_image: "https://picsum.photos/510/300?random"
    //         }
    //     ];

    // },

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

    createMeal: async function() {
      let formData = new FormData();
      formData.append("store_id", this.mealData.store_id);
      formData.append("meal_id", this.mealData.meal_id);
      formData.append("meal_name", this.mealData.meal_name);
      formData.append("meal_description", this.mealData.meal_description);
      formData.append("meal_serving", this.mealData.meal_serving);
      formData.append("meal_preparation", this.mealData.meal_preparation);
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
        function() {
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

    updateMeal: async function() {
      try {
        let formData = new FormData();
        formData.append("store_id", this.editMealData.store_id);
        formData.append("meal_id", this.editMealData.meal_id);
        formData.append("meal_name", this.editMealData.meal_name);
        formData.append("meal_description", this.editMealData.meal_description);
        formData.append("meal_serving", this.editMealData.meal_serving);
        formData.append("meal_preparation", this.editMealData.meal_preparation);
        formData.append("meal_instruction", this.editMealData.meal_instruction);
        formData.append("meal_nutrition", this.editMealData.meal_nutrition);
        formData.append("meal_image", this.editMealData.meal_image);
        formData.append("_method", "put");

        const response = await manageMealService.updateMeal(
          this.editMealData.id,
          formData
        );
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

    deleteMeal: async function(item) {
      // await manageMealService.deleteCategory(item.id);
      Vue.$confirm({
        message: `Are you sure you want to delete ${item.meal_name}?`,
        button: {
          no: "No",
          yes: "Yes"
        },
        callback: confirm => {
          if (!confirm) {
          } else {
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
