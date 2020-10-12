<template>
<div>
    <br /><br />
    <div class="card-header">
        <b>Manage Price</b>
    </div>
    <br />
    <div class="card-header">
        <div class="my-2">
            <v-btn small color="success" dark @click="createPriceList">create</v-btn>
            <!-- <v-btn small color="success"  @click="showImportBulkModal" dark>Import Bulk</v-btn> -->
            <v-btn small color="success"  @click="showPriceChangeModal" dark>Price Change</v-btn>

        </div>
    </div>
    <b-card title>
        <v-card-title>
            Price List
            <v-spacer></v-spacer>
            <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
        </v-card-title>
        <v-data-table :headers="headers" :items="PriceData" :search="search">
            <template v-slot:item.updated_at="{ item }">
                <span>{{new Date(item.updated_at).toLocaleString().substring(0,10)}}</span>
            </template>
            <template v-slot:item.actions="{ item }">
                <v-icon small class="mr-2" @click="editPrice(item)">mdi-pencil</v-icon>
                <v-icon small @click="deletePrice(item)">mdi-delete</v-icon>
            </template>
        </v-data-table>

        <b-modal ref="newPriceModal" hide-footer title="Add New Price">
            <div class>
                <v-form @submit.prevent="createPrice">
                    <v-container>
                        <v-row>
                            <v-col cols="12" md="5">
                                <v-select :items="StoreCodeItems" v-model="priceData.store_id" label="Store" item-text="store_name" item-value="store_id" dense>
                                </v-select>
                                <div class="invalid-feedback" v-if="errors.store_id">
                                    {{ errors.store_id[0] }}
                                </div>
                            </v-col>
                            <v-col cols="12" md="7">
                                <v-select :items="ProductCodeItems" v-model="priceData.product_id" item-text="product_name" item-value="product_id" label="Product" dense>
                                </v-select>
                                <div class="invalid-feedback" v-if="errors.product_id">
                                    {{ errors.product_id[0] }}
                                </div>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="12" md="12">
                                <v-text-field v-model="priceData.price" label="Amount/Price" @keypress="isNumber"></v-text-field>
                                <div class="invalid-feedback" v-if="errors.price">
                                    {{ errors.price[0] }}
                                </div>
                            </v-col>
                        </v-row>
                    </v-container>
                    <div class="text-right">
                        <v-btn color="blue darken-1" text @click="hideNewPriceModal">Cancel</v-btn>
                        <v-btn color="blue darken-1" type="submit" text>Save</v-btn>
                    </div>
                </v-form>
            </div>
        </b-modal>

        <b-modal ref="updatePriceModal" hide-footer title="Edit Price">
            <div class>
                <v-form @submit.prevent="updatePrice">
                    <v-container>
                        <v-row>
                            <v-col cols="12" md="5">
                                <v-text-field type="number" v-model="editPriceData.store_id" label="Store Code" disabled=""></v-text-field>
                                <div class="invalid-feedback"></div>
                            </v-col>
                            <v-col cols="12" md="7">
                                <v-text-field v-model="editPriceData.product_id" label="Product Code" disabled=""></v-text-field>
                                <div class="invalid-feedback" v-if="errors.product_id">
                                    {{ errors.product_id[0] }}
                                </div>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="12" md="12">
                                <v-text-field v-model="editPriceData.price" label="Amount/Price" @keypress="isNumber"></v-text-field>
                                <div class="invalid-feedback" v-if="errors.price">
                                    {{ errors.price[0] }}
                                </div>
                            </v-col>
                        </v-row>
                    </v-container>
                    <div class="text-right">
                        <v-btn color="blue darken-1" text @click="hideEditPriceModal">Cancel</v-btn>
                        <v-btn color="blue darken-1" type="submit" text>Save</v-btn>
                    </div>
                </v-form>
            </div>
        </b-modal>

        <b-modal ref="priceChangeModal" hide-footer title="Price Change">
            <div class>
                <v-form @submit.prevent="importPriceChange" >
                    <v-container>
                        <v-row>
                            <v-col cols="12" md="12">
                                 <v-file-input type="file" id="input-excelfile" name="excelfile" ref="excelfile"  @change="onFileChange"  accept=".xlsx" v-model="excelfile" label="File input"></v-file-input>
                                 <!-- <input type="file" class="form-control"  id="input-excelfile" name="excelfile" accept=".xlsx" ref="excelfile"  @change="onFileChange"> -->
                                <div class="invalid-feedback" v-if="errors.excelfile">
                                    {{ errors.excelfile[0] }}
                                </div>
                                <div class="invalid-feedback" v-if="errors">
                                    {{ errors[0] }}
                                </div>
                            </v-col>
                        </v-row>
                    </v-container>
                    <div class="text-right">
                        <v-btn color="blue darken-1" text @click="hidepriceChangeModal">Cancel</v-btn>
                        <v-btn color="blue darken-1" type="submit" text>Update</v-btn>
                    </div>
                </v-form>
            </div>
        </b-modal>

        <b-modal ref="importBulkPriceModal" hide-footer title="Add Price">
            <div class>
                <v-form @submit.prevent="importBulkPrice" >
                    <v-container>
                        <v-row>

                            <v-col cols="12" md="12">
                                 <v-file-input type="file" id="input-importBulk" name="importBulk" ref="importBulk"  @change="onimportFileChange"  accept=".xlsx" v-model="importBulk" label="File input"></v-file-input>
                                 <!-- <input type="file" class="form-control"  id="input-excelfile" name="excelfile" accept=".xlsx" ref="excelfile"  @change="onFileChange"> -->
                                <div class="invalid-feedback" v-if="errors.importBulk">
                                    {{ errors.importBulk[0] }}
                                </div>
                                <div class="invalid-feedback" v-if="errors">
                                    {{ errors[0] }}
                                </div>
                            </v-col>
                        </v-row>
                    </v-container>
                    <div class="text-right">
                        <v-btn color="blue darken-1" text @click="hideimportbulkpriceModal">Cancel</v-btn>
                        <v-btn color="blue darken-1" type="submit" text>Add</v-btn>
                    </div>
                </v-form>
            </div>
        </b-modal>

    </b-card>
