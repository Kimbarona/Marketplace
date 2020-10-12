import { http, httpFile } from "./http_service";

// to create
export function createUser(data) {
    return httpFile().post("/manage-user", data);
}

// to retrieve
export function retrieveData() {
    return http().get("/manage-user");
}

// to update
export function updateUser(id, data) {
    return httpFile().post(`manage-user/${id}`, data);
}

// to delete
export function deleteUser(id) {
    return http().delete(`manage-user/${id}`);
}
