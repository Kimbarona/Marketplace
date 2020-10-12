<template>
    <v-card class="mx-auto my-1" max-width="auto">
        <!-- <v-carousel height="350" hide-delimiters> -->
        <v-carousel
            height="300"
            hide-delimiters
            @change="newMeal(allMealData[$event].id)"
        >
            <v-carousel-item
                ref="mealId"
                v-for="(item, i) in allMealData"
                :key="'allMeal' + i"
                :src="`${$store.state.serverPath}/storage/${item.meal_image}`"
                :alt="item.meal_image"
                :meal="item.meal_name"
                :meal_id="item.meal_id"
            >
                <v-card-title>{{ item.meal_name }}</v-card-title>
            </v-carousel-item>
        </v-carousel>

        <v-card-text v-for="(itemr, r) in filterData" :key="'r' + r">
            <v-row align="center" class="mx-0">
                <v-rating
                    :value="4.5"
                    color="amber"
                    dense
                    half-increments
                    readonly
                    size="14"
                ></v-rating>
                <div class="grey--text ml-4">4.5 (413)</div>
                <v-row align="center" justify="end">
                    <v-icon class="mr-1">mdi-account-multiple</v-icon>
                    <span class="subheading mr-2">{{
                        itemr.meal_serving
                    }}</span>
                    <span class="mr-1">·</span>
                    <v-icon class="mr-1">mdi-alarm</v-icon>
                    <span class="subheading mr-2">{{
                        itemr.meal_preparation
                    }}</span>
                </v-row>
            </v-row>

            <div class="my-4 subtitle-1"></div>

            <div>
                {{ itemr.meal_description }}
            </div>
        </v-card-text>

        <v-divider class="mx-4"></v-divider>

        <v-card-text v-for="(price, p) in filterData" :key="'p' + p">
             Estimated Amount:<span class="meal-amount">₱ {{  price.estimated_amount }}</span>
        </v-card-text>

        <v-divider class="mx-4"></v-divider>

        <v-card-title>Recipe</v-card-title>
        <v-container fluid>
            <v-row v-for="(recipe, x) in filterData" :key="'r' + x" dense>
                <v-col
                    v-for="(imahe, i) in recipe.recipeitem"
                    :key="'i' + i"
                    :cols="flex"
                >
                    <v-card>
                        <v-img
                            class="white--text align-end"
                            gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                            height="100px"
                            :src="
                                `${$store.state.serverPath}/storage/${imahe.product_image}`
                            "
                            :alt="imahe.recipe_image"
                        ></v-img>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-list-item-title>
                                {{ imahe.product_name }}
                            </v-list-item-title>
                        </v-card-actions>
                        <v-list-item-title class="recipe-amount">
                            ₱ {{ imahe.product_amount }}
                        </v-list-item-title>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        <v-divider class="mx-4"></v-divider>
        <v-card-title>How To Cook</v-card-title>
        <v-card-text>
            <div v-for="(itemc, c) in filterData" :key="'c' + c">
                {{ itemc.meal_instruction }}
                <br />
                <br />

                <b>Nutrition</b>
                <br />
                <p class="nutri-text">
                    {{ itemc.meal_nutrition }}
                </p>
            </div>
        </v-card-text>
    </v-card>
</template>

<script>
import * as frontEndData from "../../services/front-end-service";
export default {
    data() {
        return {
            flex: 4,

            mealId: {},

            allMealData: []
        };
    },
    computed: {
        filterData() {
            let param = this.mealId;
            if (param) {
                return this.allMealData.filter(d => {
                    return d.id === this.mealId;
                });
            }
        }
    },

    mounted() {
        this.retrieveSearchData();
        // this.retrieveData();
        // console.log(this.$route.query.meat_type);
    },

    methods: {
        retrieveSearchData: async function() {
            try {
                const response = await frontEndData.retrieveSearchData(this.$route.query.meat_type);
                this.allMealData = response.data.meals;
                // console.log(response);
            } catch (error) {
                this.flashMessage.error({
                    message: "Some error occured, Please refresh!",
                    time: 5000
                });
            }
        },

        reserve() {
            this.loading = true;

            setTimeout(() => (this.loading = false), 2000);
        },

        newMeal(val) {
            this.mealId = val;
        }
    }
};
</script>