</div>
</template>

<script>
import * as managePriceService from "../../services/price_services";
import * as importPriceService from "../../services/import-price";
export default {
    name: "price",
    data() {
        return {
            search: "",

            priceData: {
                store_id: "",
                product_id: "",
                price: ""
            },

            excelfile: "",
            importBulk: "",

            headers: [{
                    text: "Store",
                    align: "start",
                    sortable: false,
                    value: "store_id"
                },
                {
                    text: "Product Code",
                    value: "product_id"
                },
                {
                    text: "Item Price",
                    value: "price"
                },
                {
                    text: "Price Updated Date",
                    value: "updated_at"
                },
                {
                    text: "Actions",
                    value: "actions",
                    sortable: false
                }
            ],

            PriceData: [],

            StoreCodeItems: [],

            ProductCodeItems: [],

            editPriceData: {},

            errors: {}
        };
    },

    mounted() {
        this.retrieveData();
    },

    methods: {
        retrieveData: async function () {
            try {
                const response = await managePriceService.retrieveData();
                // console.log(response);
                this.PriceData = response.data.price.data;
                this.StoreCodeItems = response.data.sid.data;
                this.ProductCodeItems = response.data.pid.data;
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

        hideNewPriceModal() {
            this.$refs.newPriceModal.hide();
            this.priceData = {
                store_id: "",
                product_id: "",
                price: ""
            };
        },

        createPriceList() {
            this.$refs.newPriceModal.show();
        },

        hideEditPriceModal() {
            this.$refs.updatePriceModal.hide();
        },

        showEditPriceList() {
            this.$refs.updatePriceModal.show();
        },

//  this is for the price change
        hidepriceChangeModal() {
            this.$refs.priceChangeModal.hide();
            this.excelfile = ""
        },
        showPriceChangeModal() {
            this.$refs.priceChangeModal.show();
        },
//  this is for the Add bulk price
        hideimportbulkpriceModal() {
            this.$refs.importBulkPriceModal.hide();
            this.importBulk = ""
        },
        showImportBulkModal() {
            this.$refs.importBulkPriceModal.show();
        },

        createPrice: async function () {
            let formData = new FormData();
            formData.append("store_id", this.priceData.store_id);
            formData.append("product_id", this.priceData.product_id);
            formData.append("price", this.priceData.price);

            try {
                const response = await managePriceService.createPrice(formData);
                // to load database without reload the browser
                this.PriceData.unshift(response.data);

                this.hideNewPriceModal();

                this.flashMessage.setStrategy("single");
                this.flashMessage.success({
                    message: "Price saved Successfully!",
                    time: 5000
                });

                // to empty the element
                this.priceData = {
                    store_id: "",
                    product_id: "",
                    price: ""
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

        onFileChange(e) {
            this.excelfile = e.target.files[0];
        },

        onimportFileChange(e) {
            this.importBulk = e.target.files[0];
        },

        importPriceChange: async function () {
            let formDatas = new FormData();
            formDatas.append("excelfile", this.excelfile);

            try {
                const response = await importPriceService.importPriceChange(formDatas);
                // console.log(response);
                    this.PriceData.unshift(response.data);
                    this.hidepriceChangeModal();

                    this.flashMessage.setStrategy("single");
                    this.flashMessage.success({
                        message: "Price List Updated Successfully!",
                        time: 5000
                    });

                // to empty the element
                    this.excelfile = "";

            }catch (error) {
                switch (error.response.status) {
                    case 422:
                        this.errors = error.response.data.errors;
                    break;
                    case 404:
                        this.errors = error.response.data.errors;
                    break;
                    default:
                        this.flashMessage.error({
                            message: error.response.data.message,
                            time: 5000
                        });
                    break;
                }
            }
        },

        importBulkPrice: async function () {
            let formDatas = new FormData();
            formDatas.append("importBulk", this.importBulk);

            try {
                const response = await importPriceService.importBulkPrice(formDatas);
                console.log(response);
                    this.PriceData.unshift(response.data);
                    this.hideimportbulkpriceModal();

                    this.flashMessage.setStrategy("single");
                    this.flashMessage.success({
                        message: response.data.message,
                        time: 5000
                    });

                // to empty the element
                    this.importBulk = "";

            }catch (error) {
                switch (error.response.status) {
                    case 422:
                        this.errors = error.response.data.errors;
                    break;
                    case 404:
                       this.flashMessage.error({
                            message: "Oppps! No list to be update!!",
                            time: 5000
                        });
                    break;

                    default:
                        this.flashMessage.error({
                            message: error.response.data.message,
                            time: 5000
                        });
                    break;
                }
            }
        },

        editPrice(item) {
            this.editPriceData = {
                ...item
            };
            // this.editCategoryData = category; //kung gusto mo mkita real time editing
            this.showEditPriceList();
        },

        updatePrice: async function () {
            try {
                const formData = new FormData();
                formData.append("store_id", this.editPriceData.store_id);
                formData.append("product_id", this.editPriceData.product_id);
                formData.append("price", this.editPriceData.price);
                formData.append("_method", "put");

                const response = await managePriceService.updatePrice(
                    this.editPriceData.id,
                    formData
                );

                this.PriceData.map(item => {
                    if (item.id == response.data.id) {
                        for (let key in response.data) {
                            item[key] = response.data[key];
                        }
                    }
                });

                this.hideEditPriceModal();
                this.flashMessage.success({
                    message: "Price Updated Successfully!",
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

        deletePrice: async function (item) {
            // await managePriceService.deleteCategory(item.id);
            Vue.$confirm({
                message: `Are you sure you want to delete ${item.price_name}?`,
                button: {
                    no: "No",
                    yes: "Yes"
                },
                callback: confirm => {
                    if (!confirm) {} else {
                        try {
                            managePriceService.deletePrice(item.id);
                            this.PriceData = this.PriceData.filter(obj => {
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
