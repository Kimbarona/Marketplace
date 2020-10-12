import {http, httpFile} from './http_service';

// to create
export function createItemRecipe(data){
    return httpFile().post('/manage-recipe-items', data);
}

// to retrieve
export function retrieveItemData(){
    return http().get('/manage-recipe-items');
}

// to update
export function updateItemRecipe(id, data){
    return httpFile().post(`manage-recipe-items/${id}`, data);
}

// to delete
export function deleteItemRecipe(id){
    return http().delete(`manage-recipe-items/${id}`);
}
