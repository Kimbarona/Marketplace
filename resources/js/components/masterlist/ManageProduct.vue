<template>
<div>
    <br /><br />
    <div class="card-header">
        <b>Manage Product</b>
    </div>
    <br />
    <div class="card-header">
        <div class="my-2">
            <v-btn small color="success" dark @click="createProductList">create</v-btn>
        </div>
    </div>
    <b-card title>
        <v-card-title>
            Product List
            <v-spacer></v-spacer>
            <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
        </v-card-title>
        <v-data-table :headers="headers" :items="DataProduct" :search="search">
            <template v-slot:item.product_image="{ item }">
                <div class="p-2">
                    <v-img :src="
                                `${$store.state.serverPath}/storage/${item.product_image}`
                            " :alt="item.product_image" height="40px" width="40px"></v-img>
                </div>
            </template>
            <template v-slot:item.actions="{ item }">
                <v-icon small class="mr-2" @click="editProduct(item)">mdi-pencil</v-icon>
                <v-icon small @click="deleteProduct(item)">mdi-delete</v-icon>
            </template>
        </v-data-table>

        <b-modal ref="newProductModal" hide-footer title="Add New Product">
            <div class>
                <v-form @submit.prevent="createProduct">
                    <v-container>
                        <v-row>
                            <v-col cols="12" md="5">
                                <v-text-field type="number" v-model="productData.product_id" label="Product Code"></v-text-field>
                                <div class="invalid-feedback" v-if="errors.product_id">
                                    {{ errors.product_id[0] }}
                                </div>
                            </v-col>
                            <v-col cols="12" md="7">
                                <v-text-field v-model="productData.product_name" label="Product Name"></v-text-field>
                                <div class="invalid-feedback" v-if="errors.product_name">
                                    {{ errors.product_name[0] }}
                                </div>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" md="12">
                                <v-select v-model="productData.product_category" :items="product_category" label="Product Category" dense>
                                </v-select>
                                <div class="invalid-feedback" v-if="errors.product_category">
                                    {{ errors.product_category[0] }}
                                </div>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" md="12">
                                <div v-if="productData.product_image.name">
                                    <img src="" ref="newProductImageDisplay" class="responsive" />
                                </div>

                                <v-btn small color="blue-grey" class="ma-2 white--text" @click="pickFile">
                                    Upload Image
                                    <v-icon right dark>mdi-cloud-upload</v-icon>
                                </v-btn>
                                <input type="file" class="form-control" accept="image/png, image/jpeg, image/bmp" v-on:change="attachImage" ref="newProductImage" style="display:none" />
                                <div class="invalid-feedback" v-if="errors.product_image">
                                    {{ errors.product_image[0] }}
                                </div>
                            </v-col>
                        </v-row>
                    </v-container>
                    <div class="text-right">
                        <v-btn color="blue darken-1" text @click="hideNewProductModal">Cancel</v-btn>
                        <v-btn color="blue darken-1" type="submit" text>Save</v-btn>
                    </div>
                </v-form>
            </div>
        </b-modal>

        <b-modal ref="updateProductModal" hide-footer title="Edit Product">
            <div class>
                <v-form @submit.prevent="updateProduct">
                    <v-container>
                        <v-row>
                            <v-col cols="12" md="5">
                                <v-text-field type="number" v-model="editProductData.product_id" label="Product Code" disabled="">
                                </v-text-field>
                                <div class="invalid-feedback" v-if="errors.product_id">
                                    {{ errors.product_id[0] }}
                                </div>
                            </v-col>
                            <v-col cols="12" md="7">
                                <v-text-field v-model="editProductData.product_name" label="Product Name"></v-text-field>
                                <div class="invalid-feedback" v-if="errors.product_name">
                                    {{ errors.product_name[0] }}
                                </div>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" md="12">
                                <v-select v-model="
                                            editProductData.product_category
                                        " :items="product_category" label="Product Category" dense>
                                </v-select>
                                <div class="invalid-feedback" v-if="errors.product_category">
                                    {{ errors.product_category[0] }}
                                </div>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="12" md="12">
                                <div v-if="editProductData.product_image">
                                    <img :src="
                                                `${$store.state.serverPath}/storage/${editProductData.product_image}`
                                            " ref="editProductImageDisplay" class="responsive" />
                                </div>

                                <v-btn small color="blue-grey" class="ma-2 white--text" @click="pickeditFile">
                                    Upload Image
                                    <v-icon right dark>mdi-cloud-upload</v-icon>
                                </v-btn>
                                <input type="file" class="form-control" accept="image/png, image/jpeg, image/bmp" v-on:change="editAttachImage" ref="editProductImage" style="display:none" />
                                <div class="invalid-feedback" v-if="errors.product_image">
                                    {{ errors.product_image[0] }}
                                </div>
                            </v-col>
                        </v-row>
                    </v-container>
                    <div class="text-right">
                        <v-btn color="blue darken-1" text @click="hideEditProductModal">Cancel</v-btn>
                        <v-btn color="blue darken-1" type="submit" text>Update</v-btn>
                    </div>
                </v-form>
            </div>
        </b-modal>
    </b-card>
