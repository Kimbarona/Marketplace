<b-modal size="lg" ref="viewRecipeModal" hide-footer title="Recipe Details">

            <v-card :loading="loading" class="mx-auto my-12" max-width="auto">
                <v-img height="250" :src="`${$store.state.serverPath}/storage/${editMealData.meal_image}`" ref="editMealImageDisplay"></v-img>
                <v-card-title>{{ editMealData.meal_name }}</v-card-title>
                <v-card-text>
                    <!-- <div class="my-4 subtitle-1">
                        Meal Description
                    </div> -->

                    <div>{{ editMealData.meal_description }}</div>
                </v-card-text>

                <v-divider class="mx-4"></v-divider>

                <v-card-title>Recipe</v-card-title>

                <v-card-text>
                    <v-chip-group v-model="selection" column>
                        <v-chip v-for="item in recipeData.recipe" :key="item.recipe_name">
                            {{ item.recipe_quantity+" - "+item.recipe_name  }}
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