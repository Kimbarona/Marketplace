import {http, httpFile} from './http_service';

// to create
export function createMeal(data){
    return httpFile().post('/manage-meal', data);
}

// to retrieve
export function retrieveData(){
    return http().get('/manage-meal');
}

//to find data
export function findData(id){
    return httpFile().get(`manage-meal/${id}`);
}

// to update
export function updateMeal(id, data){
    return httpFile().post(`manage-meal/${id}`, data);
}

// to delete
export function deleteMeal(id){
    return http().delete(`manage-meal/${id}`);
}