</div>
</template>

<script>
import * as manageProductService from "../../services/product_services";
export default {
    name: "products",
    data() {
        return {
            search: "",
            productData: {
                product_id: "",
                product_name: "",
                product_category: "",
                product_image: "",
                name: ""
            },

            headers: [{
                    text: "Product Code",
                    align: "start",
                    sortable: false,
                    value: "product_id"
                },
                {
                    text: "Product Name",
                    value: "product_name"
                },
                {
                    text: "Product Image",
                    value: "product_image"
                },
                {
                    text: "Product Category",
                    value: "category_name"
                },
                {
                    text: "Actions",
                    value: "actions",
                    sortable: false
                }
            ],

            DataProduct: [],

            product_category: [],

            editProductData: {},

            errors: {}
        };
    },

    mounted() {
        this.retrieveData();
    },

    methods: {
        retrieveData: async function () {
            try {
                const response = await manageProductService.retrieveData();
                this.DataProduct = response.data.data;
                console.log(response.data.data);
                this.product_category = [
                    "Pork",
                    "Chicken",
                    "Beef",
                    "Fish",
                    "Vegestables",
                    "Condiments"
                ];
            } catch (error) {
                this.flashMessage.error({
                    message: "Some error occured, Please refresh!",
                    time: 5000
                });
            }
        },

        pickFile() {
            this.$refs.newProductImage.click();
        },

        pickeditFile() {
            this.$refs.editProductImage.click();
        },

        attachImage() {
            this.productData.product_image = this.$refs.newProductImage.files[0];
            let reader = new FileReader();
            reader.addEventListener(
                "load",
                function () {
                    this.$refs.newProductImageDisplay.src = reader.result;
                }.bind(this),
                false
            );

            reader.readAsDataURL(this.productData.product_image);
            // console.log('clicked!');
        },

        hideNewProductModal() {
            this.$refs.newProductModal.hide();
            this.productData = {
                product_id: "",
                product_name: "",
                product_category: "",
                product_image: "",
                name: ""
            };
        },
        createProductList() {
            this.$refs.newProductModal.show();
        },

        hideEditProductModal() {
            this.$refs.updateProductModal.hide();
        },
        showEditProductList() {
            this.$refs.updateProductModal.show();
        },

        createProduct: async function () {
            let formData = new FormData();
            formData.append("product_id", this.productData.product_id);
            formData.append("product_name", this.productData.product_name);
            formData.append(
                "product_category",
                this.productData.product_category
            );
            formData.append("product_image", this.productData.product_image);

            try {
                const response = await manageProductService.createProduct(
                    formData
                );
                // to load database without reload the browser
                this.DataProduct.unshift(response.data);
                this.hideNewProductModal();
                this.flashMessage.setStrategy("single");
                this.flashMessage.success({
                    message: "Category stored successfully!",
                    time: 5000
                });
                this.productData = {
                    product_id: "",
                    product_name: "",
                    product_category: "",
                    product_image: ""
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
            this.editProductData.product_image = this.$refs.editProductImage.files[0];
            let reader = new FileReader();
            reader.addEventListener(
                "load",
                function () {
                    this.$refs.editProductImageDisplay.src = reader.result;
                }.bind(this),
                false
            );

            reader.readAsDataURL(this.editProductData.product_image);
        },

        editProduct(item) {
            this.editProductData = {
                ...item
            };
            // this.editCategoryData = category; //kung gusto mo mkita real time editing
            this.showEditProductList();
        },

        updateProduct: async function () {
            try {
                const formData = new FormData();
                formData.append("product_id", this.editProductData.product_id);
                formData.append(
                    "product_name",
                    this.editProductData.product_name
                );
                formData.append(
                    "product_category",
                    this.editProductData.product_category
                );
                formData.append(
                    "product_image",
                    this.editProductData.product_image
                );
                formData.append("_method", "put");

                const response = await manageProductService.updateProduct(
                    this.editProductData.id,
                    formData
                );
                // console.log(this.editProductData.id);
                this.DataProduct.map(item => {
                    if (item.id == response.data.id) {
                        for (let key in response.data) {
                            item[key] = response.data[key];
                        }
                    }
                });

                this.hideEditProductModal();
                this.flashMessage.success({
                    message: "Product Updated Successfully!",
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

        deleteProduct: async function (item) {
            // await manageProductService.deleteCategory(item.id);
            Vue.$confirm({
                message: `Are you sure you want to delete ${item.product_name}?`,
                button: {
                    no: "No",
                    yes: "Yes"
                },
                callback: confirm => {
                    if (!confirm) {} else {
                        // try {
                        //     manageProductService.deleteProduct(item.id);
                        // this.DataProduct = this.DataProduct.filter(obj => {
                        //     return obj.id != item.id;
                        // });

                        this.flashMessage.success({
                            message: "Product Deleted successfully!",
                            time: 5000
                        });
                        // } catch (error) {
                        //     this.flashMessage.error({
                        //         message: error.response.data.message,
                        //         time: 5000
                        //     });
                        // }
                    }
                }
            });
        }
    }
};
</script>
