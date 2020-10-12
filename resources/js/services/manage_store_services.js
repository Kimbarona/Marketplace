import { http, httpFile } from "./http_service";

// to create
export function createStore(data) {
    return httpFile().post("/manage-store", data);
}

// to retrieve
export function retrieveData() {
    return http().get("/manage-store");
}

// to update
export function updateStore(id, data) {
    return httpFile().post(`manage-store/${id}`, data);
}

// to delete
export function deleteStore(id) {
    return http().delete(`manage-store/${id}`);
}
