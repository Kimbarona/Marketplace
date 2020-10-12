import {http, httpFile} from './http_service';

// to create
export function createRecipe(data){
    return httpFile().post('/manage-recipe', data);
}

// to retrieve
export function retrieveData(){
    return http().get('/manage-recipe');
}

// to update
export function updateRecipe(id, data){
    return httpFile().post(`manage-recipe/${id}`, data);
}

// to delete
export function deleteRecipe(id){
    return http().delete(`manage-recipe/${id}`);
}
