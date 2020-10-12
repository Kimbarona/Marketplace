import {http, httpFile} from './http_service';

// to create
export function createPrice(data){
    return httpFile().post('/manage-price', data);
}

// to retrieve
export function retrieveData(){
    return http().get('/manage-price');
}

// to update
export function updatePrice(id, data){
    return httpFile().post(`manage-price/${id}`, data);
}

// to delete
export function deletePrice(id){
    return http().delete(`manage-price/${id}`);
}
