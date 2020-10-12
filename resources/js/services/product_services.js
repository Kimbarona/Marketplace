import {http, httpFile} from './http_service';

// to create
export function createProduct(data){
    return httpFile().post('/manage-product', data);
}

// to retrieve
export function retrieveData(){
    return http().get('/manage-product');
}

// to update
export function updateProduct(id, data){
    return httpFile().post(`manage-product/${id}`, data);
}

// to delete
export function deleteProduct(id){
    return http().delete(`manage-product/${id}`);
}
